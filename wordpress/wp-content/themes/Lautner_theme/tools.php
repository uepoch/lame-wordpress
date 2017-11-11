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

function get_objects($type){
    global $wpdb;
    $results = $wpdb->query($wpdb->prepare("SELECT * FROM %s", $type));
    $res = [];
    foreach ($results as $r) {
        $res[$r->id] = $r;
    }
    return $res;
}

function get_courses() {
    return get_objects("courses");
}

function get_marks() {
    return get_objects("marks");
}

function get_controls() {
    return get_objects("controls");
}