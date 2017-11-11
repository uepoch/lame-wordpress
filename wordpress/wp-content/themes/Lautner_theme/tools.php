<?php

require_wp_db();

$localPrefix = __DIR__ . '/../../uploads';

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

function get_controls()
{
    return get_objects("controls");
}

function upload_file(array $file, $type)
{
    $path = "/$type/" . uniqid() . '.' . pathinfo($file["name"], PATHINFO_EXTENSION);
    move_uploaded_file($file['tmp_name'], $localPrefix . $path);
    return $path;
}

function fullUrl_from_url($url)
{
    return get_bloginfo('url') . '/wp-content/uploads' . $url;
}
