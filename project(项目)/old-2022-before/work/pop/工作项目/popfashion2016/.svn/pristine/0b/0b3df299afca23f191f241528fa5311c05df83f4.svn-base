<?php
defined('BASEPATH') OR exit('No direct script access allowed');

switch (ENVIRONMENT) {
    case 'production':
        $config['solr'] = [
            'endpoint' => [
                'write' => [
                    'host' => '192.168.0.222',
                    'port' => 8080,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
                'read' => [
                    'host' => '192.168.0.201',
                    'port' => 8000,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
            ]
        ];
        break;
    case 'testing':
        $config['solr'] = [
            'endpoint' => [
                'write' => [
                    'host' => '192.168.3.186',
                    'port' => 9090,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
                'read' => [
                    'host' => '192.168.3.186',
                    'port' => 9090,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
            ]
        ];
        break;
    case 'development':
    default:
        $config['solr'] = [
            'endpoint' => [
                'write' => [
                    'host' => '192.168.3.184',
                    'port' => 9090,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
                'read' => [
                    'host' => '192.168.3.184',
                    'port' => 9090,
                    'path' => '/solr/',
                    'core' => 'pop-fashion',
                ],
            ]
        ];
        break;
}
