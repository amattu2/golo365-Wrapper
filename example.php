<?php

// Get required wrapper class
require(__DIR__ . "/Golo365.class.php");

// Initialize Golo365 wrapper
$wrapper = new amattu\Golo365();

echo "<pre>";

// Pull Diagnostic reports by device Serial Number
//print_r($wrapper->setSerialNo("")->reportList());

// Pull Diagnostic reports by VIN
//print_r($wrapper->reportListByVIN(""));

// Pull Diagnostic reports by License Plate Number
//print_r($wrapper->reportListByPlateNumber(""));

// Fetch additional details about a Diagnostic report
//print_r($wrapper->reportDetail(1, "X"));

// Fetch a license plate number by VIN
//print_r($wrapper->getPlateByVIN(""));