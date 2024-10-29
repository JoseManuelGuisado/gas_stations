<?php

declare(strict_types=1);

namespace Drupal\gas_stations\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\gas_stations\ProvinciasInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the provincias entity class.
 *
 * @ContentEntityType(
 *   id = "gas_stations_provincias",
 *   label = @Translation("Provincias"),
 *   label_collection = @Translation("Provinciass"),
 *   label_singular = @Translation("provincias"),
 *   label_plural = @Translation("provinciass"),
 *   label_count = @PluralTranslation(
 *     singular = "@count provinciass",
 *     plural = "@count provinciass",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\gas_stations\ProvinciasListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\gas_stations\Form\ProvinciasForm",
 *       "edit" = "Drupal\gas_stations\Form\ProvinciasForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\gas_stations\Routing\ProvinciasHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gas_stations_provincias",
 *   admin_permission = "administer gas_stations_provincias types",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *     "idprovincia" = "idprovincia",
 *     "idccaa" = "idccaa",
 *   },
 *   links = {
 *     "collection" = "/admin/content/provincias",
 *     "add-form" = "/provincias/add/{gas_stations_provincias_type}",
 *     "add-page" = "/provincias/add",
 *     "canonical" = "/provincias/{gas_stations_provincias}",
 *     "edit-form" = "/provincias/{gas_stations_provincias}",
 *     "delete-form" = "/provincias/{gas_stations_provincias}/delete",
 *     "delete-multiple-form" = "/admin/content/provincias/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.gas_stations_provincias_type.edit_form",
 * )
 */
final class GasStationsProvincias extends ContentEntityBase implements ProvinciasInterface {

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

      $fields['idprovincia'] = BaseFieldDefinition::create('string')
          ->setLabel(t('ID Provincia'))
          ->setDescription(t('The ID Provincia.'))
          ->setDisplayOptions('form', [
                  'type' => 'string',
                  'weight' => 2,
                  'label' => 'The ID Provincia',
              ]
          );

      $fields['idccaa'] = BaseFieldDefinition::create('string')
          ->setLabel(t('ID Comunidad Autonoma'))
          ->setDescription(t('The ID Comunidad Autonoma.'))
          ->setDisplayOptions('form', [
                  'type' => 'string',
                  'weight' => 2,
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
      ->setDescription(t('The time that the provincias was created.'))
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
      ->setDescription(t('The time that the provincias was last edited.'));

    return $fields;
  }

}
