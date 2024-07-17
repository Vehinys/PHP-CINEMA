<?php

namespace Controller;

use Model\Connect;

Class CinemaController {

    /* ----------------- HOME ----------------- */

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

     /* ----------------- LISTE FILMS ----------------- */

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

        /* --- REQUETE REAL AFFICHAGE MENU DEROULANT --- */

        $requeteReal = $pdo->query("

        SELECT CONCAT(p.nom,' ', p.prenom) as realisateur ,id_realisateur
        FROM realisateur r
        INNER JOIN personne p ON p.id_personne = r.id_personne
        
        ");

        /* --- REQUETE ACT AFFICHAGE MENU DEROULANT --- */

        $requeteAct = $pdo->query("
        
        SELECT CONCAT(p.nom,' ', p.prenom) as acteur ,id_acteur
        FROM acteur a
        INNER JOIN personne p ON p.id_personne = a.id_acteur
        
        ");    

    require "view/listFilms.php";
    }

    /* --- LISTE FILM - DETAILS FILM --- */
        
    public function detailFilm($id) {

        $pdo = Connect::seConnecter();

        $requete = $pdo->prepare("

        SELECT titre, id_film, urlImage
        FROM film
        WHERE id_film= :id

        ");

        $requete->execute(["id" => $id]); // requête récupère le titre et l'id du film

        $requete1 = $pdo->prepare("

        SELECT f.id_film, YEAR(dateDeSortieEnFrance) AS 'sortie', CONCAT(FLOOR(duree/60), ' h ',ROUND((duree/60 - FLOOR(duree/60)) * 60)) AS 'duree',GROUP_CONCAT(g.libelle) as genre
        FROM film f
        INNER JOIN film_genres fg ON fg.id_film= f.id_film
        INNER JOIN genre g ON g.id_genre = fg.id_genre
        WHERE f.id_film = :id
        
        ");

        $requete1->execute(["id" => $id]); // requête qui récupère toutes les informations du film (titre, année de sortie, durée en h et min, prénom et nom du réalisateur)


        $requete2 = $pdo->prepare("

        SELECT f.id_film, CONCAT(prenom, ' ', nom) AS realisateur , re.id_realisateur
        FROM film f, realisateur re, personne p
        WHERE f.id_realisateur = re.id_realisateur
        AND re.id_personne = p.id_personne 
        AND id_film = :id 

        ");

        $requete2->execute(["id" => $id]); // requête qui récupère les genres associés au film


        $requete3 = $pdo->prepare("

        SELECT f.id_film, c.id_acteur, prenom, nom, nomRole
        FROM personne p, film f, casting c, acteur a, role r
        WHERE p.id_personne = a.id_personne
        AND f.id_film = c.id_film
        AND c.id_acteur = a.id_acteur
        AND c.id_role = r.id_role
        AND f.id_film = :id 

        ");

        $requete3->execute(["id" => $id]); // requête qui récupère tous les acteurs associés au film et le nom du rôle correspondant


        $requete4 = $pdo->prepare("

        SELECT f.synopsis, f.note
        FROM film f
        WHERE f.id_film =:id 
   
       ");
       $requete4->execute(["id" => $id]); // requête qui récupère tous les acteurs associés au film et le nom du rôle correspondant

    require "view/detailFilm.php";

    }

    /* --- LISTE FILM - ADD FILM --- */
    
    public function addNouveauFilm() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $titreFilm = filter_input(INPUT_POST, 'titreFilm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dureeFilm = filter_input(INPUT_POST, 'dureeFilm', FILTER_VALIDATE_INT);
            $synopsisFilm = filter_input(INPUT_POST, 'synopsisFilm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $noteFilm = filter_input(INPUT_POST, 'noteFilm', FILTER_VALIDATE_INT);
            $urlImageFilm = filter_input(INPUT_POST, 'urlImageFilm', FILTER_SANITIZE_URL);
            $id_realisateurFilm = filter_input(INPUT_POST, 'id_realisateurFilm', FILTER_VALIDATE_INT);
            $dateSortieFilm = filter_input(INPUT_POST, 'dateDeSortieEnFrance', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Vérification des données filtrées et validées

        if ($titreFilm && $dureeFilm && $synopsisFilm && $noteFilm  && $urlImageFilm && $id_realisateurFilm && $dateSortieFilm !== false)  {

            $pdo = Connect::seConnecter();

            $requeteAddFilm = $pdo->prepare("

            INSERT INTO film (titre, duree, synopsis, note, urlImage, id_realisateur, dateDeSortieEnFrance)
            VALUES (:titre, :duree, :synopsis, :note, :urlImage, :id_realisateur, :dateDeSortieEnFrance)

            ");
    
    // Liaison des paramètres et exécution de la requête d'insertion

            $requeteAddFilm->bindParam(':titre', $titreFilm);
            $requeteAddFilm->bindParam(':duree', $dureeFilm);
            $requeteAddFilm->bindParam(':synopsis', $synopsisFilm);
            $requeteAddFilm->bindParam(':note', $noteFilm);
            $requeteAddFilm->bindParam(':urlImage', $urlImageFilm);
            $requeteAddFilm->bindParam(':id_realisateur', $id_realisateurFilm);
            $requeteAddFilm->bindParam(':dateDeSortieEnFrance', $dateSortieFilm);
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
    
    public function deleteFilm() {
        $pdo = Connect::seConnecter();
        $id_genre = filter_input(INPUT_POST, 'id_genre', FILTER_VALIDATE_INT);
    
        if ($id_genre) {
            $requeteSuppression = $pdo->prepare("
                DELETE FROM genre
                WHERE id_genre = :id_genre
            ");
            $requeteSuppression->bindParam(':id_genre', $id_genre);
            $requeteSuppression->execute();
    
            header("Location: index.php?action=listGenres");
        }
    }
    
    public function editFilm() {
        $pdo = Connect::seConnecter();
        $id_genre = filter_input(INPUT_POST, 'id_genre', FILTER_VALIDATE_INT);
        $new_libelle = filter_input(INPUT_POST, 'new_libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        if ($id_genre && $new_libelle) {
            $requeteModification = $pdo->prepare("
                UPDATE genre
                SET libelle = :new_libelle
                WHERE id_genre = :id_genre
            ");
            $requeteModification->bindParam(':id_genre', $id_genre);
            $requeteModification->bindParam(':new_libelle', $new_libelle);
            $requeteModification->execute();
    
            header("Location: index.php?action=listGenres");
        }
    }

    /* ----------------- LISTE ACTEURS ----------------- */

    public function listActeurs() {

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("

        SELECT a.id_acteur, p.prenom, p.nom
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ORDER BY p.nom ASC

        ");
    
    require "view/listActeurs.php";

    }

    /* --- LISTE FILM - DETAILS ACTEUR --- */
        
    public function detailActeur($id) {
        $pdo = Connect::seConnecter();
        
        // Récupération des détails de l'acteur
        $requete = $pdo->prepare("

        SELECT a.id_acteur, CONCAT(prenom, ' ', nom) as 'acteur', prenom, nom,DATE_FORMAT(dateNaissance, '%d/%m/%Y') AS naissance, sexe, TIMESTAMPDIFF(YEAR, dateNaissance, CURDATE()) AS age 
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        WHERE a.id_acteur = :id
            
        ");
        
        $requete->execute(["id" => $id]);
        
        // Récupération des films de l'acteur

        $requete2 = $pdo->prepare("
        
        SELECT f.titre, YEAR(dateDeSortieEnFrance) AS 'sortie', f.id_film, r.nomRole, GROUP_CONCAT(g.libelle) as genre,TIMESTAMPDIFF(YEAR, dateDeSortieEnFrance, CURDATE()) AS age
        FROM film f
        INNER JOIN film_genres fg ON f.id_film   = fg.id_film
        INNER JOIN genre g        ON g.id_genre  = fg.id_genre
        INNER JOIN casting c      ON c.id_film   = f.id_film
        INNER JOIN acteur a       ON a.id_acteur = c.id_acteur
        INNER JOIN role r         ON r.id_role   = c.id_role
        WHERE a.id_acteur = :id
        GROUP BY f.id_film,r.nomRole
        ORDER BY age DESC;
        
        ");
        
        $requete2->execute(["id" => $id]);
    
        // Passer les résultats à la vue
    require "view/detailActeur.php";

    }

    /* ----------------- LISTE REALISATEURS ----------------- */

    public function listRealisateurs() {

        $pdo = Connect::seConnecter();
        $requete = $pdo->query("

        SELECT id_realisateur, prenom, nom
        FROM realisateur re, personne p
        WHERE re.id_personne = p.id_personne
        ORDER BY nom

        ");

    require "view/listRealisateurs.php";

    }

    /* --- LISTE FILM - DETAILS REALISATEUR --- */
        
    public function detailRealisateur($id) {
        $pdo = Connect::seConnecter();
        
        // Récupération des détails de l'acteur
        $requete = $pdo->prepare("

        SELECT a.id_acteur, CONCAT(prenom, ' ', nom) as 'realisateur', prenom, nom,DATE_FORMAT(dateNaissance, '%d/%m/%Y') AS naissance, sexe, TIMESTAMPDIFF(YEAR, dateNaissance, CURDATE()) AS age 
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        WHERE a.id_acteur = :id
            
        ");
        
        $requete->execute(["id" => $id]);
        
        // Récupération des films de l'acteur

        $requete2 = $pdo->prepare("
        
        SELECT f.titre, YEAR(dateDeSortieEnFrance) AS 'sortie', f.id_film, GROUP_CONCAT(g.libelle) as genre,TIMESTAMPDIFF(YEAR, dateDeSortieEnFrance, CURDATE()) AS age
        FROM film f
        INNER JOIN film_genres fg ON f.id_film   = fg.id_film
        INNER JOIN genre g        ON g.id_genre  = fg.id_genre
        INNER JOIN casting c      ON c.id_film   = f.id_film
        INNER JOIN acteur a       ON a.id_acteur = c.id_acteur
        WHERE a.id_acteur = :id
        GROUP BY f.id_film
        ORDER BY age DESC;
        
        ");
        
        $requete2->execute(["id" => $id]);
    
        // Passer les résultats à la vue
    require "view/detailRealisateur.php";

    }

    /* ----------------- LISTE GENRES ----------------- */

    public function listGenres() {

        $pdo = connect::seConnecter();
        $requete = $pdo->query("

        SELECT g.id_genre, libelle, COUNT(fg.id_film) as compte 
        FROM genre g 
        LEFT JOIN film_genres fg ON g.id_genre = fg.id_genre
        GROUP BY g.id_genre
        ORDER BY libelle

    ");

        require "view/listGenres.php";
    }

    /* ----------------- ADMIN ----------------- */

    /* ------ ADMIN GENRE ------ */

    public function adminGenre() {

        $pdo = connect::seConnecter();
        $requete = $pdo->query("

        SELECT g.id_genre, libelle, COUNT(fg.id_film) as compte 
        FROM genre g 
        LEFT JOIN film_genres fg ON g.id_genre = fg.id_genre
        GROUP BY g.id_genre
        ORDER BY libelle

        ");

        require "view/adminGenre.php";
    }

    /* --- ADMIN - ADD GENRE --- */

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

            header("Location: index.php?action=adminGenre");
        }
    }

    /* --- ADMIN - EDIT GENRE --- */
    
    public function editGenre() {
        $pdo = Connect::seConnecter();

        $id_genre = filter_input(INPUT_POST, 'id_genre', FILTER_VALIDATE_INT);
        $new_libelle = filter_input(INPUT_POST, 'new_libelle', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        if ($id_genre && $new_libelle) {

            $requeteModification = $pdo->prepare("

                UPDATE genre
                SET libelle = :new_libelle
                WHERE id_genre = :id_genre

            ");

            $requeteModification->bindParam(':id_genre', $id_genre);
            $requeteModification->bindParam(':new_libelle', $new_libelle);
            $requeteModification->execute();
    
            header("Location: index.php?action=adminGenre");
        }

    require "view/adminGenre.php";

    }

    /* --- ADMIN - DELETE GENRE --- */

    public function deleteGenre() {
        $pdo = Connect::seConnecter();

        $id_genre = filter_input(INPUT_POST, 'id_genre', FILTER_VALIDATE_INT);
    
        if ($id_genre) {

            $requeteSuppression = $pdo->prepare("

                DELETE FROM genre
                WHERE id_genre = :id_genre

            ");

            $requeteSuppression->bindParam(':id_genre', $id_genre);
            $requeteSuppression->execute();
    
            header("Location: index.php?action=adminGenre");
        }

        require "view/adminGenre.php";
    }

    /* ------ ADMIN ACTEUR ------ */

    public function adminActeur() {
        $pdo = Connect::seConnecter();
        $requete = $pdo->query("
            SELECT a.id_acteur, p.prenom, p.nom
            FROM acteur a
            INNER JOIN personne p ON a.id_personne = p.id_personne
            ORDER BY p.nom ASC
        ");
        require "view/adminActeur.php";
    }

    /* --- ADMIN - ADD ACTEUR --- */
    
    public function addNouveauActeur() {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $sexe = filter_input(INPUT_POST, 'sexe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dateNaissance = filter_input(INPUT_POST, 'dateNaissance', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            if ($nom && $prenom && $sexe && $dateNaissance && $role) {
                $pdo = Connect::seConnecter();

                $requeteAddPersonne = $pdo->prepare("

                    INSERT INTO personne (nom, prenom, sexe, dateNaissance)
                    VALUES (:nom, :prenom, :sexe, :dateNaissance)

                ");

                $requeteAddPersonne->bindParam(':nom', $nom);
                $requeteAddPersonne->bindParam(':prenom', $prenom);
                $requeteAddPersonne->bindParam(':sexe', $sexe);
                $requeteAddPersonne->bindParam(':dateNaissance', $dateNaissance);
                $requeteAddPersonne->execute();

                $id_personne = $pdo->lastInsertId();

                if ($role === 'acteur' || $role === 'les deux') {
                    $requeteAddActeur = $pdo->prepare("
                    
                        INSERT INTO acteur (id_personne)
                        VALUES (:id_personne)
                    ");

                    $requeteAddActeur->bindParam(':id_personne', $id_personne);
                    $requeteAddActeur->execute();
                }

                if ($role === 'realisateur' || $role === 'les deux') {
                    $requeteAddRealisateur = $pdo->prepare("

                        INSERT INTO realisateur (id_personne)
                        VALUES (:id_personne)

                    ");

                    $requeteAddRealisateur->bindParam(':id_personne', $id_personne);
                    $requeteAddRealisateur->execute();
                }

                header("Location: index.php?action=adminActeur");

                exit;

            } else {

                echo "Erreur : Veuillez vérifier les données saisies.";

            }

        }

        require "view/adminActeur.php";
    }
    
    /* --- ADMIN - DELETE ACTEUR --- */

    public function deleteActeur() {
        $pdo = Connect::seConnecter();
        $id_acteur = filter_input(INPUT_POST, 'id_acteur', FILTER_VALIDATE_INT);
    
        if ($id_acteur) {
            // Step 1: Retrieve the id_personne associated with the id_acteur
            $requetePersonne = $pdo->prepare("
                SELECT id_personne 
                FROM acteur 
                WHERE id_acteur = :id_acteur
            ");
            $requetePersonne->bindParam(':id_acteur', $id_acteur);
            $requetePersonne->execute();
            $id_personne = $requetePersonne->fetchColumn();
    
            if ($id_personne) {
                // Step 2: Delete the id_personne from the personne table
                $requeteSuppPersonne = $pdo->prepare("
                    DELETE FROM personne
                    WHERE id_personne = :id_personne
                ");
                $requeteSuppPersonne->bindParam(':id_personne', $id_personne);
                $requeteSuppPersonne->execute();
            }
    
            // Step 3: Delete the id_acteur from the acteur table
            $requeteSuppActeur = $pdo->prepare("
                DELETE FROM acteur
                WHERE id_acteur = :id_acteur
            ");
            $requeteSuppActeur->bindParam(':id_acteur', $id_acteur);
            $requeteSuppActeur->execute();
    
            header("Location: index.php?action=adminActeur");
            exit;
        }
    
        require "view/adminActeur.php";
    }

    /* --- ADMIN - EDITE ACTEUR --- */
    
    public function editActeur() {

        $pdo = Connect::seConnecter();
        $id_acteur = filter_input(INPUT_POST, 'id_acteur', FILTER_VALIDATE_INT);
        $new_nom = filter_input(INPUT_POST, 'new_nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_prenom = filter_input(INPUT_POST, 'new_prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_sexe = filter_input(INPUT_POST, 'new_sexe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_dateNaissance = filter_input(INPUT_POST, 'new_dateNaissance', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_role = filter_input(INPUT_POST, 'new_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    if ($id_acteur && $new_nom && $new_prenom && $new_sexe && $new_dateNaissance && $new_role) {
        $requeteUpdatePersonne = $pdo->prepare("
            UPDATE personne p
            INNER JOIN acteur a ON p.id_personne = a.id_personne
            SET p.nom = :new_nom, p.prenom = :new_prenom, p.sexe = :new_sexe, p.dateNaissance = :new_dateNaissance
            WHERE a.id_acteur = :id_acteur
        ");
        $requeteUpdatePersonne->bindParam(':new_nom', $new_nom);
        $requeteUpdatePersonne->bindParam(':new_prenom', $new_prenom);
        $requeteUpdatePersonne->bindParam(':new_sexe', $new_sexe);
        $requeteUpdatePersonne->bindParam(':new_dateNaissance', $new_dateNaissance);
        $requeteUpdatePersonne->bindParam(':id_acteur', $id_acteur);
        $requeteUpdatePersonne->execute();

    if ($new_role === 'acteur' || $new_role === 'les deux') {
        $requeteUpdateActeur = $pdo->prepare("
            INSERT INTO acteur (id_personne)
            SELECT p.id_personne
            FROM personne p
            WHERE p.id_personne = (
                SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
            )
            ON DUPLICATE KEY UPDATE id_personne = VALUES(id_personne)
        ");
        $requeteUpdateActeur->bindParam(':id_acteur', $id_acteur);
        $requeteUpdateActeur->execute();
    } else {
        $requeteDeleteActeur = $pdo->prepare("
            DELETE FROM acteur
            WHERE id_acteur = :id_acteur
        ");
        $requeteDeleteActeur->bindParam(':id_acteur', $id_acteur);
        $requeteDeleteActeur->execute();
    }

    if ($new_role === 'realisateur' || $new_role === 'les deux') {
        $requeteUpdateRealisateur = $pdo->prepare("
            INSERT INTO realisateur (id_personne)
            SELECT p.id_personne
            FROM personne p
            WHERE p.id_personne = (
                SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
            )
            ON DUPLICATE KEY UPDATE id_personne = VALUES(id_personne)
        ");
        $requeteUpdateRealisateur->bindParam(':id_acteur', $id_acteur);
        $requeteUpdateRealisateur->execute();
    } else {
        $requeteDeleteRealisateur = $pdo->prepare("
            DELETE FROM realisateur
            WHERE id_personne = (
                SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
            )
        ");
        $requeteDeleteRealisateur->bindParam(':id_acteur', $id_acteur);
        $requeteDeleteRealisateur->execute();
    }

    header("Location: index.php?action=adminActeur");

    exit;

    }

    require "view/adminActeur.php";
    }

    /* ------ ADMIN REALISATEUR------ */

    public function adminRealisateur() {
        $pdo = Connect::seConnecter();
        $requete = $pdo->query("

            SELECT id_realisateur, prenom, nom
        FROM realisateur re, personne p
        WHERE re.id_personne = p.id_personne
        ORDER BY nom

        ");

    require "view/adminRealisateur.php";

    }

    /* --- ADMIN - DELETE ACTEUR --- */

    public function deleteRealisateur() {
        $pdo = Connect::seConnecter();
        $id_realisateur = filter_input(INPUT_POST, 'id_realisateur', FILTER_VALIDATE_INT);
    
        if ($id_realisateur) {
            // Step 1: Retrieve the id_personne associated with the id_acteur
            $requetePersonne = $pdo->prepare("
                SELECT id_personne 
                FROM realisateur 
                WHERE id_realisateur = :id_realisateur
            ");
            $requetePersonne->bindParam(':id_realisateur', $id_realisateur);
            $requetePersonne->execute();
            $id_personne = $requetePersonne->fetchColumn();
    
            if ($id_personne) {
                // Step 2: Delete the id_personne from the personne table
                $requeteSuppPersonne = $pdo->prepare("
                    DELETE FROM personne
                    WHERE id_personne = :id_personne
                ");
                $requeteSuppPersonne->bindParam(':id_personne', $id_personne);
                $requeteSuppPersonne->execute();
            }
    
            // Step 3: Delete the id_acteur from the acteur table
            $requeteSuppActeur = $pdo->prepare("
                DELETE FROM realisateur
                WHERE id_realisateur = :id_realisateur
            ");
            $requeteSuppActeur->bindParam(':id_realisateur', $id_realisateur);
            $requeteSuppActeur->execute();
    
            header("Location: index.php?action=adminActeur");
            exit;
        }
    
        require "view/adminRealisateur.php";
    }

    /* --- ADMIN - EDITE ACTEUR --- */
    
    public function editRealisateur() {
        $pdo = Connect::seConnecter();
        $id_acteur = filter_input(INPUT_POST, 'id_acteur', FILTER_VALIDATE_INT);
        $new_nom = filter_input(INPUT_POST, 'new_nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_prenom = filter_input(INPUT_POST, 'new_prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_sexe = filter_input(INPUT_POST, 'new_sexe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_dateNaissance = filter_input(INPUT_POST, 'new_dateNaissance', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $new_role = filter_input(INPUT_POST, 'new_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        if ($id_acteur && $new_nom && $new_prenom && $new_sexe && $new_dateNaissance && $new_role) {
            $requeteUpdatePersonne = $pdo->prepare("
                UPDATE personne p
                INNER JOIN acteur a ON p.id_personne = a.id_personne
                SET p.nom = :new_nom, p.prenom = :new_prenom, p.sexe = :new_sexe, p.dateNaissance = :new_dateNaissance
                WHERE a.id_acteur = :id_acteur
            ");
            $requeteUpdatePersonne->bindParam(':new_nom', $new_nom);
            $requeteUpdatePersonne->bindParam(':new_prenom', $new_prenom);
            $requeteUpdatePersonne->bindParam(':new_sexe', $new_sexe);
            $requeteUpdatePersonne->bindParam(':new_dateNaissance', $new_dateNaissance);
            $requeteUpdatePersonne->bindParam(':id_acteur', $id_acteur);
            $requeteUpdatePersonne->execute();
    
            if ($new_role === 'acteur' || $new_role === 'les deux') {
                $requeteUpdateActeur = $pdo->prepare("
                    INSERT INTO acteur (id_personne)
                    SELECT p.id_personne
                    FROM personne p
                    WHERE p.id_personne = (
                        SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
                    )
                    ON DUPLICATE KEY UPDATE id_personne = VALUES(id_personne)
                ");
                $requeteUpdateActeur->bindParam(':id_acteur', $id_acteur);
                $requeteUpdateActeur->execute();
            } else {
                $requeteDeleteActeur = $pdo->prepare("
                    DELETE FROM acteur
                    WHERE id_acteur = :id_acteur
                ");
                $requeteDeleteActeur->bindParam(':id_acteur', $id_acteur);
                $requeteDeleteActeur->execute();
            }
    
            if ($new_role === 'realisateur' || $new_role === 'les deux') {
                $requeteUpdateRealisateur = $pdo->prepare("
                    INSERT INTO realisateur (id_personne)
                    SELECT p.id_personne
                    FROM personne p
                    WHERE p.id_personne = (
                        SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
                    )
                    ON DUPLICATE KEY UPDATE id_personne = VALUES(id_personne)
                ");
                $requeteUpdateRealisateur->bindParam(':id_acteur', $id_acteur);
                $requeteUpdateRealisateur->execute();
            } else {
                $requeteDeleteRealisateur = $pdo->prepare("
                    DELETE FROM realisateur
                    WHERE id_personne = (
                        SELECT id_personne FROM acteur WHERE id_acteur = :id_acteur
                    )
                ");
                $requeteDeleteRealisateur->bindParam(':id_acteur', $id_acteur);
                $requeteDeleteRealisateur->execute();
            }
    
            header("Location: index.php?action=adminActeur");
            exit;
        }
    
        require "view/adminActeur.php";
    }

    /* ------ ADMIN FILM ------ */

    public function adminFilm() {
        $pdo = Connect::seConnecter();
        $requete = $pdo->query("
            SELECT a.id_acteur, p.prenom, p.nom
            FROM acteur a
            INNER JOIN personne p ON a.id_personne = p.id_personne
            ORDER BY p.nom ASC
        ");
        require "view/adminFilm.php";
    }
    



}
