<div class="row">
    <div class="col">
        <h2>Request</h2>

        <div class="alert alert-secondary">
            <pre>$response = $syno->request('FileStation', 'List', 'list_share', ['limit' => 5], 'nasdev')</pre>
        </div>
    </div>
</div>
<div class="row">
    <?php
    $request = $syno->request('FileStation', 'List', 'list_share', ['limit' => 5]);
    ?>
    <div class="col">
        <h3>Instance</h3>
        <?php r($request) ?>
        <hr/>
        <h4>Options</h4>
        <?php r($request->getOptions()); ?>
    </div>

    <div class="col">

        <h3>Exécution</h3>

        <div class="alert alert-secondary">
            <pre>$request->exec('Liste des dossiers', true, 'shares', 'name')->toArray();</pre>
        </div>

        <h4>Résultat</h4>
        <?= r($request->exec('Liste des dossiers', true, 'shares', 'name')->toArray()) ?>
        <hr/>

        <h4>URL</h4>
        <small><code><?= $request->getSynology()->getCurl()->getUrl() ?></code></small>
        <hr/>

    </div>
</div>
<hr/>
<?php $syno->logout('FileStation', 'nas') ?>