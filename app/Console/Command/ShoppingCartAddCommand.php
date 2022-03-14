<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\Dto\CartInputDto;
use App\CommandProcessor\ShoppingCartAddProcessor;

class ShoppingCartAddCommand extends Command
{
    protected static $defaultName = 'cart:process --';
    
    protected function configure(): void 
    {
        $this
            ->addArgument('product_identifier', InputArgument::REQUIRED, 'Product identifier.')
            ->addArgument('product_name', InputArgument::REQUIRED, 'Product name.')
            ->addArgument('quantity', InputArgument::REQUIRED, 'Quantity.')
            ->addArgument('price', InputArgument::REQUIRED, 'Price.')
            ->addArgument('price_currency', InputArgument::REQUIRED, 'Currency')
            ->setDescription('Processing shopping cart (add/update/delete item). '
                    . 'Input data format : {identifier} "{name}" {quantity} {price} {currency}.')
            ->setHelp('This command for shopping cart.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cartInputDto = new CartInputDto();        
        $cartInputDto->identifier = $input->getArgument('product_identifier');
        $cartInputDto->name = $input->getArgument('product_name');
        $cartInputDto->quantity = $input->getArgument('quantity');
        $cartInputDto->price = $input->getArgument('price');
        $cartInputDto->currency = strtoupper($input->getArgument('price_currency'));
        
        $shoppingCartAddProcessor = new ShoppingCartAddProcessor($cartInputDto);    
                
        $output->writeln($shoppingCartAddProcessor->execute());
        
        return Command::SUCCESS;
    }
}