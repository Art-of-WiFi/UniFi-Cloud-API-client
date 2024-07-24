<?php

namespace UniFiCloudApiClient\Interface;

/**
 * Defines the basic structure for service classes within the UniFi Cloud API client.
 *
 * This interface mandates the implementation of methods for listing all resources
 * and retrieving a single resource by its identifier.
 *
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 */
interface ServiceInterface
{
    /**
     * Lists all resources.
     *
     * @return mixed The list of resources, format can vary based on implementation.
     */
    public function list(): mixed;

    /**
     * Retrieves a single resource by its identifier.
     *
     * @param string $id The identifier of the resource to retrieve.
     * @return mixed The resource data, format can vary based on implementation.
     */
    public function get(string $id): mixed;
}
