<?php
/**
 * Created by PhpStorm.
 * User: nabossha
 * Date: 29.07.2016
 * Time: 15:35
 */
require __DIR__ . '/vendor/autoload.php';
// get external config-file:
try {
    if ( ! file_exists('config.php')) {
        throw new Exception ('cannot load file config.php did you create it?');
    }
    else {
        require 'config.php';
    }
}
catch(Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SessionCookieJar;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;


$vehicleData = [];
$updateFile = false;
$GLOBALS['base_url'] = 'https://www.services.renault-ze.com';
$GLOBALS['cookieJar'] = new SessionCookieJar('SESSION_STORAGE', true);
$GLOBALS['AuthToken'] ='';


// first check if local buffer-file is valid within our expiry-rules (better performance):
$haslocalStatus = checkLocalStatus($confArray["JSONfile"]);

if($haslocalStatus == true)
{
    addLog("local bufferfile is available and valid");
    // read data from local bufferfile:
    $vehicleData = json_decode(file_get_contents($confArray["JSONfile"]), true);
}
else {
    addLog("bufferfile expired or not available - getting data from remote server");
    // authenticate with server:
    authenticateToken($confArray['user'],$confArray['password']);

    // loop through our vehicles:
    foreach ($confArray["vehicles"] as $key => $val)
    {
        // get status for vehicle:
        $vehicleData["vehicles"][$key] = getVehicleStatus($key,$confArray["vehicles"][$key]);
    }
    $vehicleData["LastUpdateFromAPI"] = date("c", time());
    $updateFile = true;
}

$jsonCompleteData = json_encode($vehicleData);

// output data to browser?
if($confArray["returnJSON"]) {
    echo $jsonCompleteData;
}
// write data to buffer-file:
if($updateFile) {
    file_put_contents($confArray["JSONfile"], $jsonCompleteData);
}


////////////////////////////////////////////////////////////////////////
///  get authentication token from ZE-servers :
function authenticateToken($user,$password) {

    $GLOBALS['$client'] = new Client([
        'base_uri' =>  $GLOBALS['base_url'],
        'cookies' => $GLOBALS['cookieJar'],
        'timeout'  => 20.0,
    ]);

    $UserData = array(
        'username' => $user,
        'password' => $password
    );

    $options = array(
        'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
        'body' =>  json_encode($UserData),
        'cookies' => $GLOBALS['cookieJar'],
        'debug' => false
    );
    try {
        $requestAuth = $GLOBALS['$client']->post("/api/user/login",$options);
        $body = $requestAuth->getBody();
        $json_output = json_decode($body);
        $GLOBALS['AuthToken'] = $json_output->token;

    } catch (ClientException $e) {
        echo $e->getRequest() . "\n";
        if ($e->hasResponse()) {
            echo $e->getResponse() . "\n";
        }
    }

}

////////////////////////////////////////////////////////////////////////
// get vehicle status
function getVehicleStatus($vin,$VehicleName) {
    // needed: set the active vehicle to our current VIN:
    $requestActiveVehicle = $GLOBALS['$client']->put("/api/vehicle",[
        'headers' => [
            'Authorization'      => 'Bearer '.$GLOBALS['AuthToken'],
        ],
        'json' => ['active_vehicle' => $vin],
        'cookies' => $GLOBALS['cookieJar'],
        'debug' => false
    ]);
    // get current battery-status:
    $BatteryResponse = $GLOBALS['$client']->request('GET', '/api/vehicle/'.$vin.'/battery', [
        'headers' => [
            'Authorization'      => 'Bearer '.$GLOBALS['AuthToken'],
            'Content-Type' => 'application/json',
        ],
        'cookies' => $GLOBALS['cookieJar'],
        'debug' => false
    ]);
    $rawData = json_decode($BatteryResponse->getBody(),true);

    // get data about previous and next charge:
    $SiblingsResponse = $GLOBALS['$client']->request('GET', '/api/vehicle/'.$vin.'/charge/siblings', [
        'headers' => [
            'Authorization'      => 'Bearer '.$GLOBALS['AuthToken'],
            'Content-Type' => 'application/json',
        ],
        'cookies' => $GLOBALS['cookieJar'],
        'debug' => false
    ]);

    $rawData += json_decode($SiblingsResponse->getBody(),true);
    // assemble the missing parts:
    $rawData['VehicleName'] = $VehicleName;
    // pass the array back since we modify it further!
    return $rawData;
}

////////////////////////////////////////////////////////////////////////
///
function checkLocalStatus($filetoCheck) {
    global $confArray;
    $now = time();
    if (is_file($filetoCheck))
    {
        // check if the file is older than X seconds
        if ($now - filemtime($filetoCheck) >= intval($confArray["maxBufferAge"]))
        {
            // do the deletion
            unlink($filetoCheck);
            addLog("deleted puffer");
            return false;
        }
    } else {
        return false;
    }
    return true;
}
////////////////////////////////////////////////////////////////////////
///  not finished log-function....
function addLog($logEntry) {

    //echo('<br>'.$logEntry);

}