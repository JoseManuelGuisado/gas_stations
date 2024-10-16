<?php

namespace Drupal\gas_stations;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\ClientInterface;

class RestGasStationClientCalls {

    /**
     * The client used to send HTTP requests.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * The headers used when sending HTTP request.
     *
     * The headers are very important when communicating with the REST server.
     * They are used by the server that verifies it supports the data sent
     * (Content-Type) and it supports the type of response the client wants.
     *
     * @var array
     */
    protected $clientHeaders = [
        'Accept' => 'application/haljson',
        'Content-Type' => 'application/haljson',
    ];

    /**
     * The URL of the remote REST server.
     *
     * @var string
     */
    protected $remoteUrl;

    /**
     * The constructor.
     *
     * @param \GuzzleHttp\ClientInterface $client
     *   The HTTP client.
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The config factory.
     */
    public function __construct(ClientInterface $client, ConfigFactoryInterface $config_factory) {
        $this->client = $client;

        // Retrieve the config from the configuration page set at
        // examples/rest_client_settings.
        $rest_config = $config_factory->get('rest_example.settings');

        $this->remoteUrl = $rest_config->get('server_url');
    }

    protected function getQuery($query):array
    {
        if (empty($this->remoteUrl)) {
            return '';
        }

        $response = $this->client->request(
            'GET',
            $this->remoteUrl . $query,
            ['headers' => $this->clientHeaders]
        );

        return Json::decode($response->getBody()->getContents());
    }

    public function getCCAA(): array
    {
        return $this->getQuery('Listados/ComunidadesAutonomas/');
    }

    public function getProvincias(): array
    {
        return $this->getQuery('Listados/Provincias/');
    }



}