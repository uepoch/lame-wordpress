<?php

if (!current_user_can('professeur')) {
    wp_redirect(get_bloginfo('url').'/accueil/');
}

require_once "tools.php";

function handle_course_upload()
{
    global $wpdb;

    // input validation
    if (empty($_POST['class'])) {
        return "Veuillez selectionner une classe";
    }
        $classId = (int)$_POST['class'];

    if (empty($_POST['subject'])) {
        return "Veuillez selectionner une matière";
    }
        $subjectId = (int)$_POST['subject'];

    if (empty($_POST['course_name'])) {
        return "Veuillez donner un nom pour le contrôle";
    }

    $courseName = $_POST['course_name'];

    // file upload
    $controlPath = upload_file($_FILES["control_file"], "controls");
    $correctionPath = null;
    if (!empty($_FILES["correction_file"]['tmp_name'])) {
        $correctionPath = upload_file($_FILES["correction_file"], "corrections");
    }

    $result = $wpdb->query($wpdb->prepare(
        "
			INSERT INTO controls (
				name,
				subject_id,
				class_id,
				file_url,
				correction_url
			) VALUES (
				%s, %d, %d, %s, %s
			)
		",
        $courseName,
        $subjectId,
        $classId,
        $controlPath,
        $correctionPath
    ));

    if (!$result) {
        return "Une erreur est survenue lors de l'enregistrement du contrôle ({$wpdb->last_error})";
    }

    return true;
}

$uploadStatus = null;
if (!empty($_FILES["control_file"]['tmp_name'])) {
    $uploadStatus = handle_course_upload();
}

get_header('entProfesseur');
?>

<div class="container">

    <?php if ($uploadStatus === true) { ?>
        <p>
            Votre contrôle a été enregistré avec succès
        </p>
    <?php } elseif ($uploadStatus !== null) {?>
        <p><?=$uploadStatus?></p>
    <?php } ?>

    <form action="" method="post" enctype="multipart/form-data">

        <div>Nom du contrôle :
            <input class="champtext" type="text" name="course_name" placeholder="Nom du contôle">
        </div>

        <div>
            <div>Matière :
                <select name="subject" >
                    <?php foreach (get_subjects() as $id => $name) { ?>
                        <option value="<?=$id?>"><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
        </div>


            <div>
            <div>Classe :
                <select name="class">
                    <?php foreach (get_classes() as $id => $name) { ?>
                        <option value="<?=$id?>"><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
        </div>

        <div>
            <div>Fichier du contrôle :</div> <input class="" type="file" name="control_file" placeholder=""><br>
        </div>

        <div>
            <div>Fichier de la correction :</div> <input class="" type="file" name="correction_file" placeholder=""><br>
        </div>

        <input class="validation" type="submit" value="VALIDER">

    </form>

</div>