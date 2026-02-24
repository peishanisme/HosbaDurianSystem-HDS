<?php

namespace App\Traits;

trait PhoneNumberTrait
{
    /**
     * Format phone number for storage: ensure +60 prefix
     */
    public static function formatForStorage(string $phone): string
    {
        // Remove any non-digit characters
        $digits = preg_replace('/\D/', '', $phone);

        // Add country code 60 if not present
        if (substr($digits, 0, 2) !== '60') {
            if (substr($digits, 0, 1) === '0') {
                $digits = '60' . substr($digits, 1);
            } else {
                $digits = '60' . $digits;
            }
        }

        return $digits;
    }

    /**
     * Format phone number for display: remove +60 prefix
     */
    public static function formatForDisplay(string $phone): string
    {
        // Remove country code 60 if present
        if (substr($phone, 0, 2) === '60') {
            return substr($phone, 2);
        }

        return $phone;
    }
}
