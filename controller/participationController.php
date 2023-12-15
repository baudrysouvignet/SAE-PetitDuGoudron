<?php

use entity\userEntity;

/**
 * @param DatabaseManager $databaseManager
 * @param array $data
 * @return void
 *
 * This function is used to add a participation
 */
function addParticipation(
    DatabaseManager $databaseManager,
    array $data
): void {

    $databaseManager->insert (
        request: "INSERT INTO Inscription (Prenom_Enfant, Date_Naissance, Sexe, Adresse, Telephone, Email, Allergies, Conditions_Medicales, Medicaments, Telephone_Medecin, Num_Secu, Autorisation_Parentale, ID_Utilisateur) VALUES (:firstName, :date, :sexe, :adress, :phone, :email, :allergy, :health, :drug, :doctorPhone, :secu, :autorisation_parentale, :id_utilisateur)",
        param: $data
    );
}

/**
 * @param DatabaseManager $databaseManager
 * @param array $data
 * @param userEntity $user
 * @param int $idParticipation
 * @return void
 *
 * This function is used to edit a participation
 */
function editParticipation(
    DatabaseManager $databaseManager,
    array $data,
    userEntity $user,
    int $idParticipation
): void {
    $participation = $databaseManager->select(
        request: "SELECT ID_Utilisateur FROM Inscription WHERE ID_Insription  = :idParticipation",
        param: [
            "idParticipation" => $idParticipation
        ]
    );

    if ($participation[0]["ID_Utilisateur"] == $user->loggedInUser[0]["ID_Utilisateur"]) {
        $databaseManager->insert (
            request: "UPDATE Inscription
                        SET Prenom_Enfant = :firstName,
                            Date_Naissance = :date,
                            Sexe = :sexe,
                            Adresse = :adress,
                            Telephone = :phone,
                            Email = :email,
                            Allergies = :allergy,
                            Conditions_Medicales = :health,
                            Medicaments = :drug,
                            Telephone_Medecin = :doctorPhone,
                            Num_Secu = :secu,
                            Autorisation_Parentale = :autorisation_parentale,
                            ID_Utilisateur = :id_utilisateur
                        WHERE ID_Insription = :id_form;"
            ,param: $data
        );
    }

}

/**
 * @param DatabaseManager $databaseManager
 * @param userEntity $user
 * @return array
 *
 * This function is used to recover a participation
 */
function recoverParticipation(
    DatabaseManager $databaseManager,
    userEntity $user
): array {
    $participation = $databaseManager->select(
        request: "SELECT * FROM Inscription WHERE ID_Utilisateur = :user",
        param: [
            "user" => $user->loggedInUser[0]["ID_Utilisateur"]
        ]
    );

    return $participation;
}
