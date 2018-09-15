<?php
/**
 * Fichier FileStation.php du 05/09/2018
 * Description : Fichier de la classe FileStation
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\FileStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\FileStation;

use Rcnchris\Synology\Entity;
use Rcnchris\Synology\Package;

/**
 * Class FileStation
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\FileStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class FileStation extends Package
{
    /**
     * Obtenir les informations du package
     *
     * - `$package->infos()->toArray()`
     * - `$package->infos('is_manager')`
     *
     * @param string|null $itemsKey Clé à retourner
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function infos($itemsKey = null)
    {
        return $this->get('Info', 'get', [], 'Informations FileStation', $itemsKey);
    }

    /**
     * Obtenir la liste des dossiers partagés
     *
     * - `$pkg->shares()->toArray();`
     * - `$pkg->shares(['limit' => 5])->toArray();`
     * - `$pkg->shares(['limit' => 5], 'shares')->toArray();`
     * - `$pkg->shares(['limit' => 5], 'shares', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function shares(array $params = [], $itemsKey = null, $extractKey = null)
    {
        return $this->get('List', 'list_share', $params, 'Shares list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir le contenu d'un chemin
     *
     * - `$package->contentOfPath('/Download/Tests')->toArray();`
     * - `$package->contentOfPath('/Download/Tests', ['limit' => 5], 'files', 'name')->toArray();`
     *
     * @param string      $folderPath Dossier
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function contentOfPath($folderPath, array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'folder_path' => $folderPath,
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'filetype' => 'all',
//            'pattern' => '',
//            'goto_path' => '',
            'additional' => 'size'
        ], $params);
        return $this->get('List', 'list', $params, 'Content of ' . $folderPath, $itemsKey, $extractKey);
    }

    /**
     * Obtenir la liste des liens partagés
     *
     * - `$pkg->sharings()->toArray();`
     * - `$pkg->sharings(['limit' => 5])->toArray();`
     * - `$pkg->sharings(['limit' => 5], 'links')->toArray();`
     * - `$pkg->sharings(['limit' => 5], 'links', 'name')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function sharings(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'force_clean' => 'false'
        ], $params);
        return $this->get('Sharing', 'list', $params, 'Sharings list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir un lien partagé par son identifiant
     *
     * - `$package->sharing('BvIxLaka4');`
     * - `$package->sharing('BvIxLaka4', 'name');`
     *
     * @param string      $id       Identifiant du lien partagé
     * @param string|null $itemsKey Nom de la clé à retourner
     *
     * @return \Rcnchris\Synology\Entity
     */
    public function sharing($id, $itemsKey = null)
    {
        $response = $this->get('Sharing', 'getinfo', compact('id'), 'Get sharing')->toArray();
        $entity = new Entity($this, $response);
        if (!is_null($itemsKey)) {
            return $entity->$itemsKey;
        }
        return $entity;
    }

    /**
     * Créer un lien partagé à partir d'un chemin
     *
     * - `$package->createSharing(/Download/Tests/Piles.xlsx);`
     *
     * @param string     $path   Chemin du dossier ou fichier
     * @param array|null $params Paramètres de la requêtes
     *                           - password
     *                           - date_expired (YYYY-MM-DD)
     *                           - date_available (YYYY-MM-DD)
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function createSharing($path, array $params = [])
    {
        $params = array_merge([
            'path' => $path,
            //'password' => '',
            'date_expired' => 0,
            'date_available' => 0
        ], $params);
        return $this->get('Sharing', 'create', $params, 'Sharing create');
    }

    /**
     * Supprimer un lien partagé à partir de son identifiant
     *
     * - `$package->deleteSharing('BvIxLaka4');`
     *
     * @param string $id Identifiant du lien partagé à supprimer
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function deleteSharing($id)
    {
        return $this
            ->get('Sharing', 'delete', compact('id'), "Sharing delete", 'items')
            ->get('success');
    }

    /**
     * Supprimer tous les liens expirés
     *
     * - `$pkg->clearSharing();`
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function clearSharing()
    {
        return $this
            ->get('Sharing', 'clear_invalid', [], 'Sharings clear', 'items')
            ->get('success');
    }

    /**
     * Effectuer une recherche de contenu à partir d'un chemin
     *
     * - `$package->search('/Download/Tests', '*.zip')->toArray():`
     * - `$package->search('/Download/Tests', '*.zip', ['limit' => 5])->toArray():`
     * - `$package->search('/Download/Tests', '*.zip', [], 'files')->toArray():`
     * - `$package->search('/Download/Tests', '*.zip', [], 'files', 'name')->toArray():`
     *
     * @param string      $folderPath Chemin de départ de la recherche
     * @param null        $pattern    Glob pattern (*.zip...)
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function search($folderPath, $pattern = null, array $params = [], $itemsKey = null, $extractKey = null)
    {
        $apiName = 'Search';
        $params = array_merge([
            'folder_path' => $folderPath,
            'recursive' => 'true',
            'pattern' => $pattern,
            'extension' => null,
            'filetype' => 'all',
            // 'size_from' => 0,
            // 'size_to' => 0,
            // 'mtime_from' => 0,
            // 'mtime_to' => 0,
            // 'crtime_from' => 0,
            // 'crtime_to' => 0,
            // 'atime_from' => 0,
            // 'atime_to' => 0,
            // 'owner' => '',
            // 'group' => ''
        ], $params);

        $taskid = $this->startTask($apiName, $params);
        $params = [
            'taskid' => $taskid,
            'offset' => 0,
            'limit' => -1,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            //'pattern' => $pattern,
            'filetype' => 'all',
            'additional' => 'size'
        ];
        $response = $this->get($apiName, 'list', $params, 'Search list', $itemsKey, $extractKey);
        $this->stopTask($apiName, $taskid, true);
        return $response;
    }

    /**
     * Obtenir la liste des montages virtuels
     *
     * - `$pkg->virtualFolders()->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function virtualFolders(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'type' => 'iso',
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'additional' => 'volume_status'
        ], $params);
        return $this->get('VirtualFolder', 'list', $params, 'Viruals folders list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir la listes des favoris
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function favorites(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
            'status_filter' => 'all'
        ], $params);
        return $this->get('Favorite', 'list', $params, 'Favorites list', $itemsKey, $extractKey);
    }

    /**
     * Ajouter un favori
     *
     * - `$pkg->addFavorite('/Download/Tests', 'Tests');`
     *
     * @param string   $path  Chemin du favori
     * @param string   $name  Nom du favori
     * @param int|null $index Position dans la liste
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function addFavorite($path, $name, $index = -1)
    {
        return $this
            ->get('Favorite', 'add', compact('path', 'name', 'index'), 'Favorite create')
            ->get('success');
    }

    /**
     * Supprimer un favori par son chemin
     *
     * - `$pkg->->deleteFavorite('/Download/Tests');`
     *
     * @param string $path Chemin du favori à supprimer
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function deleteFavorite($path)
    {
        return $this
            ->get('Favorite', 'delete', compact('path'), 'Favorite delete')
            ->get('success');
    }

    /**
     * Modier le nom d'un favori
     *
     * - `$pkg->->editFavorite('/Download/Tests', 'Nouveau nom');`
     *
     * @param string $path Chemin du favori
     * @param string $name Nouveau nom du favori
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function editFavorite($path, $name)
    {
        return $this
            ->get('Favorite', 'edit', compact('path', 'name'), 'Favorite edit')
            ->get('success');
    }

    /**
     * Supprimer les favoris dont le lien est inacessible
     *
     * - `$pkg->->clearFavorites();`
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function clearFavorites()
    {
        return $this
            ->get('Favorite', 'clear_broken', [], 'Favorites clear')
            ->get('success');
    }

    /**
     * Obtenir l'URL d'une image
     *
     * - `$package->thumb('/Download/Tests/chevrolet.jpg');`
     * - `$package->thumb('/Download/Tests/chevrolet.jpg', 'small');`
     * - `$package->thumb('/Download/Tests/chevrolet.jpg', 'large', 4);`
     *
     * @param string      $path   Chemin du fichier image
     * @param string|null $size   Taille de l'image (small, medium, large ou original)
     * @param int|null    $rotate Rotation de l'image (0, 1, 2, 3 ou 4)
     *
     * @return bool|string
     */
    public function thumb($path, $size = 'original', $rotate = 0)
    {
        return $this->get('Thumb', 'get', compact('path', 'size', 'rotate'), "Thumb $path", null, null, true);
    }

    /**
     * Obtenir la taille d'un fichier/dossier
     *
     * - `$package->size('/Download/Tests/chevrolet.jpg');`
     *
     * @param string $path Chemin du fichier/dossier
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function size($path)
    {
        $apiName = 'DirSize';
        $taskid = $this->startTask($apiName, compact('path'));
        $response = $this->get('DirSize', 'status', compact('taskid'), "Size of $path", 'total_size');
        $this->stopTask($apiName, $taskid);
        return $response;
    }

    /**
     * Obtenir le code md5 d'un fichier
     *
     * - `$pkg->md5('/Download/Tests/chevrolet.jpg');`
     *
     * @param string $file_path Chemin du fichier
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function md5($file_path)
    {
        $apiName = 'MD5';
        $taskid = $this->startTask($apiName, compact('file_path'));
        $response = $this->get('MD5', 'status', compact('taskid'), "Get md5 of $file_path", 'md5');
        $this->stopTask($apiName, $taskid);
        return $response;
    }

    /**
     * Créer un dossier dans un dossier partagé
     *
     * - `$pkg->createFolder('/Download/Tests', 'NouveauDossier');`
     *
     * @param string     $folderPath Chemin dans lequel le dossier doit être créé
     * @param string     $name       Nom du dossier à créer
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function createFolder($folderPath, $name, array $params = [])
    {
        $params = array_merge([
            'folder_path' => $folderPath,
            'name' => $name,
            'force_parent' => 'false',
            'additional' => 'size'
        ], $params);
        return $this
            ->get('CreateFolder', 'create', $params, "Folder create $folderPath", 'folders')
            ->first();
    }

    /**
     * Renommer un fichier/dossier
     *
     * - `$pkg->rename('/Download/Tests/chevrolet.jpg', 'CamaroSS.jpg')`
     *
     * @param string     $path   Chemin du fichier/dossier à renommer
     * @param string     $name   Nouveau nom
     * @param array|null $params Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function rename($path, $name, array $params = [])
    {
        $params = array_merge([
            'path' => $path,
            'name' => $name,
            'additional' => 'size',
            //'search_taskid' => ''
        ], $params);
        return $this
            ->get('Rename', 'rename', $params, "Rename $path to $name", 'files')
            ->first();
    }

    /**
     * Copier/déplacer un fichier/dossier
     *
     * - `$pkg->copy('/Download/Tests/Dossier', '/Download/Tests/CopieDossier');`
     *
     * @param string     $path       Chemin du fichier/dossier à copier
     * @param string     $destFolder Chemin de la destination de la copie
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function copy($path, $destFolder, array $params = [])
    {
        $apiName = 'CopyMove';
        $params = array_merge([
            'path' => $path,
            'dest_folder_path' => $destFolder,
            'overwrite' => 'false',
            'remove_src' => 'false',
            'accurate_progress' => 'true',
            //'search_taskid' => ''
        ], $params);
        $taskid = $this->startTask($apiName, $params);
        $response = $this->get('CopyMove', 'status', compact('taskid'), "Copy $path to $destFolder");
        $this->stopTask($apiName, $taskid);
        return $response;
    }

    /**
     * Supprimer un fichier/dossier
     *
     * - `$pkg->delete('/Download/Tests/DossierSupprime');`
     *
     * @param string     $path   Chemin du fichier/dossier à supprimer
     * @param array|null $params Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function delete($path, array $params = [])
    {
        $params = array_merge([
            'path' => $path,
            'recursive' => 'true',
            //'search_taskid' => ''
        ], $params);
        return $this->get('Delete', 'delete', $params, "Delete $path");
    }

    /**
     * Extraction d'un archive
     *
     * - `$pkg->extract();`
     *
     * @param string     $filePath   Chemin de l'archive
     * @param string     $destFolder Destination de l'extraction
     * @param array|null $params     Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function extract($filePath, $destFolder, array $params = [])
    {
        $apiName = 'Extract';
        $params = array_merge([
            'file_path' => $filePath,
            'dest_folder_path' => $destFolder,
            'overwrite' => 'true',
            'keep_dir' => 'true',
            'create_subfolder' => 'true',
            'codepage' => $this->infos('system_codepage'),
//            'password' => '',
//            'item_id' => 0,
        ], $params);
        $taskid = $this->startTask($apiName, $params);
        $response = $this->get($apiName, 'status', compact('taskid'), "Get status extract $filePath");
        $this->stopTask($apiName, $taskid);
        return $response;
    }

    /**
     * Lire le contenu d'une archive
     *
     * - `$package->extractList('/Download/Tests/tests.zip')->toArray();`
     * - `$package->extractList('/Download/Tests/tests.zip', ['limit' => 5])->toArray();`
     * - `$package->extractList('/Download/Tests/tests.zip', ['limit' => 5], 'items')->toArray();`
     * - `$package->extractList('/Download/Tests/tests.zip', ['limit' => 5], 'items', 'name')->toArray();`
     *
     * @param string      $filePath   Chemin de l'archive
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function extractList($filePath, array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'file_path' => $filePath,
            'offset' => 0,
            'limit' => -1,
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ], $params);
        return $this->get('Extract', 'list', $params, "Content of archive $filePath", $itemsKey, $extractKey);
    }

    /**
     * Créer une archive
     *
     * - `$pkg->compress('/Download/Piles.xlsx', '/Download/piles.zip');`
     *
     * @param string     $src    Chemin du fichier/dossier à compresser
     * @param string     $dest   Chemin de l'archive
     * @param array|null $params Paramètres de la requête
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function compress($src, $dest, array $params = [])
    {
        $apiName = 'Compress';
        $params = array_merge([
            'path' => $src,
            'dest_file_path' => $dest,
            'level' => 'best',
            'mode' => 'synchronize',
            'format' => 'zip',
            //'password' => ''
        ], $params);
        $taskid = $this->startTask($apiName, $params);
        $response = $this->get('Compress', 'status', compact('taskid'), "Get status compress $src");
        $this->stopTask($apiName, $taskid);
        return $response;
    }

    /**
     * Télécharger un fichier/dossier
     *
     * @param string      $path  Chemin du fichier/dossier à télécharger
     * @param string|null $mode  (open ou download)
     * @param bool|null   $toUrl Obtnir l'URL sans exécuter la requête
     *
     * @return $this|bool|\GuzzleHttp\Psr7\MessageTrait|\Intervention\Image\Image|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement|string
     */
    public function download($path, $mode = 'download', $toUrl = false)
    {
        return $this
            ->get(
                'Download',
                'download',
                compact('path', 'mode'),
                $this->getName() . ' ' . ucfirst($mode) . ' ' . $path,
                null,
                null,
                $toUrl
            );
    }

    /**
     * Copier un fichier dans un répertoire partagé
     * à tester en post.
     * Notez que chaque paramètre est passé dans chaque partie mais
     * que les données du fichier binaire doivent être la dernière partie.
     *
     * @param mixed      $src    Contenu du fichier au format binaire
     * @param string     $dest   Un chemin de dossier de destination commençant par un dossier partagé dans lequel les
     *                           fichiers peuvent être téléchargés.
     * @param array|null $params Paramètres de la requête
     *                           - create_parents : Créez un ou plusieurs dossiers parents s'il n'en existe pas.
     *                           - overwrite (true: remplace le fichier de destination s'il en existe un, false: ignore
     *                           le téléchargement si le fichier de destination existe et lorsqu'il n'est pas spécifié
     *                           comme vrai ou faux, le téléchargement sera traité avec une erreur lorsque le fichier
     *                           de destination existe)
     *                           - mtime : Définir la dernière heure de modification du fichier téléchargé. Timestamp
     *                           Linux en milliseconde.
     *                           - crtime : Définissez l'heure de création du fichier téléchargé. Timestamp Linux en
     *                           milliseconde.
     *                           - atime : Définir la dernière heure d'accès du fichier téléchargé. Timestamp Linux en
     *                           milliseconde.
     *
     * @return bool|null|\Rcnchris\Core\Tools\Items
     */
    public function uploadFile($src, $dest, array $params = [])
    {
        $params = array_merge([
            'path' => $dest,
            'create_parents' => 'false',
            'overwrite' => 'true',
//            'mtime' => 0,
//            'crtime' => 0,
//            'atime' => 0,
            'filename' => $src
        ], $params);
        return $this->get('Upload', 'upload', $params, "Upload $src");
    }

    /**
     * Obtenir la liste des tâches en cours
     *
     * - `$package->backgroundTask()->toArray();`
     * - `$package->backgroundTask(['limit' => 5])->toArray();`
     * - `$package->backgroundTask(['limit' => 5], 'tasks')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function backgroundTask(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0,
            'sort_by' => 'crtime',
            'sort_direction' => 'asc',
            //'api_filter' => 'SYNO.FileStation.CopyMove'
        ], $params);
        return $this->get('BackgroundTask', 'list', $params, 'Get background tasks', $itemsKey, $extractKey);
    }

    /**
     * Supprime les tâches terminées ou l'une d'entre elle
     *
     * - `$pkg->clearFinishedTasks();`
     * - `$pkg->clearFinishedTasks('klmh78h');`
     *
     * @param string|null $taskid Identifiant de la tâche à suuprimer
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function clearFinishedTasks($taskid = null)
    {
        return is_null($taskid)
            ? $this->get('BackgroundTask', 'clear_finished', [])
            : $this->get('BackgroundTask', 'clear_finished', compact('taskid'));
    }
}
