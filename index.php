<?php

use Controller\CinemaController;

spl_autoload_register(function ($class_name){
    include $class_name . '.php'; 
});

$ctrlCinema = new CinemaController();

$id = (isset($_GET["id"])) ? $_GET["id"] : null;
$new_libelle = isset($_POST['new_libelle']) ? $_POST['new_libelle'] : null;

if (isset($_GET["action"])) {

    switch ($_GET["action"]) {

        case "listFilms"          : $ctrlCinema->listFilms(); break;
        case "listActeurs"        : $ctrlCinema->listActeurs(); break;
        case "listGenres"         : $ctrlCinema->listGenres(); break;
        case "listRealisateurs"   : $ctrlCinema->listRealisateurs(); break;

        case "adminGenre"         : $ctrlCinema->adminGenre(); break;
        case "adminActeur"        : $ctrlCinema->adminActeur(); break;

        case "detailFilm"         : $ctrlCinema->detailFilm($id); break;
        case "detailActeur"       : $ctrlCinema->detailActeur($id); break;
        case "detailRealisateur"  : $ctrlCinema->detailRealisateur($id); break;

        case "addNouveauActeur"   : $ctrlCinema->addNouveauActeur(); break;
        case "editActeur"         : $ctrlCinema->editActeur($id); break;
        case "deleteActeur"       : $ctrlCinema->deleteActeur($id); break; 

        case "addNouveauGenre"    : $ctrlCinema->addNouveauGenre(); break;
        case "editGenre"          : $ctrlCinema->editGenre($id, $new_libelle); break;
        case "deleteGenre"        : $ctrlCinema->deleteGenre($id); break;

        case "deletePersonne"        : $ctrlCinema->deletePersonne($id); break;

        default                   : $ctrlCinema->Home();
    }
}

?>
