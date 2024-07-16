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

        case "detailFilm"         : $ctrlCinema->detailFilm($id); break;

        case "listActeurs"        : $ctrlCinema->listActeurs(); break;

        
        case "detailActeur"       : $ctrlCinema->detailActeur($id); break;
        case "listRealisateurs"   : $ctrlCinema->listRealisateurs(); break;
        case "detailRealisateur"  : $ctrlCinema->detailRealisateur($id); break;
        case "listGenres"         : $ctrlCinema->listGenres(); break;
        
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
