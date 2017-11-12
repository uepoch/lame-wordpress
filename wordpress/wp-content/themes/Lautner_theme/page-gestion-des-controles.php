<?php

if (!current_user_can('professeur') && !current_user_can('eleve')) {
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

if (current_user_can('professeur')) {
    get_header('entProfesseur');
} else {
    get_header('entEleve');
}
?>

<div class="container">
    <?php if (current_user_can('professeur')) { ?>
    <div class="form-container">
        <h2>Ajouter un controle</h2>
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
    <?php } ?>

    <div class="form-container">
        <h2>Chercher un contrôle</h2>
        <form action="" method="get">
            <div>
                Matière :
                <select name="search-subject">
                    <option value="">Selectionner...</option>
                    <?php foreach (get_subjects() as $id => $name) { ?>
                        <option value="<?=$id?>" <?php if (!empty($_GET["search-subject"]) && $_GET["search-subject"] == $id) {
                            echo "selected";
} ?>><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
            <?php if (current_user_can('professeur')) { ?>
            <div>
                Classe :
                <select name="search-class">
                    <option value="">Selectionner...</option>
                    <?php foreach (get_classes() as $id => $name) { ?>
                        <option value="<?=$id?>" <?php if (!empty($_GET["search-class"]) && $_GET["search-class"] == $id) {
                            echo "selected";
} ?>><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
            <?php } ?>

            <input class="validation" type="submit" value="VALIDER">
        </form>
    </div>

    <div style="clear: both"></div>
<?php
$filters = [];
if (!empty($_GET['search-subject'])) {
    $filters['subject_id'] = $_GET['search-subject'];
}
if (!empty($_GET['search-class'])) {
    $filters['class_id'] = $_GET['search-class'];
}
if (current_user_can('eleve')) {
    $filters['class_id'] = get_user_meta(get_current_user_id(), "classe", true);
}

$controls = get_controls($filters);
if (!$controls) {
    echo "Aucun contrôles n'ont été trouvé pour votre recherche";
} else {
?>
<table border=1>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Classe</th>
        <th>Matière</th>
        <?php if (current_user_can('eleve')) { ?>
        <th>Note</th>
        <?php } ?>
        <th>Actions</th>
    </tr>
<?php
foreach ($controls as $id => $c) { ?>
    <tr>
        <td><?=$id?></td>
        <td><?=$c->name?></td>
        <td><?=$classes[$c->class_id]?></td>
        <td><?=$subjects[$c->subject_id]?></td>
        <?php
        if (current_user_can('eleve')) {
            $res = $wpdb->get_col($wpdb->prepare("
                SELECT mark
                FROM marks
                WHERE
                    user_id = %d &&
                    control_id = %d
            ", get_current_user_id(), $c->id));
            if ($res) {
                echo "<td>{$res[0]}</td>";
            }
        }
        ?>
        <td>
            <span><a href="<?=fullUrl_from_url($c->file_url)?>">OPEN</a></span> <?php if (!empty($c->correction_url)) { ?>
            <span><a href="<?=fullUrl_from_url($c->correction_url)?>">OPEN</a></span> <?php
} ?>
            <?php if (current_user_can('professeur')) { ?>
                <span><a href="<?=get_page_link(336) . "?controlid=" . $c->id ?>">Marks</a></span>
                <span><a href="<?=get_page_link() . "?delete=" . $c->id ?>">DELETE</a></span>
            <?php } ?>
        </td>
    </tr><?php } ?>
</table>
<?php } ?>
</div>
