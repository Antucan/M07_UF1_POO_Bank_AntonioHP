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
    protected $phone;

    function __construct($name, $idCard, $email, $phone = 608938062)
    {
        $this->name = $name;
        $this->idCard = $idCard;
        if ($this->validateEmail($email)) {
            $this->email = $email;
            pl("Email is valid -- " . $email);
        } else {
            pl("Invalid email address -- " . $email);
        }
        // if ($this->validatePhoneNumber($phone)) {
        //     $this->phone = $phone;
        //     pl("The phone is valid -- " . $phone);
        // } else {
        //     pl("Invalid phone number -- " . $phone);
        // }
    }



}