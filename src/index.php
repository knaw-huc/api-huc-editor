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
    default:
        send_json(array("application" => "Huc editor API", "version" => "0.1"));
}
