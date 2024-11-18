<?php

namespace Drupal\gas_stations\Query;

class GasStationsQuery {

    public static function getGasStationsCCAAById($value) {
        $query = \Drupal::entityQuery('gas_stations_ccaa')
            ->accessCheck(FALSE)
            ->condition('idccaa', $value, '=')
            ->condition('status', TRUE)
            ->sort('idccaa', "ASC");
        return $query->execute();
    }

    public static function getGSProvinciasById($value) {
        $query = \Drupal::entityQuery('gas_stations_provincias')
            ->accessCheck(FALSE)
            ->condition('idprovincia', $value, '=')
            ->condition('status', TRUE)
            ->sort('idprovincia', "ASC");
        return $query->execute();
    }

    public static function getGSMunicipiosById($value) {
        $query = \Drupal::entityQuery('gas_stations_municipios')
            ->accessCheck(FALSE)
            ->condition('idmunicipio', $value, '=')
            ->condition('status', TRUE)
            ->sort('idmunicipio', "ASC");
        return $query->execute();
    }

  public static function getAllCCAA(): array
  {
    $data = [0 => t('Select one')];

    $query = \Drupal::entityQuery('gas_stations_ccaa')
      ->accessCheck(FALSE)
      ->condition('status', TRUE)
      ->sort('idccaa', "ASC");

    $ccaa = $query->execute();

    foreach ($ccaa as $key => $value) {
      $entity = \Drupal::entityTypeManager()->getStorage('gas_stations_ccaa')->load($key);
      $data[$key] = $entity->get('label')->value;
    }

    return $data;
  }

  public static function getProvinciasFromCCAA (int $ccaa = 0): array
  {
    $data = [0 => t('Select one')];

    $query = \Drupal::entityQuery('gas_stations_provincias')
      ->accessCheck(FALSE)
      ->condition('status', TRUE)
      ->sort('idprovincia', "ASC");

    if ($ccaa != 0) {
      $query->condition('idccaa', $ccaa);
    }

    $provincias = $query->execute();

    foreach ($provincias as $key => $value) {
      $entity = \Drupal::entityTypeManager()->getStorage('gas_stations_provincias')->load($value);
      $data[$entity->get('idprovincia')->value] = $entity->get('label')->value;
    }

    return $data;
  }


  public static function getMunicipiosFromProvincia (int $provincia = 0): array
  {
    $data = [0 => t('Select one')];

    $query = \Drupal::entityQuery('gas_stations_municipios')
      ->accessCheck(FALSE)
      ->condition('status', TRUE)
      ->sort('idmunicipio', "ASC");

    if ($provincia != 0) {
      $query->condition('idprovincia', $provincia);
    }

    $municipios = $query->execute();

    foreach ($municipios as $key => $value) {
      $entity = \Drupal::entityTypeManager()->getStorage('gas_stations_municipios')->load($value);
      $data[$entity->get('idmunicipio')->value] = $entity->get('label')->value;
    }

    return $data;
  }


}