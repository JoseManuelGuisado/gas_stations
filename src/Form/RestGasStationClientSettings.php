<?php

namespace Drupal\gas_stations\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RestGasStationClientSettings extends ConfigFormBase {
    public function getFormID() {
        return 'rest_gas_station_client_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['intro'] = [
            '#markup' => t('Set here the remote server base url.'),
        ];
        $form['server_url'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Remote server URL'),
            '#description' => $this->t('For example https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/'),
            '#default_value' => $this->config('rest_example.settings')->get('server_url'),
            '#required' => TRUE,
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_values = $form_state->getValues();

        $this->config('rest_example.settings')
            ->set('server_url', $form_values['server_url'])
            ->save();

        parent::submitForm($form, $form_state);
    }

    protected function getEditableConfigNames()
    {
        return ['rest_example.settings'];
    }
}