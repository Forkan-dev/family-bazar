<?php

use Carbon\Carbon;

if (! function_exists('datetime_parse')) {
    /**
     * Parse different datetime inputs into a Carbon instance or formatted string.
     *
     * Examples:
     * - datetime_parse('2025-12-26 10:00:00')
     * - datetime_parse(1672012800) // timestamp
     * - datetime_parse('26/12/2025', 'd/m/Y', 'Asia/Dhaka', false)
     *
     * @param mixed $value  String, int timestamp, DateTimeInterface or null
     * @param string|null $format Optional input format for createFromFormat
     * @param string|null $timezone Optional timezone to convert to
     * @param bool $asCarbon Return Carbon when true, otherwise string from toDateTimeString()
     * @return \Carbon\Carbon|string
     */
    function datetime_parse($value, ?string $format = null, ?string $timezone = null, bool $asCarbon = true)
    {
        try {
            if ($value === null || $value === '') {
                $dt = Carbon::now();
            } elseif ($value instanceof DateTimeInterface) {
                $dt = Carbon::instance($value);
            } elseif (is_numeric($value)) {
                $dt = Carbon::createFromTimestamp((int) $value);
            } elseif ($format) {
                $dt = Carbon::createFromFormat($format, $value);
                if (! $dt) {
                    $dt = Carbon::parse($value);
                }
            } else {
                $dt = Carbon::parse($value);
            }

            if ($timezone) {
                $dt = $dt->setTimezone($timezone);
            }

            return $asCarbon ? $dt : $dt->toDateTimeString();
        } catch (Throwable $e) {
            return $asCarbon ? Carbon::now() : Carbon::now()->toDateTimeString();
        }
    }
}
