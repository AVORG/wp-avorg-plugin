<?php

namespace Avorg;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use function defined;
use function get_option;
use GuzzleHttp\Client;

if (!defined('ABSPATH')) exit;

class Guzzle
{
    /** @var Client $newApiClient */
    private $newApiClient;

    /** @var Client $oldApiClient */
    private $oldApiClient;

    public function __construct()
    {
        $this->newApiClient = new Client([
            'base_uri' => 'https://api.audioverse.org/',
            'headers' => ['Authorization' => 'Bearer ' . get_option("avorgApiKey")]
        ]);

        $this->oldApiClient = new Client([
            'base_uri' => 'https://api2.audioverse.org/',
            'auth' => [get_option("avorgApiUser"), get_option("avorgApiPass")]
        ]);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function handleOld(string $method, string $endpoint, array $options = [])
    {
        return $this->handle($this->oldApiClient, $method, $endpoint, $options);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function handleNew(string $method, string $endpoint, array $options = [])
    {
        return $this->handle($this->newApiClient, $method, $endpoint, $options);
    }

    /**
     * @param Client $client
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    private function handle(
        Client $client,
        string $method,
        string $endpoint,
        array $options = []
    )
    {
        if (defined('AVORG_TESTS_RUNNING') && AVORG_TESTS_RUNNING) {
            throw new Exception("Unmocked API method called");
        }

        try {
            $response = $client->request($method, $endpoint, $options);

            return json_decode($response->getBody());
        } catch (GuzzleException $e) {
            throw new Exception("Failed to get response from url $endpoint");
        }
    }
}