<?php

declare(strict_types=1);

namespace Drupal\gas_stations\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gas_stations\GasStationsInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the gas_stations entity class.
 *
 * @ContentEntityType(
 *   id = "gas_stations",
 *   label = @Translation("gas_stations"),
 *   label_collection = @Translation("gas_stationss"),
 *   label_singular = @Translation("gas_stations"),
 *   label_plural = @Translation("gas_stationss"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gas_stationss",
 *     plural = "@count gas_stationss",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\gas_stations\GasStationsListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\gas_stations\GasStationsAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\gas_stations\Form\GasStationsForm",
 *       "edit" = "Drupal\gas_stations\Form\GasStationsForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gas_stations\Routing\GasStationsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gas_stations",
 *   admin_permission = "administer gas_stations",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gas-stations",
 *     "add-form" = "/gas-stations/add",
 *     "canonical" = "/gas-stations/{gas_stations}",
 *     "edit-form" = "/gas-stations/{gas_stations}",
 *     "delete-form" = "/gas-stations/{gas_stations}/delete",
 *     "delete-multiple-form" = "/admin/content/gas-stations/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.gas_stations.settings",
 * )
 */
final class GasStations extends ContentEntityBase implements GasStationsInterface {

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

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['idgasstation'] = BaseFieldDefinition::create('string')
      ->setLabel(t('ID Gas Station'))
      ->setDescription(t('The Gas Station ID.'))
      ->setDisplayOptions('form', [
          'type' => 'string',
          'weight' => 20,
          'label' => 'The Gas Station ID',
        ]
      );

    $fields['idmunicipio'] = BaseFieldDefinition::create('string')
      ->setLabel(t('ID Municipio'))
      ->setDescription(t('The ID Municipio.'))
      ->setDisplayOptions('form', [
          'type' => 'string',
          'weight' => 20,
          'label' => 'The ID Municipio',
        ]
      );

    $fields['idprovincia'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID Provincia'))
      ->setDescription(t('The ID Provincia.'))
      ->setDisplayOptions('form', [
          'type' => 'integer',
          'label' => 'The ID Provincia',
        ]
      );

    $fields['idccaa'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID Comunidad Autonoma'))
      ->setDescription(t('The ID Comunidad Autonoma.'))
      ->setDisplayOptions('form', [
          'type' => 'integer',
          'label' => 'The ID Comunidad Autonoma',
        ]
      );

    $fields['lat'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Gas Station Latitude'))
      ->setDescription(t('The Gas Station Latitude.'))
      ->setDisplayOptions('form', [
          'type' => 'varchar',
          'size' => 12,
          'label' => 'The Gas Station Latitude',
        ]
      );

    $fields['long'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Gas Station Longitude'))
      ->setDescription(t('The Gas Station Longitude.'))
      ->setDisplayOptions('form', [
          'type' => 'varchar',
          'size' => 12,
          'label' => 'The Gas Station Longitude',
        ]
      );

    $fields['address'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Gas Station Address'))
      ->setDescription(t('The Gas Station Address.'))
      ->setDisplayOptions('form', [
          'type' => 'varchar',
          'label' => 'The Gas Station Address.',
        ]
      );

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
      ->setDescription(t('The time that the gas_stations was created.'))
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
      ->setDescription(t('The time that the gas_stations was last edited.'));

    return $fields;
  }

}
