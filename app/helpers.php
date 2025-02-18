<?php

// app/helpers.php

use Illuminate\Support\Facades\DB;


if (!function_exists('autonumber')) {
    /**
     * Generate autonumber based on the last number in the database table
     *
     * @param string $table The name of the table
     * @param string $column The name of the column to generate autonumber for
     * @param int $length The length of the autonumber
     * @param string $prefix The prefix of the autonumber
     * @return string The autonumber
     */
    function autonumber($table, $column, $length = 3, $prefix = '')
    {
        $lastNumber = DB::table($table)->max($column);

        if (!$lastNumber) {
            return $prefix . str_pad(1, $length, '0', STR_PAD_LEFT);
        }

        $lastNumber = substr($lastNumber, strlen($prefix));
        $newNumber = intval($lastNumber) + 1;

        return $prefix . str_pad($newNumber, $length, '0', STR_PAD_LEFT);
    }
}
