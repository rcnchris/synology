<?php
/** @var \Rcnchris\Synology\Packages\AudioStation\AudioStation $package */
$package = $syno->AudioStation;
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
        //r($package->albums()->toArray());
        //r($package->artists()->toArray());
        //r($package->composers()->toArray());
        //r($package->folders(['limit' => 5])->toArray());

        //        $folder = $package->folder('dir_9');
        //        r($folder);
        //        r($folder->content()->toArray());
        //        r($folder->size());

        //r($package->genres()->toArray());
        //r($package->playlists()->toArray());
        //r($package->playlist('playlist_shared_normal/345')->toArray());
        $player = $package->player('F4CAE55B33A0');
        r($player);
        r($player->toArray());

        //r($package->playlistOfPlayer('F4CAE55B33A0')->toArray());
        //r($package->songs(['limit' => 5])->toArray());

        //        $song = $package->song('music_72628', false);
        //        r($song->toArray());
        //r($song->setSharing());
        //r($song->lyrics());
        //r($song->sharingUrl());
        //r($song->coverUrl());

        //r($package->searchSong('paese')->toArray());
        //r($package->lyricsOfSong('music_72628'));
        //r($package->radios(['limit' => 5])->toArray());
        //r($package->servers(['limit' => 5])->toArray());

        $package->logout();

        ?>
    </div>

    <div class="col-6">
        <h3>JSON Documentation</h3>
        <?= r($package->getJsonDocumentation()->toArray()) ?>
    </div>
</div>

