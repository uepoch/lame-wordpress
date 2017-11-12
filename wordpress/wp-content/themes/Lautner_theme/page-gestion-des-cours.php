<?php

if (!current_user_can('professeur') && !current_user_can('eleve')) {
    wp_redirect(get_bloginfo('url').'/accueil/');
}

require_once "tools.php";

$deleteStatus = null;
if (!empty($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (is_int($id)) {
        $res = $wpdb->get_results($wpdb->prepare('SELECT id, file_url FROM courses WHERE id = %d LIMIT 1', $id));
        if (!empty($res)) {
            if (!empty($res[0]->file_url) && is_writable(realpath(localPrefix . $res[0]->file_url))) {
                unlink(localPrefix . $res[0]->file_url);
            }
            $req = $wpdb->delete(
                'courses',
                array('id' => $id),
                '%d'
            );
            if ($req === false) {
                $deleteStatus = "Une erreur SQL est survenue ". $id;
            } else {
                $deleteStatus = true;
            }
        } else {
            $deleteStatus = "L'ID n'est pas présent";
        }
    } else {
        $deleteStatus = "Mauvais format ID:". $id;
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
        return "Veuillez donner un nom pour le cours";
    }
    $courseName = $_POST['course_name'];

    $path = upload_file($_FILES["file"], "courses");

    $result = $wpdb->query($wpdb->prepare(
        "
			INSERT INTO courses (
				name,
				subject_id,
				class_id,
				file_url
			) VALUES (
				%s, %d, %d, %s
			)
		",
        $courseName,
        $subjectId,
        $classId,
        $path
    ));

    if (!$result) {
        return "Une erreur est survenue lors de l'enregistrement du cours ({$wpdb->last_error})";
    }

    return true;
}

$uploadStatus = null;
if (!empty($_FILES["file"]['tmp_name'])) {
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
    <?php if ($uploadStatus === true) { ?>
        <p>
            Votre cours a été enregistré avec succès
        </p>
    <?php } elseif ($uploadStatus !== null) {?>
        <p><?=$uploadStatus?></p>
    <?php } ?>
    <?php if ($deleteStatus === true) { ?>
        <p>
            Votre cours a été supprimé avec succès
        </p>
    <?php } elseif ($deleteStatus !== null) {?>
        <p><?=$deleteStatus?></p>
    <?php } ?>

    <form action="" method="post" enctype="multipart/form-data">

        <div>Nom du cours :
            <input class="champtext" type="text" name="course_name" placeholder="Nom du cours">
        </div>

        <div>
            <div>Matière :
                <select name="subject" >
                    <?php foreach ($subjects as $id => $name) { ?>
                        <option value="<?=$id?>"><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
        </div>


            <div>
            <div>Classe :
                <select name="class">
                    <?php foreach ($classes as $id => $name) { ?>
                        <option value="<?=$id?>"><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
        </div>


        <div>
            <div>Fichier :</div> <input class="" type="file" name="file" placeholder=""><br>
        </div>

        <input class="validation" type="submit" value="VALIDER">

    </form>

    <?php } ?>
    <h2>Chercher un cours</h2>
    <form action="" method="get">
        <div>
            <div>Matière :
                <select name="search-subject">
                    <option value="">Selectionner...</option>
                    <?php foreach (get_subjects() as $id => $name) { ?>
                        <option value="<?=$id?>" <?php if (!empty($_GET["search-subject"]) && $_GET["search-subject"] == $id) {
                            echo "selected";
} ?>><?=$name?></option>
                    <?php } // foreach ?>
                </select>
            </div>
        </div>


        <?php if (current_user_can('professeur')) { ?>
        <div>
            <div>Classe :
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
<div class="container">

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

$courses = get_courses($filters);
if (!$courses) {
    echo "Aucun cours n'ont été trouvé pour votre recherche";
} else {
?>
<table border=1>
<tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Class</th>
        <th>Sujet</th>
        <th>Actions</th>
</tr>
<?php
foreach ($courses as $id => $c) { ?>
        <tr>
            <td><?=$id?></td>
            <td><?=$c->name?></td>
            <td><?=$classes[$c->class_id]?></td>
            <td><?=$subjects[$c->subject_id]?></td>
            <td>
                <span><a href="<?=fullUrl_from_url($c->file_url)?>">OPEN</a></span>
                <?php if (current_user_can('professeur')) { ?>
                <span><a href="<?=get_page_link() . "?delete=" . $c->id ?>">DELETE</a></span>
                <?php } ?>
            </td>
        </tr><?php
}
?>
</table>
<?php } ?>
</div>
