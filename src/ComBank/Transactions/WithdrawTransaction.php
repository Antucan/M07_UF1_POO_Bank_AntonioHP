<?php
namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Support\Traits\ApiTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    use ApiTrait;
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        if ($this->detectFraud($this)) {
            throw new FailedTransactionException('Blocked by possible fraud');
        }
        $newBalance = $bankAccount->getBalance() - $this->amount;
        if (!$bankAccount->getOverdraft()->isGrantOverdraftFunds($newBalance)) {
            throw new InvalidOverdraftFundsException('You withdraw has reach the max overdraft funds.');
        }
        return $newBalance;
    }
    public function getTransactionInfo(): string
    {
        return "WITHDRAW_TRANSACTION";

    }
    public function getAmount(): float
    {
        return $this->amount;
    }
}

