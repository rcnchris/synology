<?php
/**
 * Fichier TVShow.php du 10/09/2018
 * Description : Fichier de la classe TVShow
 *
 * PHP version 5
 *
 * @category New
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

use Rcnchris\Core\Tools\Items;
use Rcnchris\Synology\Entity;

/**
 * Class TVShow
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
class TVShow extends Entity
{

    /**
     * Obtenir les épisodes de la série
     *
     * @return Items
     */
    public function episodes()
    {
        $titleParts = explode(' ', $this->title);
        $title = strtolower(current($titleParts));
        return $this->getPackage()->searchEpisodes($title);
    }
}
