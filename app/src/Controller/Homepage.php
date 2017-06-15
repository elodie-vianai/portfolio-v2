<?php

namespace Portfolio\Controller;

use Portfolio\Portfolio\Controller;
use Portfolio\Portfolio\Flash;
use Portfolio\Model\Project as ProjectModel;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Slim\Http\Request;
use Slim\Http\Response;

class Homepage extends Controller
{
#region /******************************* METHOD : Homepage **************************************************************/
    public function index (Request $request, Response $response) {
        //session_destroy();
        $project_model = new ProjectModel($this->container);
        $projects_result = $project_model->get4LastProjects();
        $lastProject = $project_model->getLastProject();
        $projects= [];
        foreach ($projects_result as $project) {
            if ($project['id'] == $lastProject['id']) {
                $project['last'] = 'oui';
            }
            $projects[] = $project;
        }
        $user = [];
        if (!empty($_SESSION)) {
            $user = $_SESSION['user'];

// API Spotify
            $api = new SpotifyWebAPI();
            $api->setAccessToken($_SESSION['spotify_token']);
            // récupère la liste des playlists publiques
            $listPlaylists = $api->getUserPlaylists('1157591261', ['limit' => 5]);
            $playlists = [];
            foreach ($listPlaylists->items as $item) {
                $temp = [];
                $tempId[] = $item->id;
                foreach ($tempId as $i) {
                    $temp['idPlaylist'] = $i;
                }
                $tempName[] = $item->name;
                foreach ($tempName as $i) {
                    $temp['namePlaylist'] = $i;
                }
                $tempHref[] = $item->href;
                foreach ($tempHref as $i) {
                    $temp['hrefPlaylist'] = $i;
                }
                $playlists[] = $temp;
            }

            if ($user['roles'] == 'admin') {
                return $this->render($response, 'AdminZone/homepage.html.twig',
                    ['projects' => $projects, 'user' => $user, 'playlists' => $playlists]);
            } else {
                return $this->render($response, 'UsersZone/homepage.html.twig',
                    ['projects' => $projects, 'user' => $user, 'playlists' => $playlists]);
            }
        } else {
            return $this->render($response, 'UsersZone/homepage.html.twig',
                ['projects' => $projects, 'user' => $user]);
        }
    }
#endregion


#region /******************************* METHOD : Api Spotify ***********************************************************/
    #region --> Authentification with Spotify and get the token
    public function spotifyAuthentification(Request $request, Response $response)
    {
        $router = $this->container->get('router');

        $session = new Session(
            'da8c033ac8bb4c16a9be33cbd7501e58',
            '97e33270196d42f5a953524381ea5cda',
            'http://portfolio-v2.dev:8080/spotify'
        );
        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $_SESSION['spotify_token'] = $session->getAccessToken();
        }
        if ($_SESSION['roles'] == 'admin'){
            return $response->withRedirect($router->pathFor('adminHomepage'));
        }
        else {
            return $response->withRedirect($router->pathFor('userHomepage'));
        }

    }
    #endregion

    #region --> get the list of playlists and its titles
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function getPlaylist(Request $request, Response $response, array $args)
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($_SESSION['spotify_token']);
        // récupère le nom de la playlist
        $playlist = $api->getUserPlaylist('1157591261', $args['idPlaylist']);
        $namePlaylist = $playlist->name;
        // récupère la liste des titres d'une playlist
        $tracksPlaylist = $api->getUserPlaylistTracks('1157591261', $args['idPlaylist']);
        $tracks = [];
        foreach ($tracksPlaylist->items as $item) {
            $temp = [];
            $tempId[] = $item->track->id;
            foreach ($tempId as $id) {
                $temp['idTrack'] = $id;
            }
            $tempTracks[] = $item->track->name;
            foreach ($tempTracks as $track) {
                $temp['nameTrack'] = $track;
            }
            $temp['artist'] = $item->track->artists[0]->name;
            $temp['album'] = $item->track->album->name;
            $temp['previewUrl'] = $item->track->preview_url;
            $tracks[] = $temp;
        }
        return $response->withJson([
            'namePlaylist' => $namePlaylist,
            'tracks' => $tracks,
        ]);
    }
    #endregion

    #region --> Get data of a title
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function getTrack(Request $request, Response $response, array $args)
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken($_SESSION['spotify_token']);
        // récupère la liste des titres d'une playlist
        $trackInfos = $api->getTrack($args['idTrack']);
        $track['idTrack'] = $trackInfos->id;
        $track['nameTrack'] = $trackInfos->name;
        $track['artistTrack'] = $trackInfos->album->artists[0]->name;
        $track['albumTrack'] = $trackInfos->album->name;
        $track['previewUrl'] = $trackInfos->preview_url;
        $track['albumImgTrack'] = $trackInfos->album->images[1]->url;

        return $response->withJson([
            'track' => $track,
        ]);
    }
    #endregion
#endregion
}