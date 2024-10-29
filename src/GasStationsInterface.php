<?php

declare(strict_types=1);

namespace Drupal\gas_stations;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a gas_stations entity type.
 */
interface GasStationsInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
