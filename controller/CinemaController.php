<?php

namespace Controller;

use Model\Connect;

Class CinemaController {

        /* Liste des films - SORTIE DE LA SEMAINE ET TOUJOURS A L AFFICHE */

    public function home() {
        $pdo = connect::seConnecter();
        $requete = $pdo->query("

        WITH DerniersFilms AS (SELECT f.titre, f.urlImage, f.id_film, ROW_NUMBER() OVER (ORDER BY f.dateDeSortieEnFrance DESC) AS row_num
        FROM film f )
        
        SELECT titre, urlImage, id_film
        FROM DerniersFilms
        WHERE row_num <= 4;

        ");

        $requete2 = $pdo->query("
        WITH FilmsAleatoires AS (
        SELECT f.titre, f.urlImage, f.id_film, ROW_NUMBER() OVER (ORDER BY RAND()) AS row_num
        FROM film f)
        SELECT titre, urlImage, id_film
        FROM FilmsAleatoires
        WHERE row_num <= 4;
        ");

        require "view/home.php";
    }

    /* Liste des films */

    public function listFilms() {
        $pdo = connect::seConnecter();
        $requete = $pdo->query("
            SELECT f.id_film, titre, YEAR(dateDeSortieEnFrance) as 'year', CONCAT(prenom, ' ', nom) as 'realisateur', f.id_realisateur,f.urlImage
            FROM film f
            LEFT JOIN realisateur re ON f.id_realisateur = re.id_realisateur
            LEFT JOIN personne p ON re.id_personne = p.id_personne
            AND re.id_personne = p.id_personne
            ORDER BY titre ASC;
        ");

        require "view/listFilms.php";
    }

    public function listActeurs() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("
    
        // ");

        require "view/listActeurs.php";
    }

    public function listRealisateurs() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("

        // ");

        require "view/listRealisateurs.php";
    }

    public function listGenres() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("

        // ");

        require "view/listGenres.php";
    }

    public function detailFilm($id) {

        $pdo = Connect::seConnecter();

        $requete = $pdo->prepare("

SELECT 
    f.titre, YEAR(dateDeSortieEnFrance) AS 'sortie', f.id_film, g.libelle
FROM 
    film f
JOIN 
    film_genres fg ON f.id_film = fg.id_film
JOIN 
    genre g ON g.id_genre = fg.id_genre
WHERE 
    f.id_film = :id

    
    ");

    $requete->execute(["id" => $id]); 

    require "view/detailFilm.php";

    }

}