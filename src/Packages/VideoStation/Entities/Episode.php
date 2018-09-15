<?php
/**
 * Fichier Episode.php du 10/09/2018
 * Description : Fichier de la classe Episode
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\VideoStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\VideoStation\Entities;

use Rcnchris\Synology\Entity;

/**
 * Class Episode
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\VideoStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Episode extends Entity
{

    /**
     * Obtenir la série de l'épisode
     *
     * @return TVShow
     */
    public function tvshow()
    {
        return $this->getPackage()->tvshow($this->tvshow_id);
    }
}
