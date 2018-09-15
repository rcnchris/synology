<?php
/**
 * Fichier Package.php du 05/09/2018
 * Description : Fichier de la classe Package
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
 * Class Package
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
class Package
{
    /**
     * @var \Rcnchris\Synology\Synology
     */
    private $synology;

    /**
     * Nom du package
     *
     * @var string
     */
    private $packageName;

    /**
     * Utilisateur et mot de passe
     *
     * @var array
     */
    private $params;

    /**
     * Nom du serveur dans la configuration de l'instance Synology
     *
     * @var string
     */
    private $serverName;

    /**
     * Constructeur
     *
     * @param \Rcnchris\Synology\Synology $synology    Instance d'abstraction Synology
     * @param string                      $packageName Nom du package
     * @param array                       $params      Utilisateur et mot de passe à utiliser
     * @param string                      $serverName  Nom du serveur
     */
    public function __construct(Synology $synology, $packageName, array $params, $serverName)
    {
        $this->synology = $synology;
        $this->setName($packageName);
        $this->params = $params;
        $this->serverName = $serverName;
    }

    /**
     * Obtenir le nom du package
     *
     * @return string
     */
    public function getName()
    {
        return $this->packageName;
    }

    /**
     * Définir le nom du package
     *
     * @param string $packageName Nom du package
     */
    public function setName($packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * Obtenir l'instance de Synology
     *
     * @return Synology
     */
    public function getSynology()
    {
        return $this->synology;
    }

    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * Effectuer une requête, l'éxécuter et obtenir le résultat
     *
     * - `$pkg->get('Sharing', 'list', ['limit' => 5], 'Liste des liens partagés', $itemsKey, $extractKey)->toArray();`
     *
     * @param string      $apiName    Nom de l'API (Album, Movie...)
     * @param string      $method     Nom de la méthode
     * @param array       $params     Paramètres de la requête
     * @param string      $title      Titre de la requête pour le journal
     * @param string|null $itemsKey   Nom de la clé qui contient les items
     * @param string|null $extractKey Nom de la clé des items à extraire
     * @param bool|null   $onlyUrl    Obtenir uniquement l'URL sans exécuter la requête
     *
     * @return $this|bool|\GuzzleHttp\Psr7\MessageTrait|\Intervention\Image\Image|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement|string
     */
    public function get(
        $apiName,
        $method,
        array $params = [],
        $title = null,
        $itemsKey = null,
        $extractKey = null,
        $onlyUrl = false
    ) {
        $response = $this
            ->getSynology()
            ->request($this->getName(), $apiName, $method, $params, $this->getServerName());
        if ($onlyUrl) {
            return $response->url();
        }
        if (is_null($title)) {
            $title = $this->getName() . '.' . $apiName . ' ' . $method;
        }
        return $response->exec($title, true, $itemsKey, $extractKey);
    }

    /**
     * Démarrer une tâche d'une API et obtenir le taskid
     *
     * - `$pkg->startTask('Search')`
     *
     * @param string      $apiName Nom de l'API
     * @param array|null  $params  Paramètres de le raquête
     * @param string|null $method  Nom de la méthode
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function startTask($apiName, array $params = [], $method = 'start')
    {
        $title = 'Start ' . $this->getName() . ' ' . $apiName . ' task';
        return $this->get($apiName, $method, $params, $title, 'taskid');
    }

    /**
     * Arrête une tâche
     *
     * - `$pkg->stopTask('Search', 'mfsk741');`
     *
     * @param string      $apiName   Nom de l'API
     * @param mixed|null  $taskid    Identifiant de la tâche
     * @param bool|null   $withClean Nettoyer la base de données des tâches
     * @param string|null $method    Nom de la méthode à utiliser
     *
     * @return bool|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function stopTask($apiName, $taskid = null, $withClean = false, $method = 'stop')
    {
        $response = $this->get($apiName, $method, compact('taskid'), "Stop $apiName task");
        if ($withClean) {
            $response = $this->get($apiName, 'clean', compact('taskid'), 'items');
        }
        return $response;
    }

    /**
     * Obtenir une entité à partir de son contenu sous forme de tableau et de la classe de retour
     *
     * - `$this->getEntity($item->toArray(), Song::class);`
     * - `$this->getEntity($item->toArray(), Movie::class);`
     *
     * @param array       $content   Contenu de l'entité
     * @param string|null $className Nom de la classe à instancier pour l'entité
     *
     * @return Entity
     */
    public function getEntity($content, $className = null)
    {
        if (!is_null($className) && class_exists($className)) {
            return new $className($this, $content);
        }
        return new Entity($this, $content);
    }

    /**
     * Obtenir la documentation du package
     *
     * @param string|null $packageName Nom du package
     *
     * @return bool|\Rcnchris\Core\Tools\Items
     */
    public function getJsonDocumentation($packageName = null)
    {
        if (is_null($packageName)) {
            $packageName = $this->getName();
        }
        return $this
            ->getSynology()
            ->getJsonDocumentation($packageName);

    }

    /**
     * Obtenir les paramètres de construction de l'instance
     *
     * @param string|null $key Nom de la clé à retourner
     *
     * @return array|mixed|bool
     */
    public function getParams($key = null)
    {
        if (is_null($key)) {
            return $this->params;
        } elseif (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return false;
    }

    /**
     * Se déconnecter du package
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function logout()
    {
        return $this
            ->getSynology()
            ->logout($this->getName(), $this->serverName);
    }
}
