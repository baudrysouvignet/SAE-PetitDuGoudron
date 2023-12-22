<?php
require_once ('../../../entity/userEntity.php');
use entity\userEntity;

/**
 * @param userEntity $user
 * @param string $mail
 * @param string $password
 * @return void
 *
 * Function to connect a user after form validation and set session .
 */
function connection(
    userEntity $user,
    string $mail,
    string $password
): void
{
    $isConnect = $user->connect(
        mail: strtolower($mail),
        password: $password
    );

    if ($isConnect) {
        $_SESSION['user_info'] = $isConnect[0]['ID_Utilisateur'];
    }
}

/**
 * @param userEntity $user
 * @param DatabaseManager $database
 * @param string $mail
 * @param string $password
 * @return bool
 *
 * Function to register a user after form validation
 */
function inscription(
    userEntity $user,
    DatabaseManager $database,
    string $mail,
    string $password
): bool
{
    $isMailUsed = $database->select(
        request: "SELECT ID_Utilisateur FROM User WHERE mail = :mail",
        param: [
            "mail" => strtolower($mail),
        ]
    );

    if (empty($isMailUsed)){
        $user->register (
            mail: strtolower($mail),
            password: $password
        );
        return true;
    }
    return false;
}

/**
 * @param DatabaseManager $database
 * @param int $userID
 * @return void
 *
 * Function to ban a user
 */
function banUser(
    DatabaseManager $database,
    int $userID
): void
{
    $database->insert(
        request: "UPDATE User SET role = 'ROLE_BANNED' WHERE ID_Utilisateur = :userID",
        param: [
            "userID" => $userID,
        ]
    );
}


/**
 * @param DatabaseManager $database
 * @param int $userID
 * @return void
 *
 * Function to unban a user
 */
function unBanUser(
    DatabaseManager $database,
    int $userID
): void
{
    $database->insert(
        request: "UPDATE User SET role = null WHERE ID_Utilisateur = :userID",
        param: [
            "userID" => $userID,
        ]
    );
}

/**
 * @param DatabaseManager $database
 * @return array
 *
 *  Function to recover all user
 */
function recoverAllUser (
    DatabaseManager $database
): array
{
    $query = $database->select(
        request: "SELECT * FROM User"
    );

    $userList = [];

    foreach ($query as $user) {
        $userList[$user['ID_Utilisateur']] = new userEntity(databaseManager: $database);
        $userList[$user['ID_Utilisateur']]->recoverUserData(userID: $user['ID_Utilisateur']);
    }

    return $userList;
}
