<?php

namespace UniFiCloudApiClient\Service;

use Exception;
use UniFiCloudApiClient\Client\UniFiClient;
use UniFiCloudApiClient\Interface\ServiceInterface;

/**
 * Service class for managing hosts-related endpoints/routes within the UniFi API client.
 * Provides functionality to list all hosts and retrieve a single host by its identifier.
 *
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 */
class HostService implements ServiceInterface
{
    /**
     * @var UniFiClient The UniFi API client instance.
     */
    private UniFiClient $client;

    /**
     * Constructs a new HostService instance.
     *
     * @param UniFiClient $client The UniFi API client instance.
     */
    public function __construct(UniFiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lists all hosts.
     *
     * @return array The list of all hosts, format can vary based on implementation.
     * @throws Exception
     */
    public function list(): array
    {
        return $this->client->request('GET', '/ea/hosts');
    }

    /**
     * Retrieves a single host by its identifier.
     *
     * @param string $id The identifier of the host to retrieve.
     * @return array The host data, format can vary based on implementation.
     * @throws Exception
     */
    public function get(string $id): array
    {
        return $this->client->request('GET', "/ea/hosts/$id");
    }
}
