<?php
namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $newBalance = $bankAccount->getBalance() - $this->amount;
        if (!$bankAccount->getOverdraft()->isGrantOverdraftFunds($newBalance)) {
            throw new InvalidOverdraftFundsException('You withdraw has reach the max overdraft funds.');
        }
        return $newBalance;
    }
    public function getTransactionInfo(): string
    {
        return "DEPOSIT_TRANSACTION";

    }
    public function getAmount(): float
    {
        return $this->amount;
    }
}

