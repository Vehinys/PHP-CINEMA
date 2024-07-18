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
        $pdo = Connect::seConnecter();
    
        // Requête pour récupérer la liste des films avec leurs détails
        $requete = $pdo->query("

            SELECT f.id_film, titre, YEAR(dateDeSortieEnFrance) as 'year', CONCAT(prenom, ' ', nom) as 'realisateur', f.id_realisateur, f.urlImage
            FROM film f
            LEFT JOIN realisateur re ON f.id_realisateur = re.id_realisateur
            LEFT JOIN personne p ON re.id_personne = p.id_personne
            ORDER BY titre ASC;

        ");
    
        // Requête pour récupérer la liste des roles pour la liste déroulante
        $requeteRole = $pdo->query("

            SELECT  r.nomRole, r.id_role,f.titre
            FROM film f 
            INNER JOIN casting c ON c.id_film = f.id_film
            INNER JOIN role r ON	r.id_role = c.id_role
            
        ");
    
        // Requête pour récupérer la liste des acteurs pour la liste déroulante
        $requeteAct = $pdo->query("

            SELECT CONCAT(p.nom, ' ', p.prenom) as acteur, id_acteur
            FROM acteur a
            INNER JOIN personne p ON p.id_personne = a.id_personne;

        ");
    
        // Inclusion de la vue qui affiche la liste des films
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

        SELECT p.id_personne, a.id_acteur, CONCAT(prenom, ' ', nom) as 'acteur', prenom, nom,DATE_FORMAT(dateNaissance, '%d/%m/%Y') AS naissance, sexe, TIMESTAMPDIFF(YEAR, dateNaissance, CURDATE()) AS age 
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

/* ---------------------------------- ADMIN ---------------------------------- */


/* ----------------------- ADMIN GENRE ----------------------- */

/* Affiche la page d'administration des genres avec la liste des genres */

public function adminGenre() {
    $pdo = Connect::seConnecter();
    $requete = $pdo->query("

        SELECT g.id_genre, libelle
        FROM genre g 
        LEFT JOIN film_genres fg ON g.id_genre = fg.id_genre
        GROUP BY g.id_genre
        ORDER BY libelle

    ");

    require "view/adminGenre.php";
}

/* -------------------- ADMIN - ADD GENRE -------------------- */

/* Ajoute un nouveau genre à la base de données */

public function addNouveauGenre() {
    $pdo = Connect::seConnecter();

    $libelle = filter_input(INPUT_POST, 'addGenre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST["submit"])) {
        $requeteAjoutGenre = $pdo->prepare("

            INSERT INTO genre (libelle)
            VALUES (:libelle)

        ");

        $requeteAjoutGenre->bindParam(':libelle', $libelle);
        $requeteAjoutGenre->execute();

        header("Location: index.php?action=adminGenre");
    }
}

/* -------------------- ADMIN - EDIT GENRE -------------------- */

/* Modifie le libellé d'un genre existant */

public function editGenre($id, $new_libelle) {
    $pdo = Connect::seConnecter();

    $id_genre = filter_var($id, FILTER_VALIDATE_INT);
    $new_libelle = htmlspecialchars(trim($new_libelle), ENT_QUOTES, 'UTF-8');

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

        exit;

    } else {

        echo "ID de genre ou libellé invalide.";
    }
}

/* -------------------- ADMIN - DELETE GENRE -------------------- */

/* Supprime un genre de la base de données */

public function deleteGenre($id) {
    $pdo = Connect::seConnecter();

    $id_genre = filter_var($id, FILTER_VALIDATE_INT);

    if ($id_genre) {
        $requeteSuppression = $pdo->prepare("

            DELETE FROM genre
            WHERE id_genre = :id_genre

        ");

        $requeteSuppression->bindParam(':id_genre', $id_genre);
        $requeteSuppression->execute();

        header("Location: index.php?action=adminGenre");

        exit;

    } else {

        echo "ID de genre est invalide.";

    }
}


/* ----------------------- ADMIN ACTEUR ----------------------- */

 // Méthode pour afficher la vue d'administration des acteurs
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

// Méthode pour ajouter un nouvel acteur

public function addNouveauActeur() {
    $pdo = Connect::seConnecter();

    if (isset($_POST["submit"])) {
        // Récupération des données du formulaire
        $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
        $prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8');
        $sexe = htmlspecialchars($_POST['sexe'], ENT_QUOTES, 'UTF-8');
        $jour = filter_var($_POST['jour'], FILTER_SANITIZE_NUMBER_INT);
        $mois = filter_var($_POST['mois'], FILTER_SANITIZE_NUMBER_INT);
        $annee = filter_var($_POST['annee'], FILTER_SANITIZE_NUMBER_INT);
        
        // Formatage de la date de naissance
        $dateNaissance = "$annee-$mois-$jour";

        // Début de la transaction
        $pdo->beginTransaction();

        // 1. Insérer dans la table personne
        $requeteAjoutPersonne = $pdo->prepare("
            INSERT INTO personne (nom, prenom, sexe, dateNaissance)
            VALUES (:nom, :prenom, :sexe, :dateNaissance)
        ");
        $requeteAjoutPersonne->bindParam(':nom', $nom);
        $requeteAjoutPersonne->bindParam(':prenom', $prenom);
        $requeteAjoutPersonne->bindParam(':sexe', $sexe);
        $requeteAjoutPersonne->bindParam(':dateNaissance', $dateNaissance);
        $requeteAjoutPersonne->execute();
        
        // Récupérer l'id_personne généré
        $id_personne = $pdo->lastInsertId();

        // 2. Insérer dans la table acteur
        $requeteAjoutActeur = $pdo->prepare("
            INSERT INTO acteur (id_personne)
            VALUES (:id_personne)
        ");
        $requeteAjoutActeur->bindParam(':id_personne', $id_personne);
        $requeteAjoutActeur->execute();

        // Validation de la transaction
        $pdo->commit();

        // Redirection vers la page d'administration des acteurs
        header("Location: index.php?action=adminActeur");
        exit;
    }
}


// Méthode pour supprimer un acteur

public function deleteActeur($id) {
    $pdo = Connect::seConnecter();
    $id_acteur = filter_var($id, FILTER_VALIDATE_INT);

    if ($id_acteur) {
        $requeteSuppression = $pdo->prepare("
            DELETE FROM acteur
            WHERE id_acteur = :id_acteur
        ");
        $requeteSuppression->bindParam(':id_acteur', $id_acteur);
        $requeteSuppression->execute();

        header("Location: index.php?action=adminActeur");
        exit;
    } else {
        echo "ID d'acteur invalide.";
    }
}

// Méthode pour modifier un acteur

public function editActeur($id) {
    $pdo = Connect::seConnecter();

    // Récupération et validation de l'ID de l'acteur
    $id_acteur = filter_input(INPUT_POST, 'id_acteur', FILTER_VALIDATE_INT);
    
    // Vérification de la validité de l'ID
    if (!$id_acteur) {
        echo "ID d'acteur invalide.";
        return;
    }
    
    // Récupération des nouvelles données depuis le formulaire
    $new_nom = filter_input(INPUT_POST, 'new_nom', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_prenom = filter_input(INPUT_POST, 'new_prenom', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_sexe = filter_input(INPUT_POST, 'new_sexe', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_dateNaissance = $_POST['new_dateNaissance']; // Ajustez ce traitement selon vos besoins de validation

    // Préparation de la requête de mise à jour
    $requeteModification = $pdo->prepare("

        UPDATE personne p
        JOIN acteur a ON a.id_personne = p.id_personne
        SET nom = :new_nom , prenom = :new_prenom, sexe = :new_sexe, dateNaissance = :new_dateNaissance
        WHERE id_acteur = :id_acteur

    ");

    // Liaison des paramètres et exécution de la requête
    $requeteModification->bindParam(':new_nom', $new_nom);
    $requeteModification->bindParam(':new_prenom', $new_prenom);
    $requeteModification->bindParam(':new_sexe', $new_sexe);
    $requeteModification->bindParam(':new_dateNaissance', $new_dateNaissance);
    $requeteModification->bindParam(':id_acteur', $id_acteur);
    $requeteModification->execute();

    // Redirection après la modification
    header("Location: index.php?action=adminActeur");
    exit;
}


/* ----------------------- ADMIN REALISATEUR ----------------------- */

 // Méthode pour afficher la vue d'administration des acteurs
 public function adminRealisateur() {
    $pdo = Connect::seConnecter();
    $requete = $pdo->query("
        SELECT r.id_realisateur, p.prenom, p.nom
        FROM realisateur r
        INNER JOIN personne p ON r.id_personne = p.id_personne
        ORDER BY p.nom ASC
    ");
    require "view/adminRealisateur.php";
}

// Méthode pour ajouter un nouvel realisateur

public function addRealisateur() {
    $pdo = Connect::seConnecter();

    if (isset($_POST["submit"])) {

        // Récupération des données du formulaire

        $nom    = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $sexe   = $_POST['sexe'];
        $jour   = $_POST['jour'];
        $mois   = $_POST['mois'];
        $annee  = $_POST['annee'];
        
        // Formatage de la date de naissance
        $dateNaissance = "$annee-$mois-$jour";

        // Début de la transaction
        $pdo->beginTransaction();

        // 1. Insérer dans la table personne
        $requeteAjoutPersonne = $pdo->prepare("

            INSERT INTO personne (nom, prenom, sexe, dateNaissance)
            VALUES (:nom, :prenom, :sexe, :dateNaissance)
        ");

        $requeteAjoutPersonne->bindParam(':nom', $nom);
        $requeteAjoutPersonne->bindParam(':prenom', $prenom);
        $requeteAjoutPersonne->bindParam(':sexe', $sexe);
        $requeteAjoutPersonne->bindParam(':dateNaissance', $dateNaissance);
        $requeteAjoutPersonne->execute();
        
        // Récupérer l'id_personne généré
        $id_personne = $pdo->lastInsertId();

        // 2. Insérer dans la table acteur
        $requeteAjoutRealisateur = $pdo->prepare("
            INSERT INTO realisateur (id_personne)
            VALUES (:id_personne)
        ");
        $requeteAjoutRealisateur->bindParam(':id_personne', $id_personne);
        $requeteAjoutRealisateur->execute();

        // Validation de la transaction
        $pdo->commit();

        // Redirection vers la page d'administration des acteurs
        header("Location: index.php?action=adminRealisateur");

        exit;
    }
}

// Méthode pour supprimer un acteur

public function deleteRealisateur($id) {
    $pdo = Connect::seConnecter();
    $id_realisateur = filter_var($id, FILTER_VALIDATE_INT);

    if ($id_realisateur) {
        $requeteSuppression = $pdo->prepare("
            DELETE FROM realisateur
            WHERE id_realisateur = :id_realisateur
        ");
        $requeteSuppression->bindParam(':id_realisateur', $id_realisateur);
        $requeteSuppression->execute();

        header("Location: index.php?action=adminRealisateur");
        exit;
    } else {
        echo "ID d'acteur invalide.";
    }
}

// Méthode pour modifier un acteur

public function editRealisateur($id) {
    $pdo = Connect::seConnecter();

    // Récupération et validation de l'ID de l'acteur
    $id_realisateur = filter_input(INPUT_POST, 'id_realisateur', FILTER_VALIDATE_INT);
    
    // Vérification de la validité de l'ID
    if (!$id_realisateur) {
        echo "ID d'acteur invalide.";
        return;
    }
    
    // Récupération des nouvelles données depuis le formulaire
    $new_nom = filter_input(INPUT_POST, 'new_nom', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_prenom = filter_input(INPUT_POST, 'new_prenom', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_sexe = filter_input(INPUT_POST, 'new_sexe', FILTER_SANITIZE_SPECIAL_CHARS);
    $new_dateNaissance = $_POST['new_dateNaissance']; // Ajustez ce traitement selon vos besoins de validation

    // Préparation de la requête de mise à jour
    $requeteModification = $pdo->prepare("

        UPDATE personne p
        JOIN realisateur r ON r.id_personne = p.id_personne
        SET nom = :new_nom , prenom = :new_prenom, sexe = :new_sexe, dateNaissance = :new_dateNaissance
        WHERE id_realisateur = :id_realisateur

    ");

    // Liaison des paramètres et exécution de la requête
    $requeteModification->bindParam(':new_nom', $new_nom);
    $requeteModification->bindParam(':new_prenom', $new_prenom);
    $requeteModification->bindParam(':new_sexe', $new_sexe);
    $requeteModification->bindParam(':new_dateNaissance', $new_dateNaissance);
    $requeteModification->bindParam(':id_realisateur', $id_realisateur);
    $requeteModification->execute();

    // Redirection après la modification
    header("Location: index.php?action=adminRealisateur");
    exit;
}

 public function adminFilm() {
    $pdo = Connect::seConnecter();
    $requete = $pdo->query("
        SELECT f.titre
        FROM film f
        ORDER BY f.titre ASC
    ");

    require "view/adminFilm.php";
}
public function addCasting() {
    $pdo = Connect::seConnecter();

    // Fetch actors
    $requeteActeurs = $pdo->query("
        SELECT a.id_acteur, CONCAT(p.prenom, ' ', p.nom) AS nom_complet
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ORDER BY nom_complet ASC
    ");
    $acteurs = $requeteActeurs->fetchAll();

    // Fetch films
    $requeteFilms = $pdo->query("
        SELECT id_film, titre
        FROM film
        ORDER BY titre ASC
    ");
    $films = $requeteFilms->fetchAll();

    // Fetch roles
    $requeteRoles = $pdo->query("
        SELECT id_role, description
        FROM role
        ORDER BY description ASC
    ");
    $roles = $requeteRoles->fetchAll();

    require "view/adminFilm.php";  // Adjust the view file path accordingly
}



}


