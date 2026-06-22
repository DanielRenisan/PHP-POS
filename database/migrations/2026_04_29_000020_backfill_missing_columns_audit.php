<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Audit-driven backfill: adds columns the application code references but
 * which were never added to the local DB by any prior migration. Each addition
 * is guarded by Schema::hasColumn so this is idempotent.
 *
 * Sources of truth:
 *  - Eloquent writes (Model assignments) and reads (->where) in app/Http/Controllers and app/Models
 *  - Confirmed against the schema produced by every migration up to 2026_04_29.
 */
class BackfillMissingColumnsAudit extends Migration
{
    public function up(): void
    {
        // 1. room_assigns.checkin_status — POSController, CheckinController, BookingController, CheckoutController
        $this->ensureColumn('room_assigns', 'checkin_status', function (Blueprint $t) {
            $t->tinyInteger('checkin_status')->default(0)->after('status');
            $t->index('checkin_status');
        });

        // 2. product_variation_values: product_id, selling_price
        $this->ensureColumn('product_variation_values', 'product_id', function (Blueprint $t) {
            $t->unsignedBigInteger('product_id')->nullable()->after('id');
            $t->index('product_id');
        });
        $this->ensureColumn('product_variation_values', 'selling_price', function (Blueprint $t) {
            $t->decimal('selling_price', 20, 2)->default(0);
        });

        // 3. contacts.is_default — ContactController filters with is_default = 0/1
        $this->ensureColumn('contacts', 'is_default', function (Blueprint $t) {
            $t->tinyInteger('is_default')->default(0);
            $t->index('is_default');
        });

        // 4. taxes: name, amount, status, group_parent_id — TaxController writes all four
        $this->ensureColumn('taxes', 'name', function (Blueprint $t) {
            $t->string('name')->nullable();
        });
        $this->ensureColumn('taxes', 'amount', function (Blueprint $t) {
            $t->decimal('amount', 20, 2)->default(0);
        });
        $this->ensureColumn('taxes', 'status', function (Blueprint $t) {
            $t->string('status')->default('Active');
        });
        $this->ensureColumn('taxes', 'group_parent_id', function (Blueprint $t) {
            $t->unsignedBigInteger('group_parent_id')->nullable();
            $t->index('group_parent_id');
        });

        // 5. purchase_lines: discount, discount_type, line_total, parent_id
        $this->ensureColumn('purchase_lines', 'discount', function (Blueprint $t) {
            $t->decimal('discount', 20, 2)->default(0);
        });
        $this->ensureColumn('purchase_lines', 'discount_type', function (Blueprint $t) {
            $t->string('discount_type', 32)->default('percentage');
        });
        $this->ensureColumn('purchase_lines', 'line_total', function (Blueprint $t) {
            $t->decimal('line_total', 20, 2)->default(0);
        });
        $this->ensureColumn('purchase_lines', 'parent_id', function (Blueprint $t) {
            $t->unsignedBigInteger('parent_id')->nullable();
            $t->index('parent_id');
        });

        // 6. sell_line_variations.transaction_id — POSController/QRController write it on create
        $this->ensureColumn('sell_line_variations', 'transaction_id', function (Blueprint $t) {
            $t->unsignedBigInteger('transaction_id')->nullable();
            $t->index('transaction_id');
        });

        // 7. business_locations.location_id — used as a unique human-readable code
        // (LocationController::checkLocationId checks uniqueness against this)
        $this->ensureColumn('business_locations', 'location_id', function (Blueprint $t) {
            $t->string('location_id')->nullable();
            $t->index('location_id');
        });
    }

    public function down(): void
    {
        // No-op. Removing these columns could destroy operational data.
        // Drop them manually if required.
    }

    private function ensureColumn(string $table, string $column, \Closure $callback): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }
        if (Schema::hasColumn($table, $column)) {
            return;
        }
        Schema::table($table, $callback);
    }
}
