<?php
/**
 * Fichier VideoStation.php du 08/09/2018
 * Description : Fichier de la classe VideoStation
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\VideoStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\VideoStation;

use Rcnchris\Synology\Entity;
use Rcnchris\Synology\Package;
use Rcnchris\Synology\Packages\VideoStation\Entities\Collection;
use Rcnchris\Synology\Packages\VideoStation\Entities\Episode;
use Rcnchris\Synology\Packages\VideoStation\Entities\Movie;
use Rcnchris\Synology\Packages\VideoStation\Entities\TVShow;
use Rcnchris\Synology\Packages\VideoStation\Entities\Video;

/**
 * Class VideoStation
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\VideoStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class VideoStation extends Package
{

    /**
     * Obtenir les informations du package
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function infos($extractKey = null)
    {
        return $this->get('Info', 'getinfo', [], 'VideoStation Infos', $extractKey);
    }

    /**
     * Obtenir la liste des collections
     *
     * - `$pkg->collections()->toArray();`
     * - `$pkg->collections(['limit' => 5])->toArray();`
     * - `$pkg->collections(['limit' => 5], 'collections')->toArray();`
     * - `$pkg->collections(['limit' => 5], 'collections', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function collections(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Collection', 'list', $params, 'Collections list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir une collection par son identifiant
     *
     * - `$package->collection(3);`
     *
     * @param int         $id        Identifiant de la collection
     * @param string|null $className Nom de la classe à instancier pour l'entité
     *
     * @return Entity|Collection
     */
    public function collection($id, $className = null)
    {
        $item = $this->get('Collection', 'getinfo', compact('id'), "Get collection $id");
        if (is_null($className)) {
            $className = Collection::class;
        }
        return $this->getEntity($item->toArray(), $className);
    }

    /**
     * Obtenir les vidéos d'une collection par son identifiant
     *
     * - `$video->videosOfCollection(3)->toArray();`
     * - `$video->videosOfCollection(3, 'title');`
     *
     * @param string      $id         Identifiant de la collection
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function videosOfCollection($id, $extractKey = null)
    {
        return $this->get('Collection', 'video_list', compact('id'), 'videos', $extractKey);
    }

    /**
     * Obtenir la liste des librairies
     *
     * - `$package->libraries()->toArray();`
     * - `$package->libraries('title')->toArray();`
     *
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function libraries($extractKey = null)
    {
        return $this->get('Library', 'list', [], 'Libraries list', 'libraries', $extractKey);
    }

    /**
     * Obtenir la liste des films
     *
     * - `$video->movies(['limit' => 10])->toArray();`
     * - `$video->movies(['limit' => 10], 'movies')->toArray();`
     * - `$video->movies(['limit' => 10], 'movies', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function movies(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
        ], $params);
        return $this->get('Movie', 'list', $params, 'Movies list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir un film par son identifiant
     *
     * - `$video->movie(292);`
     *
     * @param string|int  $id        Identifiant de l'item
     * @param string|null $className Nom de la classe à instancier pour l'entité
     *
     * @return Entity|Movie
     */
    public function movie($id, $className = null)
    {
        $version = 2;
        $item = $this
            ->get('Movie', 'getinfo', compact('version', 'id'), "Get movie $id", 'movies')
            ->first();
        if (is_null($className)) {
            $className = Movie::class;
        }
        return $this->getEntity($item->toArray(), $className);
    }

    /**
     * Effectuer une recherche de films
     *
     * @param string $title Titre à chercher
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchMovies($title)
    {
        return $this->get('Movie', 'search', ['title' => $title], "Movies search $title");
    }

    /**
     * Obtenir la liste des collections
     *
     * - `$pkg->tvshows()->toArray();`
     * - `$pkg->tvshows(['limit' => 5])->toArray();`
     * - `$pkg->tvshows(['limit' => 5], 'tvshows')->toArray();`
     * - `$pkg->tvshows(['limit' => 5], 'tvshows', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function tvshows(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('TVShow', 'list', $params, 'TV Show list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir une série TV par son identifiant
     *
     * - `$package->tvshow(54);`
     * - `$package->tvshow(54, false);`
     *
     * @param int         $id        Identifiant de la série
     * @param string|null $className Nom de la classe à instancier pour l'entité
     *
     * @return Entity|TVShow
     */
    public function tvshow($id, $className = null)
    {
        $version = 2;
        $item = $this
            ->get('TVShow', 'getinfo', compact('version', 'id'), "Get TV Show $id", 'tvshows')
            ->first();
        if (is_null($className)) {
            $className = TVShow::class;
        }
        return $this->getEntity($item->toArray(), $className);
    }

    /**
     * Obtenir la liste des épisodes
     *
     * - `$pkg->episodes()->toArray();`
     * - `$pkg->episodes(['limit' => 5])->toArray();`
     * - `$pkg->episodes(['limit' => 5], 'episodes')->toArray();`
     * - `$pkg->episodes(['limit' => 5], 'episodes', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function episodes(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'version' => 1,
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('TVShowEpisode', 'list', $params, 'Episodes list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir un épisode par son identifiant
     *
     * - `$pkg->episode(292);`
     * - `$pkg->episode(292, false);`
     *
     * @param int         $id        Identifiant de l'épisode
     * @param string|null $className Nom de la classe à instacier pour l'entité
     *
     * @return Entity|Episode
     */
    public function episode($id, $className = null)
    {
        $version = 2;
        $item = $this
            ->get('TVShowEpisode', 'getinfo', compact('version', 'id'), "Get episode $id", 'episodes')
            ->first();
        if (is_null($className)) {
            $className = Episode::class;
        }
        return $this->getEntity($item->toArray(), $className);
    }

    /**
     * Effectuer une recherche d'épisodes
     *
     * @param string $title Titre à chercher
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchEpisodes($title)
    {
        return $this->get('TVShowEpisode', 'search', ['title' => $title], "Episodes for $title");
    }

    /**
     * Obtenir la liste des vidéos personnelles
     *
     * - `$pkg->videos()->toArray();`
     * - `$pkg->videos(['limit' => 5])->toArray();`
     * - `$pkg->videos(['limit' => 5], 'videos')->toArray();`
     * - `$pkg->videos(['limit' => 5], 'videos', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function videos(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('HomeVideo', 'list', $params, 'Videos list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir une vidéo personnelle par son identifiant
     *
     * - `$package->video(26);`
     * - `$package->video(26, false);`
     *
     * @param int         $id        Identifiant de la vidéo
     * @param string|null $className Nom de la classe à instancier pour l'entité
     *
     * @return Entity|Video
     */
    public function video($id, $className = null)
    {
        $version = 2;
        $item = $this
            ->get('HomeVideo', 'getinfo', compact('version', 'id'), "Get video $id", 'videos')
            ->first();
        if (is_null($className)) {
            $className = Video::class;
        }
        return $this->getEntity($item->toArray(), $className);
    }

    /**
     * Effectuer une recherche de vidéos
     *
     * @param string $title Titre à chercher
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchVideos($title)
    {
        return $this->get('HomeVideo', 'search', ['title' => $title], "Videos search $title");
    }

    /**
     * Obtenir la liste des enregistrements TV
     *
     * - `$package->recordings()->toArray();`
     * - `$package->recordings(['limit' => 5])->toArray();`
     * - `$package->recordings(['limit' => 5], 'recordings')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function recordings(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('TVRecording', 'list', $params, 'TVRecording list', $itemsKey, $extractKey);
    }
}
