<?php
class WeatherController extends BaseController
{
    /** 
    * "/{location}/list" Endpoint - Get list of available dates 
    */
    public function listDatesAction($location)
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $weatherModel = new WeatherModel();
                $arrDates = $weatherModel->getDates($location);
                $responseData = json_encode($arrDates);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /** 
    * "/{location}/{datetime}" Endpoint - Get weather on datetime format: 2024-06-06T1400
    */
    public function getDBWeatherAction($location, $dateTimeStr)
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $weatherModel = new WeatherModel();
                $dateTime = date_create($dateTimeStr);
                $weatherJSON = $weatherModel->getDBWeatherJSON($location, $dateTime);
                $responseData = json_encode($weatherJSON);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /** 
    * "/{location}/add_now" Endpoint - GET remote weather now and put to DB 
    */    
    public function putNowWeatherJSONAction($location)
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $weatherModel = new WeatherModel();
                $curl_data = $weatherModel->getNowWeatherRemote($location);
                json_decode($curl_data);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = json_decode($curl_data);
                    $location = $data->location->name;
                    $dateTimeSQL = date($data->current->last_updated);
                    $responseData = $weatherModel->insertWeatherJSON($location, $dateTimeSQL, $curl_data);
                   
                } else {
                    $responseData = "response is not a JSON";
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}