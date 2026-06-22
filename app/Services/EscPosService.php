<?php

namespace App\Services;

use App\Models\Printer;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/**
 * ESC/POS thermal printer driver.
 *
 * Supports:
 *   - network  : raw TCP to ip:port (default 9100)  — Star, Epson, Xprinter, etc.
 *   - linux    : direct write to /dev/usb/lp0, /dev/ttyUSB0, /dev/rfcomm0 (Bluetooth)
 *                or CUPS queue name via `lp -d <name> -o raw`
 *   - windows  : direct write to LPT1/COM3 etc., or UNC share \\HOST\Share via `copy /B`
 */
class EscPosService
{
    private const ESC = "\x1B";
    private const GS  = "\x1D";
    private const LF  = "\x0A";
    private const CR  = "\x0D";

    private const INIT              = "\x1B\x40";
    private const CODEPAGE_CP437    = "\x1B\x74\x00";
    private const CANCEL_KANJI      = "\x1C\x2E";
    private const ALIGN_LEFT        = "\x1B\x61\x00";
    private const ALIGN_CENTER      = "\x1B\x61\x01";
    private const ALIGN_RIGHT       = "\x1B\x61\x02";
    private const BOLD_ON           = "\x1B\x45\x01";
    private const BOLD_OFF          = "\x1B\x45\x00";
    private const DOUBLE_HEIGHT_ON  = "\x1B\x21\x10";
    private const DOUBLE_HEIGHT_OFF = "\x1B\x21\x00";
    private const CUT               = "\x1D\x56\x41\x05";
    private const FEED_LINES_3      = "\x1B\x64\x03";

    private string $buffer = '';
    private int $charsPerLine;
    private bool $cutAppended = false;

    public function __construct(int $charsPerLine = 42)
    {
        $this->charsPerLine = $charsPerLine;
        $this->buffer = self::INIT . self::CANCEL_KANJI . self::CODEPAGE_CP437;
    }

    public function center(string $text): self
    {
        $this->buffer .= self::ALIGN_CENTER . $text . self::LF;
        return $this;
    }

    public function left(string $text): self
    {
        $this->buffer .= self::ALIGN_LEFT . $text . self::LF;
        return $this;
    }

    public function boldCenter(string $text): self
    {
        $this->buffer .= self::ALIGN_CENTER . self::BOLD_ON . $text . self::BOLD_OFF . self::LF;
        return $this;
    }

    public function bigCenter(string $text): self
    {
        $this->buffer .= self::ALIGN_CENTER . self::BOLD_ON . self::DOUBLE_HEIGHT_ON . $text . self::DOUBLE_HEIGHT_OFF . self::BOLD_OFF . self::LF;
        return $this;
    }

    public function divider(): self
    {
        $this->buffer .= self::ALIGN_LEFT . str_repeat('-', $this->charsPerLine) . self::LF;
        return $this;
    }

    public function blank(): self
    {
        $this->buffer .= self::LF;
        return $this;
    }

    public function row(string $left, string $right): self
    {
        $pad = $this->charsPerLine - mb_strlen($right);
        $left = mb_substr($left, 0, max(0, $pad - 1));
        $line = str_pad($left, $pad) . $right;
        $this->buffer .= self::ALIGN_LEFT . $line . self::LF;
        return $this;
    }

    /**
     * Audible beep — useful so kitchen/bar staff notice a new ticket landed.
     * ESC B n t : beep n times for t * 100ms.
     */
    public function beep(int $times = 2, int $durationTenths = 4): self
    {
        $times = max(1, min(9, $times));
        $duration = max(1, min(9, $durationTenths));
        $this->buffer .= self::ESC . 'B' . chr($times) . chr($duration);
        return $this;
    }

    public function cut(): self
    {
        $this->buffer .= self::FEED_LINES_3 . self::CUT;
        $this->cutAppended = true;
        return $this;
    }

