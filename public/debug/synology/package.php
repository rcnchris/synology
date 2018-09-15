<?php $package = $syno->MariaDB ?>
    <div class="row">
        <div class="col-6">
            <h2>
                Package non dédié -
                <small class="text-muted"><?= $package->getName() ?> - <?= $package->getServerName() ?></small>
            </h2>
            <div class="alert alert-secondary">
                <pre>$package = $syno->getPackage('<?= $package->getName() ?>');</pre>
                <pre>$package = $syno-><?= $package->getName() ?>;</pre>
            </div>
            <?php
            r($package);
            //r($package->get('Repository', 'list', ['version' => 1])->toArray())
            ?>
        </div>
        <div class="col-6">
            <h3>Définition JSON</h3>
            <?= r($package->getJsonDocumentation()->toArray()) ?>
        </div>
    </div>
<?php $package->logout() ?>