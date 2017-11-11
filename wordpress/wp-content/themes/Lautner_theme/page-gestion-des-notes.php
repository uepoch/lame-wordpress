<?php

if (!current_user_can('professeur')) {
    wp_redirect(get_bloginfo('url') . '/accueil/');
}

$controlId = !empty($_GET["id"]) ? (int)$_GET["id"] : -1;

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

<form action="" method="post">
    <table>
        <tr>
            <td>
                Nom de l'élève
            </td>
            <td>
                Note
            </td>
        </tr>
        <?php foreach ($marks as $userMark) {?>
        <tr>
            <td><?=$userMark->user_nicename?></td>
            <td><input type="text" name="marks[<?=$userMark->ID?>][mark]" value="<?=strtr($userMark->mark, ".", ",")?>"> / 20</td>
        </tr>
        <tr>
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