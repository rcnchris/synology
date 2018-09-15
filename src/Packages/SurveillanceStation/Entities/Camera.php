<?php
/**
 * Fichier Camera.php du 11/09/2018
 * Description : Fichier de la classe Camera
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\SurveillanceStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\SurveillanceStation\Entities;

use Rcnchris\Synology\Entity;

/**
 * Class Camera
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\SurveillanceStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Camera extends Entity
{

    /**
     * Obtenir l'URL du snapshot de la caméra
     *
     * - `$camera->snapshotUrl();`
     *
     * @return string
     * @throws \Exception
     */
    public function snapshotUrl()
    {
        $sid = $this
            ->getPackage()
            ->getSynology()
            ->getSids(
                $this->getPackage()->getServerName(),
                $this->getPackage()->getName(),
                $this->getPackage()->getParams('account')
            );

        return $this->getPackage()->getSynology()->baseUrl()
        . str_replace('/webapi', '', $this->snapshot_path)
        . '&_sid=' . $sid;
    }
}
