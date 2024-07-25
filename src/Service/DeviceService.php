<?php

namespace UniFiCloudApiClient\Service;

use Exception;
use UniFiCloudApiClient\Client\UniFiClient;
use UniFiCloudApiClient\Interface\ServiceInterface;

/**
 * Service class for managing devices within the UniFi API client.
 * Provides functionality to list all devices based on host IDs and time parameters.
 * Note: Retrieving a single device by ID is currently not implemented.
 *
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 */
class DeviceService implements ServiceInterface
{
    /**
     * @var UniFiClient The UniFi API client instance.
     */
    private UniFiClient $client;

    /**
     * Constructs a new DeviceService instance.
     *
     * @param UniFiClient $client The UniFi API client instance.
     */
    public function __construct(UniFiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lists all devices based on provided host IDs and time.
     * If host IDs are provided, devices are filtered by those IDs.
     * If time is provided, filters devices by the specified time.
     *
     * @param array $hostIds Optional. An array of host IDs to filter the devices.
     * @param mixed $time Optional. A time parameter to filter the devices.
     * @return array The list of devices, format can vary based on implementation.
     * @throws Exception If there is an error in the HTTP request.
     */
    public function list(array $hostIds = [], string $time = null): array
    {
        /**
         * array_filter() removes any null values from the $query array
         */
        $query = array_filter([
            'hostIds' => !empty($hostIds) ? $hostIds : null,
            'time'    => $time
        ]);

        return $this->client->request('GET', '/ea/devices', ['query' => $query]);
    }

    /**
     * Retrieves a single device by its identifier.
     * Note: This method is not implemented.
     *
     * @param string $id The identifier of the device to retrieve.
     * @return mixed The device data, format can vary based on implementation.
     * @throws Exception If the method is not implemented.
     */
    public function get(string $id): mixed
    {
        throw new Exception('Get device by ID not (yet) implemented');
    }
}
