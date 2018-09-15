<?php
/**
 * Fichier config.php du 02/09/2018
 * Description : Fichier de la classe ${NAME}
 *
 * PHP version 5
 *
 * @category Configuration
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */
return [
    'debug' => true,
    'servers' => [
        [
            'name' => 'nas',
            'description' => 'Nas de la maison',
            'address' => '192.168.1.2',
            'port' => 5551,
            'protocol' => 'http',
            'version' => 1,
            'user' => 'phpunit',
            'pwd' => '?)(8ct',
        ],
        [
            'name' => 'nasdev',
            'description' => 'Nas de développement',
            'address' => '192.168.1.20',
            'port' => 5552,
            'protocol' => 'http',
            'version' => 1,
            'user' => 'mycore',
            'pwd' => 'c=|#B@',
            'format' => 'sid'
        ]
    ]
];
