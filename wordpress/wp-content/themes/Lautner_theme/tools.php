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
