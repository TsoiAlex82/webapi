<?php

require __DIR__ . "/inc/bootstrap.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


if ( !isset($uri[3]) || !isset($uri[4]) ) {
    header("HTTP/1.1 404 Not Found");
    exit();
}  

$location = $uri[3];
$option = $uri[4];
require PROJECT_ROOT_PATH . "/Controller/Api/WeatherController.php";
$objFeedController = new WeatherController();
if ( $option == "list" ) {
    $strMethodName = 'listDatesAction';
    $objFeedController->{$strMethodName}($location);
} else if ( $option == "add_now" ) {
    $strMethodName = 'putNowWeatherJSONAction';
    $objFeedController->{$strMethodName}($location);
} else {
    $strMethodName = 'getDBWeatherAction';
    $objFeedController->{$strMethodName}($location, $option);
}
