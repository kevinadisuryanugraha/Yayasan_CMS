<?php
// Helper Functions for Campaign Section

/**
 * Calculate progress percentage from raised and goal amounts
 * @param float $raised Amount raised
 * @param float $goal Goal amount
 * @return float Progress percentage (0-100)
 */
function calculate_progress($raised, $goal)
{
    if ($goal <= 0)
        return 0;
    $percentage = ($raised / $goal) * 100;
    return min(round($percentage, 1), 100); // Cap at 100%
}

/**
 * Format currency with Rupiah and thousand separators
 * @param float $amount Amount to format
 * @return string Formatted currency string
 */
function format_currency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format currency for display (short version with rb/jt suffix)
 * @param float $amount Amount to format
 * @return string Formatted currency string
 */
function format_currency_short($amount)
{
    if ($amount >= 1000000000) {
        return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . ' M';
    } elseif ($amount >= 1000000) {
        return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . ' Jt';
    } elseif ($amount >= 1000) {
        return 'Rp ' . number_format($amount / 1000, 0, ',', '.') . ' rb';
    } else {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
?>