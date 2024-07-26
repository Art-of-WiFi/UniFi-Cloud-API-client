# API client class for Official UniFi Cloud API

A PHP class that provides access to Ubiquiti's official [**UniFi Cloud API**](https://developer.ui.com/unifi-api/).

## Introduction

This client provides a modern and convenient way to interact with the official UniFi Cloud API using PHP.
It uses Guzzle for HTTP requests and supports various operations to interact with UniFi deployments programmatically.

This client supports the official UniFi Cloud API up to version **0.1**.

### Important Note

This is **not** a replacement for our original API Client which offers more functions and endpoints,
but does not support connections through unifi.ui.com:
https://github.com/Art-of-WiFi/UniFi-API-client

## Requirements

- a server with:
    - PHP **8.1** or higher
    - php-curl module installed and enabled
- direct network connectivity between this server and https://api.ui.com
- an API key that can be obtained as follows:
  - Sign in to the UniFi Site Manager at https://unifi.ui.com.
  - From the left navigation bar, click on API.
  - Click **Create API Key**.
  - Copy the key and store it securely, as it will only be displayed once.
  - Click Done to ensure the key is hashed and securely stored.

## Installation

The package needs to be installed using 
Composer/[packagist](https://packagist.org/packages/art-of-wifi/unifi-cloud-api-client)
for easy inclusion in your projects by executing the following command from your project directory:

```bash
composer require art-of-wifi/unifi-cloud-api-client
```

Follow these [installation instructions](https://getcomposer.org/doc/00-intro.md) if you don't have
Composer installed already.

---

# Developer Documentation

## Usage

Below is a basic example of how to use the UniFi Cloud API Client:

```php
<?php

require 'vendor/autoload.php';

use UniFiCloudApiClient\Client\UniFiClient;

$apiKey = 'your_api_key_here';

try {
    // Initialize the UniFi Cloud API client, optionally you can pass a different base URI
    $unifiClient = UniFiClient::getInstance($apiKey);
    
    // Enable debug mode, optional
    $unifiClient->setDebug(true);
    
    // Set a custom timeout to override the default value of 10 seconds, optional
    $unifiClient->setTimeout(5);
    
    // Fetch and echo the version
    echo 'UniFi Cloud API client version: ' . $unifiClient->getVersion() . PHP_EOL;
    
    // List all hosts
    $hosts = $unifiClient->hosts->list();
    print_r($hosts);
    
    // Get host by ID
    $hostId = 'your_host_id_here';
    $host = $unifiClient->hosts->get($hostId);
    print_r($host);
    
    // List all sites
    $sites = $unifiClient->sites->list();
    print_r($sites);
    
    // List all devices with optional parameters
    $devices = $unifiClient->devices->list(
        ['900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789', '900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:987654321'], 
        '2024-07-15T07:01:13Z'
    );
    print_r($devices);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Getter and Setter Methods

There are several getter and setter methods available to interact with the UniFi Cloud API client:
- setTimeout(int $timeout): Sets the timeout for the HTTP requests in seconds, default is 10 seconds.
- getTimeout(): Returns the timeout for the HTTP requests in seconds.
- setDebug(bool $debug): Enables or disables debug mode, false by default.
- getDebug(): Returns the debug mode status.
- getVersion(): Returns the version of the UniFi Cloud API client.

## API Reference

### List Hosts

#### API Route
`GET /ea/hosts`

#### Description
Retrieve a list of all hosts associated with the UI account making the API call.

#### Usage
```php
$hosts = $unifiClient->hosts->list();
```

#### Example Response

```php
[
  "data" => [
    [
      "hardwareId" => "eae0f123-0000-5111-b111-f833f56eade5",
      "id" => "900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789",
      "ipAddress" => "192.168.220.114",
      "isBlocked" => false,
      "lastConnectionStateChange" => "2024-06-23T03:59:52Z",
      "latestBackupTime" => "2024-06-22T11:55:10Z",
      "owner" => true,
      "registrationTime" => "2024-04-17T07:27:14Z",
      "reportedState" => [...], // Replace [...] with actual data structure
      "type" => "console",
      "userData" => [...], // Replace [...] with actual data structure
    ]
  ],
  "httpStatusCode" => 200,
  "traceId" => "a7dc15e0eb4527142d7823515b15f87d"
]
```

### Get Host by ID

#### API Route
`GET /ea/hosts/{id}`

#### Description
Retrieve detailed information about a specific host by ID.

#### Usage
```php
$hostId = 'your_host_id_here';
$host = $unifiClient->hosts->get($hostId);
```

#### Example Response
```php
[
  "data" => [
    "hardwareId" => "eae0f123-0000-5111-b111-f833f56eade5",
    "id" => "900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789",
    "ipAddress" => "192.168.220.114",
    "isBlocked" => false,
    "lastConnectionStateChange" => "2024-06-23T03:59:52Z",
    "latestBackupTime" => "2024-06-22T11:55:10Z",
    "owner" => true,
    "registrationTime" => "2024-04-17T07:27:14Z",
    "reportedState" => [...], // Replace [...] with actual data structure
    "type" => "console",
    "userData" => [...], // Replace [...] with actual data structure
  ],
  "httpStatusCode" => 200,
  "traceId" => "a7dc15e0eb4527142d7823515b15f87d"
]
```

### List Sites

#### API Route
`GET /ea/sites`

#### Description
List sites associated with the UI account making the API call.

#### Usage
```php
$sites = $unifiClient->sites->list();
```

#### Example Response
```php
[
  "data" => [
    [
      "hostId" => "900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789",
      "isOwner" => true,
      "meta" => [
      "desc" => "Default",
      "gatewayMac" => "f4:e2:c6:c2:3f:13",
      "name" => "default",
      "timezone" => "Europe/Riga"
    ],
      "permission" => "admin",
      "siteId" => "661900ae6aec8f548d49fd54",
      "statistics" => [...], // Replace [...] with actual data structure
      "subscriptionEndTime" => "2024-06-27T13:09:46Z"
    ]
  ],
  "httpStatusCode" => 200,
  "traceId" => "a7dc15e0eb4527142d7823515b15f87d"
]
```

### List Devices

#### API Route
`GET /ea/devices`

#### Description
List UniFi devices that are associated with the UI account making the API call.

#### Usage
```php
$devices = $unifiClient->devices->list(
    ['900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789', '900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:987654321'], 
    '2024-07-15T07:01:13Z'
);
```

#### Parameters
- `hostIds[]` (array, optional): List of host IDs.
- `time` (string, optional): Last processed timestamp of devices.

#### Example Response
```php
[
  "data" => [
    [
      "devices" => [
        [
          "adoptionTime" => null,
          "firmwareStatus" => "upToDate",
          "id" => "F4E2C6C23F13",
          "ip" => "192.168.1.226",
          "isConsole" => true,
          "isManaged" => true,
          "mac" => "F4E2C6C23F13",
          "model" => "UDM SE",
          "name" => "unifi.yourdomain.com",
          "note" => null,
          "productLine" => "network",
          "shortname" => "UDMPROSE",
          "startupTime" => "2024-06-19T13:41:43Z",
          "status" => "online",
          "uidb" => [
            "guid" => "0fd8c390-a0e8-4cb2-b93a-7b3051c83c46",
            "id" => "e85485da-54c3-4906-8f19-3cef4116ff02",
            "images" => [
              "default" => "3008400039c483c496f4ad820242c447",
              "nopadding" => "67b553529d0e523ca9dd4826076c5f3f",
              "topology" => "8371ecdda1f00f1636a2eefadf0d7d47"
            ]
          ],
          "updateAvailable" => null,
          "version" => "4.0.6"
        ]
      ],
      "hostId" => "900A6F00301100000000074A6BA90000000007A3387E0000000063EC9853:123456789",
      "hostName" => "unifi.yourdomain.com",
      "updatedAt" => "2024-07-15T07:01:13Z"
    ]
  ],
  "httpStatusCode" => 200,
  "traceId" => "a7dc15e0eb4527142d7823515b15f87d"
]
```

### Rate Limiting

The API rate limit is set to 100 requests per minute. If you exceed this limit, the server will respond with a 
**429 Too Many Requests** status code. The client will throw an Exception if this happens.

## Important note on general API support

When encountering issues with the official UniFi Cloud API using other libraries, cURL or Postman, please do **not**
open an Issue here. Such Issues will be closed immediately.
Please use the [Discussions](https://github.com/Art-of-WiFi/UniFi-Cloud-API-client/discussions) section instead.

## Contribute

If you would like to contribute to this repository, please open an issue and include your code there or else
create a pull request.

## Credits

This class is based on the documentation provided by Ubiquiti for their UniFi Cloud API:
https://developer.ui.com/unifi-api/

## Important Disclaimer

This PHP class is provided "as is" without any guarantees or warranties. Use at your own risk.
The author is not responsible for any damage or losses of any kind caused by the use or misuse of this class.