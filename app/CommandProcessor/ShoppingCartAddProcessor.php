<?php

namespace App\CommandProcessor;

use App\Dto\CartInputDto;
use App\Exceptions\CommonException;
use App\Components\CurrencyProcessor;
use App\Components\StorageProcessor;
use App\Components\TotalProcessor;

class ShoppingCartAddProcessor
{
    protected $dto = null;
    
    protected $message = '';
    
    protected $currencyProcessor;
    protected $storageProcessor;
    protected $storagePath;
    
    public function __construct(CartInputDto $dto)
    {
        $this->dto = $dto;
        
        $this->storagePath = STORAGE_FOLDER . DS . STORAGE_FILE;
        
        $this->currencyProcessor = new CurrencyProcessor();
        $this->storageProcessor = new StorageProcessor($this->storagePath);   
    }
    
    /**
     * Execute command functionality and return message 
     * 
     * @return string Command output text
     */
    public function execute() : string
    {
        try {
            $this->validate();
            $this->insert();
            
            return $this->read();            
        } catch (CommonException $ex) {
            return $ex->getMessage();
        }
    }
    
    /**
     * Validate input arguments  and storage status
     * @throws CommonException
     */
    protected function validate()
    {
        //chech storage folder
        if (!is_writable(STORAGE_FOLDER)) {
            throw new CommonException('Storage folder is not writeable.'); 
        }
        
        //check if correct currency
        if ($this->dto->quantity > 0 && !$this->currencyProcessor->isCorrectCurrency($this->dto->currency)) {
            throw new CommonException('Incorrect currency value.');
        }
    }
    
    /**
     * Insert input in storage file
     * 
     * @return void
     * @throws CommonException
     */
    protected function insert() : void
    {
        //check if storage file exist
        if (!file_exists($this->storagePath) && !fopen($this->storagePath, "w+")) {
            throw new CommonException('Storage file was not created.'); 
        }
        
        $this->storageProcessor->insertInStorage($this->dto);
    }
    
    /**
     * Read storage information and calculate total
     * 
     * @return string
     */
    protected function read() : string
    {
        $storageRows = $this->storageProcessor->readFromStorage();
        
        $totalProcessor = new TotalProcessor($storageRows);
        $total = $totalProcessor->calculate();
        
        return 'Cart total ' . $total['price'] . ' ' . $total['currency'];
    }
}