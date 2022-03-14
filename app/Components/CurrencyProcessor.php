<?php

namespace App\Components;

class CurrencyProcessor
{
    //currencies list
    protected $currencyList = [
        'EUR' => ['rate' => 1],
        'USD' => ['rate' => 1.14],
        'GBP' => ['rate' => 0.88],
    ];
    
    //default currency
    protected $defCurrency = 'EUR';
    
    /**
     * Check currency name
     * 
     * @param type $currency
     * 
     * @return bool
     */
    public function isCorrectCurrency($currency) : bool
    {
        return array_key_exists($currency, $this->currencyList);
    }
    
    /**
     * Get default currency
     * 
     * @return string Currency name
     */
    public function getDefaultCurrency() : string
    {
        return $this->defCurrency;
    }
    
    /**
     * Convert currency
     * 
     * @param float $price Price value
     * @param string $currency Currency name
     * 
     * @return float Converted price
     */
    public function convert($price, $currency)
    {
        if (!$this->isCorrectCurrency($currency)) { // incorrect currency name
            return 0;
        }
        
        if ($currency == $this->defCurrency) { // default currency
            return $price;
        }
        
        $coef = $this->getCurrencyCoef($currency);
        
        return $price / $coef;
    }
    
    /**
     * Get currency coefficient 
     * 
     * @param string $currency
     * 
     * @return float Coefficient value
     */
    protected function getCurrencyCoef($currency) : float
    {
        return $this->currencyList[$currency]['rate'] ?? 1;
    }
}