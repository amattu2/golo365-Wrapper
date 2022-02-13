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

/**
 * Golo-Wrapper
 *
 * @author Alec M.
 * @version 1.0.0
 * @package amattu
 */
class Golo365 {
  /**
   * An array of Golo365 endpoints
   *
   * @var array
   */
  private $endpoints = [
    "reportList" => "http://%service.golo365.com/Home/HttApi/reportList",
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
    if ($serial_no) {
      $this->serial_no = $serial_no;
    }

    // Assign Golo service
    if ($service === "ait") {
      $this->service = "ait";
    } else {
      $this->service = "aitus";
    }
  }
}
