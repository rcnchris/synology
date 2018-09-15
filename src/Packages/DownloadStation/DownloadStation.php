<?php
/**
 * Fichier DownloadStation.php du 10/09/2018
 * Description : Fichier de la classe DownloadStation
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\DownloadStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\DownloadStation;

use Rcnchris\Synology\Package;
use Rcnchris\Synology\Packages\DownloadStation\Entities\Task;

/**
 * Class DownloadStation
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\DownloadStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class DownloadStation extends Package
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
     * Obtenir la configuration du package
     *
     * - `$package->config()->toArray();`
     * - `$package->config('ftp_max_download');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function config($extractKey = null)
    {
        return $this->get('Info', 'getconfig', [], 'VideoStation Config', $extractKey);
    }

    /**
     * Obtenir la configuration du planificateur
     *
     * - `$package->configSchedule()->toArray();`
     * - `$package->configSchedule('emule_enabled');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function configSchedule($extractKey = null)
    {
        return $this->get('Schedule', 'getconfig', [], 'Schedule Config', $extractKey);
    }

    /**
     * Obtenir la liste des sites RSS
     *
     * - `$package->rssSites()->toArray();`
     * - `$package->rssSites(['limit' => 5])->toArray();`
     * - `$package->rssSites(['limit' => 5], 'sites')->toArray();`
     * - `$package->rssSites(['limit' => 5], 'sites', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function rssSites(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('RSS.Site', 'list', $params, 'RSS Sites list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir les statistiques de vitesse de téléchargement
     *
     * - `$package->statistics()->toArray();`
     * - `$package->statistics('speed_download');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function statistics($extractKey = null)
    {
        return $this->get('Statistic', 'getinfo', [], 'Statistic infos', $extractKey);
    }

    /**
     * Obtenir les tâches de téléchargement
     *
     * - `$pkg->tasks()->toArray();`
     * - `$pkg->tasks(['limit' => 5])->toArray();`
     * - `$pkg->tasks(['limit' => 5], 'tasks')->toArray();`
     * - `$pkg->tasks(['limit' => 5], 'tasks', 'title')->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function tasks(array $params = [], $itemsKey = null, $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => -1
        ], $params);
        return $this->get('Task', 'list', $params, 'Tasks list', $itemsKey, $extractKey);
    }

    /**
     * Obtenir une tâche par son identifiant
     *
     * @param string    $id       Identifiant de la tâche
     * @param bool|null $toEntity Si faux, c'est une instance de Items qui est retournée
     *
     * @return \Rcnchris\Core\Tools\Items|\Rcnchris\Synology\Entity
     */
    public function task($id, $toEntity = true)
    {
        $response = $this->get(
            'Task',
            'getinfo',
            ['id' => $id, 'additional' => 'detail,transfer,file,tracker,peer'],
            "Task get $id",
            'tasks'
        )->first();
        return $toEntity
            ? $this->getEntity($response->toArray(), Task::class)
            : $response;
    }

    /**
     * Obtenir la liste des recherches BT
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function listBT()
    {
        return $this->get('BTSearch', 'list', [], 'BT list');
    }
}
