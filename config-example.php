<?
/**
 * Created by PhpStorm.
 * User: nabossha
 *
 * DESCRIPTION:
 * config file for Z.E.Garage
 *
 */
$confArray = [
    "returnJSON"    => true,                // true => output JSON in browser + write to file // false: write only to file (file must exist)
    "JSONfile"      => "vehicles.json",     // name of output-file
    "maxBufferAge"  => 300,                 // age (in seconds) of local buffer file - this prevents us from querying the API too much
    "user"          => "your-ze-username-here",   // your Z.E.Services username
    "password"      => "your-ze-password-here",          // your Z.E.Services password (yes you must provide that...)
    "vehicles"      => [                    // array of vehicles (how many do you have?)
        "vehicle-VIN-here"  => "vehicle-nickname-here",
    ]
];