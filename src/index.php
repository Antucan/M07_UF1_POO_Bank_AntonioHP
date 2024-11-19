<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\Bank\InternationalBankAccount;
use ComBank\Bank\NationalBankAccount;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Persona\PersonaAccount;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Transactions\BaseTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\ZeroAmountException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Support\Traits\ApiTrait;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
$account1 = new BankAccount(new PersonaAccount(0, 0, 'a@gmail.com'), 400);
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    pl('My balance:' . $account1->getBalance());
    // crear una cuenta con balance 400 y mostrar su balance
    $bankAccount1 = new BankAccount(new PersonaAccount(0, 0, 'a@gmail.com'), 400);
    // close account
    $bankAccount1->closeAccount();
    // reopen account
    $bankAccount1->reopenAccount();
    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150.0));
    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(25.0));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600.0));
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (InvalidOverdraftFundsException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());
$bankAccount1->closeAccount();

//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {
    $bankAccount2 = new BankAccount(new PersonaAccount(0, 0, 'a@gmail.com'), 200.0);
    // show balance account
    pl('My balance:' . $bankAccount2->getBalance());
    $bankAccount2->applyOverdraft(new SilverOverdraft());
    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100.0));
    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300.0));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50.0));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120.0));
} catch (InvalidOverdraftFundsException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(20.0));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());
$bankAccount2->closeAccount();
$bankAccount2->closeAccount();
try {

} catch (BankAccountException $e) {
    pl($e->getMessage());
}
//---[National account]---/
pl('--------- [Start testing National account #3, No overdraft] --------');
$nationalAccount = new NationalBankAccount(new PersonaAccount(0, 0, 'a@gmail.com'), 500);
pl('My balance:' . $nationalAccount->getBalance() . ' €');

//---[International account]---/
pl('--------- [Start testing International account #4, No overdraft] --------');
$intAccount = new InternationalBankAccount(new PersonaAccount(0, 0, 'a@gmail.com'), 300);
pl('My balance:' . $intAccount->getBalance() . ' €');
pl('Converting balance to USD ...');
pl('My balance:' . $intAccount->getConvertedBalance() . $intAccount->getConvertedCurrency());

//---[Testing National account Mail]---/
pl('--------- [Start testing National account] --------');
$nationalMail = new NationalBankAccount(new PersonaAccount('Alabau', 123, 'alabike@gmail.com'), 400);

//---[Testing International account Mail]---/
pl('--------- [Start testing International account] --------');
$internationalMail = new InternationalBankAccount(new PersonaAccount('Li', 234, 'li@alex'), 400);

//---[Testing National account Fraud]---/
pl('--------- [Start testing National account FRAUD #1] --------');

