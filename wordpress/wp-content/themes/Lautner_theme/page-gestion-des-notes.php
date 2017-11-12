<?php

if (!current_user_can('professeur')) {
    wp_redirect(get_bloginfo('url') . '/accueil/');
}

$userId = !empty($_GET["userid"]) ? (int)$_GET["userid"] : -1;
$controlId = !empty($_GET["controlid"]) ? (int)$_GET["controlid"] : -1;

if ($userId > 0) {
if (!empty($_POST)) {
    foreach ($_POST["marks"] as $controlId => $data) {
        if ($data["mark"] === '') {
            continue;
        }

        $mark = strtr($data["mark"], ",", ".");
        $mark = (float)$mark;
        $description = stripslashes($data['description']);

        $hasMark = $wpdb->get_col($wpdb->prepare("
			SELECT COUNT(*) FROM marks
			WHERE user_id = %d && control_id = %d
        ", $userId, $controlId))[0] > 0;

        if ($hasMark) {
            $wpdb->query($wpdb->prepare("
				UPDATE marks
				SET
					mark = %f,
					description = %s
			", $mark, $description));
        } else {
            $wpdb->query($wpdb->prepare("
				INSERT INTO marks (
					user_id,
					control_id,
					mark,
					description
				) VALUES (
					%d, %d, %f, %s
				)
			", $userId, $controlId, $mark, description));
        }
	}
}

	$user = get_user_by('id', $userId);
	if ($user){
		$controls = $wpdb->get_results($wpdb->prepare("SELECT * FROM controls WHERE id = %d", $controlId));
		$marks = [];
		$control = null;

		// if ($controls) {
		// $control = $controls[0];
		$marks = $wpdb->get_results($wpdb->prepare("
			SELECT cl.name as class_name,
					co.id as control_id,
					co.name as control_name,
					m.description as description,
					m.mark as mark,
					m.id as mark_id
			FROM classes cl
			JOIN controls co
			ON co.class_id = cl.id
			INNER JOIN marks m
			ON m.control_id = co.id
			WHERE
				( m.user_id = %d )
		", $userId));
	// }
}


get_header('entProfesseur');
?>

<?php if (!$user) { ?>
    <p>Cet élève n'existe pas</p>
<?php return;
}?>

<p>
    Notes pour l'élève <?=$user->user_nicename?>
</p>

<form action="?userid=<?=$userId?>" method="post">
    <table border=1>
        <tr>
            <th>
                Nom du contrôle
            </th>
            <th>
                Note
            </th>
			<th>
				Description
			</th>
        </tr>
        <?php foreach ($marks as $userMark) {?>
        <tr>
            <td><a href="?controlid=<?=$userMark->control_id?>"><?=$userMark->control_name?></a></td>
            <td><input type="text" name="marks[<?=$userMark->control_id?>][mark]" value="<?=strtr($userMark->mark, ".", ",")?>"> / 20</td>
            <td colspan="2">	
                <textarea name="marks[<?=$userMark->control_id?>][description]"><?=$userMark->description?></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>
    <p>
        <input class="validation" type="submit" value="VALIDER">
    </p>
</form>

<?php 

} else if ($controlId > 0) {
 
if (!empty($_POST)) {
    foreach ($_POST["marks"] as $userId => $data) {
        if ($data["mark"] === '') {
            continue;
        }

        $mark = strtr($data["mark"], ",", ".");
        $mark = (float)$mark;
        $description = stripslashes($data['description']);

        $hasMark = $wpdb->get_col($wpdb->prepare("
			SELECT COUNT(*) FROM marks
			WHERE user_id = %d && control_id = %d
        ", $userId, $controlId))[0] > 0;

        if ($hasMark) {
            $wpdb->query($wpdb->prepare("
				UPDATE marks
				SET
					mark = %f,
					description = %s
			", $mark, $description));
        } else {
            $wpdb->query($wpdb->prepare("
				INSERT INTO marks (
					user_id,
					control_id,
					mark,
					description
				) VALUES (
					%d, %d, %f, %s
				)
			", $userId, $controlId, $mark, description));
        }
    }
}

$controls = $wpdb->get_results($wpdb->prepare("SELECT * FROM controls WHERE id = %d", $controlId));
$marks = [];
$control = null;
if ($controls) {
    $control = $controls[0];
    $marks = $wpdb->get_results($wpdb->prepare("
		SELECT *
		FROM wp_users u
		JOIN wp_usermeta um
		ON um.user_id = u.id && um.meta_key = 'classe'
		LEFT JOIN marks m
		ON m.user_id = u.id
		WHERE
			(m.control_id = %s || m.control_id is null) &&
			um.meta_value = %d
	", $control->id, $control->class_id));
}


get_header('entProfesseur');
?>

<?php if (!$control) { ?>
    <p>Ce contrôle n'existe pas</p>
<?php return;
}?>

<p>
    Notes pour le controle <?=$control->name?>
</p>

<form action="?controlid=<?=$controlId?>" method="post">
    <table border=1>
        <tr>
            <th>
                Nom de l'élève
            </th>
            <th>
                Note
            </th>
            <th>
				Description
            </th>
        </tr>
        <?php foreach ($marks as $userMark) {?>
        <tr>
            <td><a href="?userid=<?=$userMark->ID?>"><?=$userMark->user_nicename?></a></td>
            <td><input type="text" name="marks[<?=$userMark->ID?>][mark]" value="<?=strtr($userMark->mark, ".", ",")?>"> / 20</td>
            <td colspan="2">
                <textarea name="marks[<?=$userMark->ID?>][description]"><?=$userMark->description?></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>
    <p>
        <input class="validation" type="submit" value="VALIDER">
    </p>
</form>

<?php 
}
?>