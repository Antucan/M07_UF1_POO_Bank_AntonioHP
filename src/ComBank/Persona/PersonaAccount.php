<?php
namespace ComBank\Persona;
use ComBank\Support\Traits\ApiTrait;
use ComBank\Bank\BankAccount;

class PersonaAccount
{
    use ApiTrait;
    protected $name;
    protected $idCard;
    //function que llama a validar el correo
    protected $email;

    function __construct($name, $idCard, $email)
    {
        $this->name = $name;
        $this->idCard = $idCard;
        if ($this->validateEmail($email)){
            $this->email = $email;
            echo "Buen correo bro";
        } else {
            echo "Mal correo bro";
        }
    }



}