    public function getBytes(): string
    {
        if (!$this->cutAppended) {
            return $this->buffer . self::FEED_LINES_3 . self::CUT;
        }
        return $this->buffer;
    }

    /**
     * Strip the ESC/POS control sequences this service emits and return the
     * plain printable text — the same content that would land on paper.
     * Used by the 'file' driver so devs can read receipts without hardware.
     */
    public function getPrintablePreview(): string
    {
        $text = $this->getBytes();

        $text = str_replace([
            self::INIT,
            self::CODEPAGE_CP437,
            self::CANCEL_KANJI,
            self::ALIGN_LEFT, self::ALIGN_CENTER, self::ALIGN_RIGHT,
            self::BOLD_ON, self::BOLD_OFF,
            self::DOUBLE_HEIGHT_ON, self::DOUBLE_HEIGHT_OFF,
            self::FEED_LINES_3,
            self::CUT,
        ], '', $text);

        $text = preg_replace("/\x1BB.{2}/s", '', $text);

        return $text;
    }

    /**
     * Send the buffered bytes to the given printer based on its connection type.
     * Throws on failure so the caller can mark the ticket failed and retry.
     */
    public function sendTo(Printer $printer, int $timeoutSeconds = 5): void
    {
        $bytes = $this->getBytes();

        switch ($printer->connection_type) {
            case 'network':
                $this->writeNetwork(
                    (string) $printer->ip_address,
                    (int) ($printer->port ?: 9100),
                    $bytes,
                    $timeoutSeconds,
                );
                return;

            case 'linux':
                $this->writeLinux((string) $printer->path, $bytes, $timeoutSeconds);
                return;

            case 'windows':
                $this->writeWindows((string) $printer->path, $bytes, $timeoutSeconds);
                return;

            case 'file':
                $this->writeFile($printer, $bytes);
                return;

            default:
                throw new \RuntimeException("Unsupported printer connection_type: {$printer->connection_type}");
        }
    }

    /**
     * Backwards-compatible direct network send (kept for any external callers).
     */
    public function sendToNetwork(string $ip, int $port = 9100, int $timeoutSeconds = 5): void
    {
        $this->writeNetwork($ip, $port, $this->getBytes(), $timeoutSeconds);
    }

    private function writeNetwork(string $ip, int $port, string $bytes, int $timeoutSeconds): void
    {
        if ($ip === '') {
            throw new \RuntimeException('Network printer has no IP address configured.');
        }

        $socket = @fsockopen($ip, $port, $errno, $errstr, $timeoutSeconds);
        if ($socket === false) {
            throw new \RuntimeException("Cannot connect to printer {$ip}:{$port} — {$errstr} ({$errno})");
        }
        stream_set_timeout($socket, $timeoutSeconds);
        $written = @fwrite($socket, $bytes);
        fclose($socket);
        if ($written === false || $written < strlen($bytes)) {
            throw new \RuntimeException("Failed to write all bytes to printer {$ip}:{$port}");
        }
    }

