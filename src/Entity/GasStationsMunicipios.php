<?php

declare(strict_types=1);

namespace Drupal\gas_stations\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gas_stations\GasStationsMunicipiosInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the gas_stations_municipios entity class.
 *
 * @ContentEntityType(
 *   id = "gas_stations_municipios",
 *   label = @Translation("gas_stations_municipios"),
 *   label_collection = @Translation("gas_stations_municipioss"),
 *   label_singular = @Translation("gas_stations_municipios"),
 *   label_plural = @Translation("gas_stations_municipioss"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gas_stations_municipioss",
 *     plural = "@count gas_stations_municipioss",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\gas_stations\GasStationsMunicipiosListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\gas_stations\Form\GasStationsMunicipiosForm",
 *       "edit" = "Drupal\gas_stations\Form\GasStationsMunicipiosForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gas_stations\Routing\GasStationsMunicipiosHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gas_stations_municipios",
 *   admin_permission = "administer gas_stations_municipios",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "idmunicipio" = "idmunicipio",
 *     "idprovincia" = "idprovincia",
 *     "idccaa" = "idccaa",
 *   },
 *   links = {
 *     "collection" = "/admin/content/municipios",
 *     "add-form" = "/municipios/add",
 *     "canonical" = "/municipios/{gas_stations_municipios}",
 *     "edit-form" = "/municipios/{gas_stations_municipios}",
 *     "delete-form" = "/municipios/{gas_stations_municipios}/delete",
 *     "delete-multiple-form" = "/admin/content/municipios/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.gas_stations_municipios.settings",
 * )
 */
final class GasStationsMunicipios extends ContentEntityBase implements GasStationsMunicipiosInterface {

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
      ->setDescription(t('The time that the gas_stations_municipios was created.'))
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
      ->setDescription(t('The time that the gas_stations_municipios was last edited.'));

    return $fields;
  }

}
