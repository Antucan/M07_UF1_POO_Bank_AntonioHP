<?php namespace ComBank\Support\Traits;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 2:35 PM
 */

use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;

trait AmountValidationTrait
{
    /**
     * @param float $amount
     * @throws InvalidArgsException
     * @throws ZeroAmountException
     */
    public function validateAmount(float $amount):void
    {
        if (!is_numeric($amount)){
            throw new InvalidArgsException('Invalid inserted value, the amount should be a numeric.');
        }
        if ($amount <= 0){
            throw new ZeroAmountException('The amount should be more than zero for this transaction.');
        }
    }
}
