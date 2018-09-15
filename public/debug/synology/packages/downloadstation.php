<?php
/** @var \Rcnchris\Synology\Packages\DownloadStation\DownloadStation $package */
$package = $syno->DownloadStation;
?>
<div class="row">
    <div class="col">
        <h3>Package -
            <small class="text-muted"><?= $package->getName() ?> - <?= $package->getServerName() ?></small>
        </h3>
        <div class="alert alert-secondary">
            <pre>$package = $syno->getPackage('<?= $package->getName() ?>');</pre>
            <pre>$package = $syno-><?= $package->getName() ?>;</pre>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <?php
        r($package);
        ?>
        <h4>Listes</h4>
        <?php
//        r($package->infos()->toArray());
//        r($package->config()->toArray());
        //r($package->rssSites()->toArray());
        r($package->tasks()->toArray());
        r($package->task('dbid_70'));
        //r($package->listBT()->toArray());
        ?>
        <h4>Entités</h4>
        <?php
        //        $collection = $package->collection(3);
        //        r($collection);
        //        r($collection->videos());
        $package->logout();
        ?>
    </div>

    <div class="col-6">
        <h3>JSON Documentation</h3>
        <?= r($package->getJsonDocumentation()->toArray()) ?>
    </div>
</div>
