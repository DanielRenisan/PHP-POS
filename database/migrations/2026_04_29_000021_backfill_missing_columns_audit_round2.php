<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Round 2 of the audit-driven backfill. Found by widening the scan to catch
 * "table.column" string literals inside select() / addSelect() chains.
 *
 *  - transactions.ref_no, transactions.old_ref_no, transactions.discount_type
 *      → PurchaseController, PurchaseWastageController, PurchaseReturnController,
 *        RegisterController, PaymentController, SaleController
 *  - transaction_payments.paid_on
 *      → PaymentController (select + whereBetween on date(paid_on))
 */
class BackfillMissingColumnsAuditRound2 extends Migration
{
    public function up(): void
    {
        $this->ensureColumn('transactions', 'ref_no', function (Blueprint $t) {
            $t->string('ref_no')->nullable();
            $t->index('ref_no');
        });
        $this->ensureColumn('transactions', 'old_ref_no', function (Blueprint $t) {
            $t->string('old_ref_no')->nullable();
        });
        $this->ensureColumn('transactions', 'discount_type', function (Blueprint $t) {
            $t->string('discount_type', 32)->default('percentage');
        });

        $this->ensureColumn('transaction_payments', 'paid_on', function (Blueprint $t) {
            $t->dateTime('paid_on')->nullable();
            $t->index('paid_on');
        });
    }

    public function down(): void
    {
        // No-op. Removing these columns could destroy operational data.
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
