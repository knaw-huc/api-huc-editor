<?php
require(dirname(__FILE__) . '/config.php');

function send_json($message_array)
{
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($message_array);
}

function profiles() {
    $profiles = get_collection(PROFILE_DIR);
    send_json(collection_list('profile', $profiles));
}

function tweaks() {
    $tweaks = get_collection(TWEAK_DIR);
    send_json(collection_list('tweak', $tweaks));
}

function identifiers() {
    $ret_list = array();
    $ids = get_collection(PROFILE_DIR);
    foreach($ids as $id) {
        $ret_list[] = substr($id, 0, strrpos($id, ".")); ;
    }
    send_json(collection_list('identifier', $ret_list));
}

function collection_list($type, $collection) {
    return array("type" => $type, "items" => $collection);
}

function get_collection($dir) {
    return array_diff(scandir($dir), array('..', '.'));
}

