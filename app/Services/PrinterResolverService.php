<?php

namespace App\Services;

use App\Models\Printer;
use Illuminate\Database\Eloquent\Collection;

class PrinterResolverService
{
    public function resolveForStation(int $stationId): ?Printer
    {
        return $this->stationQuery($stationId)->first();
    }

    /**
     * All active printers for a station, default first then by id.
     * Used by the print job to fall back if the primary is unreachable.
     *
     * @return Collection<int, Printer>
     */
    public function resolveAllForStation(int $stationId): Collection
    {
        return $this->stationQuery($stationId)->get();
    }

    public function defaultReceiptPrinter(): ?Printer
    {
        return Printer::query()
            ->where('printer_type', Printer::TYPE_RECEIPT)
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();
    }

    private function stationQuery(int $stationId)
    {
        return Printer::query()
            ->where('station_id', $stationId)
            ->where('printer_type', Printer::TYPE_STATION)
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id');
    }
}
