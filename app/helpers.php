<?php

use Carbon\Carbon;

if (! function_exists('datetime_parse_local_to_utc')) {
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
    function datetime_parse_local_to_utc($value, ?string $input_format = null, ?string $output_format = null)
    {
        try {
            $datetime = Carbon::createFromFormat($input_format, $value, config('app.timezone'))
                ->setTimezone('UTC')
                ->format($output_format);
            return $datetime;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}



if (! function_exists('datetime_parse_utc_to_local')) {
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
    function datetime_parse_utc_to_local($value, ?string $format = null)
    {
        try {
            $datetime =  Carbon::parse($value, 'UTC')
                ->setTimezone(config('app.timezone'))
                ->format($format);
            return $datetime;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
