<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class WeatherModel extends Database
{
    public function getDates($location)
    {
        return $this->select("SELECT date_time FROM weather_data WHERE location = \"$location\"");
    }
    public function getDBWeatherJSON($location, $dateTime)
    {    
        $dateTimeSQL = $dateTime->format('Y-m-d H:i');
        $sql = "SELECT weather_json FROM weather_data WHERE (date_time = \"$dateTimeSQL\" AND location = \"$location\")";
        return $this->select($sql)[0];
    }
    public function insertWeatherJSON($location, $dateTimeSQL, $JSON)
    {   
        $sql = "INSERT INTO weather_data(location, date_time, weather_json)
                            VALUES('$location', '$dateTimeSQL', '$JSON')";
        $status = $this->insert($sql);
        if ( $status ) {
            return "weather JSON for $location on $dateTimeSQL added succesfully";
        }  else {
            return "already exists!";
        }

    }
    public function getNowWeatherRemote($location)
    {   
        $service_url = 'api.weatherapi.com/v1/current.json'.'?q='.$location;
        $apiKey = "cfc864fd457347b7b1692417240406"; //move to environment variables

        $curl = curl_init($service_url);
        $curl_response = "";
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'key: ' . $apiKey,
            ));

        $curl_response = curl_exec($curl);
        if ( curl_errno($curl) ) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            die($error_msg.'Error during curl exec.');
        }
        curl_close($curl);
        return $curl_response;               
    }
}