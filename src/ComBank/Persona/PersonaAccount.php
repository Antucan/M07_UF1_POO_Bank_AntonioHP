<?php
namespace ComBank\Persona;
use ComBank\Support\Traits\ApiTrait;
use ComBank\Bank\BankAccount;

class PersonaAccount
{
    use ApiTrait;
    protected $name;
    protected $idCard;
    protected $email;

    function __construct($name, $idCard, $email)
    {
        $this->name = $name;
        $this->idCard = $idCard;
        if ($this->validateEmail($email)) {
            $this->email = $email;
            pl("Email is valid -- " . $email);
        } else {
            pl("Invalid email address -- " . $email);
        }
    }



}