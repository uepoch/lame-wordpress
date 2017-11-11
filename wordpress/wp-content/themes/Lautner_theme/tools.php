<?php

require_wp_db();

const localPrefix = __DIR__ . '/../../uploads';

function get_classes()
{
    global $wpdb;
    $classes = $wpdb->get_results("SELECT id, name FROM classes");
    $res = [];
    foreach ($classes as $c) {
        $res[$c->id] = $c->name;
    }
    return $res;
}

function get_subjects()
{
    global $wpdb;
    $classes = $wpdb->get_results("SELECT id, name FROM subjects");
    $res = [];
    foreach ($classes as $c) {
        $res[$c->id] = $c->name;
    }
    return $res;
}

function get_objects($type)
{
    global $wpdb;
    if (empty( $fields )) {
        $fields = "*";
    } else {
        $fields = implode(", ", $fields);
    }
    $results = $wpdb->get_results("SELECT " . $fields . " FROM " . $type);
    $res = [];
    foreach ($results as $r) {
        $res[$r->id] = $r;
    }
    return $res;
}

function get_courses()
{
    return get_objects("courses");
}

function get_marks()
{
    return get_objects("marks");
}

function get_controls(array $filters = [])
{
    global $wpdb;
    $availableFilters = ['class_id', 'subject_id'];
    $filters = array_intersect_key($filters, array_flip($availableFilters));

    $q = "SELECT * FROM controls WHERE 1 = %d";
    $qargs = [1];
    foreach ($filters as $k => $v) {
        $q .= " && $k = %s";
        $qargs[] = $v;
    }
    array_unshift($qargs, $q);
    $results = $wpdb->get_results(call_user_func_array([$wpdb, "prepare"], $qargs));
    $res = [];
    foreach ($results as $r) {
        $res[$r->id] = $r;
    }
    return $res;
}

function upload_file(array $file, $type)
{
    $path = "/$type/" . uniqid() . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
    move_uploaded_file($file['tmp_name'], localPrefix . $path);
    return $path;
}

function fullUrl_from_url($url)
{
    return get_bloginfo('url') . '/wp-content/uploads' . $url;
}

$subjects = get_subjects();
$classes = get_classes();
