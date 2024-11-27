<?php

namespace Drupal\gas_stations\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Raffle panel Block.
 *
 * @Block(
 *  id = "gas_stations_block",
 *  admin_label = @Translation("Gas Stations Block"),
 *  group = "Gas Stations"
 * )
 */
class GasStationsBlock extends BlockBase {

    public function build()
    {
      $data = [];

      $data['form'] = \Drupal::formBuilder()->getForm('Drupal\gas_stations\Form\GasStationsMultiselectForm');

      $build = [
        '#theme' => 'gas_stations_block',
        '#attached' => [
          'library' => [
            'gas_stations/gas_stations',
            'core/drupal.dialog.ajax',
          ],
        ],
        '#data' => $data
      ];


      return $build;
    }
}