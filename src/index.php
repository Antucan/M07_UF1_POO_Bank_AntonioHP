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
$account1 = new BankAccount(400);
pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    pl('My balance:' . $account1->getBalance());
    // crear una cuenta con balance 400 y mostrar su balance
    $bankAccount1 = new BankAccount(400);
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
    $bankAccount2 = new BankAccount(200.0);
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
$nationalAccount = new NationalBankAccount(500);
pl('My balance:' . $nationalAccount->getBalance() . ' €');

//---[International account]---/
pl('--------- [Start testing International account #4, No overdraft] --------');
$intAccount = new InternationalBankAccount(300);
pl('My balance:' . $intAccount->getBalance() . ' €');
pl('Converting balance to USD ...');
pl('My balance:' . $intAccount->getConvertedBalance() . $intAccount->getConvertedCurrency());

//---[Testing National account Mail]---/
pl('--------- [Start testing National account] --------');
$nationalMail = new NationalBankAccount(400);
$nationalMail->setPersona(new PersonaAccount('Alabau', 123, 'alabike@gmail.com'));

//---[Testing International account Mail]---/
pl('--------- [Start testing International account] --------');
$internationalMail = new InternationalBankAccount(400);
$internationalMail->setPersona(new PersonaAccount('Li', 234, 'li@alex'));

//---[Testing International account Fraud]---/
pl('--------- [Start testing International account FRAUD] --------');
$intFraudAcc = new InternationalBankAccount(200);
pl('Doing transaction deposit (+100000) with current balance ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());
try {
    $intFraudAcc->transaction(new DepositTransaction(100000));
} catch (FailedTransactionException $e) {
    pl($e->getMessage());
}
pl('My new balance after deposti (+100000) : ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());
pl('Doing transaction deposit (+5000) with current balance ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());
$intFraudAcc->transaction(new DepositTransaction(5000));
pl('My new balance after deposti (+5000) : ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());
pl('Doing transaction withdraw (+10000) with current balance ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());
try {
    $intFraudAcc->transaction(new WithdrawTransaction(10000));
} catch (FailedTransactionException $e) {
    pl($e->getMessage());
}
pl('My new balance after withdraw (+10000) : ' . $intFraudAcc->getConvertedBalance() . $intFraudAcc->getConvertedCurrency());

//---[Testing National account Fraud]---/
pl('--------- [Start testing National account FRAUD] --------');
$natFraudAcc = new NationalBankAccount(200);
pl('Doing transaction deposit (+100000) with current balance ' . $natFraudAcc->getBalance());
try {
    $natFraudAcc->transaction(new DepositTransaction(100000));
} catch (FailedTransactionException $e) {
    pl($e->getMessage());
}
pl('My new balance after deposti (+100000) : ' . $natFraudAcc->getBalance() . ' €');
pl('Doing transaction deposit (+5000) with current balance ' . $natFraudAcc->getBalance() . ' €');
$natFraudAcc->transaction(new DepositTransaction(5000));
pl('My new balance after deposti (+5000) : ' . $natFraudAcc->getBalance() . ' €');
pl('Doing transaction withdraw (+10000) with current balance ' . $natFraudAcc->getBalance() . ' €');
try {
    $natFraudAcc->transaction(new WithdrawTransaction(10000));
} catch (FailedTransactionException $e) {
    pl($e->getMessage());
}
pl('My new balance after withdraw (+10000) : ' . $natFraudAcc->getBalance() . ' €');

//---[Testing National account Phone Number]---/
pl('--------- [Start testing National account with correct Phone Number] --------');
$natFraudAcc = new InternationalBankAccount(200);
$natFraudAcc->setPersona(new PersonaAccount(0, 0, 'a@gmail.com', 608938062));
pl('--------- [Start testing National account with wrong Phone Number] --------');
$natFraudAcc2 = new InternationalBankAccount(200);
$natFraudAcc2->setPersona(new PersonaAccount(0, 0, 'a@gmail.com', 58938062));



