<?php
/**
 * Fichier Backup.php du 13/09/2018
 * Description : Fichier de la classe Backup
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\Backup
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology\Packages\Backup;

use Rcnchris\Synology\Package;

/**
 * Class Backup
 *
 * @category API
 *
 * @package  Rcnchris\Synology\Packages\Backup
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Backup extends Package
{
    /**
     * Obtenir la liste des repositories
     *
     * - `$package->repositories()->toArray();`
     *
     * @param string|null $extractKey Nom de la clé à extraire
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function repositories($extractKey = null)
    {
        return $this->get('Repository', 'list', ['version' => 1], null, null, $extractKey);
    }
}
