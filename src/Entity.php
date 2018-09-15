<?php
/**
 * Fichier Entity.php du 06/09/2018
 * Description : Fichier de la classe Entity
 *
 * PHP version 5
 *
 * @category API
 *
 * @package  Rcnchris\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @link     https://github.com/rcnchris On Github
 */

namespace Rcnchris\Synology;

/**
 * Class Entity
 *
 * @category API
 *
 * @package  Rcnchris\Synology
 *
 * @author   Raoul <rcn.chris@gmail.com>
 *
 * @license  https://github.com/rcnchris GPL
 *
 * @version  Release: <1.0.0>
 *
 * @link     https://github.com/rcnchris on Github
 */
class Entity
{
    /**
     * Instance du package
     *
     * @var Package
     */
    private $package;

    /**
     * Liste des champs de l'entité
     *
     * @var array
     */
    private $fields = [];

    /**
     * Constructeur
     *
     * @param \Rcnchris\Synology\Package $package
     * @param array                      $content
     */
    public function __construct(Package $package, array $content = [])
    {
        $this->setPackage($package);
        $this->addFields($content);
    }

    /**
     * Obtenir le package de l'entité ou celui désiré
     *
     * @param string|null $packageName Nom du package à retourner
     *
     * @return \Rcnchris\Synology\Package
     */
    public function getPackage($packageName = null)
    {
        if (is_null($packageName)) {
            return $this->package;
        } else {
            return $this
                ->getPackage()
                ->getSynology()
                ->getPackage($packageName);
        }
    }

    /**
     * Définir le package de l'entité
     *
     * @param Package $package
     *
     * @return $this
     */
    private function setPackage(Package $package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * Ajouter les clés du tableau comme propriétés de l'instance
     *
     * @param array $content Tableau de données de l'entité
     *
     * @return $this
     */
    private function addFields(array $content = [])
    {
        foreach ($content as $property => $value) {
            $this->$property = $value;
            $this->fields[$property] = gettype($value);
        }
        return $this;
    }

    /**
     * Obtenir la liste des champs de l'entité et leur type
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Obtenir le contenu de l'entité dans un tableau
     *
     * @return array
     */
    public function toArray()
    {
        $content = [];
        foreach (get_object_vars($this) as $attribute => $value) {
            if (!in_array($attribute, ['package', 'fields'])) {
                $content[$attribute] = $value;
            }
        }
        return $content;
    }
}
