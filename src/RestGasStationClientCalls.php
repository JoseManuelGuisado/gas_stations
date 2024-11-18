<?php

namespace Drupal\gas_stations;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\gas_stations\Query\GasStationsQuery;
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

  public function getMunicipios(): array
  {
    return $this->getQuery('Listados/Municipios/');
  }

  public function getProducts(): array
  {
    return $this->getQuery('Listados/ProductosPetroliferos/');
  }

  public function getGasStationsList(): array
  {
    $data = $this->getQuery('EstacionesTerrestres/');

    return $data['ListaEESSPrecio'];
  }

  public function updateMasterData() {

    $ccaa = $this->getCCAA();
    $provincias = $this->getProvincias();
    $municipios = $this->getMunicipios();

    // Update the CCAA
    foreach ($ccaa as $value) {
      if (!GasStationsQuery::getGasStationsCCAAById($value['IDCCAA'])) {
        $data = [
          'idccaa' => $value['IDCCAA'],
          'label' => $value['CCAA']
        ];
        $entity_type_manager = \Drupal::entityTypeManager()->getStorage('gas_stations_ccaa')->create($data);
        $entity_type_manager->save();
      }
    }

    // Update the Provincias
    foreach ($provincias as $value) {
      if (!GasStationsQuery::getGSProvinciasById($value['IDProvincia'])) {
        $data = [
          'idprovincia' => $value['IDPovincia'],
          'idccaa' => $value['IDCCAA'],
          'label' => $value['Provincia']
        ];
        $entity_type_manager = \Drupal::entityTypeManager()->getStorage('gas_stations_provincias')->create($data);
        $entity_type_manager->save();
      }
    }

    // Update the Municipios
    foreach ($municipios as $value) {
      if (!GasStationsQuery::getGSMunicipiosById($value['IDMunicipio'])) {
        $data = [
          'idmunicipio' => $value['IDMunicipio'],
          'idprovincia' => $value['IDProvincia'],
          'idccaa' => $value['IDCCAA'],
          'label' => $value['Municipio']
        ];
        $entity_type_manager = \Drupal::entityTypeManager()->getStorage('gas_stations_municipios')->create($data);
        $entity_type_manager->save();
      }
    }
  }

  public function updateGasStationData() {

    $gas_stations = $this->getGasStationsList();
    foreach ($gas_stations as $value) {
      if (!GasStationsQuery::getGasStationById((int)$value['IDEESS'])) {
        $data = [
          'idGasStation' => $value['IDEESS'],
          'idmunicipio' => $value['IDMunicipio'],
          'idprovincia' => $value['IDProvincia'],
          'idccaa' => $value['IDCCAA'],
          'label' => $value['Rótulo'],
          'description' => $value['Rótulo'],
          'address' => $value['Dirección'],
          'lat' => $value['Latitud'],
          'long' => $value['Longitud (WGS84)'],
        ];
        $entity_type_manager = \Drupal::entityTypeManager()->getStorage('gas_stations')->create($data);
        $entity_type_manager->save();
      }
    }
  }



}