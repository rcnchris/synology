<?php
/**
 * Fichier Task.php du 12/09/2018
 * Description : Fichier de la classe Task
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\DownloadStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\DownloadStation\Entities;

use Rcnchris\Synology\Entity;
use Rcnchris\Synology\Package;

/**
 * Class Task
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\DownloadStation\Entities
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Task extends Entity
{

    /**
     * Obtnir l'URL de la tâche
     *
     * @return string
     */
    public function url()
    {
        return $this->additional['detail']['uri'];
    }
}
