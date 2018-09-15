<?php
use Rcnchris\Core\Html\Html;

$html = Html::getInstance();
?>
<!-- Accordéon -->
<div class="row">
    <div class="col">
        <div class="accordion" id="accDebug">

            <!-- Readme -->
            <div class="card">
                <div class="card-header" id="hReadme">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collReadme"
                                aria-expanded="true" aria-controls="collReadme">
                            Readme
                        </button>
                    </h5>
                </div>

                <div id="collReadme" class="collapse show" aria-labelledby="hReadme" data-parent="#accDebug">
                    <div class="card-body">
                        <?= \Michelf\MarkdownExtra::defaultTransform(file_get_contents('../README.md')) ?>
                    </div>
                </div>
            </div>

            <!-- Environnement -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-controls="collapseOne">
                            <?php
                            $context = 'success';
                            $confName = $config->get('config.name');
                            if ($confName === 'local') {
                                $context = 'danger';
                            } elseif ($confName === 'dev') {
                                $context = 'info';
                            }
                            ?>
                            Environnement <span
                                class="badge badge-<?= $context ?>"><?= $config->get('config.name') ?></span>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accDebug">
                    <div class="card-body">
                        <h5 class="card-title">Serveur, configuration et constantes</h5>
                        <table class="table table-sm">
                            <tbody>
                            <tr>
                                <th>Serveur</th>
                                <td><?= Html::surround($e->getUname(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Adresse IP</th>
                                <td><?= Html::surround($e->getIp(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Nom du serveur</th>
                                <td><?= Html::surround($e->getServerName(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Version Apache</th>
                                <td><?= Html::surround($e->getApacheVersion(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Utilisateur Apache</th>
                                <td><?= Html::surround($e->getApacheUser(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Modules Apache</th>
                                <td>
                                    <?=
                                    Html::details(
                                        'Voir tous les modules',
                                        Html::surround(
                                            Html::liste(
                                                $e->getApacheModules()->toArray(),
                                                ['type' => 'ol'],
                                                false
                                            ),
                                            'samp'
                                        )
                                    )
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Version MySQL</th>
                                <td><?= Html::surround($e->getMysqlVersion(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Version de PHP</th>
                                <td><?= Html::surround($e->getPhpVersion(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>SAPI name</th>
                                <td><?= Html::surround($e->getSapi(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Fichier INI</th>
                                <td><?= Html::surround($e->getPhpIniFile(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Fichiers INI supplémentaires <span
                                        class="badge badge-warning"><?= count($e->getPhpIniFiles()) ?></span></th>
                                <td>
                                    <?=
                                    Html::details(
                                        'Voir tous les fichiers',
                                        Html::surround(
                                            Html::liste(
                                                $e->getPhpIniFiles()->toArray(),
                                                ['type' => 'ol'],
                                                false
                                            ),
                                            'samp'
                                        )
                                    )
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Extensions PHP <span
                                        class="badge badge-warning"><?= count($e->getPhpExtensions()) ?></span></th>
                                <td>
                                    <?=
                                    Html::details(
                                        'Voir toutes les extensions',
                                        Html::surround(
                                            Html::liste(
                                                $e->getPhpExtensions()->sort()->toArray(),
                                                ['type' => 'ol'],
                                                false
                                            ),
                                            'samp'
                                        )
                                    )
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Modules PHP <span
                                        class="badge badge-warning"><?= count($e->getPhpModules()) ?></span></th>
                                <td>
                                    <?=
                                    Html::details(
                                        'Voir tous les modules',
                                        Html::surround(
                                            Html::liste(
                                                $e->getPhpModules()->toArray(),
                                                ['type' => 'ol']
                                                ,
                                                false
                                            ),
                                            'samp'
                                        )
                                    )
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Drivers PDO <span
                                        class="badge badge-warning"><?= count($e->getPdoDrivers()) ?></span></th>
                                <td><?= Html::surround($e->getPdoDrivers()->join(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Timezone</th>
                                <td><?= Html::surround($e->getTimezone(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Charset</th>
                                <td><?= Html::surround($e->getCharset(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Localisation</th>
                                <td><?= Html::surround($e->getLocale()->getDefault(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Affichage des erreurs</th>
                                <td><?= Html::surround($e->getPhpErrorReporting(), 'samp') ?></td>
                            </tr>
                            <tr>
                                <th>Constantes <span
                                        class="badge badge-warning"><?= $e->getConstants()->get('user')->count() ?></span>
                                </th>
                                <td>
                                    <?=
                                    Html::details(
                                        'Voir toutes les constantes utilisateurs',
                                        Html::surround(
                                            Html::liste($e->getConstants()->get('user')),
                                            'samp'
                                        )
                                    )
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Composer -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Composer <span
                                class="badge badge-warning"><?= count($composer->getRequires('req')) + count($composer->getRequires('dev')) ?></span>
                            librairies utilisées
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accDebug">
                    <div class="card-body">

                        <!-- Description -->
                        <h5 class="card-title">
                            Description
                        </h5>

                        <div class="row">
                            <div class="col">
                                <dl>
                                    <dt>name</dt>
                                    <dd><?= $composer->get('name') ?></dd>
                                </dl>
                            </div>
                            <div class="col">
                                <dl>
                                    <dt>description</dt>
                                    <dd><?= $composer->get('description') ?></dd>
                                </dl>
                            </div>
                            <div class="col">
                                <dl>
                                    <dt>type</dt>
                                    <dd><?= $composer->get('type') ?></dd>
                                </dl>
                            </div>
                            <div class="col">
                                <dl>
                                    <dt>license</dt>
                                    <dd><?= $composer->get('license') ?></dd>
                                </dl>
                            </div>
                            <div class="col">
                                <dl>
                                    <dt>minimum-stability</dt>
                                    <dd><?= $composer->get('minimum-stability') ?></dd>
                                </dl>
                            </div>
                            <div class="col-12">
                                <hr/>
                            </div>
                        </div>

                        <!-- Requires -->
                        <div class="row">
                            <div class="col-6">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Librairies <span
                                                class="badge badge-warning"><?= count($composer->getRequires('req')) ?></span>
                                        </th>
                                        <th>Version</th>
                                        <th>Taille</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($composer->getRequires('req') as $name => $version): ?>
                                        <tr>
                                            <td><samp><?= $name ?></samp></td>
                                            <td><samp><span
                                                        class="badge badge-warning"><?= $version ?></span></samp>
                                            </td>
                                            <?php if ($name === 'php'): ?>
                                                <td></td>
                                            <?php else: ?>
                                                <td><samp><span
                                                            class="badge badge-warning"><?= \Rcnchris\Core\Tools\Cmd::size(ROOT . DS . 'vendor' . DS . $name,
                                                                true) ?></span></samp></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Développement <span
                                                class="badge badge-warning"><?= count($composer->getRequires('dev')) ?></span>
                                        </th>
                                        <th>Version</th>
                                        <th>Taille</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($composer->getRequires('dev') as $name => $version): ?>
                                        <tr>
                                            <td><samp><?= $name ?></samp></td>
                                            <td><samp><span
                                                        class="badge badge-warning"><?= $version ?></span></samp>
                                            </td>
                                            <td><samp><span
                                                        class="badge badge-warning"><?= \Rcnchris\Core\Tools\Cmd::size(ROOT . DS . 'vendor' . DS . $name,
                                                            true) ?></span></samp></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr/>

                        <!-- Autoload -->
                        <h5 class="card-title">
                            Namespaces
                        </h5>
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Namespace</th>
                                <th>Chemin</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($composer->get('autoload') as $type => $values): ?>
                                <?php foreach ($values as $namespace => $path): ?>
                                    <tr>
                                        <th><?= Html::surround($type, 'samp') ?></th>
                                        <td><?= Html::surround($namespace, 'samp') ?></td>
                                        <td><?= Html::surround($path, 'samp') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Fichiers et dossiers -->
            <div class="card">
                <div class="card-header" id="hFiles">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collFiles" aria-expanded="false" aria-controls="collFiles">
                            Fichiers et dossiers <span class="badge badge-warning"><?= $folder->size() ?></span>
                        </button>
                    </h5>
                </div>
                <div id="collFiles" class="collapse" aria-labelledby="hFiles" data-parent="#accDebug">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title">Dossiers</h5>
                                <table class="table table-sm">
                                    <tbody>
                                    <?php foreach ($folder->folders() as $dir): if ($dir[0] != '.'): ?>
                                        <tr>
                                            <th><?= Html::surround($dir, 'samp') ?></th>
                                            <td>
                                                <?= Html::surround(
                                                    Html::surround(
                                                        $folder->get($dir)->size(),
                                                        'span',
                                                        ['class' => 'badge badge-warning']
                                                    ), 'samp'
                                                ) ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <h5 class="card-title">Fichiers</h5>
                                <table class="table table-sm">
                                    <tbody>
                                    <?php foreach ($folder->files() as $file): ?>
                                        <tr>
                                            <th><samp><?= $file ?></samp></th>
                                            <td>
                                                <?= Html::surround(
                                                    Html::surround(
                                                        $folder->get($file)->size(true, 2),
                                                        'span',
                                                        ['class' => 'badge badge-warning']
                                                    ), 'samp'
                                                ) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fichiers de configuration de la racine -->
            <div class="card">
                <div class="card-header" id="hMakefile">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collMakefile" aria-expanded="false" aria-controls="collMakefile">
                            Fichiers de configuration de la racine
                        </button>
                    </h5>
                </div>
                <div id="collMakefile" class="collapse" aria-labelledby="hMakefile" data-parent="#accDebug">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title">Make <code>Makefile</code></h5>
                                <?= Html::source(ROOT . DS . 'Makefile', ['class' => 'sh_makefile'], true) ?>
                            </div>

                            <div class="col-6">
                                <h5 class="card-title">URL Rewrite <code>.htaccess</code></h5>
                                <?= Html::source(ROOT . DS . '.htaccess', ['class' => 'sh_sh'], true) ?>

                                <h5 class="card-title">Code Sniffer <code>phpcs.xml</code></h5>
                                <?= Html::source(ROOT . DS . 'phpcs.xml', ['class' => 'sh_xml'], true) ?>

                                <h5 class="card-title">Tests unitaires <code>phpunit.xml</code></h5>
                                <?= Html::source(ROOT . DS . 'phpunit.xml', ['class' => 'sh_xml'], true) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Outils serveur -->
            <div class="card">
                <div class="card-header" id="hServerTools">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collServerTools" aria-expanded="false"
                                aria-controls="collServerTools">
                            Outils serveur
                        </button>
                    </h5>
                </div>
                <div id="collServerTools" class="collapse" aria-labelledby="hServerTools" data-parent="#accDebug">
                    <div class="card-body">
                        <table class="table table-sm">
                            <tbody>
                            <tr>
                                <th>Composer</th>
                                <td><span class="badge badge-warning"><?= $e->getComposerVersion() ?></span></td>
                            </tr>
                            <tr>
                                <th>Curl</th>
                                <td><span class="badge badge-warning"><?= $e->getCurlVersion() ?></span></td>
                            </tr>
                            <tr>
                                <th>Git</th>
                                <td><span class="badge badge-warning"><?= $e->getGitVersion(true) ?></span></td>
                            </tr>
                            <tr>
                                <th>Wkhtmltopdf</th>
                                <td><span class="badge badge-warning"><?= $e->getWkhtmltopdfVersion() ?></span></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- Session et cookies -->
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Session et cookies
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accDebug">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="card-title">Session</h5>
                                <table class="table table-sm">
                                    <tbody>
                                    <?php foreach ($session->get() as $key => $value): ?>
                                        <tr>
                                            <th><samp><?= $key ?></samp></th>
                                            <td><samp><?= $value ?></samp></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-6">
                                <h5 class="card-title">Cookies</h5>
                                <table class="table table-sm">
                                    <tbody>
                                    <?php foreach ($_COOKIE as $key => $value): ?>
                                        <tr>
                                            <th><samp><?= $key ?></samp></th>
                                            <td><samp><?= $value ?></samp></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PSR7 -->
            <div class="card">
                <div class="card-header" id="hPsr7">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collPsr7" aria-expanded="false" aria-controls="collPsr7">
                            PSR7
                        </button>
                    </h5>
                </div>
                <div id="collPsr7" class="collapse" aria-labelledby="hPsr7" data-parent="#accDebug">
                    <div class="card-body">
                        <div class="row">

                            <!-- Request -->
                            <div class="col-6">
                                <h3>
                                    <code><?= get_class($request) ?></code>
                                </h3>
                                <hr/>
                                <table class="table table-sm">
                                    <tbody>

                                    <tr>
                                        <th>Cible</th>
                                        <td><samp><?= $request->getRequestTarget() ?></samp></td>
                                    </tr>

                                    <tr>
                                        <th>Méthode</th>
                                        <td><samp><?= $request->getMethod() ?></samp></td>
                                    </tr>

                                    <tr>
                                        <th>Version du protocol</th>
                                        <td><?= $request->getProtocolVersion() ?></td>
                                    </tr>

                                    <tr>
                                        <th>Paramètres du serveur</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les paramètres <span
                                                        class="badge badge-warning"><?= count($request->getServerParams()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getServerParams() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Paramètres de l'URL</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les paramètres <span
                                                        class="badge badge-warning"><?= count($request->getQueryParams()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getQueryParams() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Données postées</th>
                                        <td>
                                            <details>
                                                <summary>Voir toutes les données postées <span
                                                        class="badge badge-warning"><?= count($request->getParsedBody()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getParsedBody() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Attributs de la requête</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les attributs <span
                                                        class="badge badge-warning"><?= count($request->getAttributes()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getAttributes() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Cookies</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les cookies <span
                                                        class="badge badge-warning"><?= count($request->getCookieParams()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getCookieParams() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Headers</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les headers <span
                                                        class="badge badge-warning"><?= count($request->getHeaders()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getHeaders() as $key => $header): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= current($header) ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>

                                <!-- URI -->
                                <h3>
                                    <code><?= get_class($request->getUri()) ?></code>
                                </h3>
                                <hr/>
                                <table class="table table-sm">
                                    <tbody>
                                    <tr>
                                        <th>URL</th>
                                        <td><?= $request->getUri() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Schéma</th>
                                        <td><?= $request->getUri()->getScheme() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Host</th>
                                        <td><?= $request->getUri()->getHost() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Port</th>
                                        <td><?= $request->getUri()->getPort() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Path</th>
                                        <td><?= $request->getUri()->getPath() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Query</th>
                                        <td><?= $request->getUri()->getQuery() ?></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <!-- Body -->
                                <h3>
                                    Body <code><?= get_class($request->getBody()) ?></code>
                                </h3>
                                <hr/>
                                <table class="table table-sm">
                                    <tbody>
                                    <tr>
                                        <th>Readable</th>
                                        <td><?= $request->getBody()->isReadable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Writable</th>
                                        <td><?= $request->getBody()->isWritable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Seekable</th>
                                        <td><?= $request->getBody()->isSeekable() ? '<span class="badge badge-success">TRUE</span>' : '<span class="badge badge-danger">FALSE</span>' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Taille</th>
                                        <td><?= $request->getBody()->getSize() ?></td>
                                    </tr>
                                    <tr>
                                        <th>Meta-données</th>
                                        <td>
                                            <details>
                                                <summary>Voir toutes les meta-données <span
                                                        class="badge badge-warning"><?= count($request->getBody()->getMetadata()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($request->getBody()->getMetadata() as $key => $value): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= $value ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Response -->
                            <div class="col-6">
                                <h3>
                                    <code><?= get_class($response) ?></code>
                                </h3>
                                <hr/>
                                <table class="table table-sm">
                                    <tbody>
                                    <tr>
                                        <th>Statut</th>
                                        <td><samp><?= $response->getStatusCode() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Phrase de raison</th>
                                        <td><samp><?= $response->getReasonPhrase() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Version du protocol</th>
                                        <td><samp><?= $response->getProtocolVersion() ?></samp></td>
                                    </tr>
                                    <tr>
                                        <th>Headers</th>
                                        <td>
                                            <details>
                                                <summary>Voir tous les headers <span
                                                        class="badge badge-warning"><?= count($response->getHeaders()) ?></span>
                                                </summary>
                                                <table class="table table-sm">
                                                    <tbody>
                                                    <?php foreach ($response->getHeaders() as $key => $header): ?>
                                                        <tr>
                                                            <th><samp><?= $key ?></samp></th>
                                                            <td><samp><?= current($header) ?></samp></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </details>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Coverage -->
            <div class="card">
                <div class="card-header" id="hCoverage">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collCoverage" aria-expanded="false" aria-controls="collCoverage">
                            Coverage
                        </button>
                    </h5>
                </div>
                <div id="collCoverage" class="collapse" aria-labelledby="hCoverage" data-parent="#accDebug">
                    <div class="card-body">
                        <iframe src="public/coverage/index.html" frameborder="0" width="100%" height="600"></iframe>
                    </div>
                </div>
            </div>

            <!-- Documentation -->
            <div class="card">
                <div class="card-header" id="hDoc">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                data-target="#collDoc" aria-expanded="false" aria-controls="collDoc">
                            Documentation
                        </button>
                    </h5>
                </div>
                <div id="collDoc" class="collapse" aria-labelledby="hDoc" data-parent="#accDebug">
                    <div class="card-body">
                        <iframe src="public/doc/index.html" frameborder="0" width="100%" height="600"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>