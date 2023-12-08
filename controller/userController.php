<?php
require_once '../entity/userEntity.php';
use entity\userEntity;

function connection(
    userEntity $user,
    string $mail,
    string $password
): string
{

    $isConnect = $user->connect(
        mail: $mail,
        password: $password
    );

    if ($isConnect) {
        $_SESSION['user_info'] = $isConnect;
        return "Vous êtes connecté";
    } else {
        return "Identifiants ou mot de passe incorrect";
    }
}



