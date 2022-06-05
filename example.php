<?php

// Get required wrapper class
require(__DIR__ . "/src/Wrapper.php");

// Initialize Golo365 wrapper
$wrapper = new Golo365\Wrapper();

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

// Fetch a VIN by plate num
//print_r($wrapper->getVINByPlateNumber(""));
