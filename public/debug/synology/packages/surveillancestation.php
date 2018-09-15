<?php
/** @var \Rcnchris\Synology\Packages\SurveillanceStation\SurveillanceStation $package */
$package = $syno->SurveillanceStation;
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
        //r($package->infos()->toArray());
        //r($package->cameras()->toArray());
        //r($package->camera(1));
        //r($package->camera(1)->snapshotUrl());
//        r($package->servicesSetting('vsEnabled'));
//        r($package->servicesSetting()->toArray());
//        r($package->vsList()->toArray());
//        r($package->cmsList()->toArray());
        $camera = $package->camera(1);
        //$syno->logout($package->getName(), $package->getServerName());
        $package->logout();
        ?>
        <img src="<?= $camera->snapshotUrl() ?>" alt="<?= $camera->name ?>"/>
    </div>

    <div class="col-6">
        <h3>JSON Documentation</h3>
        <?= r($package->getJsonDocumentation()->toArray()) ?>
        <?= r($package->getJsonDocumentation('Camera')->toArray()) ?>
    </div>
</div>

