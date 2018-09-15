<?php
/**
 * Fichier Collection.php du 09/09/2018
 * Description : Fichier de la classe Collection
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
 * Class Collection
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
class Collection extends Entity
{

    /**
     * Obtenir les videos de la collection
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function videos()
    {
        return $this->getPackage()->videosOfCollection($this->id);
    }
}
