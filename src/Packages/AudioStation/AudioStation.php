<?php
/**
 * Fichier AudioStation.php du 07/09/2018
 * Description : Fichier de la classe AudioStation
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Packages\FileStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\AudioStation;

use Rcnchris\Synology\Package;
use Rcnchris\Synology\Packages\AudioStation\Entities\Folder;
use Rcnchris\Synology\Packages\AudioStation\Entities\Player;
use Rcnchris\Synology\Packages\AudioStation\Entities\Song;

/**
 * Class AudioStation
 *
 * @category API
 *
 * @package  Packages\FileStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class AudioStation extends Package
{

    /**
     * Obtenir la liste des albums
     *
     * - `$pkg->albums()->toArray();`
     * - `$pkg->albums(['limit' => 5])->toArray();`
     * - `$pkg->albums(['limit' => 5], 'albums')->toArray();`
     * - `$pkg->albums(['limit' => 5], 'albums', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function albums(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1,
            'artist' => null
        ], $params);
        return $this->get('Album', 'list', $params, 'Albums list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste des artistes
     *
     * - `$pkg->artists()->toArray();`
     * - `$pkg->artists(['limit' => 5])->toArray();`
     * - `$pkg->artists(['limit' => 5], 'artists')->toArray();`
     * - `$pkg->artists(['limit' => 5], 'artists', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function artists(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Artist', 'list', $params, 'Artists list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste des compositeurs
     *
     * - `$pkg->composers()->toArray();`
     * - `$pkg->composers(['limit' => 5])->toArray();`
     * - `$pkg->composers(['limit' => 5], 'composers')->toArray();`
     * - `$pkg->composers(['limit' => 5], 'composers', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function composers(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('Composer', 'list', $params, 'Composers list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste de dossiers
     *
     * - `$pkg->folders()->toArray();`
     * - `$pkg->folders(['limit' => 5])->toArray();`
     * - `$pkg->folders(['limit' => 5], 'items')->toArray();`
     * - `$pkg->folders(['limit' => 5], 'items', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function folders(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Folder', 'list', $params, 'Folders list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir un dossier par son identifiant
     *
     * - `$package->folder('dir_9')->toArray();`
     *
     * @param string    $id       Identifiant du dossier
     * @param bool|null $toEntity Si faux, les données sont retournées dans un tableau
     *
     * @return \Rcnchris\Core\Tools\Items|\Rcnchris\Synology\Entity
     */
    public function folder($id, $toEntity = true)
    {
        $response = $this
            ->get('Folder', 'getinfo', compact('id'), "Folder get $id", 'items')
            ->first();
        if ($toEntity) {
            return $this->getEntity($response->toArray(), Folder::class);
        }
        return $response;
    }

    /**
     * Obtenir la liste des genres
     *
     * - `$pkg->genres()->toArray();`
     * - `$pkg->genres(['limit' => 5])->toArray();`
     * - `$pkg->genres(['limit' => 5], 'genres')->toArray();`
     * - `$pkg->genres(['limit' => 5], 'genres', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function genres(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Genre', 'list', $params, 'Genres list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir les listes de lectures
     *
     * - `$pkg->playlists()->toArray();`
     * - `$pkg->playlists(['limit' => 5])->toArray();`
     * - `$pkg->playlists(['limit' => 5], 'playlists')->toArray();`
     * - `$pkg->playlists(['limit' => 5], 'playlists', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function playlists(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Playlist', 'list', $params, 'Playlists list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir une playlist par son identifiant
     *
     * - `$package->playlist('playlist_shared_normal/345')->toArray();`
     *
     * @param string $id Identifiant de la playlist
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function playlist($id)
    {
        return $this
            ->get('Playlist', 'getinfo', compact('id'), 'Playlist get', 'playlists')
            ->first();
    }

    /**
     * Obtenir la liste des radios
     *
     * - `$pkg->radios()->toArray();`
     * - `$pkg->radios(['limit' => 5])->toArray();`
     * - `$pkg->radios(['limit' => 5], 'radios')->toArray();`
     * - `$pkg->radios(['limit' => 5], 'radios', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function radios(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Radio', 'list', $params, 'Radios list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste des lecteurs
     *
     * - `$pkg->players()->toArray();`
     * - `$pkg->players(['limit' => 5])->toArray();`
     * - `$pkg->players(['limit' => 5], 'players')->toArray();`
     * - `$pkg->players(['limit' => 5], 'players', 'name')->toArray();`
     *
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function players($extractKey = null)
    {
        return $this->get('RemotePlayer', 'list', [], 'Players list', 'players', $extractKey);
    }

    /**
     * Obtenir un lecteur
     *
     * - `$package->player('playlist_shared_normal/345')->toArray();`
     *
     * @param string    $id       Identifiant de la playlist
     * @param bool|null $toEntity Si faux, les données sont retournées dans une instance de Items
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\Rcnchris\Synology\Entity|Player
     */
    public function player($id, $toEntity = true)
    {
        // Obtenir le lecteur parmi la liste des lecteurs
        // car la méthode getstatus ne retourne pas les mêmes champs
        $player = $this
            ->players()
            ->toArray(function ($player) use ($id) {
                if ($player['id'] === $id) {
                    return $player;
                }
                return null;
            });

        if (!empty($player)) {
            $player = current($player);
            $response = $this->get('RemotePlayer', 'getstatus', ['id' => $player['id']], 'Player get');
            foreach ($player as $field => $value) {
                $response->set($field, $value);
            }
            $item = $this->getEntity($response->toArray(), Player::class);
            return !$toEntity
                ? $item->toArray()
                : $item;
        }
        return false;
    }

    /**
     * Obtenir la liste de lecture d'un lecteur
     *
     * @param string $id Identifiant du lecteur
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function playlistOfPlayer($id)
    {
        return $this->get('RemotePlayer', 'getplaylist', compact('id'), 'Get playlist of player');
    }


    /**
     * Obtenir la liste des radios
     *
     * - `$pkg->servers()->toArray();`
     * - `$pkg->servers(['limit' => 5])->toArray();`
     * - `$pkg->servers(['limit' => 5], 'list')->toArray();`
     * - `$pkg->servers(['limit' => 5], 'list', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function servers(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('MediaServer', 'list', $params, 'Servers list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste des morceaux
     *
     * - `$pkg->songs()->toArray();`
     * - `$pkg->songs(['limit' => 5])->toArray();`
     * - `$pkg->songs(['limit' => 5], 'songs')->toArray();`
     * - `$pkg->songs(['limit' => 5], 'songs', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function songs(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('Song', 'list', $params, 'Songs list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir un morceau par son identifiant
     *
     * - `$package->song('music_72628');`
     * - `$package->song('music_72628', false);`
     *
     * @param string    $id       Identifiant du morceau
     * @param bool|null $toEntity Si faux, les données sont retournées dans une instance de Items
     *
     * @return \Rcnchris\Core\Tools\Items|\Rcnchris\Synology\Entity
     */
    public function song($id, $toEntity = true)
    {
        $response = $this
            ->get('Song', 'getinfo', compact('id'), 'Get song', 'songs')
            ->first();
        return $toEntity
            ? $this->getEntity($response->toArray(), Song::class)
            : $response;
    }

    /**
     * Obtenir les paroles d'une chanson
     *
     * - `$package->lyricsOfSong('music_v_77896');`
     *
     * @param string $id Identifiant du morceau
     *
     * @return string
     */
    public function lyricsOfSong($id)
    {
        return $this
            ->get('Lyrics', 'getlyrics', compact('id'), "Lyrics of $id")
            ->get('lyrics');
    }

    /**
     * Effectuer une recherche d'un morceau
     *
     * - `$package->searchSong('postale')->toArray();`
     *
     * @param string      $title      Titre du morceau
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function searchSong($title, array $params = [], $itemsKey = null, $extractKey = null)
    {
        return $this->get(
            'Song',
            'search',
            array_merge($params, compact('title')),
            'Search song',
            $itemsKey,
            $extractKey
        );
    }
}
