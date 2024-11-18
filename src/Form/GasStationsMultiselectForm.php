<?php

namespace Drupal\gas_stations\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gas_stations\Query\GasStationsQuery;

class GasStationsMultiselectForm extends FormBase {

  public function getFormId() {
    return 'gas_stations_multiselect_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();

    $form['CCAA'] = [
      '#type' => 'select',
      '#title' => $this->t('Comunidades Autónomas'),
      '#options' => GasStationsQuery::getAllCCAA(),
      '#ajax' => [
        'callback' => [$this, 'reloadProvincias'],
        'event' => 'change',
        'wrapper' => 'provincias-field-wrapper',
      ],
    ];

    $form['provincias'] = [
      '#type' => 'select',
      '#title' => $this->t('Provincias'),
      '#options' => empty($triggering_element) ? [] : GasStationsQuery::getProvinciasFromCCAA($triggering_element['#value']),
      '#prefix' => '<div id="provincias-field-wrapper">',
      '#suffix' => '</div>',
      '#ajax' => [
        'callback' => [$this, 'reloadMunicipios'],
        'event' => 'change',
        'wrapper' => 'municipios-field-wrapper',
      ],
    ];

    $form['municipios'] = [
      '#type' => 'select',
      '#title' => $this->t('Municipios'),
      '#options' => empty($triggering_element) ? [] : GasStationsQuery::getMunicipiosFromProvincia($triggering_element['#value']),
      '#prefix' => '<div id="municipios-field-wrapper">',
      '#suffix' => '</div>'
    ];

    return $form;
  }

  public function reloadProvincias(array $form, FormStateInterface $form_state) {
    return $form['provincias'];
  }


  public function reloadMunicipios(array $form, FormStateInterface $form_state) {
    return $form['municipios'];
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    //
  }


}