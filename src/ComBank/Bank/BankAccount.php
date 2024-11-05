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

class BankAccount implements BankAccountInterface
{
    private $balance;
    private $status;
    private $overdraft;
    private $currency;

    /**
     * Setting the constructor for the account
     */
    function __construct($balance)
    {
        $this->balance = $balance;
        $this->status = BankAccountInterface::STATUS_OPEN;
        $this->overdraft = new NoOverdraft;
    }
    public function applyOverdraft(OverdraftInterface $overdraft): void
    {
        $this->overdraft = $overdraft;
    }


    /**
     * Setting isOpen  method to check if the account is open
     */
    public function isOpen(): bool
    {
        return $this->status === BankAccountInterface::STATUS_OPEN;
    }
    /**
     * Used to close the bank account
     */
    public function closeAccount(): void
    {
        if ($this->status == BankAccountInterface::STATUS_CLOSED) {
            pl('Error: My account is already closed');
        } else {
            $this->status = BankAccountInterface::STATUS_CLOSED;
            pl('My account is now closed');
        }
    }

    /**
     * Used to open the bank account
     */
    public function reopenAccount(): void
    {
        $this->status = BankAccountInterface::STATUS_OPEN;
        pl('My account is now reopened');
    }

    /**
     * Used to make transactions
     */
    public function transaction(BankTransactionInterface $bankTransaction): void
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
    public function setBalance($balance): void
    {
        $this->balance = $balance;

    }

    /**
     * Get the value of overdraft
     */
    public function getOverdraft(): OverdraftInterface
    {
        return $this->overdraft;
    }
}


