<?php

$isValue = isset($infoForm);
?>

<form action="<?= $field ?>" method="post">
    <label for="firstName">Prénom de l'enfant :</label>
    <input type="text" id="firstName" name="firstName" <?= $isValue ? 'value ='. $infoForm['firstName'] : ""?> required>
    
    <label for="date">Date de Naissance :</label>
    <input type="date" id="date" name="date" <?= $isValue ? 'value ='. $infoForm['date'] : ""?> required>
    
    <label for="sexe">Sexe :</label>

    <input type="radio" id="sexH" name="sexe" value="1" <?= $isValue && $infoForm['sexe'] == 1 ? 'checked="checked"' : ""?> checked="checked">
    <label for="sexH">Masculin</label>

    <input type="radio" id="sexF" name="sexe" value="0" <?= $isValue && $infoForm['sexe'] == 0 ? 'selected' : ""?>>
    <label for="sexF">Féminin</label>
    
    <label for="adress">Adresse :</label>
    <input type="text" id="adress" name="adresse" <?= $isValue ? 'value ='. $infoForm['adress'] : ""?> required>
    
    <label for="phone">Téléphone :</label>
    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" <?= $isValue ? 'value =0'. $infoForm['phone'] : ""?> required>
    
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" <?= $isValue ? 'value ='. $infoForm['email'] : ""?> required>
    
    <label for="allergy">Allergies :</label>
    <textarea id="allergy" name="allergy" <?= $isValue ? 'value ='. $infoForm['allergy'] : ""?>></textarea>
    
    <label for="health">Conditions Médicales :</label>
    <textarea id="health" name="health" <?= $isValue ? 'value ='. $infoForm['health'] : ""?>></textarea>
    
    <label for="drug">Médicaments :</label>
    <textarea id="drug" name="drug"  <?= $isValue ? 'value ='. $infoForm['drug'] : ""?>></textarea>
    
    <label for="doctorPhone">Téléphone du Médecin :</label>
    <input type="tel" id="doctorPhone" name="doctorPhone" pattern="[0-9]{10}"  <?= $isValue ? 'value =0'. $infoForm['doctorPhone'] : ""?> required>
    
    <label for="secu">Numéro de Sécurité Sociale :</label>
    <input type="text" id="secu" name="secu" <?= $isValue ? 'value =0'. $infoForm['secu'] : ""?> required>
    
    <input type="hidden" name="id_utilisateur" value="<?= $user->loggedInUser[0]["ID_Utilisateur"] ?>">
    <input type="hidden" name="id_form" value="<?= $isValue ? $infoForm['ID_Insription'] : ""?>">
    
    <label for="autorisation_parentale">Autorisation Parentale :</label>
    <select id="autorisation_parentale" name="autorisation_parentale" required>
        <option <?= $isValue ? 'selected' : ""?> value="1" >Oui</option>
        <option value="0">Non</option>
    </select>

    <input type="submit" name="participer" value="<?= $btnName ?>">
</form>