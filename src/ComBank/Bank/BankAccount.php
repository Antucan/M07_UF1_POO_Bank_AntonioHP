<?php
namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;
use ComBank\Transactions\DepositTransaction;
use PhpParser\Node\Stmt\TryCatch;

class BankAccount
{
    private $balance;
    private $status;
    private $overdraft;

    /**
     * Setting the constructor for the account
     */
    function __construct($balance)
    {
        $this->balance = $balance;
    }

    /**
     * Used to close the bank account
     */
    public function closeAccount(): void
    {
        $this->status = BankAccountInterface::STATUS_CLOSED;
    }

    /**
     * Used to open the bank account
     */
    public function openAccount(): void
    {
        $this->status = BankAccountInterface::STATUS_OPEN;
    }

    /**
     * Used to make transactions
     */
    public function transaction(DepositTransaction $bankTransaction): void
    {

        $newBalance = $bankTransaction->applyTransaction($this);
        $this->setBalance($newBalance);

    }

    /**
     * Get the value of balance
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Set the value of balance
     *
     * @return  self
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get the value of overdraft
     */
    public function getOverdraft()
    {
        return $this->overdraft;
    }
}


