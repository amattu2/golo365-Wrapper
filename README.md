# Introduction
## What is Golo365?
Golo365 is the underlying data connection service behind many automotive diagnostic computers and equipment. Device manufacturers, such as Launch Tech LTD and Matco Tools rely on them for various services. The goal of this API wrapper is to simplify access to Golo365's *extremely undocumented* API endpoints, and to allow for independent individuals or companies to integrate critical data connections between Golo365 and their own proprietary services.

## How can I use this wrapper?
The end goal of the wrapper is to remove the concept of relying the handheld diagnostic computer, as well as the physical connection to the vehicle, in order to retrieve diagnostic history for a vehicle. This is possible because Golo365 (i.e. AIT / DBSCAR / X431) stores the scan history in the cloud. Here are some ways you can use this API wrapper to facilitate a connection between your service (e.g. a Shop Management System) and Golo365:
- Fetch Diagnostic Scan History reports by License Plate or VIN numbers
- Perform a VIN-to-License plate decode
- Perform a License-to-VIN decode
- ... More integrations coming soon

## Am I in the right place?
### Related Domains

If any of the following domains look familiar to you, then you're probably in the right place.
- https://www.golo365.com/
- https://www.launchtechusa.com/
- http://x431.com
- http://api.dbscar.com/
- http://dbscar.com/
- http://ait.golo365.com
- http://aitus.golo365.com
- https://api.mythinkcar.com/

### Related Auto Diagnostic Equipment

Or, if your shop is using any of the following tools, you're probably in the right place:
- [MATCO MAXIMUS 4.0 TABLET](https://www.matcotools.com/catalog/product/MDMAX4CL/MAXIMUS-4-0-TABLET-WITH-PASSENGER-CAR-SOFTWARE/)
- [Launch Gear Scan Plus Diagnostic Tool](https://www.bestbuyautoequipment.com/launch-gear-scan-plus-diagnostic-tool-p/301050458.htm)
- [Launch X-431 Torque 3](https://www.launchtechusa.com/product-page/x431-torqueiii)
- [Launch X-431 V+ Pro](https://www.amazon.com/gp/product/B00NID586M)
- ... Basically anything from Launch, even if it's rebranded (like the MATCO tools)

If your diagnostic scanner (or the Android app) has a "Home" page that looks similar to below, your data is probably being synced with Golo / Golo365 / AIT.

![android_app_screenshot](./screenshot.jpg)

### Diagnostic Report/Record Examples
If your diagnostic tool generates a report that looks like these, you're definitely in the right place:
- http://aitus.golo365.com/report/think_car/?id=14025fbfjq8cKwTd8cOM5454&type=diag&lan=en-us
- http://reportview.thinkcar.com/report/think_car/?id=e4a79e73jq1uKwtZtZTdLrnR&type=diag&lan=en-us
- http://aws.ithinkcar.com/Home/Index/shareReportNew?id=e598545djq8cTdAEnR2YOMoG&type=diag&lan=en-us
- http://aitus.golo365.com/Home/Report/reportDetail/diagnose_record_id/{ID_NUM_HERE}/report_type/X/timezone/0/l/en-us

## Contributions
Many hours of research and development went into discovering these entirely undocumented API endpoints. If you have an unlisted Golo365 endpoint, or know something that isn't listed here, please reach out via a GitHub Issue or Pull Request.

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

___

# To-Do
- upload_report_data
- upload_accessory_info
- mergeMultiReport

___

# Requirements & Dependencies
- PHP7+
- cURL Extension

