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
        case "listRealisateurs"   : $ctrlCinema->listRealisateurs(); break;
        case "listGenres"         : $ctrlCinema->listGenres(); break;
        
        case "FormulaireGenre"    : $ctrlCinema->FormulaireGenre(); break;
        case "addNouveauGenre"    : $ctrlCinema->addNouveauGenre(); break;

        case "FormulaireFilm"     : $ctrlCinema->FormulaireFilm(); break;
        case "addNouveauFilm"     : $ctrlCinema->addNouveauFilm(); break;
        
        default                   : $ctrlCinema->Home();
    }
}