<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 03/01/2019
 * Time: 14:23
 */

class AzureController extends Controller{
    public function index()
    {
        require_once 'vendor/autoload.php';

        session_start();


        $config = [
            'authentication' => [
                'ad' => [
                    'client_id' => '1770db53-ac01-44c2-86b0-96d675653724',
                    'client_secret' => 'mbauBRD7271+mbrLSYH7%^#',
                    'enabled' => '1',
                    'directory' => 'common',
                    'return_url' => 'http://localhost/sga/'
                ]
            ]
        ];

        $request = new \Zend\Http\PhpEnvironment\Request();

        $ad = new \Magium\ActiveDirectory\ActiveDirectory(
            new \Magium\Configuration\Config\Repository\ArrayConfigurationRepository($config),
            Zend\Psr7Bridge\Psr7ServerRequest::fromZend(new \Zend\Http\PhpEnvironment\Request())
        );

        $entity = $ad->authenticate();

        echo $entity->getName() . '<Br />';
        echo $entity->getOid() . '<Br />';
        echo $entity->getPreferredUsername() . '<Br />';

        echo "<pre>";
        print_r($entity);

        echo "<br><br><br><br>";

        echo "<a href='http://localhost/sga/logout.php'>clique para sair</a>";
    }
}