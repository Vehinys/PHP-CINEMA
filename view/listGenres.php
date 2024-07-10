<?php ob_start(); ?>


<?php

$titre = "Film releases - Genres";
$contenu = ob_get_clean();

require "template.php";