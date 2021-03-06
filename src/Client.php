<?php

namespace Ejabberd\Rest;

use GuzzleHttp\Client as GuzzleHttpClient;

/**
 * Ejabberd XMPP connection client
 *
 * @package Ejabberd\Rest
 */
class Client
{

    use Traits\User;
    use Traits\Roster;
    use Traits\Service;

    /**
     * @var GuzzleHttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $host;

    public function __construct(array $options)
    {
        $this->checkConfigParameters($options);

        $this->client = new GuzzleHttpClient([
            'base_uri' => $this->apiUrl
        ]);
    }

    /**
     * Check mandatory configuration parameters
     *
     */
    protected function checkConfigParameters($options)
    {
        // ToDo: Use OptionsResolver from Symfony: https://symfony.com/doc/current/components/options_resolver.html
        if (!isset($options['apiUrl'])) {
            throw new \InvalidArgumentException("Parameter 'apiUrl' is not specified");
        }

        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("Parameter 'host' is not specified");
        }

        $this->apiUrl = $options['apiUrl'];
        $this->host = $options['host'];
    }

    /**
     * Sends the call to the Ejabberd REST API, with a payload
     *
     * @param $endPoint
     * @param $payload
     * @return mixed
     */
    protected function sendRequest($endPoint, $payload)
    {
        try {
            $response = $this->client->post($endPoint, ['json' => $payload]);
        } catch (\Exception $exception) {
            //Log ?
            throw new \RuntimeException($exception->getMessage());
        }

        // ToDo: Check that we have a response body ($response->getCode())
        return \GuzzleHttp\json_decode($response->getBody(), true);
    }
}