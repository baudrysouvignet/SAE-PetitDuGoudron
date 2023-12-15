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

function deleteParticipation (
    DatabaseManager $databaseManager,
    userEntity $user,
    int $idParticipation
): void {
    $participation = $databaseManager->select(
        request: "SELECT ID_Utilisateur FROM Inscription WHERE ID_Insription  = :idParticipation",
        param: [
            "idParticipation" => $idParticipation
        ]
    );

    if ($participation[0]["ID_Utilisateur"] == $user->loggedInUser[0]["ID_Utilisateur"] || $user->loggedInUser[0]["role"] == "ROLE_ADMIN") {
        $databaseManager->insert (
            request: "DELETE FROM Inscription WHERE ID_Insription = :idParticipation",
            param: [
                "idParticipation" => $idParticipation
            ]
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
    userEntity $user = null
): array {
    $rq = "SELECT * FROM Inscription";
    $param = [];
    if ($user != null) {
        $rq = "SELECT * FROM Inscription WHERE ID_Utilisateur = :user";
        $param = ["user" => $user->loggedInUser[0]["ID_Utilisateur"]];
    }

    $participation = $databaseManager->select(
        request: $rq,
        param: $param
    );

    return $participation;
}

/**
 * @param array $data
 * @return void
 *
 * This function is used to create a PDF
 */
function createPDF(array $data): void {
    require_once(__DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php');
    $pdf = new TCPDF();

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Author');
    $pdf->SetTitle('Données pour chaque personne');
    $pdf->SetMargins(10, 10, 10);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Inscription n°'.$data['ID_Insription'], 0, 1, 'C');

    $pdf->SetFont('helvetica', 'B', 12);
    foreach ($data as $key => $value) {
        $pdf->Cell(40, 10, $key . ':', 0, 0, 'L');
        $pdf->SetFont('helvetica', '    ', 12);
        $pdf->Cell(0, 10, '       '.$value, 0, 1, 'L');
    }

    $pdf->Output('inscription_n_'.$data['ID_Insription'].'.pdf', 'D');
}


