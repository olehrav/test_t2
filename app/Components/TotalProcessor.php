<?php

namespace App\Components;

use App\Components\CurrencyProcessor;

class TotalProcessor
{
    //storage rows
    protected $storageRows = [];
    
    //currency processor
    protected $currencyProcessor;
    
    public function __construct(array $storageRows)
    {
        $this->storageRows = $storageRows;
        
        $this->currencyProcessor = new CurrencyProcessor();
    }
    
    /**
     * Total sum calculation
     * 
     * @return array Total sum and currency
     */
    public function calculate() : array
    {
        $currencyGroups = $this->groupByCurrency();
        
        return $this->calculateTotal($currencyGroups);
    }
    
    /**
     * Group storage rows by currency
     * 
     * @return array Groups
     */
    protected function groupByCurrency() : array
    {
        $groups = [];
        
        foreach ($this->storageRows as $productPrices) {
            foreach ($productPrices as $productPrice) {
                if (!isset($groups[$productPrice[4]])) {
                    $groups[$productPrice[4]] = 0;
                }

                $groups[$productPrice[4]] += $productPrice[3];
            }
        }
        
        return $groups;
    }
    
    /**
     * Calculate total 
     * 
     * @param type $groups
     * 
     * @return array Cart total
     */
    protected function calculateTotal($groups) : array
    {
        $totalPrice = 0;
        $totalCurrency = $this->currencyProcessor->getDefaultCurrency();
        
        foreach ($groups as $currency => $price) {
            $totalPrice += $this->currencyProcessor->convert($price, $currency);
        }
        
        return ['price' => round($totalPrice, 2), 'currency' => $totalCurrency];
    }
}