<?php
use Rcnchris\Core\Html\Html;

$syno = new \Rcnchris\Synology\Synology($config->get('servers'));
?>

<!-- Titre & logo -->
<div class="row">
    <div class="col">
        <?php
        $title = 'Debug <img src="' . $syno->logo() . '" alt="Logo Synology"/>';
        echo Html::surround($title, 'h1', ['class' => 'display-3']);
        ?>
        <hr/>
    </div>
</div>

<div class="row">
    <div class="col">

        <h2><i class="fa fa-cogs"></i> Construction</h2>

        <div class="alert alert-secondary">
            <pre>$syno = new \Rcnchris\Synology\Synology($config->get('servers'));</pre>
        </div>

        <div class="row">
            <div class="col">
                <h3>Instance</h3>
                <?= r($syno) ?>
            </div>
            <div class="col">

                <h3>Configuration</h3>
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th>ServerName</th>
                        <th>IP</th>
                        <th>Port</th>
                        <th>User</th>
                        <th>URL de base</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($syno->getConfigs() as $serverName => $config): ?>
                        <tr>
                            <td>
                                <small><?= $serverName ?></small>
                            </td>
                            <td>
                                <small><?= $config['address'] ?></small>
                            </td>
                            <td>
                                <small><?= $config['port'] ?></small>
                            </td>
                            <td>
                                <small><?= $config['user'] ?></small>
                            </td>
                            <td>
                                <small><?= $syno->baseUrl($serverName) ?></small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <hr/>
                <h3>Liste des packages de chaque serveur</h3>
                <div class="row">
                    <div class="col-6">
                        <p>Uniquement la liste des packages</p>
                        <?php r($syno->getPackagesList()->toArray()) ?>
                    </div>
                    <div class="col-6">
                        <p>Avec le nom des APIs pour chaque pacakge</p>
                        <?php r($syno->getPackagesList('nasdev', true)->toArray()) ?>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <hr/>
            </div>
        </div>

    </div>
</div>

<?php include 'request.php' ?>
<?php include 'package.php' ?>
<?php include "packages/antivirus.php" ?>
<?php //include "packages/audiostation.php" ?>
<?php //include "packages/backup.php" ?>
<?php //include "packages/documentviewer.php" ?>
<?php // include "packages/downloadstation.php" ?>
<?php //include "packages/filestation.php" ?>
<?php //include "packages/surveillancestation.php" ?>
<?php //include "packages/videostation.php" ?>

<div class="row">
    <div class="col-12">
        <hr/>
    </div>
    <div class="col">
        <h3>Définitions obtenues</h3>
        <?= Html::table($syno->getDefinitions(), ['class' => 'table table-sm']) ?>
    </div>
    <div class="col">
        <h3>SIDs</h3>
        <?php echo Html::table($syno->getSids(), ['class' => 'table table-sm']) ?>
    </div>

    <div class="col-12">
        <hr/>
        <h3>Logs</h3>
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <td>
                    <span class="badge badge-warning"><?= $syno->getLogs()->count() ?></span> requêtes, en <span class="badge badge-secondary"><?= $syno->getLogs()->extract('details')->extract('total_time')->sum() ?></span> seconde(s)
                </td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($syno->getLogs() as $key => $log): ?>
                <tr>
                    <td>
                        <blockquote class="blockquote">
                            <i><?= $log['title'] ?></i>
                            <small><span class="badge badge-secondary"><?= $log['details']['total_time'] ?></span></small>
                        </blockquote>
                        <small>
                            <a href="<?= $log['details']['url'] ?>" target="_blank"><?= $log['details']['url'] ?></a>
                        </small>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-6">
        <hr/>
        <h3>JSON Documentation</h3>
        <?= r($syno->getJsonDocumentation()) ?>
    </div>
    <div class="col-6">
        <hr/>
        <h3>Messages d'erreurs Synology</h3>
        <?= r($syno->getErrorMessages()->toArray()) ?>
    </div>
</div>