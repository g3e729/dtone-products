<?php

namespace App\Services;

use Illuminate\Support\Arr;

class DToneService
{
    protected $client = null;

    protected $credentials = null;

    protected $currency = 'GBP';

    protected $url = 'https://dvs-api.dtone.com/v1';

    protected $all_products = false;

    protected $per_page = 100;

    protected $fee = null;

    public function setCredentials($currency = 'GBP')
    {
        $currency = is_null($currency) || empty($currency) ? 'GBP' : $currency;
        $currency = strtoupper($currency);

        $this->currency = $currency;
        $this->credentials = config("dtone.credentials.{$currency}");

        return $this;
    }

    public function getBalance()
    {
        return $this->apiCall('/balances');
    }

    public function getAllProducts($iso = null)
    {
        $this->all_products = true;
        $amounts = [];
        $cache_index = 'DT.getProducts.'.$this->currency.'.all';

        if (\Cache::has($cache_index) && \Cache::get($cache_index) != false) {
            return \Cache::get($cache_index);
        }

        $page = 1;
        $callAgain = true;
        $products = [];

        do {
            $callAgain = false;

            if ($page % 10 == 0) {
                sleep(1);
            }

            $url = "/products?per_page={$this->per_page}&page={$page}";

            if (! is_null($iso)) {
                $url .= "&country_iso_code={$iso}";
            }

            $result = $this->apiCall($url);

            if (is_array($result) && count($result) > 0) {
                $products = array_merge($products, $result);

                if (count($result) == $this->per_page) {
                    $callAgain = true;
                    $page++;
                }
            }
        } while ($callAgain == true);

        foreach ($products as $product) {
            $amount = $product['destination']['amount'];

            if (is_array($amount)) {
                continue;
            }

            $amounts[] = $this->setProductsAttr($product);
        }

        $amounts = array_values(\Arr::sort($amounts, function ($value) {
            return $value['Price'];
        }));

        \Cache::put($cache_index, $amounts, 3200);

        return $amounts;
    }

    private function setProductsAttr($product)
    {
        try {
            $retail = $product['prices']['retail'];
            $source = $product['source'];
            $info = $product['destination'];
            $SkuCode = $product['id'];
            $amount = $product['destination']['amount'];
            $Maximum = ['SendValue' => $amount];
            $Minimum = ['SendValue' => $amount];
            $SearchableValue = $amount;
            $Value = $amount;

            if (! is_null($this->fee)) {
                $Price = $Value + processingFee($Value, $this->fee);
            } else {
                $Price = $Value + processingFee($Value);
            }

            $Currency = $info['unit'];
            $PriceCurrency = $retail['unit'];
            $Fee = $Price - $Value;
            $PercentFee = ($Fee / $Value) * 100;
            $DefaultDisplayText = amountCurrency($info['amount'], $info['unit']);
            $Description = $product['description'];

            // Format numbers
            $BeforeTax = $amount;
            $Value = price($Value);
            $Fee = price($Fee);

            $PercentDiscount = $this->discount ?? 0;

            $Price = price($Price);
            $BeforeTax = price($BeforeTax);

            $BeforeTaxDisplay = amountCurrency($BeforeTax, $PriceCurrency);
            $PriceDisplay = amountCurrency($Price, $PriceCurrency);
            $FeeDisplay = amountCurrency($Fee, $PriceCurrency);
            $CurrencySymbol = getCurrencySymbol($PriceCurrency);

            $RedemptionMechanism = \Str::contains($product['type'], ['PIN']) ? 'ReadReceipt' : 'Immediate';
            $ThirdParty = 'Dtone';

            if ($RedemptionMechanism == 'Immediate' && \Str::contains(strtolower($product['operator']['name']), ['pin'])) {
                $RedemptionMechanism = 'ReadReceipt';
            }

            $operator_id = $product['operator']['id'];
            $ProviderCode = $network_codes[$operator_id] ?? $operator_id;
            $Benefits = \Arr::pluck($product['benefits'], 'type');
            $Instructions = @$product['pin']['usage_info'];

            return array_merge($product, compact(
                'BeforeTax',
                'BeforeTaxDisplay',
                'Benefits',
                'Currency',
                'CurrencySymbol',
                'DefaultDisplayText',
                'Description',
                'Fee',
                'FeeDisplay',
                'Instructions',
                'Maximum',
                'Minimum',
                'PercentDiscount',
                'PercentFee',
                'Price',
                'PriceCurrency',
                'PriceDisplay',
                'ProviderCode',
                'RedemptionMechanism',
                'SearchableValue',
                'SkuCode',
                'ThirdParty',
                'Value'
            ));
        } catch (\Exception $ex) {
            logException(__METHOD__, $ex, compact('ex', 'product'));
        }
    }

    private function apiCall($path, $method = 'GET', $body = null)
    {
        try {
            if (is_null($this->credentials)) {
                $this->setCredentials();
            }

            $data = [
                'auth' => [
                    $this->credentials['DTONE_KEY'],
                    $this->credentials['DTONE_SECRET'],
                ],
            ];

            if (! is_null($body)) {
                $data['headers'] = [
                    'content-type' => 'application/json',
                ];
                $data['body'] = json_encode($body);
            }

            $response = (new \GuzzleHttp\Client)->request($method, "{$this->url}{$path}", $data);
            $contents = $response->getBody()->getContents();

            $data = false;

            if (is_string($contents)) {
                $data = json_decode($contents, true);
            }

            return $data;
        } catch (\Exception $exception) {
            $code = $exception->getCode();
            $message = $exception->getMessage();

            if (! \Str::contains($message, ['number is invalid']) && $code != 404) {
                logException(__METHOD__, $exception, compact('exception', 'body', 'path'));
            }
        }

        return false;
    }
}
