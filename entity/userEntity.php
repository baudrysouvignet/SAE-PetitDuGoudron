<?php
namespace entity;

use \DatabaseManager;

class userEntity
{
    private $databaseManager;
    public ?array $loggedInUser = null;

    public function __construct(
        DatabaseManager $databaseManager
    )
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param string $mail
     * @param string $password
     *
     * @return bool
     *
     * Function to connect a user after form validation
     */
    public function connect(
        string $mail,
        string $password
    ): bool|array
    {
        $user = $this->databaseManager->select(
            request: "SELECT ID_Utilisateur, password FROM User WHERE mail = :mail",
            param: [
                "mail" => $mail,
            ]
        );

        if (!empty($user)) {
            $isGoodPassword = password_verify(
                $password,
                $user[0]["password"]
            );

            if ($isGoodPassword) {
                $dataUser = $this->recoverUserData (
                    userID: $user[0]["ID_Utilisateur"]
                );

                return $dataUser;
            }
        }
        return false;
    }

    public function recoverUserData (
        int $userID
    ): array
    {
        $user = $this->databaseManager->select(
            request: "SELECT ID_Utilisateur, role FROM User WHERE ID_Utilisateur = :userID",
            param: [
                "userID" => $userID,
            ]
        );

        $this->loggedInUser = $user;
        return  $this->loggedInUser;

    }
}