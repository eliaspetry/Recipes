<?php

namespace Models;

require_once __DIR__ . '/model.php';

/**
 * User DB columns/fields trait
 */
trait UserFields
{
    public string $username;
    public string $password;
    public string $password_hash;
    public ?string $name;
    public ?string $surname;
}

/**
 * Recipe model
 */
class User extends Model
{
    use UserFields;

    /**
     * User constructor
     * @param string $username The username
     * @param string $password The password
     * @param ?string $name The name of the user
     * @param ?string $surname The surname of the user
     * @return void
     */
    public function __construct($username, $password, $name = null, $surname = null)
    {
        $this->username = $username;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->encryptPassword();
    }

    /**
     * Encrypts the password into a separate property on the model, using the Blowfish algorithm
     * @return void
     */
    public function encryptPassword()
    {
        $this->password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    }
}
