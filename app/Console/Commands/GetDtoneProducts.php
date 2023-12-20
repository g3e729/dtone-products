<?php

namespace App\Console\Commands;

use App\AllProductRaw;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Plustelecom\Statsd\Statsd;

class GetDtoneProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_all_products {--currency=?} {--country=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all country DTOne products';

    protected $table = 'products_gb';

    protected $startTime = null;

    protected $country = 'gb';

    protected $currency = 'gbp';

    protected $provider = null;

    protected $products = [];

    protected $reason = null;

    protected $totalInsert = 0;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('Scraping of products may take time. Please wait.');
            $this->country = $this->option('country') != '?' ? $this->option('country') : 'it';
            $this->country = strtoupper($this->country);
            $this->currency = $this->option('currency') != '?' ? $this->option('currency') : 'gbp';

            if (! $this->retrieve()) {
                dd('error');
            }

            $this->store();
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }

    private function retrieve()
    {
        $countries = config('country_iso');
        $country = $countries[$this->country];

        try {
            $this->products = (new \App\Services\DToneService)->setCredentials($this->currency)->getAllProducts($country);
        } catch (\Exception $e) {
            logException(__METHOD__, $e);
            $this->error('[DTone]'.$e->getMessage());
        }

        return true;
    }

    private function store()
    {
        if (count($this->products) < 1) {
            return;
        }

        $countries = config('country_iso');

        if ($this->currency == 'gbp') {
            $this->table = 'products_gb';
        }

        if ($this->currency == 'eur') {
            $this->table = 'products_it';
        }

        if ($this->currency == 'usd') {
            $this->table = 'products_us';
        }

        $bar = $this->output->createProgressBar(count($this->products));
        $bar->start();

        $productTracker = [];
        $inserted = 0;

        \DB::table($this->table)->truncate();

        foreach ($this->products as $product) {
            $jsonData = json_encode($product);
            $network = $product['operator'] ?? null;
            $benefits = $product['Benefits'] ?? \Arr::pluck($product['benefits'], 'type');

            if (is_null($network)) {
                $network = getNetwork($product['ProviderCode']);
                $countryIso = $network['CountryIso'];
            } else {
                $iso = strtoupper($network['country']['iso_code']);
                $countryIso = $countries[$iso] ?? $iso;
            }

            if (! isset($productTracker[$product['ProviderCode']])) {
                $productTracker[$product['ProviderCode']] = 0;
            }

            try {
                $data = [
                    'network_code' => $product['ProviderCode'],
                    'network' => $network['Name'] ?? $network['name'],
                    'country_iso' => $countryIso,
                    'sku_code' => $product['SkuCode'],
                    'name' => $product['DefaultDisplayText'],
                    'description' => $product['Description'] ?? null,
                    'value' => str_replace(',', '', $product['Value']),
                    'value_currency' => $product['Currency'],
                    'price' => str_replace(',', '', $product['BeforeTax']),
                    'price_currency' => strtoupper($this->currency),
                    'supplier_charge' => $product['source']['amount'],
                    'supplier_charge_currency' => $product['source']['unit'],
                    'benefits' => json_encode($benefits),
                    'data' => $jsonData,
                ];

                \DB::table($this->table)->insert($data);

                // exclude DATA
                if (! \Str::contains($jsonData, [
                    '"type":"DATA"',
                    '"unit_type":"DATA"',
                    '"Benefits":["Mobile","Minutes","Data"]',
                    '"Benefits":["Mobile","Data"]',
                    '"Benefits":["Data"]',
                ])) {
                    \DB::table($this->table)->insert($data);
                }

                $bar->advance();

                $inserted++;
                $productTracker[$product['ProviderCode']]++;
            } catch (\Exception $e) {
                logException(__METHOD__, $e);
                $this->error($e->getMessage());
            }
        }

        $this->totalInsert = $inserted;
        $bar->finish();
        echo PHP_EOL;
    }
}
