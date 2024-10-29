<?php

declare(strict_types=1);

namespace Drupal\gas_stations\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gas_stations\ComunidadesAutonomasInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the comunidades_autonomas entity class.
 *
 * @ContentEntityType(
 *   id = "gas_stations_ccaa",
 *   label = @Translation("comunidades_autonomas"),
 *   label_collection = @Translation("comunidades_autonomass"),
 *   label_singular = @Translation("comunidades_autonomas"),
 *   label_plural = @Translation("comunidades_autonomass"),
 *   label_count = @PluralTranslation(
 *     singular = "@count comunidades_autonomass",
 *     plural = "@count comunidades_autonomass",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\gas_stations\ComunidadesAutonomasListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\gas_stations\Form\ComunidadesAutonomasForm",
 *       "edit" = "Drupal\gas_stations\Form\ComunidadesAutonomasForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gas_stations\Routing\ComunidadesAutonomasHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gas_stations_ccaa",
 *   admin_permission = "administer gas_stations_ccaa",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "IDCCAA" = "idccaa",
 *   },
 *   links = {
 *     "collection" = "/admin/content/comunidades-autonomas",
 *     "add-form" = "/comunidades-autonomas/add",
 *     "canonical" = "/comunidades-autonomas/{gas_stations_ccaa}",
 *     "edit-form" = "/comunidades-autonomas/{gas_stations_ccaa}",
 *     "delete-form" = "/comunidades-autonomas/{gas_stations_ccaa}/delete",
 *     "delete-multiple-form" = "/admin/content/comunidades-autonomas/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.gas_stations_ccaa.settings",
 * )
 */
final class ComunidadesAutonomas extends ContentEntityBase implements ComunidadesAutonomasInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Status'))
      ->setDefaultValue(TRUE)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'settings' => [
          'display_label' => FALSE,
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'boolean',
        'label' => 'above',
        'weight' => 0,
        'settings' => [
          'format' => 'enabled-disabled',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the comunidades_autonomas was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the comunidades_autonomas was last edited.'));

    $fields['idccaa'] = BaseFieldDefinition::create('string')
        ->setLabel(t('ID Comunidad Autonoma'))
        ->setDescription(t('The ID Comunidad Autonoma.'))
        ->setDisplayOptions('form', [
                'type' => 'string',
                'weight' => 2,
                'label' => 'The ID Comunidad Autonoma',
            ]
        );

    return $fields;
  }

}
