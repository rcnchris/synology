<?php
/**
 * Fichier Request.php du 03/09/2018
 * Description : Fichier de la classe Request
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

use Rcnchris\Core\Tools\Items;

/**
 * Class Request
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
class Request
{
    /**
     * Instance Synology
     *
     * @var \Rcnchris\Synology\Synology
     */
    private $synology;

    /**
     * Options de la requête
     *
     * @var array
     */
    private $options = [];

    /**
     * Constructeur
     *
     * @param \Rcnchris\Synology\Synology $synology Instance de Synology
     * @param array                       $options  Tableau des options de la requête
     *
     * @throws \Exception
     */
    public function __construct(Synology $synology, array $options)
    {
        $this->synology = $synology;
        $this->setOptions($options);
        $apiName = $this->getOptions('packageName') . '.' . $this->getOptions('apiName');
        if (!$this->getSynology()->getDefinition($apiName, $this->getOptions('serverName'))) {
            throw new \Exception("L'API $apiName n'a pas été trouvée sur " . $this->getOptions('serverName'));
        }
    }

    /**
     * Obtenir l'instance de Synology
     *
     * - `$request->getSynology();`
     *
     * @return Synology
     */
    public function getSynology()
    {
        return $this->synology;
    }

    /**
     * Obtenir les options de la requête
     *
     * - `$request->getOptions();`
     * - `$request->getOptions('packageName');`
     * - `$request->getOptions('method', 'list');`
     *
     * @param string|null $key     Nom de la clé des options à obtenir
     * @param mixed|null  $default Valeur par défaut si la clé n'existe pas
     *
     * @return array
     */
    public function getOptions($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->options;
        } elseif (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }
        return $default;
    }

    /**
     * Obtenir l'URL de la requête
     *
     * - `$request->url();`
     *
     * @return bool|string
     * @throws \Exception
     */
    public function url()
    {
        $definition = $this
            ->getSynology()
            ->getDefinition(
                $this->getOptions('packageName') . '.' . $this->getOptions('apiName'),
                $this->getOptions('serverName')
            );

        $params = array_merge([
            'api' => $this->getSynology()->getPrefixName(true) . $this->getOptions('packageName') . '.' . $this->getOptions('apiName'),
            'version' => $definition['maxVersion'],
            'method' => $this->getOptions('method')
        ], $this->getOptions('params'));

        $url = $this
                ->getSynology()
                ->baseUrl($this->getOptions('serverName'))
            . '/' . $definition['path']
            . '?' . http_build_query($params);

        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Exécution de la requête
     *
     * - `$request->exec('Liste des genres')->toArray();`
     * - `$request->exec('Liste des genres', true, 'genres', 'name')->toArray();`
     *
     * @param string      $title      Titre de la requête pour le journal
     * @param bool|null   $onlyData   Si vrai, seule la clé 'data' est retournée
     * @param string|null $itemsKey   Nom de la clé qui contient les données
     * @param string|null $extractKey Nom de la clé des items à extraire
     *
     * @return $this|\GuzzleHttp\Psr7\MessageTrait|\Intervention\Image\Image|mixed|null|\Rcnchris\Core\Tools\Items|\SimpleXMLElement
     */
    public function exec($title, $onlyData = true, $itemsKey = null, $extractKey = null)
    {
        $response = $this
            ->getSynology()
            ->getCurl()
            ->setUrl($this->url())
            ->exec($title)
            ->getResponse();

        if ($response instanceof Items) {
            if ($response->get('success') && $response->has('data')) {
                if ($onlyData) {
                    if (!is_null($itemsKey) && $response->get('data')->has($itemsKey)) {
                        if (!is_null($extractKey) && $response->get('data')->get($itemsKey)->notEmpty()) {
                            $idKey = null;
                            if ($response->get('data')->get($itemsKey)->first()->has('id')) {
                                $idKey = 'id';
                            }
                            return $response->get('data')->get($itemsKey)->extract($extractKey, $idKey);
                        }
                        return $response->get('data')->get($itemsKey);
                    }
                    return $response->get('data');
                }
            } elseif (!$response->get('success') && $response->has('error')) {

                $error = $this
                    ->getSynology()
                    ->getErrorMessages(
                        $this->getOptions('packageName'),
                        $this->getOptions('apiName'),
                        $response->get('error.code')
                    );

                $error->set('server', $this->getOptions('serverName'));
                //$error->set('apiName', $this->getOptions('apiName'));
                $error->set('method', $this->getOptions('method'));
                $error->set('params', $this->getOptions('params'));

                if ($response->get('error') && $response->get('error')->has('errors')) {
                    $error->set('errors', $response->get('error.errors')->toArray());
                }
                return $error;
            }
        }
        return $response;
    }

    /**
     * Définir les options de la requête
     *
     * - `$this->setOptions($options);`
     *
     * @param array $options Options de la requête
     *
     * @throws \Exception
     */
    private function setOptions(array $options)
    {
        $this->options = $options;
    }
}
