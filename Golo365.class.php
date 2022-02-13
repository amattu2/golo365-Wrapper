<?php
/*
 * Produced: Sun Feb 13 2022
 * Author: Alec M.
 * GitHub: https://amattu.com/links/github
 * Copyright: (C) 2022 Alec M.
 * License: License GNU Affero General Public License v3.0
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Class Namespace
namespace amattu;

use Exception;

/**
 * Golo-Wrapper
 *
 * @author Alec M.
 * @version 1.0.0
 * @package amattu
 */
class Golo365 {
  /**
   * Golo365 Status Code for Success
   *
   * @var int
   */
  public const STATUS_CODE_SUCCESS = 0;

  /**
   * An array of Golo365 endpoints
   *
   * @var array
   */
  private $endpoints = [
    "reportList" => "http://%service.golo365.com/Home/HttApi/reportList",
    "upload_report_data" => "http://%service.golo365.com/Home/Cloud/upload_report_data",
    "getPlateByVin" => "http://%service.golo365.com/Home/HttApi/getPlateByVin",
    "getVinByplateNum" => "http://%service.golo365.com/Home/Index/getVinByplateNum",
  ];

  /**
   * Specifies which Golo service to use
   *
   * Note:
   * (1) The data does not sync across services, if you
   *     are reporting data to a service, you will want
   *     to use that same service in the future in order
   *     to fetch the data again.
   *
   * @var string ait|aitus
   */
  private $service = "aitus";

  /**
   * Serial Number to submit Golo365 reports with
   *
   * Note:
   * (1) This serial number is unique to each LaunchTech device
   *     and is provided at purchase. The full extend to which
   *     this serial number is used internally by Golo is unknown.
   * (2) If you are not reporting a diagnostic event, you don't need
   *     to provide this serial number.
   *
   * @var string 12 digit device serial number
   */
  private $serial_no = "";

  /**
   * Service Search Result Limit
   *
   * @var int
   */
  private $list_size = 0;

  /**
   * Class Constructor
   *
   * @param  ?string $serial_no reporting device serial number
   * @param  ?string $service ait|aitus
   * @author Alec M.
   * @since  1.0.0
   */
  public function __construct(string $serial_no = "", string $service = "aitus")
  {
    // Assign Serial Number
    if ($serial_no && strlen($serial_no) == 12) {
      $this->serial_no = $serial_no;
    }

    // Assign Golo service
    if ($service === "ait") {
      $this->service = "ait";
    } else {
      $this->service = "aitus";
    }
  }

  /**
   * Set or Remove the Device Serial Number
   *
   * @param  ?string $serial_no
   * @return self
   * @since  1.0.0
   */
  public function setSerialNo(string $serial_no = "") : self
  {
    // Assign Serial Number
    if ($serial_no && strlen($serial_no) == 12) {
      $this->serial_no = $serial_no;
    } else {
      $this->serial_no = "";
    }

    // Return
    return $this;
  }

  /**
   * Set or Remove the Report Listing Size Limit
   *
   * @param  ?integer $size
   * @return self
   * @since  1.0.0
   */
  public function setListSize(int $size = 0) : self
  {
    // Assign List Size
    if ($size > 0) {
      $this->list_size = $size;
    } else {
      $this->list_size = 0;
    }

    // Return
    return $this;
  }

  /**
   * Fetch Diagnostic Scan History by VIN
   *
   * Note:
   * (1) If the serial_no variable is not empty,
   *     it will be used to limit results to that
   *     specific device
   *
   * @param  string $vin VIN to search
   * @param  mixed $page page number to fetch
   * @return Array of diagnostic scan history
   * @throws \TypeError
   * @throws \InvalidArgumentException
   * @since  1.0.0
   */
  public function reportListByVIN(string $VIN, $page = "") : array
  {
    // Validate VIN
    if (!$VIN || strlen($VIN) !== 17) {
      throw new \InvalidArgumentException("VIN must be 17 characters long");
    }

    // Initialize Variables
    $formatted_result = [];
    $results = $this->post($this->endpoints["reportList"], $this->build_query_string([
      "vin" => $VIN,
      "serial_no" => $this->serial_no,
      "page" => $page,
      "size" => $this->list_size,
    ])) ?: [];

    // Build formatted result
    foreach ($results as $result) {
      // Validate Fields
      if (!$result["diagnose_record_id"])
        continue;
      if (!$result["serial_number"])
        continue;
      if (!$result["rec_date"])
        continue;
      if (!$result["vin"])
        continue;
      if (!$result["report_url"])
        continue;
      if (!$result["report_type"])
        continue;

      // Format
      $formatted_result[] = Array(
        "record_id" => $result["diagnose_record_id"],
        "serial_no" => $result["serial_number"],
        "date" => $result["rec_date"],
        "VIN" => $result["vin"],
        "plate_number" => $result["plate_number"] || "",
        "url" => $result["report_url"],
        "type" => $result["report_type"],
      );
    }

    // Return
    return $formatted_result;
  }

