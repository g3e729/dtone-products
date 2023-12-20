<?php

use App\Services\EmailService;
use App\Services\IpQualityScoreService;
use Illuminate\Support\Arr;

function price($amount, $decimal = 2, $AllowBelowZero = false)
{
    $amount = (float) $amount;
    $amount = str_replace(',', '', $amount);

    if ($AllowBelowZero && $amount < 0) {
        $amount = 0;
    }

    return number_format($amount, $decimal);
}

function getCurrencySymbol($currency = 'GBP')
{
    if (\Cache::has('amountCurrency') && \Cache::get('amountCurrency') != false) {
        $currencies = \Cache::get('amountCurrency');
    } else {
        $currencies = \App\Models\Currency::get();

        \Cache::put('amountCurrency', $currencies, 86400);
    }

    $curOption = $currencies->where('code', $currency)->first();

    return $curOption->html ?? '';
}

function amountCurrency($amount, $currency = 'GBP')
{
    $amount = (float) $amount;
    $amount = $amount + 0;

    // if $currency is equal to '' default value is ignored.
    if (empty($currency)) {
        $currency = 'GBP';
    }

    if (\Cache::has('amountCurrency') && \Cache::get('amountCurrency') != false) {
        $currencies = \Cache::get('amountCurrency');
    } else {
        $currencies = \App\Models\Currency::get();

        \Cache::put('amountCurrency', $currencies, 86400);
    }

    $curOption = null;

    if (! is_null($currencies)) {
        $curOption = $currencies->where('code', $currency)->first();
    }

    if (is_null($curOption)) {
        return $amount.$currency;
    }

    if ($curOption->right_position) {
        $amount .= $curOption->html;
    } else {
        $amount = $curOption->html.$amount;
    }

    if ($curOption->show_code) {
        $amount .= " {$curOption->code}";
    }

    return $amount;
}

function logException($path, $exception, $data = [], $info = false)
{
    $exMsg = method_exists($exception, 'getResponse') && $exception->getResponse() ?
        $exception->getResponse()->getBody()->getContents() :
        $exception->getMessage();

    $message = $path;
    $message .= ':('.$exception->getCode().')L';
    $message .= $exception->getLine().':'.$exMsg;

    if ($info) {
        return \Illuminate\Support\Facades\Log::info($message, $data);
    } else {
        return \Illuminate\Support\Facades\Log::error($message, $data);
    }
}

function processingFee($price = 0, $percent = null)
{
    if (is_null($percent)) {
        $percent = config('fee.percentage');
    }

    try {
        return number_format($price * ($percent / 100), 2, '.', '');
    } catch (\Exception $ex) {
        logException(__METHOD__, $ex);
    }

    // return '7.49';
    return '14.98';
}
