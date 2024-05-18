<?php

namespace Controllers\User;

require_once __DIR__ . '/../../services/mysql/user/queries.php';
require_once __DIR__ . '/../../models/user.php';

use Services\MySql\Queries\User\QueryHolder as UserQueryHolder;
use Models\User;

/**
 * Controller for user operations
 */
class Controller
{
    /**
     * Authenticates a user on login and initializes a new session if successful with the user data
     * @return void
     */
    public static function authenticateUser()
    {
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            header("Location: /pec3/src/views/login.php");
            die();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = UserQueryHolder::validateLogin(new User($username, $password));

        if (!$user) {
            header("Location: /pec3/src/views/login.php?error=true");
            die();
        }

        session_start();

        $_SESSION['user_data'] = [
            'username' => $user->username,
            'name' => $user->name,
            'surname' => $user->surname
        ];

        header("Location: /pec3/src/views/index.php");
        die();
    }

    /**
     * Logs out a user and destroys the existing session
     * @return void
     */
    public static function logout()
    {
        session_start();
        session_destroy();

        header("Location: /pec3/src/views/login.php");
        die();
    }

    /**
     * Registers a new user if all fields are set and valid
     * @return void
     */
    public static function registerNewUser()
    {
        // Validate all fields are set
        $fields = ['username', 'password', 'confirm_password', 'name', 'surname'];

        foreach ($fields as $field) {
            if (!isset($_POST[$field])) {
                header("Location: /pec3/src/views/register.php?error=true");
                die();
            }
        }

        // Validate actual fields
        if (
            !Controller::isUsernameValid($_POST['username']) ||
            !Controller::arePasswordsValid($_POST['password'], $_POST['confirm_password']) ||
            !Controller::areNameAndSurnameValid($_POST['name'], $_POST['surname'])
        ) {
            header("Location: /pec3/src/views/signup.php?error=true");
            die();
        }

        // Validate username is unique
        if (UserQueryHolder::userExists($_POST['username'])) {
            header("Location: /pec3/src/views/signup.php?user_exists=true");
        }

        // Register user
        UserQueryHolder::insert(new User(
            $_POST['username'],
            $_POST['password'],
            $_POST['name'],
            $_POST['surname']
        ));

        header("Location: /pec3/src/views/login.php?signup_success=true");
        die();
    }

    /**
     * Changes the user's password if all fields are set and valid, then logs out the user and destroys the existing session
     * @return void
     */
    public static function changePassword()
    {
        // Validate passwords
        if (!isset($_POST['new_password']) || !isset($_POST['confirm_new_password']) || !Controller::arePasswordsValid($_POST['new_password'], $_POST['confirm_new_password'])) {
            header("Location: /pec3/src/views/profile.php?error=true");
            die();
        }

        // Change password
        session_start();
        UserQueryHolder::changePassword($_SESSION['user_data']['username'], $_POST['new_password']);
        Controller::logout();
    }

    /**
     * Checks if a username is valid
     * @param string $username The username to validate
     * @return bool true if the username is valid, false otherwise
     */
    protected static function isUsernameValid($username)
    {
        return preg_match("/^[a-zA-Z0-9_]{3,16}$/", $username);
    }

    /**
     * Checks if two passwords are matching and valid
     * @param string $password The password to validate
     * @param string $confirm_password The password confirmation to validate
     * @return bool true if the passwords are valid and match, false otherwise
     */
    protected static function arePasswordsValid($password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            return false;
        }

        return strlen($password) >= 6 && strlen($password) <= 64;
    }

    /**
     * Checks if a name and surname are valid
     * @param string $name The name to validate
     * @param string $surname The surname to validate
     * @return bool true if the name and surname values are valid, false otherwise
     */
    protected static function areNameAndSurnameValid($name, $surname)
    {
        return strlen($name) >= 3 && strlen($name) <= 64 && strlen($surname) >= 3 && strlen($surname) <= 64;
    }
}
