<?php

use Controller\CinemaController;

spl_autoload_register(function ($class_name){
    include $class_name . '.php'; 
});

$ctrlCinema = new CinemaController();

$id = (isset($_GET["id"])) ? $_GET["id"] : null;

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "listFilms"          : $ctrlCinema->listFilms(); break;
        case "listActeurs"        : $ctrlCinema->listActeurs(); break;
        case "listGenres"         : $ctrlCinema->listGenres(); break;
        case "listRealisateurs"   : $ctrlCinema->listRealisateurs(); break;

        case "adminGenre"         : $ctrlCinema->adminGenre(); break;
        case "adminActeur"        : $ctrlCinema->adminActeur(); break;
        case "adminRealisateur"   : $ctrlCinema->adminRealisateur(); break;
        case "adminFilm"          : $ctrlCinema->adminFilm(); break;


        case "detailFilm"         : $ctrlCinema->detailFilm($id); break;
        case "detailActeur"       : $ctrlCinema->detailActeur($id); break;
        case "detailRealisateur"  : $ctrlCinema->detailRealisateur($id); break;

        case "addNouveauFilm"     : $ctrlCinema->addNouveauFilm(); break;
        case "deleteFilm"         : $ctrlCinema->deleteFilm(); break;
        case "editFilm"           : $ctrlCinema->editFilm(); break;

        case "addNouveauActeur"   : $ctrlCinema->addNouveauActeur(); break;
        case "deleteActeur"       : $ctrlCinema->deleteActeur(); break;
        case "editActeur"         : $ctrlCinema->editActeur(); break;

        case "addNouveauGenre"    : $ctrlCinema->addNouveauGenre(); break;
        case "deleteGenre"        : $ctrlCinema->deleteGenre(); break;
        case "editGenre"          : $ctrlCinema->editGenre(); break;
        
        default                   : $ctrlCinema->Home();
    }
}

?>
