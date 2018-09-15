<?php
/**
 * Fichier Folder.php du 08/09/2018
 * Description : Fichier de la classe Folder
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
 * Class Folder
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
class Folder extends Entity
{
    /**
     * Obtenir le contenu du dossier
     *
     * @return mixed
     */
    public function content()
    {
        $response = $this
            ->getPackage('FileStation')
            ->contentOfPath($this->path, [], 'files');
        return $response;
    }

    /**
     * Obtenir la taille du dossier
     *
     * @return mixed
     */
    public function size()
    {
        return $this->getPackage('FileStation')->size($this->path);
    }
}
