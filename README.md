# Introduction
The goal of this API wrapper is to simplify access to Golo365's internal API endpoints. Examples of such endpoints include:
- Vehicle Diagnostic Scan History by VIN
- VIN to License Plate
- License Plate to VIN
- ... More coming soon

P.S.,
If any of the following domains look familiar to you, then you're probably in the right place.
- https://www.golo365.com/
- https://www.launchtechusa.com/
- http://x431.com
- http://api.dbscar.com/
- http://dbscar.com/
- http://ait.golo365.com
- http://aitus.golo365.com

# Usage
## __construct
The class constructor takes only optional arguments.

```PHP
/**
 * Class Constructor
 *
 * @param  ?string $serial_no reporting device serial number
 * @param  ?string $service Golo365 service ait|aitus
 * @author Alec M.
 * @since  1.0.0
 */
public function __construct(string $serial_no = "", string $service = "aitus")
```

If a reporting device `serial_no` is provided, any search/reporting queries that use the optional `serial_no` argument will be provided with this serial number. See the variable documentation in the class for notes on what this argument is.

The `service` argument defines which Golo365 service to report/receive data from. If your Launch LTD diagnostic tablet is built for the U.S., it's likely that all of your device-generated reports will reside within the AITUS service.

## setSerialNo
This function returns the class instance, which enables function call chaining.

```PHP
/**
 * Set or Remove the Device Serial Number
 *
 * @param  ?string $serial_no
 * @return self
 * @since  1.0.0
 */
public function setSerialNo(string $serial_no = "") : self
```

An empty or invalid `serial_no` argument will result in the `serial_no` being returned to the default.

## setListSize
For the API endpoints that support pagination, this specifies the number of results per page. This function supports chaining.

```PHP
/**
 * Set or Remove the Report Listing Size Limit
 *
 * @param  ?integer $size
 * @return self
 * @since  1.0.0
 */
public function setListSize(int $size = 0) : self
```

A `size` of 0 or below results in the removal of a size pagination limitation.

## reportListByVIN
This function fetches all reported diagnostic events for the provided VIN.

```PHP
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
```

Expected return result
```PHP
Array
(
  [0] => Array
  (
    [record_id] => int
    [serial_no] => int
    [date] => 1644710448
    [VIN] => string
    [plate_number] => string
    [url] => string
    [type] => string
  )

  // ...

)
```

## reportListByPlateNumber
```PHP
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
```

Expected return result
```PHP
Array
(
  [0] => Array
  (
    [record_id] => int
    [serial_no] => int
    [date] => 1644710448
    [VIN] => string
    [plate_number] => string
    [url] => string
    [type] => string
  )

  // ...

)
```

# Requirements & Dependencies
- PHP7+
- cURL Extension

