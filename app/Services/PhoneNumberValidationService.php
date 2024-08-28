<?php

namespace App\Services;

use App\Models\Country;
use Brick\Math\Exception\NumberFormatException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneNumberValidationService
{
    public function check($number, $countryId)
    {
        $number = str_replace(['+', ' '], '', $number);
        $country = Country::find($countryId);
        if (!isset($country)) {
            return response()->json(['message' => 'Country not found!'], 400);
        }
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($number, strtoupper($country->iso_code_2));
            $isValid = $phoneUtil->isValidNumber($numberProto);
            if ($isValid) {
                $interNationalNumber = $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL);
                $internationalNumberBulshoFormat = str_replace(['-', ' '], '', $interNationalNumber);
                return response()->json(['message' => 'It is a valid number.', 'international_number' => $internationalNumberBulshoFormat], 200);
            } else {
                return response()->json(['message' => 'It is an invalid number!'], 400);
            }
        } catch (NumberParseException $e) {
            return response()->json(['message' => 'It is an invalid number!'], 400);
        }
    }

    public function getInternationalNumber($number, $countryId)
    {
        $country = Country::find($countryId);
        if (!isset($country)) {
            return response()->json(['message' => 'Country not found!'], 400);
        }
        try {
            $phone = new PhoneNumber($number, strtoupper($country->iso_code_2));
            $phone->formatE164();
        } catch (NumberFormatException $e) {
            return $number;
        }
        return $phone;
    }

}
