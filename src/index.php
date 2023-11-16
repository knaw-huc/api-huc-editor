<?php
require(dirname(__FILE__) . '/functions.php');
error_reporting(1);

$URI = $_SERVER["REQUEST_URI"];

$segments = explode('/', $URI);
if (isset($segments[1])) {
    $action = $segments[1];
} else {
    $action = "None";
}

switch ($action) {
    case "get_profiles":
        profiles();
        break;
    case "get_tweaks":
        tweaks();
        break;
    case "get_identifiers":
        identifiers();
        break;
    case "get_json":
        if (isset($segments[2])) {
            get_json($segments[2]);
        } else {
            throw_error("Missing identifier");
        }
        break;
    case "get_record_json":
        if (isset($_POST["profile"]) &&  isset($_POST["record"])) {
            get_record_json($_POST["profile"], $_POST["record"]);
        } else {
            throw_error("Missing identifier");
        }
        break;
    case "create_record":
        $cmdi = get_record($_POST["ccData"], $_POST["ccProfileID"]);
        header('Content-Type: text/xml');
        echo $cmdi->saveXML();
        break;
    default:
        send_json(array("application" => "Huc editor API", "version" => "0.1"));
}
