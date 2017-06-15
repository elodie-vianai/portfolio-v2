<?php
/**
 * Configuration de la base de données et du chemin pour récupérer les vues
 */

return [
    'settings' => [
        // false en prod
        'displayErrorDetails' => true,
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'portfolio',
            'username' => 'root',
            'password' => '',
            'charset'   => 'UTF8'
        ],
        'view' => [
            'template_path' => __DIR__ . '/src/View',
            'twig' => [
                //'cache' => __DIR__ . '/../cache/twig',
                'cache' => false,
                'debug' => true,
                'auto_reload' => true
            ]
        ]
    ]
];