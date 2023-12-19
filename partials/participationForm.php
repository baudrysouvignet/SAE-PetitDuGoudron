<?php

$isValue = isset($infoForm);
?>

<form class="formPart" action="<?= $field ?>" method="post">
    <div>
        <label for="firstName">Prénom de l'enfant :</label>
        <input type="text" id="firstName" name="firstName" <?= $isValue ? 'value ='. $infoForm['firstName'] : ""?> required>
    </div>

    <div>
        <label for="date">Date de Naissance :</label>
        <input type="date" id="date" name="date" <?= $isValue ? 'value ='. $infoForm['date'] : ""?> required>
    </div>

    <div class="contentsexe">
        <label for="sexe">Sexe</label>
        <div class="sexe">
            <div>
                <input type="radio" id="sexH" name="sexe" value="1" <?= $isValue && $infoForm['sexe'] == 1 ? 'checked="checked"' : ""?>>
                <label for="sexH">Homme</label>
            </div>
            <div>
                <input type="radio" id="sexF" name="sexe" value="0" <?= $isValue && $infoForm['sexe'] == 0 ? 'checked="checked"' : ""?>>
                <label for="sexF">Femme</label>
            </div>
        </div>
    </div>
    <div>
        <label for="adress">Adresse :</label>
        <input type="text" id="adress" name="adresse" <?= $isValue ? 'value ='. $infoForm['adress'] : ""?> required>
    </div>

    <div>
        <label for="phone">Téléphone :</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" <?= $isValue ? 'value =0'. $infoForm['phone'] : ""?> required>
    </div>

    <div>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" <?= $isValue ? 'value ='. $infoForm['email'] : ""?> required>
    </div>

    <div>
        <label for="allergy">Allergies :</label>
        <textarea id="allergy" name="allergy" rows="1" <?= $isValue ? 'value ='. $infoForm['allergy'] : ""?>></textarea>
    </div>

    <div>
        <label for="health">Conditions Médicales :</label>
        <textarea id="health" name="health" rows="1" <?= $isValue ? 'value ='. $infoForm['health'] : ""?>></textarea>
    </div>

    <div>
        <label for="drug">Médicaments :</label>
        <textarea id="drug" name="drug" rows="1"  <?= $isValue ? 'value ='. $infoForm['drug'] : ""?>></textarea>
    </div>

    <div>
        <label for="doctorPhone">Téléphone du Médecin :</label>
        <input type="tel" id="doctorPhone" name="doctorPhone" pattern="[0-9]{10}"  <?= $isValue ? 'value =0'. $infoForm['doctorPhone'] : ""?> required>
    </div>
    <div>
        <label for="secu">Numéro de Sécurité Sociale :</label>
        <input type="text" id="secu" name="secu" <?= $isValue ? 'value =0'. $infoForm['secu'] : ""?> required>
    </div>

    <input type="hidden" name="id_utilisateur" value="<?= $user->loggedInUser[0]["ID_Utilisateur"] ?>">
    <input type="hidden" name="id_form" value="<?= $isValue ? $infoForm['ID_Insription'] : ""?>">
    
    <div>
        <label for="autorisation_parentale">Autorisation Parentale :</label>
        <select id="autorisation_parentale" name="autorisation_parentale" required>
            <option <?= $isValue ? 'selected' : ""?> value="1" >Oui</option>
            <option value="0">Non</option>
        </select>
    </div>
    <input class="button" type="submit" name="participer" value="<?= $btnName ?>">
</form>