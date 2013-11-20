<?php
namespace Conference;

use ZF\Apigility\ApigilityModuleInterface;
use Guzzle\Http\Client as GuzzleClient;
use Conference\V1\Rest\Conference\JoindInService;
use Conference\V1\Rest\Conference\ConferenceResource;

class Module implements ApigilityModuleInterface
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'GuzzleClient' =>  function ($sm) {
                    // @TODO: config the URL :)
                    return new GuzzleClient('https://api.joind.in');
                },
                'Conference\V1\Rest\Conference\JoindInService' =>  function ($sm) {
                    $client = $sm->get('GuzzleClient');
                    return new JoindInService($client);
                },
                'Conference\V1\Rest\Conference\ConferenceResource' => function ($sm) {
                    $service = $sm->get('Conference\V1\Rest\Conference\JoindInService');
                    return new ConferenceResource($service);
                },


            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
