<?php ob_start(); ?>



<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date de Naissance</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Créer un formatteur de date pour la locale française
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter->setPattern('d MMMM yyyy');
        
        // Récupérer et afficher tous les acteurs
        foreach ($requete->fetchAll() as $acteur) {
            // Convertir la date de naissance en timestamp
            $timestamp = strtotime($acteur["dateNaissance"]);
            // Formater la date de naissance en français
            $dateNaissance = $formatter->format($timestamp);
        ?>
            <tr>
                <td><?= htmlspecialchars($acteur["nom"]) ?></td> <!-- récupérer et afficher le nom de l'acteur -->
                <td><?= htmlspecialchars($acteur["prenom"]) ?></td> <!-- récupérer et afficher le prénom de l'acteur -->
                <td><?= htmlspecialchars($dateNaissance) ?></td> <!-- afficher la date de naissance formatée -->
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
$titre = "Film releases - Realisateurs";
$contenu = ob_get_clean();
require "view/template.php";