    /**
     * Linux: if path starts with '/' treat as a device file (USB/serial/Bluetooth rfcomm).
     * Otherwise treat as a CUPS printer queue name and shell out to `lp -d <name> -o raw`.
     */
    private function writeLinux(string $path, string $bytes, int $timeoutSeconds): void
    {
        if ($path === '') {
            throw new \RuntimeException('Linux printer has no device path / queue name configured.');
        }

        if (str_starts_with($path, '/')) {
            if (!file_exists($path)) {
                throw new \RuntimeException("Printer device not found: {$path}");
            }
            if (!is_writable($path)) {
                throw new \RuntimeException("Printer device not writable: {$path} (check user/group permissions, e.g. add web user to 'lp' group)");
            }

            $fp = @fopen($path, 'wb');
            if ($fp === false) {
                throw new \RuntimeException("Cannot open printer device: {$path}");
            }
            $written = @fwrite($fp, $bytes);
            fclose($fp);
            if ($written === false || $written < strlen($bytes)) {
                throw new \RuntimeException("Failed to write all bytes to {$path}");
            }
            return;
        }

        $tmp = $this->writeTempFile($bytes);
        try {
            $process = new Process(['lp', '-d', $path, '-o', 'raw', $tmp]);
            $process->setTimeout($timeoutSeconds);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException(
                    "CUPS lp failed for queue '{$path}': " . trim($process->getErrorOutput() ?: $process->getOutput())
                );
            }
        } finally {
            @unlink($tmp);
        }
    }

    /**
     * Windows: if path starts with '\\' treat as a UNC printer share and copy/B the file.
     * Otherwise treat as a local port name (LPT1, COM3, etc.) and write directly.
     */
    private function writeWindows(string $path, string $bytes, int $timeoutSeconds): void
    {
        if ($path === '') {
            throw new \RuntimeException('Windows printer has no port / share path configured.');
        }

        if (str_starts_with($path, '\\\\')) {
            $tmp = $this->writeTempFile($bytes);
            try {
                $cmd = sprintf('copy /B %s %s', escapeshellarg($tmp), escapeshellarg($path));
                $process = Process::fromShellCommandline($cmd);
                $process->setTimeout($timeoutSeconds);
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new \RuntimeException(
                        "Windows copy to '{$path}' failed: " . trim($process->getErrorOutput() ?: $process->getOutput())
                    );
                }
            } finally {
                @unlink($tmp);
            }
            return;
        }

        $fp = @fopen($path, 'wb');
        if ($fp === false) {
            throw new \RuntimeException("Cannot open Windows printer port: {$path} (check port name and permissions)");
        }
        $written = @fwrite($fp, $bytes);
        fclose($fp);
        if ($written === false || $written < strlen($bytes)) {
            throw new \RuntimeException("Failed to write all bytes to Windows port {$path}");
        }
    }

    /**
     * Test driver: write the receipt to disk so it can be opened and inspected
     * without a real printer. Produces two files per print:
     *   {dir}/{timestamp}.txt — printable text (open in any editor)
     *   {dir}/{timestamp}.bin — raw ESC/POS bytes (for protocol debugging)
     *
     * Resolution of the target directory:
     *   - $printer->path empty   → storage/app/printer-output/{slug-of-printer-name}/
     *   - $printer->path absolute → used as-is
     *   - $printer->path relative → relative to storage/app/
     */
    private function writeFile(Printer $printer, string $bytes): void
    {
        $configured = trim((string) $printer->path);
        $slug = Str::slug($printer->name ?: ('printer-' . $printer->id)) ?: ('printer-' . $printer->id);

        if ($configured === '') {
            $dir = storage_path('app/printer-output/' . $slug);
        } elseif ($configured[0] === '/' || preg_match('#^[A-Za-z]:[\\\\/]#', $configured)) {
            $dir = $configured;
        } else {
            $dir = storage_path('app/' . ltrim($configured, '/\\'));
        }

        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new \RuntimeException("File printer: cannot create output directory {$dir}");
        }
        if (!is_writable($dir)) {
            throw new \RuntimeException("File printer: directory not writable {$dir}");
        }

        $stamp = date('Y-m-d_His') . '_' . substr((string) microtime(true), -4);
        $base = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $stamp;

        $okTxt = @file_put_contents($base . '.txt', $this->getPrintablePreview());
        $okBin = @file_put_contents($base . '.bin', $bytes);

        if ($okTxt === false || $okBin === false) {
            throw new \RuntimeException("File printer: failed to write receipt files in {$dir}");
        }
    }

    private function writeTempFile(string $bytes): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'escpos_');
        if ($tmp === false) {
            throw new \RuntimeException('Cannot create temp file for printer payload.');
        }
        $ok = @file_put_contents($tmp, $bytes);
        if ($ok === false) {
            @unlink($tmp);
            throw new \RuntimeException('Cannot write temp file for printer payload.');
        }
        return $tmp;
    }
}
