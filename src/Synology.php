<?php
/**
 * Fichier Synology.php du 03/09/2018
 * Description : Fichier de la classe Synology
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

use Locale;
use Rcnchris\Core\Apis\Curl;
use Rcnchris\Core\Html\Html;
use Rcnchris\Core\Tools\Items;

/**
 * Class Synology
 *
 * @property \Rcnchris\Synology\Package FileStation
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
class Synology
{
    /**
     * Préfixe du nom des API
     *
     * const string
     */
    const PREFIXE_NAME = 'SYNO';

    /**
     * Suffixe de l'url de base
     */
    const SUFFIXE_URL_PART = 'webapi';

    /**
     * Configuration par défaut
     *
     * @var array
     */
    private $defaultConfig = [
        'name' => '',
        'description' => '',
        'address' => '',
        'port' => 5000,
        'protocol' => 'http',
        'version' => 1,
        'ssl' => false,
        'user' => 'php',
        'pwd' => 'php',
        'format' => 'sid'
    ];

    /**
     * Configurations de connexion des serveurs Synology
     *
     * @var array
     */
    private $configs = [];

    /**
     * Définitions des APIs de l'instance
     * Evite de demander deux fois la même définition au serveur
     *
     * @var array
     */
    private $definitions = [];

    /**
     * SIDs obtenu(s) par package et utilisateur
     *
     * @var array
     */
    private $sids = [];

    /**
     * Instance de Curl
     *
     * @var \Rcnchris\Core\Apis\Curl
     */
    private $curl;

    /**
     * Constructeur
     * Définit la configuration
     *
     * @param array       $configs Configuration de connexion des serveurs Synology
     * @param string|null $default Nom du serveur par défaut
     *
     * @throws \Exception
     */
    public function __construct(array $configs = [], $default = null)
    {
        $this->setConfigs($configs);
        $this->curl = new Curl($this->baseUrl($default));
        $this->getCurl()->setOptions(CURLOPT_TIMEOUT, 10);
        $this->getDefinition('API.Info,API.Auth', $default);
    }

    /**
     * Libération des objets
     */
    public function __destruct()
    {
        if (!is_null($this->curl)) {
            unset($this->curl);
        }
    }

    /**
     * Effectuer une requête sur une API
     *
     * @param string      $packageName Nom du package (AudioStation, VideoStation...)
     * @param string      $apiName     Nom de l'API (Album, Movie...)
     * @param string|null $method      Nom de la méthode de l'API (list, get..)
     * @param array|null  $params      Paramètres de la requête
     * @param string|null $serverName  Nom du serveur
     * @param bool|null   $withSid     Si faux, le paramètre '_sid' est supprimé
     *
     * @return \Rcnchris\Core\Tools\Items|\Rcnchris\Synology\Request
     * @throws \Exception
     */
    public function request(
        $packageName,
        $apiName,
        $method = null,
        array $params = [],
        $serverName = null,
        $withSid = true
    ) {
        // Serveur à utiliser
        $config = is_null($serverName)
            ? $this->getConfigs()->first()
            : $this->getConfigs($serverName);

        $serverName = $config->get('name');

        // User et password
        if (isset($params['account'])) {
            $account = $params['account'];
            $passwd = $params['passwd'];
        } else {
            $account = $config->get('user');
            $passwd = $config->get('pwd');
        }

        if ($withSid) {
            // Authentification unique par instance, package et utilisateur
            if (!$this->getSids($serverName, $packageName, $account)) {

                if ($serverName != $this->getConfigs()->first()->name) {
                    $this->getDefinition('API.Info,API.Auth', $serverName);
                }
                $defAuth = $this->definitions[$serverName]['SYNO.API.Auth'];

                $response = $this
                    ->getCurl()
                    ->setUrl($this->baseUrl($serverName) . '/' . $defAuth['path'])
                    ->withParams([
                        'api' => 'SYNO.API.Auth',
                        'method' => 'login',
                        'version' => 2,
                        'account' => $account,
                        'passwd' => $passwd,
                        'session' => $packageName,
                        'format' => 'sid'
                    ], true)
                    ->exec($packageName . ' login by ' . $account . ' to ' . $serverName)
                    ->getResponse();


                if ($response->get('success')) {

                    $this->sids[$serverName][$packageName][$account] = $response->get('data')->get('sid');

                } elseif (!$response->get('success') && $response->has('error')) {

                    $error = $this
                        ->getErrorMessages(
                            'API',
                            'Auth',
                            $response->get('error.code')
                        );

                    $error->set('server', $serverName);
                    $error->set('apiName', $apiName);
                    $error->set('method', 'login');
                    $error->set('params', [
                        'api' => 'SYNO.API.Auth',
                        'method' => 'login',
                        'version' => 2,
                        'account' => $account,
                        'passwd' => $passwd,
                        'session' => $packageName,
                        'format' => 'sid'
                    ]);

                    return $error;
                }
            }

            // Paramètres
            $params = array_merge($params, ['_sid' => $this->getSids($serverName, $packageName, $account)]);
        }

        // Méthode par défaut
        if (is_null($method)) {
            $method = 'list';
        }

        // Request
        return new Request($this, compact('packageName', 'apiName', 'method', 'params', 'serverName'));
    }

    /**
     * Obtenir toutes les définitions disponibles
     *
     * - `$syno->getAllDefinitions();`
     *
     * @return array
     * @throws \Exception
     */
    public function getAllDefinitions()
    {
        $url = $this->baseUrl() . '/query.cgi?api=SYNO.API.Info&method=query&version=1&query=all';

        $response = $this
            ->getCurl()
            ->setUrl($url)
            ->exec('All definitions')
            ->getResponse();

        if ($response->get('success') && $response->has('data')) {
            $this->definitions = $response->get('data')->toArray();
        }
        return $this->definitions;
    }

    /**
     * Obtenir la définition d'une ou plusieurs APIs
     *
     * @param string $apiNames Nom des APIs séparées par des virgules (AudioStation.Genre, VideoStation.Movie...)
     *
     * @param null   $serverName
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getDefinition($apiNames, $serverName = null)
    {
        $apis = explode(',', $apiNames);
        $definitions = [];

        // Serveur à utiliser
        $serverName = is_null($serverName)
            ? $this->getConfigs()->first()->name
            : $this->getConfigs($serverName)->name;

        // Suppression des APIs dont la définition existe
        if (array_key_exists($serverName, $this->definitions)) {
            foreach ($apis as $k => $apiShortName) {
                $apiFullName = $this->getPrefixName(true) . $apiShortName;
                if (array_key_exists($apiFullName, $this->definitions[$serverName])) {
                    unset($apis[$k]);
                }
            }
        }

        // Liste des APIs dont il faut récupérer la définition
        $query = '';
        foreach ($apis as $apiName) {
            $apiFullName = $this->getPrefixName(true) . $apiName;
            if (empty($this->definitions) || !array_key_exists($apiFullName, $this->definitions)) {
                // API inconnue des définitions stockées, il faut donc la demander
                $query .= $apiFullName . ',';
            }
        }

        if ($query != '') {

            $query = substr($query, 0, strlen($query) - 1);

            $definitions = $this
                ->getCurl()
                ->setUrl($this->baseUrl($serverName) . '/query.cgi')
                ->withParams([
                    'api' => $this->getPrefixName(true) . 'API.Info',
                    'method' => 'query',
                    'version' => 1,
                    'query' => $query
                ], true)
                ->exec('Definition of ' . implode(', ', $apis) . ' on ' . $serverName)
                ->getResponse();

            if ($definitions->get('success') && $definitions->has('data') && $definitions->get('data')->notEmpty()) {

                if (!array_key_exists($serverName, $this->definitions)) {
                    $this->definitions[$serverName] = $definitions->get('data')->toArray();
                } else {
                    $this->definitions[$serverName] = array_merge(
                        $this->definitions[$serverName],
                        $definitions->get('data')->toArray()
                    );
                }

                $definitions = $this->definitions;
            }
        } else {

            $apis = explode(',', $apiNames);
            foreach ($apis as $k => $apiShortName) {
                $apiFullName = $this->getPrefixName(true) . $apiShortName;
                $definitions[$serverName][$apiFullName] = $this->definitions[$serverName][$apiFullName];
            }
        }

        if (count($definitions[$serverName]) === 1) {
            return $this->definitions[$serverName][$this->getPrefixName(true) . current($apis)];
        }
        return $definitions[$serverName];
    }

    /**
     * Obtenir la configuration des serveurs ou de l'un d'entre eux
     *
     * @param string|null $name Nom du serveur
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getConfigs($name = null)
    {
        $ret = [];
        if (is_null($name)) {
            $ret = new Items($this->configs);
        } elseif (array_key_exists($name, $this->configs)) {
            $ret = $this->configs[$name];
        }
        return new Items($ret);
    }

    /**
     * Définir la/les configuration(s) de connexion au serveur
     *
     * @param array|string $configs Configuration de connexion ou URL de base
     */
    public function setConfigs(array $configs = [])
    {
        $cf = [];
        foreach ($configs as $config) {
            $cf[$config['name']] = array_merge($this->defaultConfig, $config);
        }
        $this->configs = $cf;
    }

    /**
     * Obtenir l'URL de base d'un serveur
     *
     * @param string|null $serverName nom du serveur, si vide le premier est retourné
     *
     * @param bool        $withSuffix
     *
     * @return string
     * @throws \Exception
     */
    public function baseUrl($serverName = null, $withSuffix = true)
    {
        if (is_null($serverName)) {
            $serverName = current($this->getConfigs()->toArray())['name'];
        }
        $config = $this->getConfigs($serverName);

        $url = $config->protocol . '://'
            . $config->address
            . ':' . $config->port;

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception("La construction de l'URL de base à échouée !");
        }
        if ($withSuffix) {
            $url .= '/' . $this::SUFFIXE_URL_PART;
        }
        return $url;
    }

    /**
     * Obtenir le logo Synology
     * à inclure dans une balise HTML <img src="$syno->logo()"/>
     *
     * - `$api->logo();`
     * - `$api->logo(['class' => 'img-thumbnail']);`
     *
     * @param array|null $attributes Attrbut de la balise img
     *
     * @return string
     */
    public function logo(array $attributes = [])
    {
        return empty($attributes)
            ? "data:image/jpg;base64,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 TNuBU0kkkylImmQpSEIAFKAAABwHm8AcB//Z "
            : '<img ' . Html::parseAttributes($attributes) . 'src="data:image/jpg;base64,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 TNuBU0kkkylImmQpSEIAFKAAABwHm8AcB//Z "/>';
    }

    /**
     * Obtenir les définitions obtenues par l'instance
     *
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Obtenir les sids obtenus ou l'un d'entre eux
     *
     * - `$syno->getSids();`
     * - `$syno->getSids('AudioStation');`
     * - `$syno->getSids('AudioStation', 'rcn');`
     *
     * @param string|null $serverName  Nom du serveur dans la configuration
     * @param string|null $packageName Nom du package (AudioStation, VideoStation...)
     * @param string|null $user        Login Synology
     *
     * @return array|bool|string
     */
    public function getSids($serverName = null, $packageName = null, $user = null)
    {
        if (empty(func_get_args())) {
            return $this->sids;
        }
        if (!empty($this->sids)) {
            if (array_key_exists($serverName, $this->sids)
                && array_key_exists($packageName, $this->sids[$serverName])
                && array_key_exists($user, $this->sids[$serverName][$packageName])
            ) {
                return $this->sids[$serverName][$packageName][$user];
            }
        }
        return false;
    }

    /**
     * Obtenir le préfixe du nom des APIs
     *
     * @param bool|null $withPoint Si vrai, un point est ajouté à la fin du nom
     *
     * @return string
     */
    public function getPrefixName($withPoint = false)
    {
        $name = $this::PREFIXE_NAME;
        if ($withPoint) {
            $name .= '.';
        }
        return $name;
    }

    /**
     * Obtenir le journal des requêtes exécutées
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getLogs()
    {
        return $this->curl->getLog();
    }

    /**
     * Obtenir l'instance de Curl
     *
     * @return Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * Obtenir la liste des messages d'erreurs Synology ou l'un d'entre eux
     *
     * @param string|null $packageName Nom du package (AudioStation, VideoStation...)
     * @param int|null    $code        Code de l'erreur
     *
     * @return \Rcnchris\Core\Tools\Items
     */
    public function getErrorMessages($packageName = null, $apiName = null, $code = null)
    {
        $messages = new Items(require __DIR__ . '/errors-codes.php');
        return is_null($packageName)
            ? $messages
            : $this->getError($messages, $packageName, $apiName, $code);
    }

    /**
     * Obtenir le message d'erreur Synology pour un package et un code
     *
     * @param \Rcnchris\Core\Tools\Items $messages    Liste de tous les messages
     * @param string                     $packageName Nom du package (AudioStation, VideoStation...)
     * @param int                        $code        Code de l'erreur
     * @param string|null                $lang        Code de la langue sur deux caractères
     *
     * @return array
     */
    private function getError(Items $messages, $packageName, $apiName, $code, $lang = null)
    {
        if (is_null($lang)) {
            $lang = substr(Locale::getDefault(), 0, 2);
        }
        $error = [
            'packageName' => $packageName,
            'apiName' => $apiName,
            'code' => $code,
            'lang' => $lang,
            'details' => $this->getCurl()->getInfos()
        ];

        $msg = $messages->get($packageName . '.' . $apiName . '.' . $code);
        if ($msg) {
            $error['message'] = $msg->$lang;
        } else {
            $msg = $messages->get($packageName . '.' . $code);
            if ($msg) {
                $error['message'] = $msg->$lang;
            } else {
                $error['message'] = null;
            }
        }

        return new Items($error);
    }

    /**
     * Obtenir la documentation d'un package à partir du fichier JSON qui correspond
     * ou la liste de ceux existants
     *
     * @param string $packageName Nom du package (AudioStation, FileStation...)
     *
     * @return array|bool|\Rcnchris\Core\Tools\Items
     */
    public function getJsonDocumentation($packageName = null)
    {
        $folder = __DIR__ . '/definitions/';
        if (is_null($packageName)) {
            $files = glob($folder . '*.json');
            $packages = [];
            foreach ($files as $file) {
                $packages[] = str_replace('.json', '', basename($file));
            }
            return $packages;
        } else {
            $fileName = $packageName . '.json';
            if (is_file($folder . $fileName)) {
                $json = file_get_contents($folder . $fileName);
                $definition = json_decode($json, true);
                return new Items($definition);
            }
            return false;
        }
    }

    /**
     * Obtenir un package
     *
     * @param string $name Nom du package (FileStation, AudioStation...)
     *
     * @return \Rcnchris\Synology\Package
     */
    public function __get($name)
    {
        return $this->getPackage($name);
    }

    /**
     * Obtenir l'instance d'un package
     *
     * @param string      $packageName Nom du package
     * @param array       $params      Paramètres de connexion
     * @param string|null $serverName  Nom du serveur
     *
     * @return \Rcnchris\Synology\Package
     */
    public function getPackage($packageName, array $params = [], $serverName = null)
    {
        $config = is_null($serverName)
            ? $this->getConfigs()->first()
            : $this->getConfigs($serverName);

        $serverName = $config->get('name');

        // User et password
        if (isset($params['account'])) {
            $account = $params['account'];
            $passwd = $params['passwd'];
        } else {
            $account = $config->get('user');
            $passwd = $config->get('pwd');
        }

        $className = namespaceSplit(get_class($this))[0];
        $className .= '\\Packages\\' . $packageName . '\\' . $packageName;
        if (class_exists($className)) {
            return new $className($this, $packageName, compact('account', 'passwd'), $serverName);
        }
        return new Package($this, $packageName, compact('account', 'passwd'), $serverName);
    }

    /**
     * Se déconnecter d'un package sur un serveur
     *
     * @param string $packageName Nom du package
     * @param string $serverName  Nom du serveur
     *
     * @return mixed|null|\Rcnchris\Core\Tools\Items
     */
    public function logout($packageName, $serverName)
    {
        $response = $this
            ->request('API', 'Auth', 'logout', ['version' => 1, 'session' => $packageName], $serverName, false)
            ->exec($packageName . ' logout of ' . $serverName)
            ->get('success');

        if ($response === true) {
            unset($this->sids[$serverName][$packageName]);
        }
        return $response;
    }
}
