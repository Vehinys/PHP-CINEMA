<?php

namespace Controller;

use Model\Connect;

Class CinemaController {

    /* ----------------- LISTE FILM - HOME ----------------- */

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

     /* ----------------- LISTE FILM - FILMS ----------------- */

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

        /* ----------------- REQUETE AFFICHAGE MENU DEROULANT ----------------- */

        $requeteReal = $pdo->query("

        SELECT CONCAT(p.nom,' ', p.prenom) as realisateur ,id_realisateur
        FROM realisateur r
        INNER JOIN personne p ON p.id_personne = r.id_personne

    ");

        require "view/listFilms.php";
    }

    /* ----------------- LISTE FILM - DETAILS FILM ----------------- */
        
    public function detailFilm($id) {

        $pdo = Connect::seConnecter();
        $requete = $pdo->prepare("
    
        SELECT f.titre, YEAR(dateDeSortieEnFrance) AS 'sortie', f.id_film, g.libelle
        FROM film f
        INNER JOIN film_genres fg ON f.id_film = fg.id_film
        INNER JOIN genre g ON g.id_genre = fg.id_genre
        WHERE f.id_film = :id
        ");
            
        $requete->execute(["id" => $id]); 
    
        require "view/detailFilm.php";
    }

    /* ----------------- LISTE FILM - ADD FILM ----------------- */
    
    public function addNouveauFilm() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $titreFilm = filter_input(INPUT_POST, 'titreFilm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dureeFilm = filter_input(INPUT_POST, 'dureeFilm', FILTER_VALIDATE_INT);
            $synopsisFilm = filter_input(INPUT_POST, 'synopsisFilm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $noteFilm = filter_input(INPUT_POST, 'noteFilm', FILTER_VALIDATE_INT);
            $urlImageFilm = filter_input(INPUT_POST, 'urlImageFilm', FILTER_SANITIZE_URL);
            $id_realisateurFilm = filter_input(INPUT_POST, 'id_realisateurFilm', FILTER_VALIDATE_INT);

            // Vérification des données filtrées et validées

            if ($titreFilm && $dureeFilm !== false && $synopsisFilm && $noteFilm !== false && $urlImageFilm && $id_realisateurFilm !== false) {

                $pdo = Connect::seConnecter();

                $requeteAddFilm = $pdo->prepare("

                    INSERT INTO film (titre, duree, synopsis, note, urlImage, id_realisateur)
                    VALUES (:titre, :duree, :synopsis, :note, :urlImage, :id_realisateur)
                ");
    
                // Liaison des paramètres et exécution de la requête d'insertion

                $requeteAddFilm->bindParam(':titre', $titreFilm);
                $requeteAddFilm->bindParam(':duree', $dureeFilm);
                $requeteAddFilm->bindParam(':synopsis', $synopsisFilm);
                $requeteAddFilm->bindParam(':note', $noteFilm);
                $requeteAddFilm->bindParam(':urlImage', $urlImageFilm);
                $requeteAddFilm->bindParam(':id_realisateur', $id_realisateurFilm);
                $requeteAddFilm->execute();
    
                // Redirection vers la page listfilm après l'insertion réussie

                header("Location: index.php?action=listFilms");

                exit; // Arrête l'exécution du script après la redirection

            } else {

                // Gestion des erreurs si les données ne sont pas valides

                echo "Erreur : Veuillez vérifier les données saisies.";
            }
        }
    
        // Inclusion du fichier de vue pour afficher le formulaire

        require "view/listFilms.php";
    }

    /* ----------------- LISTE ACTEUR ----------------- */

    public function listActeurs() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("
    
        // ");

        require "view/listActeurs.php";
    }

    /* ----------------- LISTE REALISATEURS ----------------- */

    public function listRealisateurs() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("
        // ");

        require "view/listRealisateurs.php";
    }

    /* ----------------- LISTE GENRES ----------------- */

    public function listGenres() {
        $pdo = connect::seConnecter();
        // $requete = $pdo->query("
        // ");

        require "view/listGenres.php";
    }

    /* ----------------- LISTE GENRES - ADD GENRE ----------------- */

    public function FormulaireGenre() {
        require "view/listGenres.php";
    }

    public function addNouveauGenre() {
        $pdo = Connect::seConnecter();
        $libelle = filter_input(INPUT_POST, 'addGenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($_POST["submit"]) {
            $requeteAjoutGenre = $pdo->prepare("
                INSERT INTO genre (libelle)
                VALUES (:libelle)
            ");

            $requeteAjoutGenre->bindParam(':libelle', $libelle);
            $requeteAjoutGenre->execute();

            header("Location: index.php?action=listGenres");
        }
    }

}