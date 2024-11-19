<?php
namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Support\Traits\ApiTrait;

class DepositTransaction extends BaseTransaction implements BankTransactionInterface
{
    use ApiTrait;
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        if ($this->detectFraud($this)){
            throw new FailedTransactionException('Blocked by possible fraud');
        }
        return $bankAccount->getBalance() + $this->amount;
    }
    
    public function getTransactionInfo(): string {
        return "DEPOSIT_TRANSACTION";
    }
    public function getAmount(): float{
        return  $this->amount;

    }
}
