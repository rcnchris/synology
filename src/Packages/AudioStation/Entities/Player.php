<?php
/**
 * Fichier Player.php du 09/09/2018
 * Description : Fichier de la classe Player
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\AudioStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\AudioStation\Entities;

use Rcnchris\Synology\Entity;

/**
 * Class Player
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\AudioStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Player extends Entity
{
    /**
     * Obtenir la liste de lecture d'un lecteur
     *
     * @return mixed
     */
    public function playlist()
    {
        return $this->getPackage()->playlistOfPlayer($this->id);
    }
}
