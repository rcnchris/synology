<?php
/** @var Rcnchris\Synology\Packages\Backup\Backup $package */
$package = $syno->Backup;
?>
<div class="row">
    <div class="col-12">
        <hr/>
    </div>
    <div class="col-6">
        <h2>Package dédié - <small class="text-muted"><?= $package->getName() ?> - <?= $package->getServerName() ?></small></h2>
        <div class="alert alert-secondary">
            <pre>$package = $syno->getPackage('<?= $package->getName() ?>');</pre>
            <pre>$package = $syno-><?= $package->getName() ?>;</pre>
        </div>
        <?php
        r($package);
        r($package->repositories()->toArray());
        ?>
    </div>
    <div class="col-6">
        <h3>Documentation JSON</h3>
        <?= r($package->getJsonDocumentation()->toArray()) ?>
        <hr/>
    </div>
</div>
<?php $package->logout() ?>