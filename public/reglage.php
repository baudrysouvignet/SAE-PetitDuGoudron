<?php
session_start ();

if (isset($_POST['deconnexion'])) {
    session_destroy();
    header ("Location: connexion.php");
}

if (isset($_SESSION['user_info'])) {
    echo "Vous êtes connecté ". $_SESSION['user_info'][0]['mail'];
} else {
    echo "Vous n'êtes pas connecté";
}
?>

<form action="reglage.php" method="post">
    <input type="submit" name="deconnexion" value="Déconnexion">
</form>
