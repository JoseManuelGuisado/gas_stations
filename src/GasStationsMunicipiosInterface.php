<?php

declare(strict_types=1);

namespace Drupal\gas_stations;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a gas_stations_municipios entity type.
 */
interface GasStationsMunicipiosInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
