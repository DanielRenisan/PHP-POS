<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The original printers.connection_type was enum('network','windows','linux').
 * The station/printer feature added a 'file' driver for headless testing.
 * MySQL silently coerces unknown enum values to '' in non-strict mode, so any
 * printer created with connection_type='file' was being saved as empty.
 *
 * Widen the enum to include 'file', then repair any rows that were already
 * affected (empty string → 'file').
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('printers', 'connection_type')) {
            return;
        }

        DB::statement("ALTER TABLE `printers` MODIFY `connection_type` ENUM('network','windows','linux','file') NOT NULL DEFAULT 'network'");

        DB::table('printers')->where('connection_type', '')->update(['connection_type' => 'file']);
    }

    public function down(): void
    {
        if (!Schema::hasColumn('printers', 'connection_type')) {
            return;
        }

        DB::table('printers')->where('connection_type', 'file')->update(['connection_type' => 'network']);
        DB::statement("ALTER TABLE `printers` MODIFY `connection_type` ENUM('network','windows','linux') NOT NULL");
    }
};
