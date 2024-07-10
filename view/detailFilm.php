<?php ob_start(); ?>

<?php foreach($requete->fetchAll() as $film) { ?>

    <p> <?= $film["sortie"] ?> | <?=$film['libelle']?> </p>

<?php } ?>

<?php


$titre = "Film releases - Detail du Film";
$contenu = ob_get_clean();

require "template.php";
