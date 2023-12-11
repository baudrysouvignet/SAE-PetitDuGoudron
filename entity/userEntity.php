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

    /**
     * @param int $userID
     * @return array
     *
     * Function to recover user data
     */
    public function recoverUserData (
        int $userID
    ): array
    {
        $user = $this->databaseManager->select(
            request: "SELECT ID_Utilisateur, role, mail FROM User WHERE ID_Utilisateur = :userID",
            param: [
                "userID" => $userID,
            ]
        );

        $this->loggedInUser = $user;
        return  $this->loggedInUser;

    }

    /**
     * @param string $mail
     * @param string $password
     * @return void
     *
     * Function to register a user after form validation
     */
    public function register(
        string $mail,
        string $password
    ): void
    {
        $this->databaseManager->insert(
            request: "INSERT INTO User (mail, password) VALUES (:mail, :password)",
            param: [
                "mail" => $mail,
                "password" => password_hash(
                    password: $password,
                    algo: PASSWORD_DEFAULT
                ),
            ]
        );
    }

    /**
     * @param string $newPassword
     * @param string $oldPassword
     * @return bool
     *
     * Function to update user password
     */
    public function updatePassword (
        string $newPassword,
        string $oldPassword
    ):bool
    {
        $isGoodPassword = $this->passwordCheck(
            password: $oldPassword
        );

        if ($isGoodPassword) {
            $this->databaseManager->insert(
                request: "UPDATE User SET password = :newPassword WHERE ID_Utilisateur = :userID",
                param: [
                    "newPassword" => password_hash(
                        password: $newPassword,
                        algo: PASSWORD_DEFAULT
                    ),
                    "userID" => $this->loggedInUser[0]['ID_Utilisateur'],
                ]
            );
            return true;
        }
        return false;
    }

    /**
     * @param string $newMail
     * @param string $password
     * @return bool
     *
     * Function to update user mail
     */
    public function updateMail (
        string $newMail,
        string $password
    ):bool
    {
        $isGoodPassword = $this->passwordCheck(
            password: $password
        );
        if ($isGoodPassword) {
            $this->databaseManager->insert(
                request: "UPDATE User SET mail = :newMail WHERE ID_Utilisateur = :userID",
                param: [
                    "newMail" => $newMail,
                    "userID" => $this->loggedInUser[0]['ID_Utilisateur'],
                ]
            );
            return true;
        }
        return false;
    }

    /**
     * @param string $password
     * @return bool
     *
     * Private function to check user password before update account information
     */
    private function passwordCheck(
        string $password
    ):bool {
        $user = $this->databaseManager->select(
            request: "SELECT password FROM User WHERE ID_Utilisateur = :userID",
            param: [
                "userID" => $this->loggedInUser[0]['ID_Utilisateur'],
            ]
        );

        $isGoodPassword = password_verify(
            $password,
            $user[0]["password"]
        );
        return $isGoodPassword;
    }
}