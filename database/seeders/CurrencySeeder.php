<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function __construct()
    {
        $this->client = (new \GuzzleHttp\Client);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = $this->getVariables();

        foreach ($currencies as $currency => $data) {
            $data['code'] = $currency;
            $data['country_iso'] = $this->getCountryIso($currency);

            \App\Currency::create($data);
        }
    }

    private function getCountryIso($currency)
    {
        try {
            $response = $this->client->request('GET', "https://restcountries.eu/rest/v2/currency/{$currency}");
            $response = Arr::first(json_decode($response->getBody()->getContents(), true));

            return $response['alpha3Code'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getVariables()
    {
        return [
            'AED' => ['html' => 'د.إ', 'symbol' => 'د.إ', 'right_position' => 0, 'show_code' => 1],
            'AFN' => ['html' => '؋', 'symbol' => '؋', 'right_position' => 0, 'show_code' => 1],
            'ALL' => ['html' => 'Lek', 'symbol' => 'Lek', 'right_position' => 1, 'show_code' => 1],
            'AMD' => ['html' => '֏', 'symbol' => '֏', 'right_position' => 0, 'show_code' => 1],
            'ANG' => ['html' => 'ƒ', 'symbol' => 'ƒ', 'right_position' => 0, 'show_code' => 1],
            'AOA' => ['html' => 'AOA', 'symbol' => 'AOA', 'right_position' => 1, 'show_code' => 0],
            'ARS' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'AUD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'AWG' => ['html' => 'ƒ', 'symbol' => 'ƒ', 'right_position' => 0, 'show_code' => 1],
            'AZN' => ['html' => '₼', 'symbol' => '₼', 'right_position' => 0, 'show_code' => 1],
            'BAM' => ['html' => 'KM', 'symbol' => 'KM', 'right_position' => 0, 'show_code' => 1],
            'BBD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'BDT' => ['html' => '৳', 'symbol' => '৳', 'right_position' => 0, 'show_code' => 1],
            'BGN' => ['html' => 'лв', 'symbol' => 'лв', 'right_position' => 0, 'show_code' => 1],
            'BHD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'BIF' => ['html' => 'BIF', 'symbol' => 'BIF', 'right_position' => 1, 'show_code' => 0],
            'BMD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'BND' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'BOB' => ['html' => '$b', 'symbol' => '$b', 'right_position' => 0, 'show_code' => 1],
            'BRL' => ['html' => 'R$', 'symbol' => 'R$', 'right_position' => 0, 'show_code' => 1],
            'BSD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'BTN' => ['html' => 'BTN', 'symbol' => 'BTN', 'right_position' => 1, 'show_code' => 0],
            'BWP' => ['html' => 'P', 'symbol' => 'P', 'right_position' => 0, 'show_code' => 1],
            'BYN' => ['html' => 'Br', 'symbol' => 'Br', 'right_position' => 0, 'show_code' => 1],
            'BZD' => ['html' => 'BZ$', 'symbol' => 'BZ$', 'right_position' => 0, 'show_code' => 1],
            'CAD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'CDF' => ['html' => 'CDF', 'symbol' => 'CDF', 'right_position' => 1, 'show_code' => 0],
            'CHF' => ['html' => 'CHF', 'symbol' => 'CHF', 'right_position' => 0, 'show_code' => 0],
            'CLP' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'CNY' => ['html' => '¥', 'symbol' => '¥', 'right_position' => 0, 'show_code' => 1],
            'COP' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'CRC' => ['html' => '₡', 'symbol' => '₡', 'right_position' => 0, 'show_code' => 1],
            'CUC' => ['html' => '₱', 'symbol' => '₱', 'right_position' => 0, 'show_code' => 1],
            'CUP' => ['html' => '₱', 'symbol' => '₱', 'right_position' => 0, 'show_code' => 1],
            'CVE' => ['html' => 'CVE', 'symbol' => 'CVE', 'right_position' => 1, 'show_code' => 0],
            'CZK' => ['html' => 'Kč', 'symbol' => 'Kč', 'right_position' => 1, 'show_code' => 1],
            'DJF' => ['html' => 'DJF', 'symbol' => 'DJF', 'right_position' => 1, 'show_code' => 0],
            'DKK' => ['html' => 'kr', 'symbol' => 'kr', 'right_position' => 0, 'show_code' => 1],
            'DOP' => ['html' => 'RD$', 'symbol' => 'RD$', 'right_position' => 0, 'show_code' => 1],
            'DZD' => ['html' => 'DZD', 'symbol' => 'DZD', 'right_position' => 1, 'show_code' => 0],
            'EGP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'ERN' => ['html' => 'ERN', 'symbol' => 'ERN', 'right_position' => 1, 'show_code' => 0],
            'ETB' => ['html' => 'ETB', 'symbol' => 'ETB', 'right_position' => 1, 'show_code' => 0],
            'EUR' => ['html' => '€', 'symbol' => '€', 'right_position' => 1, 'show_code' => 1],
            'FJD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'FKP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'GBP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'GEL' => ['html' => '₾', 'symbol' => '₾', 'right_position' => 0, 'show_code' => 1],
            'GGP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'GHS' => ['html' => '¢', 'symbol' => '¢', 'right_position' => 0, 'show_code' => 1],
            'GIP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'GMD' => ['html' => 'D', 'symbol' => 'D', 'right_position' => 0, 'show_code' => 1],
            'GNF' => ['html' => 'GNF', 'symbol' => 'GNF', 'right_position' => 1, 'show_code' => 0],
            'GTQ' => ['html' => 'Q', 'symbol' => 'Q', 'right_position' => 0, 'show_code' => 1],
            'GYD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'HKD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'HNL' => ['html' => 'L', 'symbol' => 'L', 'right_position' => 0, 'show_code' => 1],
            'HRK' => ['html' => 'kn', 'symbol' => 'kn', 'right_position' => 1, 'show_code' => 1],
            'HTG' => ['html' => 'HTG', 'symbol' => 'HTG', 'right_position' => 1, 'show_code' => 0],
            'HUF' => ['html' => 'Ft', 'symbol' => 'Ft', 'right_position' => 1, 'show_code' => 1],
            'IDR' => ['html' => 'Rp', 'symbol' => 'Rp', 'right_position' => 0, 'show_code' => 1],
            'ILS' => ['html' => '₪', 'symbol' => '₪', 'right_position' => 0, 'show_code' => 1],
            'IMP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'INR' => ['html' => '₹', 'symbol' => '₹', 'right_position' => 0, 'show_code' => 1],
            'IQD' => ['html' => 'IQD', 'symbol' => 'IQD', 'right_position' => 1, 'show_code' => 0],
            'IRR' => ['html' => '﷼', 'symbol' => '﷼', 'right_position' => 0, 'show_code' => 1],
            'ISK' => ['html' => 'kr', 'symbol' => 'kr', 'right_position' => 0, 'show_code' => 1],
            'JEP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'JMD' => ['html' => 'J$', 'symbol' => 'J$', 'right_position' => 0, 'show_code' => 1],
            'JOD' => ['html' => 'JOD', 'symbol' => 'JOD', 'right_position' => 1, 'show_code' => 0],
            'JPY' => ['html' => '¥', 'symbol' => '¥', 'right_position' => 0, 'show_code' => 1],
            'KES' => ['html' => 'Sh', 'symbol' => 'Sh', 'right_position' => 1, 'show_code' => 1],
            'KGS' => ['html' => 'лв', 'symbol' => 'лв', 'right_position' => 0, 'show_code' => 1],
            'KHR' => ['html' => '៛', 'symbol' => '៛', 'right_position' => 0, 'show_code' => 1],
            'KMF' => ['html' => 'KMF', 'symbol' => 'KMF', 'right_position' => 1, 'show_code' => 0],
            'KPW' => ['html' => '₩', 'symbol' => '₩', 'right_position' => 0, 'show_code' => 1],
            'KRW' => ['html' => '₩', 'symbol' => '₩', 'right_position' => 0, 'show_code' => 1],
            'KWD' => ['html' => 'KD', 'symbol' => 'KD', 'right_position' => 0, 'show_code' => 1],
            'KYD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'KZT' => ['html' => 'лв', 'symbol' => 'лв', 'right_position' => 0, 'show_code' => 1],
            'LAK' => ['html' => '₭', 'symbol' => '₭', 'right_position' => 0, 'show_code' => 1],
            'LBP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'LKR' => ['html' => '₨', 'symbol' => '₨', 'right_position' => 0, 'show_code' => 1],
            'LRD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'LSL' => ['html' => 'LSL', 'symbol' => 'LSL', 'right_position' => 1, 'show_code' => 0],
            'LYD' => ['html' => 'LYD', 'symbol' => 'LYD', 'right_position' => 1, 'show_code' => 0],
            'MAD' => ['html' => 'DH', 'symbol' => 'DH', 'right_position' => 1, 'show_code' => 1],
            'MDL' => ['html' => 'MDL', 'symbol' => 'MDL', 'right_position' => 1, 'show_code' => 0],
            'MGA' => ['html' => 'Ar', 'symbol' => 'Ar', 'right_position' => 0, 'show_code' => 1],
            'MKD' => ['html' => 'ден', 'symbol' => 'ден', 'right_position' => 1, 'show_code' => 1],
            'MMK' => ['html' => 'MMK', 'symbol' => 'MMK', 'right_position' => 1, 'show_code' => 0],
            'MNT' => ['html' => '₮', 'symbol' => '₮', 'right_position' => 0, 'show_code' => 1],
            'MOP' => ['html' => 'MOP', 'symbol' => 'MOP', 'right_position' => 1, 'show_code' => 0],
            'MRU' => ['html' => 'MRU', 'symbol' => 'MRU', 'right_position' => 1, 'show_code' => 0],
            'MUR' => ['html' => '₨', 'symbol' => '₨', 'right_position' => 0, 'show_code' => 1],
            'MVR' => ['html' => 'Rf', 'symbol' => 'Rf', 'right_position' => 0, 'show_code' => 1],
            'MWK' => ['html' => 'MWK', 'symbol' => 'MWK', 'right_position' => 1, 'show_code' => 0],
            'MXN' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'MYR' => ['html' => 'RM', 'symbol' => 'RM', 'right_position' => 0, 'show_code' => 1],
            'MZN' => ['html' => 'MT', 'symbol' => 'MT', 'right_position' => 0, 'show_code' => 1],
            'NAD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'NGN' => ['html' => '₦', 'symbol' => '₦', 'right_position' => 0, 'show_code' => 1],
            'NIO' => ['html' => 'C$', 'symbol' => 'C$', 'right_position' => 0, 'show_code' => 1],
            'NOK' => ['html' => 'kr', 'symbol' => 'kr', 'right_position' => 0, 'show_code' => 1],
            'NPR' => ['html' => '₨', 'symbol' => '₨', 'right_position' => 0, 'show_code' => 1],
            'NZD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'OMR' => ['html' => '﷼', 'symbol' => '﷼', 'right_position' => 0, 'show_code' => 1],
            'PAB' => ['html' => 'B/.', 'symbol' => 'B/.', 'right_position' => 0, 'show_code' => 1],
            'PEN' => ['html' => 'S/.', 'symbol' => 'S/.', 'right_position' => 0, 'show_code' => 1],
            'PGK' => ['html' => 'PGK', 'symbol' => 'PGK', 'right_position' => 1, 'show_code' => 0],
            'PHP' => ['html' => '₱', 'symbol' => '₱', 'right_position' => 0, 'show_code' => 1],
            'PKR' => ['html' => '₨', 'symbol' => '₨', 'right_position' => 0, 'show_code' => 1],
            'PLN' => ['html' => 'zł', 'symbol' => 'zł', 'right_position' => 1, 'show_code' => 1],
            'PYG' => ['html' => 'Gs', 'symbol' => 'Gs', 'right_position' => 0, 'show_code' => 1],
            'QAR' => ['html' => '﷼', 'symbol' => '﷼', 'right_position' => 0, 'show_code' => 1],
            'RON' => ['html' => 'lei', 'symbol' => 'lei', 'right_position' => 0, 'show_code' => 1],
            'RSD' => ['html' => 'Дин.', 'symbol' => 'Дин.', 'right_position' => 0, 'show_code' => 1],
            'RUB' => ['html' => '₽', 'symbol' => '₽', 'right_position' => 1, 'show_code' => 1],
            'RWF' => ['html' => 'RWF', 'symbol' => 'RWF', 'right_position' => 1, 'show_code' => 0],
            'SAR' => ['html' => '﷼', 'symbol' => '﷼', 'right_position' => 1, 'show_code' => 1],
            'SBD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'SCR' => ['html' => '₨', 'symbol' => '₨', 'right_position' => 0, 'show_code' => 1],
            'SDG' => ['html' => 'SDG', 'symbol' => 'SDG', 'right_position' => 1, 'show_code' => 0],
            'SEK' => ['html' => 'kr', 'symbol' => 'kr', 'right_position' => 1, 'show_code' => 1],
            'SGD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'SHP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'SLL' => ['html' => 'SLL', 'symbol' => 'SLL', 'right_position' => 1, 'show_code' => 0],
            'SOS' => ['html' => 'S', 'symbol' => 'S', 'right_position' => 0, 'show_code' => 1],
            'SRD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'STN' => ['html' => '₡', 'symbol' => '₡', 'right_position' => 0, 'show_code' => 1],
            'SVC' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'SYP' => ['html' => '£', 'symbol' => '£', 'right_position' => 0, 'show_code' => 1],
            'SZL' => ['html' => 'SZL', 'symbol' => 'SZL', 'right_position' => 1, 'show_code' => 0],
            'THB' => ['html' => '฿', 'symbol' => '฿', 'right_position' => 1, 'show_code' => 1],
            'TJS' => ['html' => 'SM', 'symbol' => 'SM', 'right_position' => 0, 'show_code' => 1],
            'TMT' => ['html' => 'TMT', 'symbol' => 'TMT', 'right_position' => 1, 'show_code' => 0],
            'TND' => ['html' => 'DT', 'symbol' => 'DT', 'right_position' => 0, 'show_code' => 1],
            'TOP' => ['html' => 'TOP', 'symbol' => 'TOP', 'right_position' => 1, 'show_code' => 0],
            'TRY' => ['html' => '₺', 'symbol' => '₺', 'right_position' => 1, 'show_code' => 1],
            'TTD' => ['html' => 'TT$', 'symbol' => 'TT$', 'right_position' => 0, 'show_code' => 1],
            'TVD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'TWD' => ['html' => 'NT$', 'symbol' => 'NT$', 'right_position' => 0, 'show_code' => 1],
            'TZS' => ['html' => 'TSh', 'symbol' => 'TSh', 'right_position' => 0, 'show_code' => 1],
            'UAH' => ['html' => '₴', 'symbol' => '₴', 'right_position' => 0, 'show_code' => 1],
            'UGX' => ['html' => 'Sh', 'symbol' => 'Sh', 'right_position' => 0, 'show_code' => 1],
            'USD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'UYU' => ['html' => '$U', 'symbol' => '$U', 'right_position' => 0, 'show_code' => 1],
            'UZS' => ['html' => 'лв', 'symbol' => 'лв', 'right_position' => 0, 'show_code' => 1],
            'VEF' => ['html' => 'Bs', 'symbol' => 'Bs', 'right_position' => 0, 'show_code' => 1],
            'VND' => ['html' => '₫', 'symbol' => '₫', 'right_position' => 1, 'show_code' => 1],
            'VUV' => ['html' => 'VUV', 'symbol' => 'VUV', 'right_position' => 1, 'show_code' => 0],
            'WST' => ['html' => 'WST', 'symbol' => 'WST', 'right_position' => 1, 'show_code' => 0],
            'XAF' => ['html' => 'Fr', 'symbol' => 'Fr', 'right_position' => 0, 'show_code' => 1],
            'XCD' => ['html' => '$', 'symbol' => '$', 'right_position' => 0, 'show_code' => 1],
            'XDR' => ['html' => 'XDR', 'symbol' => 'XDR', 'right_position' => 1, 'show_code' => 0],
            'XOF' => ['html' => 'Fr', 'symbol' => 'Fr', 'right_position' => 0, 'show_code' => 1],
            'XPF' => ['html' => '₣', 'symbol' => '₣', 'right_position' => 0, 'show_code' => 1],
            'YER' => ['html' => '﷼', 'symbol' => '﷼', 'right_position' => 0, 'show_code' => 1],
            'ZAR' => ['html' => 'R', 'symbol' => 'R', 'right_position' => 0, 'show_code' => 1],
            'ZMW' => ['html' => 'ZMW', 'symbol' => 'ZMW', 'right_position' => 1, 'show_code' => 0],
            'ZWD' => ['html' => 'Z$', 'symbol' => 'Z$', 'right_position' => 0, 'show_code' => 1],
        ];
    }
}
