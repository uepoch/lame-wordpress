<?php

if (!current_user_can('professeur')) {
    wp_redirect(get_bloginfo('url').'/accueil/');
    exit();
}

require_once "tools.php";

$deleteStatus = null;
if (!empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (is_int($id)) {
        $res = $wpdb->get_results($wpdb->prepare('SELECT id, file_url, correction_url FROM controls WHERE id = %d LIMIT 1', $id));
        if (!empty($res)) {
            if (!empty($res[0]->file_url) && is_writable(realpath(localPrefix . $res[0]->file_url))) {
                unlink(localPrefix . $res[0]->file_url);
            }
            if (!empty($res[0]->correction_url) && is_writeable(realpath(localPrefix . $res[0]->correction_url))) {
                unlink(localPrefix . $res[0]->correction_url);
            }
            $req = $wpdb->delete(
                'controls',
                array('id' => $id),
                '%d'
            );
            if ($req === false) {
                $deleteStatus = "SQL Error when deleting ID: ". $id;
            } else {
                $deleteStatus = true;
            }
        } else {
            $deleteStatus = "You tried to delete a wrong ID";
        }
    } else {
        $deleteStatus = "Wrong format for ID:". $id;
    }
    // wp_redirect( get_page_link());
    // exit();
}

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
    <?php if ($deleteStatus === true) { ?>
        <p>
            Votre contrôle a été supprimé avec succès
        </p>
    <?php } elseif ($deleteStatus !== null) {?>
        <p><?=$deleteStatus?></p>
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
<div class="container">
<table border=1>
<tr>
		<th>ID</th>
		<th>Nom</th>
		<th>Class</th>
		<th>Sujet</th>
		<th>Actions</th>
</tr>
<?php 
    foreach(get_controls() as $id => $c) { ?>
    <tr>
        <td><?=$id?></td>
        <td><?=$c->name?></td>
        <td><?=$classes[$c->class_id]?></td>
        <td><?=$subjects[$c->subject_id]?></td>
        <td>
            <span><a href="<?=fullUrl_from_url($c->file_url)?>">OPEN</a></span> <?php if (!empty($c->correction_url)) { ?>
            <span><a href="<?=fullUrl_from_url($c->correction_url)?>">OPEN</a></span> <?php } ?>
            <span><a href="<?=get_page_link() . "?delete=" . $c->id ?>">DELETE</a></span>
        <td>
    </tr><?php 
    }?>
</table>
</div>
