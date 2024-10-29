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
}