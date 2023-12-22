<?php
session_start ();


use entity\userEntity;
$isError = false;
$isCreated = null;

function redirectPage(): void
{
    header("Location: reglage.php");
    exit();
}

/*Function For Link Between Controller And Entity */
function loginBtn(
    userEntity $user
): void
{
    global $isError;

    if (isset($_SESSION['user_info'])) {
        redirectPage();
    } else {
        connection(
            user: $user,
            mail: $_POST['mail'],
            password: $_POST['password']
        );

        if (!$user->loggedInUser) {
            $isError = true;
        } else {
            redirectPage();
        }
    }
}


function inscriptionBtn(
    userEntity $user,
    DatabaseManager $database
): void
{
    global $isCreated;
    $isCreated = inscription(
        user: $user,
        database: $database,
        mail: $_POST['mail'],
        password: $_POST['password'],
    );
}

/*Condition For Login Or Register*/
if (isset($_POST['mail']) && isset($_POST['password'])){
    require_once ('../../../config/DatabaseManager.php');

    require_once ('../../../controller/userController.php');
    require_once ('../../../entity/userEntity.php');

    $database = DatabaseManager::getInstance();
    $user = new userEntity(
        databaseManager: $database
    );

    if (isset($_POST['login'])) {
        loginBtn ($user);
    } else if (isset($_POST['register'])) {
         inscriptionBtn (
            user: $user,
            database: $database
        );
    }
}

if (isset($_SESSION['user_info'])) {
    redirectPage();
}

?>


<!doctype html>
<html lang="fr">
<head>
    <?php include '../partials/head.php'?>
    <link rel="stylesheet" href="../../styles/pages/connexion.css">
    <script src="../../scripts/pages/admin.js" defer></script>
    <title>PDG - Admin</title>
</head>
<body>

<?php include '../partials/nav.php'?>

<main>
    <h1 class="title">Bienvenue !</h1>
    <p style="font-size: 12px; text-align: center; margin-bottom: 40px">
        Pour accéder à l'espace administrateur, veuillez consulter le dossier admin_mdp.pdf dans le rendu.
    </p>
    <form action="connexion.php" method="post">
        <label for="mail">Mail</label>
        <input type="text" name="mail" id="mail" placeholder="mail" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Mot de passe" required>

        <input class="button" type="submit" name="login" value="Se connecter">
        <input class="button" type="submit" name="register" value="S'inscire">
    </form>

    <?php
    if ($isError) {
        echo "<p>Identifiants ou mot de passe incorrect</p>";
    }
    ?>

    <?php
    if ($isCreated) {
        echo "<p>Le compte à bien été crée</p>";
    } else if ($isCreated === false) {
        echo "<p>Le compte n'a pas pu être crée</p>";
    }
    ?>
</main>
<?php include '../partials/footer.php'?>
</body>
</html>



