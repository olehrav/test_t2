<?php

namespace App\Components;

use App\Dto\CartInputDto;

class StorageProcessor
{
    //Storage path
    protected $storagePath;
    
    public function __construct(String $path)
    {
        $this->storagePath = $path;
    }
    
    /**
     * Insert row in storage file
     * 
     * @param CartInputDto $dto
     */
    public function insertInStorage(CartInputDto $dto)
    {
        $items = [];
        
        if ($dto->quantity > 0 || $dto->quantity < 0) {
            $items[] = $dto->identifier;
            $items[] = $dto->name;
            $items[] = $dto->quantity;
            $items[] = ($dto->quantity < 0) ? '' : $dto->price;
            $items[] = ($dto->quantity < 0) ? '' : $dto->currency;
        } 
        
        if ($items) {
            $line = implode(';', $items);
            
            $fh = fopen($this->storagePath, "a+");
            fwrite($fh, $line."\n");
            fclose($fh);
        }
    }
    
    /**
     * Read storage file
     * 
     * @return array Storage file content
     */
    public function readFromStorage() : array
    {
        $rows = [];
        
        $handle = fopen($this->storagePath, "r");       
        if ($handle === FALSE) {
            return $rows;
        }
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if (count($data) != 5 || $data[2] == 0) {
                continue;
            }

            if ($data[2] < 0) { //item removing
                unset($rows[$data[0]]);
            } else { //item adding or updating
                $rows[$data[0]][$data[4]] = $data;
            }
        }
        
        fclose($handle);
        
        return $rows;
    }
}