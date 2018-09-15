<?php
/** @var Rcnchris\Synology\Packages\FileStation\FileStation $package */
$package = $syno->FileStation;
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
<?php
r($package);
//r($package->download('/Download/Tests/chevrolet.jpg', 'open'));
//r($package->download('/Download/Tests/Syno_UsersGuide_NAServer_fra.pdf', 'download'));
//r($package->download('/Download/Tests/chevrolet.jpg', 'download'));
//r($package->infos()->toArray());
//r($package->shares(['limit' => 5])->toArray());
//r($package->sharings(['limit' => 5], 'links', 'name')->toArray());
//r($package->sharing('BvIxLaka4'));
//$package->createSharing('/Download/Tests/chevrolet.jpg');
//r($package->sharings([], 'links', 'name')->toArray());
//r($package->contentOfPath('/Download/Tests')->toArray());
//r($package->contentOfPath('/Download/Tests', ['limit' => 5], 'files', 'name')->toArray());
//r($package->search('/Download/Tests', '*.zip',[], 'files', 'name')->toArray());
//r($package->favorites()->toArray());
//r($package->thumb('/Download/Tests/chevrolet.jpg'));
//r($package->size('/Download/Tests/chevrolet.jpg'));
//r($package->backgroundTask()->toArray());
//r($package->extractList('/Download/Tests/tests.zip')->toArray());
//r(ROOT . '/public/index.php');

//$fileName = ROOT . '/public/index.php';
//r($package->uploadFile($fileName, '/Download/Tests')->toArray());

?>

    <img src="<?= $package->thumb('/Download/Tests/chevrolet.jpg', 'medium') ?>" alt="Chevrolet"/>

<?php
$package->logout();
?>