<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToPrintersTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('printers') && !Schema::hasColumn('printers', 'deleted_at')) {
            Schema::table('printers', function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('printers') && Schema::hasColumn('printers', 'deleted_at')) {
            Schema::table('printers', function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
}
