<?php

namespace UniFiCloudApiClient\Service;

use Exception;
use UniFiCloudApiClient\Client\UniFiClient;
use UniFiCloudApiClient\Interface\ServiceInterface;

/**
 * Service class for managing sites within the UniFi Cloud API client.
 * Provides functionality to list all sites and retrieve a single site by its identifier.
 * Note: Retrieving a single site by ID is currently not implemented.
 *
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 */
class SiteService implements ServiceInterface
{
    /** @var UniFiClient The UniFi Cloud API client instance. */
    private UniFiClient $client;

    /**
     * Constructs a new SiteService instance.
     *
     * @param UniFiClient $client The UniFi Cloud API client instance.
     */
    public function __construct(UniFiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Lists all sites.
     *
     * @return array The list of all sites, format can vary based on implementation.
     * @throws Exception If there is an error in the HTTP request.
     */
    public function list(): array
    {
        return $this->client->request('GET', '/ea/sites');
    }

    /**
     * Retrieves a single site by its identifier.
     *
     * @param string $id The identifier of the site to retrieve.
     * @return array The site data, format can vary based on implementation.
     * @throws Exception If the method is not implemented.
     */
    public function get(string $id): array
    {
        throw new Exception('Get site by ID not implemented');
    }
}
