<?php

namespace Services\MySql\Queries\User;

require_once __DIR__ . '/../connector.php';
require_once __DIR__ . '/../../../models/user.php';

use DB;
use Models\User;

/**
 * User queries
 */
class QueryHolder
{
    /**
     * Creates a new user
     * @param User $user The user to create
     * @return void
     */
    public static function insert($user)
    {
        $user->password = $user->password_hash;
        unset($user->password_hash);
        DB::insert('users_pec3', (array)$user);
    }

    /**
     * Validates user login
     * @param User $user The user to validate
     * @return User|false The user, or false if invalid
     */
    public static function validateLogin($user)
    {
        $row = DB::queryFirstRow('SELECT * FROM users_pec3 WHERE username=%s', $user->username);
        return $row && password_verify($user->password, $row['password']) ? new User($row['username'], $row['password'], $row['name'], $row['surname']) : false;
    }

    /**
     * Checks if a username already exists to prevent DB failures on duplicate attempts (username key is unique in DB)
     * @param string $username The username to check
     * @return bool True if the given username already exists, else false
     */
    public static function userExists($username)
    {
        $row = DB::queryFirstRow('SELECT * FROM users_pec3 WHERE username=%s', $username);
        return $row != null;
    }

    /**
     * Changes a user's password
     * @param string $username The username of the user for whom to change the password
     * @param string $new_password The new password
     */
    public static function changePassword($username, $new_password)
    {
        $user = new User($username, $new_password);
        DB::query('UPDATE users_pec3 SET password=%s WHERE username=%s', $user->password_hash, $user->username);
    }
}
