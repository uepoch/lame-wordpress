<?php

require_once "tools.php";

function handle_inscription()
{
    global $wpdb;

    if (empty($_POST['identifier'])) {
        return "Veuillez renseigner un identifiant";
    }
    $identifier = $_POST['identifier'];

    if (empty($_POST['password'])) {
        return "Veuillez renseigner un mot de passe";
    }
    $password = $_POST['password'];

    if (empty($_POST['password_verification'])) {
        return "Veuillez renseigner une vérification de mot de passe";
    }
    $passwordVerification = $_POST['password_verification'];

    if ($password != $passwordVerification) {
        return "Votre mot de passe et sa vérification ne correspondent pas";
    }

    if (empty($_POST['last_name'])) {
        return "Veuillez renseigner un nom";
    }
    $name = $_POST['last_name'];

    if (empty($_POST['first_name'])) {
        return "Veuillez renseigner un prénom";
    }
    $firstName = $_POST['first_name'];

    if (empty($_POST['phone'])) {
        return "Veuillez renseigner un numéro de téléphone";
    }
    $phone = $_POST['phone'];

    if (empty($_POST['email'])) {
        return "Veuillez renseigner un email";
    }
    $email = $_POST['email'];

    if (empty($_POST['class_id'])) {
        return "Veuillez renseigner une classe";
    }
    $classId = $_POST['class_id'];

    $userdata = [
        'user_login' => $identifier,
        'user_pass'  => $password,
        'first_name' => $firstName,
        'last_name'  => $name,
        'telParent'  => $phone,
        'user_email' => $email,
        'classe'     => $classId,
        'role'       => 'wait',
    ];

    $userId = wp_insert_user($userdata);

    if (is_wp_error($userId)) {
        return "Une erreur est survenue lors de la création du compte";
    }

    return true;
}

$lol = null;
if (!empty($_POST)) {
    $lol = handle_inscription();
}

get_header();
?>

<div class="container-contact">
    <?php if ($lol === true) { ?>
        <p>
            Votre compte a été créé avec succès
        </p>
    <?php } elseif ($lol !== null) {?>
        <p><?=$lol?></p>
    <?php } ?>

    <div>
        <p>Ceci est une pré-inscription pour votre enfant.</br>Lorsque celle-ci sera validée, vous pourrez accèder à l'ENT.</br>Attention, tous les champs sont obligatoires.</p>
    </div>

    <form action="" method="post">
        <div class="containform">
            <div class="centerrightform">Identifiant de connexion :</div> <input class="champtext" type="text" name="identifier" placeholder="NomPrenom"><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Mot de passe :</div> <input class="champtext" type="password" name="password" placeholder="..."><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Mot de passe :</div> <input class="champtext" type="password" name="password_verification" placeholder="..."><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Nom :</div> <input class="champtext" type="text" name="last_name" placeholder="Nom"><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Prénom : </div><input class="champtext" type="text" name="first_name" placeholder="Prenom"><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Téléphone :</div> <input class="champtext" type="int" name="phone" placeholder="0102030405"><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Email :</div> <input class="champtext" type="text" name="email" placeholder="...@gmail.com"><br>
        </div>

        <div class="containform">
            <div class="centerrightform">Niveau de classe :</div>
            <ul class="radioul">
                <?php foreach (get_classes() as $id => $name) { ?>
                    <li><input class="radiocheck" type="radio" name="class_id" value="<?=$id?>"><?=$name?><br></li>
                <?php } // foreach ?>
            </ul>

        </div>

        <input class="validation" type="submit" value="VALIDER">

    </form>
</div>

<?php
get_footer();
