<?php
/** @var \Rcnchris\Synology\Packages\VideoStation\VideoStation $package */
$package = $syno->VideoStation;
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
//        r($package->infos('version_string'));
        //r($package->collections(['limit' => 5])->get('collections')->toArray());
        //r($package->collections(['limit' => 5])->extract('id')->toArray());
        //r($package->movies(['limit' => 5])->toArray());
        //r($package->videosOfCollection(3)->toArray());
        //r($package->videos()->toArray());
        //r($package->tvshows()->toArray());
        //r($package->episodes()->toArray());
        //r($package->searchMovies('parrain')->toArray());
        //r($package->searchVideos('luge')->toArray());
        //r($package->searchEpisodes('vikings')->toArray());
        //r($package->libraries()->toArray());
        //r($package->recordings()->toArray());

        ?>
        <h4>Entités</h4>
        <?php
        //        $collection = $package->collection(3);
        //        r($collection);
        //        r($collection->videos());


        //        $movie = $package->movie(292)->toArray();
        //        r($movie);

        //        $video = $package->video(26);
        //        r($video);

//                $tvshow = $package->tvshow(54);
//                r($tvshow);
//                r($tvshow->episodes()->toArray());

//        $episode = $package->episode(392);
//        r($episode);
//        r($episode->tvshow());
        $package->logout();
        ?>
    </div>

    <div class="col-6">
        <h3>JSON Documentation</h3>
        <?= r($package->getJsonDocumentation()->toArray()) ?>
    </div>
</div>
