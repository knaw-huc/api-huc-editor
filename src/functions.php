<?php
require(dirname(__FILE__) . '/config.php');
require(dirname(__FILE__) . '/classes/CcfParser.class.php');
require(dirname(__FILE__) . '/classes/CcfRecord.class.php');

function send_json($message_array)
{
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($message_array);
}

function throw_error($error = "Bad request")
{
    $response = array("error" => $error);
    send_json($response);
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

function get_json($identifier) {
    $files = get_collection(PROFILE_DIR);
    $tweaks = get_collection(TWEAK_DIR);
    if (in_array($identifier . '.xml', $files)) {
        $parser = new Ccfparser();
        if (!in_array($identifier . 'Tweak.xml', $tweaks))
        {
            $response = $parser->parseTweak(PROFILE_DIR . $identifier .".xml" );
        } else {
            $response = $parser->parseTweak(PROFILE_DIR . $identifier .".xml", TWEAK_DIR . $identifier . 'Tweak.xml', TWEAKER );
        }

        send_json($response);
    } else {
        throw_error("Unknown identifier");
    }
}

function get_record($json, $profileID)
{
    $struc = json_decode($json, 'JSON_OBJECT_AS_ARRAY');
    $cmdi = new DOMDocument();
    $cmdi->preserveWhiteSpace = false;
    $cmdi->load(RECORD_TEMPLATE);
    $record = new Ccfrecord();
    $cmdi = $record->createComponents($struc, $profileID, $cmdi, "", "");
    return $cmdi;
}

function collection_list($type, $collection) {
    return array("type" => $type, "items" => $collection);
}

function get_collection($dir) {
    return array_diff(scandir($dir), array('..', '.'));
}

