<?php
    require_once __DIR__ . '/../../controllers/user/user.php';

    use \Controllers\User\Controller as UserController;

    UserController::registerNewUser();
?>