<?php

namespace Modules\NsPcccDashboardFix\Services;

use App\Models\ActiveTransactionHistory;
use App\Models\DashboardDay;
use App\Services\ReportService;

/**
 * Root cause of the bug:
 * ----------------------
 * ReportService::computeIncome() sums ALL active transaction history
 * entries by operation (credit = income, debit = expenses). But every
 * time a transaction is recorded, NexoPOS's accounting engine
 * automatically creates a "reflection" counter-entry with the operation
 * flipped (credit <-> debit) and the exact same value, for double-entry
 * bookkeeping (see TransactionsHistoryAfterCreatedEventListener ->
 * AccountingReflectionJob -> TransactionService::reflectTransactionFromRule).
 *
 * Because every entry always has a same-value mirror on the opposite
 * side, SUM(credit) always equals SUM(debit) — which is exactly why the
 * "Income" and "Expenses" dashboard cards always show identical amounts,
 * regardless of what actually happened that day.
 *
 * The rest of the codebase already excludes reflections when it wants
 * "real" transactions only (e.g. TransactionService::deleteProcurementTransactions
 * filters `where('is_reflection', false)`). computeIncome() was simply
 * missing that same filter. This override adds it back.
 */
class FixedReportService extends ReportService
{
    public function computeIncome( DashboardDay $previousReport, DashboardDay $todayReport )
    {
        $totalIncome = ActiveTransactionHistory::from( $todayReport->range_starts )
            ->to( $todayReport->range_ends )
            ->operation( ActiveTransactionHistory::OPERATION_CREDIT )
            ->where( 'is_reflection', false )
            ->sum( 'value' );

        $totalExpenses = ActiveTransactionHistory::from( $todayReport->range_starts )
            ->to( $todayReport->range_ends )
            ->operation( ActiveTransactionHistory::OPERATION_DEBIT )
            ->where( 'is_reflection', false )
            ->sum( 'value' );

        $todayReport->day_expenses = $totalExpenses;
        $todayReport->day_income = $totalIncome;
        $todayReport->total_income = ( $previousReport->total_income ?? 0 ) + $todayReport->day_income;
        $todayReport->total_expenses = ( $previousReport->total_expenses ?? 0 ) + $todayReport->day_expenses;
    }
}
