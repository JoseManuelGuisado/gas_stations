<?php

declare(strict_types=1);

namespace Drupal\gas_stations;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of provincias type entities.
 *
 * @see \Drupal\gas_stations\Entity\ProvinciasType
 */
final class ProvinciasTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No provincias types available. <a href=":link">Add provincias type</a>.',
      [':link' => Url::fromRoute('entity.gas_stations_provincias_type.add_form')->toString()],
    );

    return $build;
  }

}
