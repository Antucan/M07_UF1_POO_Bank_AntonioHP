<?php
namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:24 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Support\Traits\AmountValidationTrait;

abstract class BaseTransaction
{
    use AmountValidationTrait;
    protected float $amount;

    function __construct($amount)
    {
        // validar el amount y lanzar excepcion si neagativo
        
        $this->validateAmount($amount);
        $this->amount = $amount;

    }
    public function getAmount(): float
    {
        return $this->amount;

    }
    
}
