<?php

namespace UniFiCloudApiClient\Client;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Throwable;
use UniFiCloudApiClient\Service\HostService;
use UniFiCloudApiClient\Service\SiteService;
use UniFiCloudApiClient\Service\DeviceService;

/**
 * a class designed to interact with the official UniFi Cloud API
 *
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 *
 * properties accessed via magic methods:
 * @property HostService $hosts
 * @property SiteService $sites
 * @property DeviceService $devices
 */
class UniFiClient
{
    /**
     * @var UniFiClient|null Singleton instance of UniFiClient
     */
    private static ?UniFiClient $instance = null;

    /**
     * @var string Version of the UniFi API client
     */
    private const VERSION = '1.0.1';

    /**
     * @var Client Guzzle HTTP client instance
     */
    private Client $client;

    /**
     * @var string API key for authentication
     */
    private string $apiKey;

    /**
     * @var bool Debug mode flag
     */
    private bool $debug = false;

    /**
     * @var array Services related to the API
     */
    private array $services = [];

    /**
     * @var string Base URI for the API
     */
    private string $baseUri = 'https://api.ui.com';

    /**
     * @var int Timeout value for the Guzzle client, default is 10 seconds
     */
    private int $timeout = 10;

    /**
     * Constructor is private to prevent direct creation of objects.
     * Initializes the Guzzle client, API key, and optionally the base URI.
     *
     * @param string $apiKey API key for authentication
     * @param string|null $baseUri Optional base URI for the API
     */
    private function __construct(string $apiKey, ?string $baseUri = null)
    {
        $this->baseUri = $baseUri ?? $this->baseUri;
        $this->client = new Client(['base_uri' => $this->baseUri]);
        $this->apiKey = $apiKey;
    }

    /**
     * Provides access to the singleton instance of UniFiClient.
     * Creates a new instance if it does not exist.
     *
     * @param string $apiKey API key for authentication
     * @param string|null $baseUri Optional base URI for the API
     * @return UniFiClient|null Singleton instance of UniFiClient
     */
    public static function getInstance(string $apiKey, ?string $baseUri = null): ?UniFiClient
    {
        if (self::$instance === null) {
            self::$instance = new UniFiClient($apiKey, $baseUri);
        }
        return self::$instance;
    }

    /**
     * Returns the version of the UniFi API client.
     *
     * @return string Version of the UniFi API client
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * Enables or disables debug mode.
     *
     * @param bool $debug True to enable debug mode, false to disable
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * Returns the debug mode flag.
     *
     * @return bool True if debug mode is enabled, false if disabled
     */
    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Change the timeout value for the Guzzle client.
     *
     * @param int $timeout Timeout value in seconds
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * Returns the timeout value for the Guzzle client.
     *
     * @return int Timeout value in seconds
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Makes an HTTP request to the UniFi Cloud API.
     * Automatically adds the necessary headers and builds query strings.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $uri URI for the request
     * @param array $options Additional options for the request
     * @return array Decoded JSON response
     * @throws Exception If the request fails without a response
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        $options = $this->setRequestHeaders($options);

        if (isset($options['query']) && is_array($options['query'])) {
            $uri .= '?' . $this->buildQuery($options['query']);
            unset($options['query']);
        }

        if ($this->debug) {
            $options['debug'] = true;
        }

        $options['timeout'] = $this->timeout;

        try {
            $response   = $this->client->request($method, $uri, $options);
            $body       = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                return $body;
            }

            $message = $body['message'] ?? 'Unknown error';
            throw new Exception($this->getExceptionMessageByStatusCode($statusCode, $message));
        } catch (RequestException|ConnectException|Throwable $e) {
            if ($e instanceof RequestException && $e->hasResponse()) {
                $response   = $e->getResponse();
                $body       = json_decode($response->getBody(), true);
                $statusCode = $response->getStatusCode();
                $message    = $this->getExceptionMessageByStatusCode($statusCode, $body['message'] ?? $e->getMessage());
            } else {
                $message = $e->getMessage();
            }

            throw new Exception($message);
        }
    }

    /**
     * Sets the request headers for the API request.
     *
     * @param array $options
     * @return array
     */
    public function setRequestHeaders(array $options = []): array
    {
        $options['headers']['X-API-KEY'] = $this->apiKey;
        $options['headers']['Accept']    = 'application/json';

        return $options;
    }

    /**
     * Builds a query string from an array of parameters.
     *
     * @param array $params Parameters to include in the query string
     * @return string Query string
     */
    private function buildQuery(array $params): string
    {
        $query = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $val) {
                    $query[] = urlencode($key) . '[]=' . urlencode($val);
                }
            } else {
                $query[] = urlencode($key) . '=' . urlencode($value);
            }
        }

        return implode('&', $query);
    }

    /**
     * Determines the exception message based on the status code.
     *
     * @todo Add more status codes and messages as needed, consider adding a mapping array/class
     * @param int $statusCode HTTP status code.
     * @param string $message Default message to use if a specific message isn't defined for the status code.
     * @return string Exception message.
     */
    private function getExceptionMessageByStatusCode(int $statusCode, string $message): string
    {
        return match ($statusCode) {
            401     => '401 Unauthorized: ' . $message,
            403     => '403 Forbidden: ' . $message,
            404     => '404 Not Found: ' . $message,
            405     => '405 Method Not Allowed: ' . $message,
            429     => '429 Rate Limit Exceeded: ' . $message,
            500     => '500 Internal Server Error: ' . $message,
            502     => '502 Bad Gateway: ' . $message,
            503     => '503 Service Unavailable: ' . $message,
            default => $statusCode . ' Unknown status code: ' . $message,
        };
    }

    /**
     * Magic method to get the service instance by name.
     * If the service instance does not yet exist, it creates a new one based on the name.
     * Supported services are 'hosts', 'sites', and 'devices'.
     *
     * @param string $name The name of the service to get.
     * @return mixed Returns the service instance.
     * @throws Exception If the service name is not supported.
     */
    public function __get(string $name)
    {
        if (!isset($this->services[$name])) {
            $this->services[$name] = match ($name) {
                'hosts'   => new HostService($this),
                'sites'   => new SiteService($this),
                'devices' => new DeviceService($this),
                default   => throw new Exception("Service $name not found"),
            };
        }

        return $this->services[$name];
    }
}