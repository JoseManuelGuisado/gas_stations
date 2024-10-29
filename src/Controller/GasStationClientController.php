<?php

namespace Drupal\gas_stations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\gas_stations\RestGasStationClientCalls;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;


class GasStationClientController extends ControllerBase implements ContainerInjectionInterface
{
    /**
     * The config factory.
     *
     * @var \Drupal\Core\Config\ConfigFactoryInterface
     */
    protected $configFactory;

    protected $gasStationClient;


    public function __construct(
        ConfigFactoryInterface $config_factory,
        RestGasStationClientCalls $rest_client,
    ) {
        $this->configFactory = $config_factory;
        $this->gasStationClient = $rest_client;
    }


    public function getGasStationCCAA()
    {

        $a = $this->gasStationClient->updateMasterData();

        dd($a);

        return;
    }

    public static function create(ContainerInterface $container)
    {
        $controller = new static(
            $container->get('config.factory'),
            $container->get('rest_gas_station_client_calls')
        );

        $controller->setMessenger($container->get('messenger'));
        $controller->setStringTranslation($container->get('string_translation'));

        return $controller;
    }
}