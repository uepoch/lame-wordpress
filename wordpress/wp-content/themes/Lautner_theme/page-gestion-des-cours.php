<?php

if (!current_user_can('professeur')) {
    wp_redirect(get_bloginfo('url').'/accueil/');
}

require_once "tools.php";

$deleteStatus;
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

get_header('entProfesseur');
?>

<div class="container">

    <?php if ($uploadStatus === true) { ?>
        <p>
            Votre cours a été enregistré avec succès
        </p>
    <?php } elseif ($uploadStatus !== null) {?>
        <p><?=$uploadStatus?></p>
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
    foreach(get_courses() as $id => $c) { ?>
    <tr>
        <td><?=$id?></td>
        <td><?=$c->name?></td>
        <td><?=$classes[$c->class_id]?></td>
        <td><?=$subjects[$c->subject_id]?></td>
        <td>
            <span><a href="<?=fullUrl_from_url($c->file_url)?>">OPEN</a></span>
            <span><a href="<?=get_page_link() . "?delete=" . $c->id ?>">DELETE</a></span>
        </td>
    </tr><?php 
    }?>
</table>
</div>
