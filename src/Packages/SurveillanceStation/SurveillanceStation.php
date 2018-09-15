<?php
/**
 * Fichier SurveillanceStation.php du 11/09/2018
 * Description : Fichier de la classe SurveillanceStation
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\SurveillanceStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\SurveillanceStation;

use Rcnchris\Synology\Package;
use Rcnchris\Synology\Packages\SurveillanceStation\Entities\Camera;

/**
 * Class SurveillanceStation
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\SurveillanceStation
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class SurveillanceStation extends Package
{

    /**
     * Obtenir la liste des caméras
     *
     * - `$pkg->cameras()->toArray()`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function cameras(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 5,
            'blFromCamList' => 'true',
            'blIncludeDeletedCam' => 'true',
            'basic' => 'true',
            'streamInfo' => 'false',
            'optimize' => 'true'
        ], $params);
        return $this->get('Camera', 'List', $params, 'Cameras list', 'cameras', $extractKey);
    }

    /**
     * Obtenir une caméra par son identifiant
     *
     * - `$pkg->camera(1);`
     * - `$pkg->camera(1, false);`
     *
     * @param int       $id       Identifiant de la caméra
     * @param bool|null $toEntity Si faux, les données sont retournées dans un tableau
     *
     * @return array|Camera
     */
    public function camera($id, $toEntity = true)
    {
        $camera = $this
            ->get('Camera', 'GetInfo', [
                    'version' => 8,
                    'cameraIds' => $id,
                    'basic' => 'true',
                    'streamInfo' => 'true',
                    'optimize' => 'true',
                    'ptz' => 'true',
                    'eventDetection' => 'true',
                    'deviceOutCap' => 'true',
                    'fisheye' => 'true',
                    'camAppInfo' => 'true'
                ]
                , "Camera get $id",
                'cameras'
            )->first();

        if ($toEntity) {
            return $this->getEntity($camera->toArray(), Camera::class);
        }
        return $camera;
    }

    /**
     * Obtenir l'état des services
     *
     * - `$package->servicesSetting()->toArray();`
     * - `$package->servicesSetting('vsEnabled');`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function servicesSetting($extractKey = null)
    {
        return $this->get('Device', 'GetServiceSetting', [], 'Services settings', $extractKey);
    }

    /**
     * Obtenir la liste des devices de type VS
     *
     * - `$package->vsList()->toArray();`
     *
     * @param array|null  $params     Paramètres de la requête
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function vsList(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('Device', 'ListVS', $params, 'Devices VS list', $extractKey);
    }

    /**
     * Obtenir la liste des devices de type CMS
     *
     * @param array $params
     * @param null  $extractKey
     *
     * @return array|bool|null|\Rcnchris\Core\Tools\Items
     */
    public function cmsList(array $params = [], $extractKey = null)
    {
        $params = array_merge([
            'offset' => 0,
            'limit' => 0
        ], $params);
        return $this->get('Device', 'ListCMS', $params, 'Devices CMS list', $extractKey);
    }
}
