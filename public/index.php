<?php
$start = microtime(true);
require '../vendor/autoload.php';

$config = require '../configs/config.php';
$config = new Rcnchris\Core\Config\ConfigContainer($config);

error_reporting(0);
if ($config->get('debug') === true) {
    error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    ini_set("display_errors", 1);
}

?>
<!doctype html>
<html lang="fr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Synology API Libraries</title>
</head>
<body>

<div class="container-fluid" role="main">
    <?php
    if (!$config->get('debug')) {
        echo \Michelf\MarkdownExtra::defaultTransform(file_get_contents('../README.md'));
    } else {
        include 'debug/debug-content.php';
    }
    ?>
</div>

<footer class="footer">
    <div class="container-fluid">
        <span class="text-muted">
            Mémoire utilisée : <?= \Rcnchris\Core\Tools\Common::getMemoryUse(true) ?> - <?= microtime(true) - $start ?>
        </span>
    </div>
</footer>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
</body>
</html>