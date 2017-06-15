<?php

$container = $app->getContainer();


#region --------- LIAISON AVEC TWIG ---------
    // Ajout d'un objet view dans le container (ici objet Twig)
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($c->get('settings')['view']['template_path'], [
        'cache' => $c->get('settings')['view']['twig']['cache']
    ]);

    // Ajout extension Twig à l'objet view
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $view->getEnvironment()->addGlobal('session', $_SESSION);

    return $view;
};
#endregion


#region --------- LIAISON AVEC LA BASE DE DONNÉES ---------
$container['db'] = function ($c) {
    // Récupération dans une variable du tableau DB de l'objet settings
    $settings = $c->get('settings')['db'];

    // Connexion à la base de données
    $dsn = $settings['driver'] . ':dbname=' . $settings['dbname'] . ';host=' . $settings['host'] . ';charset=' . $settings['charset'];
    $pdo = new PDO($dsn, $settings['username'], $settings['password']);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
#endregion