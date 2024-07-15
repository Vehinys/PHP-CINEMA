<?php ob_start(); ?>

<?php foreach($requete->fetchAll() as $titre) { ?>

    <p> <?= $titre["titre"] ?> </p> 
    <a href="./index.php?action=detailFilm&id=<?= $titre["id_film"] ?>"> <img class="img_url"  src='<?= isset($titre["urlImage"]) && $titre["urlImage"] ? $titre["urlImage"] : '' ?>'alt="image"> </a>

<?php } ?>

<?php foreach($requete1->fetchAll() as $personne) { ?>

    <p> <?= $personne["sortie"] ?> | <?= $personne["duree"] ?> | <?= $personne["genre"] ?> </p>

<?php } ?>

<?php foreach($requete2->fetchAll() as $realisateur) { ?>

<p> Realisateur : <?= $realisateur["realisateur"] ?> </p>

<?php } ?>


<p>Les acteurs : </p>

<?php foreach($requete3->fetchAll() as $acteur) { ?>

    <p> <?= $acteur["prenom"] ?> <?= $acteur["nom"] ?> / <?= $acteur["nomRole"] ?> </p>

<?php } ?>

<?php foreach($requete4->fetchAll() as $information) { ?>

    <p> <?= $information["note"] ?> sur 5 </p>
    <p> <?= $information["synopsis"] ?> </p>

<?php } ?>

<?php


$titre = "Film releases - Detail du Film";
$contenu = ob_get_clean();

require "template.php";
