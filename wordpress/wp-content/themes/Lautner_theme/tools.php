<?php

require_wp_db();

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

function get_objects($type, $fields = []){
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

function get_courses($fields = []) {
    return get_objects("courses", $fields);
}

function get_marks($fields = []) {
    return get_objects("marks", $fields);
}

function get_controls($fields = []) {
    return get_objects("controls", $fields);
}