  /**
   * Fetch Diagnostic Scan History by License Plate Number
   *
   * Note:
   * (1) If the serial_no variable is not empty,
   *     it will be used to limit results to that
   *     specific device
   *
   * @param  string $plate_number License Plate # to search
   * @param  mixed $page page number to fetch
   * @return Array of diagnostic scan history
   * @throws \TypeError
   * @throws \InvalidArgumentException
   * @since  1.0.0
   */
  public function reportListByPlateNumber(string $plate_number, $page = "") : array
  {
    // Validate VIN
    if (!$plate_number || empty($plate_number)) {
      throw new \InvalidArgumentException("The license plate number must not be empty");
    }

    // Initialize Variables
    $formatted_result = [];
    $results = $this->post($this->endpoints["reportList"], $this->build_query_string([
      "plate_number" => $plate_number,
      "serial_no" => $this->serial_no,
      "page" => $page,
      "size" => $this->list_size,
    ])) ?: [];

    // Build formatted result
    foreach ($results as $result) {
      // Validate Fields
      if (!$result["diagnose_record_id"])
        continue;
      if (!$result["serial_number"])
        continue;
      if (!$result["rec_date"])
        continue;
      if (!$result["vin"])
        continue;
      if (!$result["report_url"])
        continue;
      if (!$result["report_type"])
        continue;

      // Format
      $formatted_result[] = Array(
        "record_id" => $result["diagnose_record_id"],
        "serial_no" => $result["serial_number"],
        "date" => $result["rec_date"],
        "VIN" => $result["vin"],
        "plate_number" => $result["plate_number"] || "",
        "url" => $result["report_url"],
        "type" => $result["report_type"],
      );
    }

    // Return
    return $formatted_result;
  }

  /**
   * Fetch a License Plate by the VIN Number
   *
   * @param  string $VIN the VIN number to search
   * @return array
   * @since  1.0.0
   */
  public function getPlateByVIN(string $plate_number) : array
  {
    throw new Exception("Unimplemented function");
  }

  /**
   * Fetch the VIN by License Plate
   *
   * @param  string $plate_number License Plate # to search
   * @return array
   * @since  1.0.0
   */
  public function getVINByPlateNumber(string $plate_number) : array
  {
    throw new Exception("Unimplemented function");
  }

  /**
   * Upload a new Diagnostic Event
   *
   * @return array
   * @since  1.0.0
   */
  public function upload_report_data() : array
  {
    throw new Exception("Unimplemented function");
  }

  /**
   * Build a CURLOPT_POSTFIELDS valid query string
   *
   * @param  array $data
   * @return string query string
   * @throws TypeError
   * @author Alec M.
   * @date   2021-08-15
   */
  private function build_query_string(array $data) : string
  {
    // Variables
    $query_string = '';

    // Build String
    foreach($data as $key => $value) {
      if (!$key || !$value) {
        continue;
      }

      $query_string .= "{$key}={$value}&";
    }

    // Return
    return rtrim($query_string, '&');
  }

  /**
   * Perform a HTTP POST Request
   *
   * @param  string $endpoint
   * @param  string $fields
   * @return array|null
   */
  private function post(string $endpoint, string $fields) : ?array
  {
    // Create a cURL handle
    $ch = curl_init(strtr($endpoint, [
      "%service" => $this->service,
    ]));

    // Set the options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Accept: application/json",
    ]);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    // Execute the request
    $data = null;
    $resp = curl_exec($ch);
    $errn = curl_error($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Validate the response
    if (!$resp || $errn || !($data = json_decode($resp, true))) {
      return null;
    }

    // Validate response codes
    if ($status_code !== 200 || $data['code'] !== Golo365::STATUS_CODE_SUCCESS) {
      return null;
    }

    // Validate response data
    if (!isset($data['data']) || !is_array($data['data'])) {
      return null;
    }

    // Return the parsed response
    return $data['data'];
  }
}
