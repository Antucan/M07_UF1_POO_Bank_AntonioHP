<?php
namespace ComBank\Bank;

use ComBank\Bank\BankAccount;
use ComBank\Support\Traits\ApiTrait;
class InternationalBankAccount extends BankAccount
{
    function getConvertedBalance():float{
        $this->convertBalance(100);
        return $this->getBalance();
    }
    function getConvertedCurrency():string{
        return "$";
    }
}