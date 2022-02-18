# Introduction

## What is Golo365?

Golo365 is the underlying data connection service behind many automotive diagnostic computers and equipment. Device manufacturers, such as Launch Tech LTD and Matco Tools rely on them for various services. The goal of this API wrapper is to simplify access to Golo365's *extremely undocumented* API endpoints, and to allow for independent individuals or companies to integrate critical data connections between Golo365 and their own proprietary services.

## How can I use this wrapper?

The end goal of the wrapper is to remove the concept of relying the handheld diagnostic computer, as well as the physical connection to the vehicle, in order to retrieve diagnostic history for a vehicle. This is possible because Golo365 (i.e. AIT / DBSCAR / X431) stores the scan history in the cloud. Here are some ways you can use this API wrapper to facilitate a connection between your service (e.g. a Shop Management System) and Golo365:

- Fetch Diagnostic Scan History reports by License Plate or VIN numbers
- Perform a VIN-to-License plate decode
- Perform a License-to-VIN decode
- ... More integrations coming soon

## Need More?

I've put an extensive amount of Golo365 research into the [DOCUMENTATION.md](./DOCUMENTATION.md) file. If you want to create your own wrapper, without reverse engineering this one, rely on information from that file. The [DOCUMENTATION.md](./DOCUMENTATION.md) file also details endpoints discovered but not implemented yet.

## Contributions

Many ~~hours~~ days of research and development went into discovering these entirely undocumented API endpoints. If you have an unlisted Golo365 endpoint, or know something that isn't listed in this repository, please reach out via a GitHub Issue or Pull Request.

___

# Usage

The function documentation is below. Alternatively, see the `example.php` file included with the repository for hands-on demonstrations of various functionality.

## __construct

The class constructor accepts only optional arguments.

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

The `service` argument defines which Golo365 service to report/receive data from. If your diagnostic tablet is built for the U.S., it's likely that all of your device-generated reports will reside within the AITUS service.

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

### Output

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
    [_raw] => Closure
    [_reportDetail] => Closure
  )

  // ... repeating

)
```

### Notes

1. To access the raw API data (messy), you can use the closure function available via the `_raw` index.
2. You may call the `reportDetail` function via the `_reportDetail` index; it takes no arguments as they are derived from the current element.

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

### Output

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
    [_raw] => Closure
    [_reportDetail] => Closure
  )

  // ... repeating

)
```

### Notes

1. The reliability of this function is dependent on end-users reporting the License Plate Number during the diagnostic session. If this was not done, those results will not be included in the return value. **If possible, use `reportListByVIN` instead**.
2. To access the raw API data (messy), you can use the closure function available via the `_raw` index.
3. You may call the `reportDetail` function via the `_reportDetail` index; it takes no arguments as they are derived from the current element.

## reportList

This function fetches all reports produced by a device serial number. It accepts one optional argument. The serial number provided during class instantiation (or via `setSerialNo`) is used.

```PHP
/**
 * Fetch Diagnostic Scan History by Serial Number
 *
 * @param mixed $page page number to fetch
 */
public function reportList($page = "") : array
```

### Output

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
    [_raw] => Closure
    [_reportDetail] => Closure
  )

  // ... repeating

)
```

### Notes

1. To access the raw API data (messy), you can use the closure function available via the `_raw` index.
2. You may call the `reportDetail` function via the `_reportDetail` index; it takes no arguments as they are derived from the current element.

## reportDetail

This endpoint provides additional details about a diagnostic event record; such as which systems were scanned, which software was used, etc.

```PHP
/**
 * Fetch additional details about a diagnostic scan
 *
 * @param  int $record_id
 * @param  string $type
 * @return array
 * @throws \TypeError
 * @throws \InvalidArgumentException
 * @since  1.0.0
 */
public function reportDetail(int $record_id, string $type) : array
```

### Output

```PHP
Array
(
  [software_version] => string
  [software_package] => string
  [system_list] => Array
  (
    [0] => Array
    (
        [system_uid] => padded int
        [system] => string
        [name_id] => int
        [is_new_sys] => int
    )
  )
  [_raw] => Closure Object
)
```

### Notes

1. To access the raw data from this array, call the anonymous function (closure) `_raw`

## getPlateByVIN

This function will return a license plate number associated with a VIN number. **Important** see the notes about this endpoint below.

```PHP
/**
 * Fetch a License Plate by the VIN Number
 *
 * @param  string $VIN the vehicle VIN number to search a plate for
 * @return array
 * @throws \InvalidArgumentException
 * @since  1.0.0
 */
public function getPlateByVIN(string $VIN) : array
```

### Output

```PHP
Array
(
  [VIN] => string
  [plate_number] => string
  [_raw] => Closure Object
)
```

### Notes

1. This endpoint relies on user-inputted data to return a matching plate number. This endpoint does not rely on a government data source, and could easily provide incorrect information.
2. To access raw API data, simply call the `_raw` closure object

## getVINByPlateNumber

This function is the inverse of `getPlateByVIN`, and returns a VIN number when provided a license plate number. **Important** see the notes about this endpoint below.

```PHP
/**
 * Fetch the VIN by License Plate
 *
 * @param  string $plate_number License Plate # to search
 * @return array
 * @throws TypeError
 * @throws InvalidArgumentException
 * @since  1.0.0
 */
public function getVINByPlateNumber(string $plate_number) : array
```

### Output

```PHP
Array
(
  [VIN] => string
  [plate_number] => string
  [_raw] => Closure Object
)
```

### Notes

1. This endpoint **does not rely on a government data source**. It relies on the data passed when a diagnostic device is submitting a diagnostic event. Unless the plate number was reported during this diagnostic event, no results will be returned.
2. The only raw data returned is `vin` and `plate_number`; but for consistency sake, the `_raw` closure object is still available for accessing the raw API data.

___

# To-Do

- upload_report_data
- upload_accessory_info
- mergeMultiReport

___

# Requirements & Dependencies

- PHP7+
- cURL Extension
