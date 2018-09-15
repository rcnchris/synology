<!-- Synology -->
<?php
$syno = new \Rcnchris\Core\Apis\Synology\Synology($config->get('synology')[0]);
?>

<!-- API -->
<div class="row">
    <div class="col-12">
        <img src="<?= $syno->logo() ?>"/>
        <hr/>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <?= $syno->getConfig()->get('description') ?>
                    - <span class="badge badge-secondary"><?= $syno->getConfig()->get('user') ?></span>
                </h5>
            </div>
            <div class="card-body">

                <pre class="sh_php"> $syno = new \Rcnchris\Core\Apis\Synology\Synology($config);</pre>

                <h5 class="card-title">Classe : <code><?= get_class($syno) ?></code></h5>

                <h6 class="card-subtitle mb-2 text-info">Méthodes</h6>

                <div class="alert alert-secondary">
                    <code><?= implode(', ', get_class_methods($syno)) ?></code>
                </div>
                <hr/>

                <h5 class="card-title">Définitions</h5>

                <div class="row">
                    <div class="col-6">
                        <?php
                        $pkgName = 'SurveillanceStation';
                        $pkg = $syno->getPackage($pkgName);
                        //r($syno->getJsonDefinition($pkgName)->toArray());
                        r($pkg->jsonDocumentation()->toArray());
                        ?>
                    </div>
                    <div class="col-6">
                        <?php
                        echo $pkg->getName() . ' - ' . $pkg->version();
                        //echo $pkg->getName();
                        r($pkg);

                        //r($pkg->infos()->toArray());
                        //r($pkg->isManager());
                        //r($pkg->setConfig('http_max_download', 0));
                        //r($pkg->config()->toArray());
                        //r($pkg->setSchedule('enabled', 'true'));
                        //r($pkg->sitesRss()->toArray());
                        //r($pkg->btGetCategories()->toArray());
                        //r($pkg->btSearch('ubuntu', 'enabled', ['limit' => 3])->toArray());
                        //r($pkg->btGetModules()->toArray());

                        //                        r($pkg->nasDescription()->toArray());
                        //                        r($pkg->infos()->toArray());
                        //                        r($pkg->cameras(['limit' => 1])->toArray());
                        /** @var \Rcnchris\Core\Apis\Synology\Packages\SurveillanceStation\Camera $cam */
                        $cam = $pkg->camera(1);
                        r($cam);
                        //r($cam->statusName());
                        echo '<img src="' . $cam->snapshotUrl() . '" alt="' . $cam->name . '" title="' . $cam->name . '">'

                        //r($pkg->infos()->toArray());
                        //r($pkg->albums(false, ['offset' => 10, 'limit' => 5])->toArray());
                        //r($pkg->artists(false, ['offset' => 10, 'limit' => 5])->toArray());
                        //r($pkg->composers(false, ['offset' => 10, 'limit' => 5])->toArray());
                        //r($pkg->folders(false, ['offset' => 10, 'limit' => 5])->toArray());
                        //r($pkg->folder('dir_37'));
                        //r($pkg->genres(false, ['offset' => 10, 'limit' => 5])->toArray());
                        //r($pkg->songs(false, ['offset' => 1000, 'limit' => 5])->toArray());
                        //r($pkg->searchSong('u-tutn')->toArray());
                        //r($pkg->song('music_84085'));
                        //r($pkg->lyricsOfSong('music_84085'));
                        //r($pkg->sharingOfSong('music_84085')->toArray());
                        //r($pkg->request('Song', 'getsharing', ['id' => 'music_59347'])->toArray());
                        //r($pkg->servers(false, ['limit' => 2])->toArray());
                        //r($pkg->playlists(false, ['limit' => 2])->toArray());
                        //r($pkg->playlist('playlist_shared_normal/345'));
                        //r($pkg->radios(false)->toArray());
                        //                        r($pkg->remotes(false)->toArray());
                        //                        r($pkg->remote('F4CAE55B33A0'));
                        //                        r($pkg->remotePlaylist('F4CAE55B33A0'));
                        //                        r($pkg->remoteStatus('F4CAE55B33A0')->toArray());
                        //r($pkg->webPlayerPlaylist()->toArray());
                        //r($pkg->request('Cover', 'getsongcover', ['id' => 'music_59347']));
                        //r($pkg->request('Cover', 'getsongcover', ['id' => 'zob'])->toArray());

                        ?>
                        <hr/>

                    </div>
                </div>
                <hr/>

                <h5 class="card-title">Requêtes</h5>

                <div class="row">
                    <div class="col-3">
                        <?php // r($syno->request('VideoStation.Movie', 'list', ['limit' => 3])->toArray()); ?>
                    </div>
                    <div class="col-3">
                        <?php // r($syno->request('VideoStation.Movie', 'list', ['limit' => 3, 'account' => 'phpunit', 'passwd' => 'mycoretest'])->toArray()); ?>
                    </div>
                    <div class="col-3">
                        <?php // r($syno->request('VideoStation.Movie', 'search', ['title' => 'parrain'])->toArray()); ?>
                    </div>
                    <div class="col-3">
                        <?php // r($syno->request('AudioStation.Genre')->toArray()); ?>
                    </div>
                    <div class="col-3">
                        <?php //r($syno->request('AudioStation.Genre')->toArray()); ?>
                    </div>
                </div>

                <h5 class="card-title">Messages d'erreurs Synology</h5>

                <div class="row">
                    <div class="col">
                        <?= r($syno->getErrorsMessages()->toArray()) ?>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
    </div>

</div>

<?php
/**
 * Déconnexion des API utilisées
 * Pas nécessaire car fait par __destruct de SynologyAPI
 */
$sids = $syno->getSids();
foreach ($sids as $apiName => $sid) {
    $syno->logout($apiName);
}
?>

<!-- Journal des requêtes -->
<div class="row">
    <div class="col">
        <hr/>
        <div class="card">
            <div class="card-header">Contenu l'instance <code>$syno</code></div>
            <div class="card-body">

                <h6 class="card-subtitle mb-2 text-info">
                    Identifiants de connexions obtenus par les APIs
                    <span class="badge badge-warning"><?= count($sids) ?></span>
                </h6>
                <?= $html->details('Voir les SIDs', $html->table($sids, ['class' => 'table table-sm table-striped'])) ?>

                <hr/>

                <h5 class="card-title">Journal</h5>
                <?php $logs = $syno->getLog(true) ?>

                <h6 class="card-subtitle mb-2 text-info">
                    <span class="badge badge-warning"><?= $logs->count() ?></span> requêtes exécutées, en <span
                        class="badge badge-warning"><?= $logs->extract('details')->extract('total_time')->sum() ?></span>
                    seconde(s)
                </h6>
                <details>
                    <summary>Voir toutes les requêtes</summary>
                    <table class="table table-sm table-striped">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>URL</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Temps</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <small><i><?= $log['title'] ?></i></small>
                                </td>
                                <td>
                                    <small><?= $log['details']['url'] ?></small>
                                </td>
                                <td><span class="badge badge-secondary"><?= $log['details']['http_code'] ?></span></td>
                                <td>
                                    <small><?= $log['details']['content_type'] ?></small>
                                </td>
                                <td>
                                    <small><?= $log['details']['total_time'] ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </details>
            </div>
        </div>

        <hr/>
    </div>
</div>

