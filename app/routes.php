<?php
/**
 * Ensemble des routes dont l'application a besoin
 */

#region --------- PUBLIC ZONE ---------
$app->get('/', 'Portfolio\Controller\Homepage:index')->setName('publicHomepage');
$app->get('/experiences', 'Portfolio\Controller\Experience:index');
$app->get('/formation', 'Portfolio\Controller\Formation:index');
$app->get('/competences', 'Portfolio\Controller\Skill:index');
$app->get('/projet/{id}', 'Portfolio\Controller\Project:projectDetail');
$app->get('/contact', 'Portfolio\Controller\Contact:index');

// Authentifications
$app->map(['get', 'post'],'/inscription', 'Portfolio\Controller\Authentification:signUp');
$app->get('/connexion', 'Portfolio\Controller\Authentification:index')->setName('login_page');
$app->post('/connecting', 'Portfolio\Controller\Authentification:login');
$app->get('/spotify', 'Portfolio\Controller\Homepage:spotifyAuthentification');
$app->get('/disconnect', 'Portfolio\Controller\Authentification:logout')->setName('logout');
#endregion


#region --------- USER'S CONNECTED ZONE ---------
$app->group('/user', function () {
    // Homepage
    $this->get('', 'Portfolio\Controller\Homepage:index')->setName('userHomepage');

    $this->get('/experiences', 'Portfolio\Controller\Experience:index');
    $this->get('/formation', 'Portfolio\Controller\Formation:index');
    $this->get('/competences', 'Portfolio\Controller\Skill:index');
    $this->get('/projet/{id}', 'Portfolio\Controller\Project:projectDetail');
    $this->get('/contact', 'Portfolio\Controller\Contact:index');

    //Gestion du compte utilisateur
    $this->get('/moncompte', 'Portfolio\Controller\User:getmyaccount')->setName('my_account');
    $this->map(['get', 'post'], '/moncompte/modifier', 'Portfolio\Controller\User:update');
    $this->get('/supprimer', 'Portfolio\Controller\User:delete');

    //API Spotify
    $this->get('/playlist/{idPlaylist}', 'Portfolio\Controller\Homepage:getPlaylist');
    $this->get('/playlist/{idPlaylist}/{idTrack}', 'Portfolio\Controller\Homepage:getTrack');
});
#endregion


#region --------- ADMIN'S ZONE ---------
$app->group('/admin', function () {
    // Homepage
    $this->get('', 'Portfolio\Controller\Homepage:index')->setName('adminHomepage');
    $this->get('/contact', 'Portfolio\Controller\Contact:index');

    // Gestion des utilisateurs
    $this->group('/gestiondesutilisateurs', function() {
        //CRUD
        $this->get('', 'Portfolio\Controller\User:crud')->setName('CRUD_utilisateurs');
        $this->map(['get', 'post'], '/modifier/{id}', 'Portfolio\Controller\User:update');
        $this->map(['get', 'post'], '/supprimer/{id}', 'Portfolio\Controller\User:delete');
    });

    // Gestion des expériences
    $this->group('/gestiondesexperiences', function() {
        //CRUD
        $this->get('', 'Portfolio\Controller\Experience:crud')->setName('CRUD_experiences');
        $this->map(['get', 'post'], '/ajouter', 'Portfolio\Controller\Experience:add');
        $this->map(['get', 'post'], '/modifier/{id}', 'Portfolio\Controller\Experience:update');
        $this->get('/supprimer/{id}', 'Portfolio\Controller\Experience:delete');
    });

    // Gestion des formations
    $this->group('/gestiondesformations', function() {
        //CRUD
        $this->get('', 'Portfolio\Controller\Formation:crud')->setName('CRUD_formations');
        $this->map(['get', 'post'], '/ajouter', 'Portfolio\Controller\Formation:add');
        $this->map(['get', 'post'], '/modifier/{id}', 'Portfolio\Controller\Formation:update');
        $this->get('/supprimer/{id}', 'Portfolio\Controller\Formation:delete');
    });

    // Gestion des projets
    $this->group('/gestiondesprojets', function() {
        //CRUD
        $this->get('', 'Portfolio\Controller\Project:crud')->setName('CRUD_projects');
        $this->get('/detail_projet/{id}', 'Portfolio\Controller\Project:projectDetail');
        $this->map(['get', 'post'], '/ajouter', 'Portfolio\Controller\Project:add');
        $this->map(['get', 'post'], '/modifier/{id}', 'Portfolio\Controller\Project:update');
        $this->get('/supprimer/{id}', 'Portfolio\Controller\Project:delete');
    });

    // Gestion des compétences
    $this->group('/gestiondescompetences', function() {
        //CRUD
        $this->get('', 'Portfolio\Controller\Skill:crud')->setName('CRUD_skills');
        $this->map(['get', 'post'], '/ajouter', 'Portfolio\Controller\Skill:add');
        $this->map(['get', 'post'], '/modifier/{id}', 'Portfolio\Controller\Skill:update');
        $this->get('/supprimer/{id}', 'Portfolio\Controller\Skill:delete');
    });

    //API Spotify
    $this->get('/playlist/{idPlaylist}', 'Portfolio\Controller\Homepage:getPlaylist');
    $this->get('/playlist/{idPlaylist}/{idTrack}', 'Portfolio\Controller\Homepage:getTrack');
});
#endregion