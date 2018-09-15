<?php
/**
 * Fichier AntiVirus.php du 13/09/2018
 * Description : Fichier de la classe AntiVirus
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\AntiVirus
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\AntiVirus;

use Rcnchris\Core\Tools\Items;
use Rcnchris\Synology\Package;

/**
 * Class AntiVirus
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\AntiVirus
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class AntiVirus extends Package
{

    /**
     * Obtenir la configuration
     *
     * - `$package->config()->toArray();`;
     * - `$package->config('virusAction');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function config($extractKey = null)
    {
        return $this->get('Config', 'get', ['version' => 1], "AntiVirus get config", $extractKey);
    }

    /**
     * Obtenir les informations
     *
     * - `$package->infos()->toArray();`;
     * - `$package->infos('virusAction');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function infos($extractKey = null)
    {
        return $this->get('General', 'get_sys_info', [], "AntiVirus get infos", $extractKey);
    }

    /**
     * Obtenir les informations sur le dernier scan effectué
     *
     * - `$package->scan()->toArray();`
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function scan()
    {
        $infos = $this->infos();
        $scan = [
            'status' => $infos->sysStatus,
            'total' => intval($infos->scanFileCountTotal),
            'scanned' => intval($infos->scan->scanned),
            'rest' => intval($infos->scanFileCountTotal) - intval($infos->scan->scanned),
            'progress' => round((intval($infos->scan->scanned) / intval($infos->scanFileCountTotal)) * 100, 2),
            'release' => $infos->releaseDate
        ];
        return new Items($scan);
    }
}
