<?php
/**
 * Fichier Song.php du 08/09/2018
 * Description : Fichier de la classe Song
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
use Rcnchris\Synology\Package;

/**
 * Class Song
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
class Song extends Entity
{
    /**
     * @var bool
     */
    public $enable_sharing = false;

    /**
     * Constructeur
     *
     * @param \Rcnchris\Synology\Package $package
     * @param array                      $content
     */
    public function __construct(Package $package, array $content = [])
    {
        parent::__construct($package, $content);
        $this->enable_sharing = $this
            ->getPackage()
            ->get('Song', 'getsharing', ['id' => $this->id], "Sharing of " . $this->title)
            ->get('enable_sharing');

    }

    /**
     * Obtenir les paroles de la chanson
     *
     * @return string
     */
    public function lyrics()
    {
        return $this
            ->getPackage()
            ->lyricsOfSong($this->id);
    }

    /**
     * Activer ou désactiver le partage du morceau et obtenir l'URL si actif
     *
     * @param bool|null $status Statut du partage du morceau
     *
     * @return string
     */
    public function setSharing($status = true)
    {
        $status = $status === true ? 'true' : 'false';
        $response = $this
            ->getPackage()
            ->get(
                'Song',
                'setsharing',
                [
                    'id' => $this->id,
                    'enable_sharing' => $status
                ],
                'Set sharing of ' . $this->title
            );
        if ($response->get('enable_sharing')) {
            return $response->get('url');
        }
        $this->enable_sharing = $response->get('enable_sharing');
        return $this->enable_sharing;
    }

    /**
     * Obtenir l'URL de partage du morceau s'il est actif
     *
     * @return string|bool
     */
    public function sharingUrl()
    {
        $response = $this
            ->getPackage()
            ->get('Song', 'getsharing', ['id' => $this->id], "Sharing of " . $this->title);

        if ($response->get('enable_sharing')) {
            return $response->get('url');
        }
        return false;
    }

    /**
     * Obtenir l'URL de la couverture du morceau
     *
     * @return bool|string
     */
    public function coverUrl()
    {
        $response = $this
            ->getPackage()
            ->getSynology()
            ->request($this->getPackage()->getName(), 'Cover', 'getsongcover', ['id' => $this->id])
            ->url();
        return $response;
    }
}