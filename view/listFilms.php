<?php ob_start(); ?>

<div class="container_listFilms">

    <div class="container_section_film">

        <h2 class="flex_text_film">
            <div id="rectangle_red"></div>Les films
        </h2>

        <div class="container_section_film">
            <?php foreach ($requete->fetchAll() as $listFilm) { ?>
                <div class="container_section_film_requete">
                    <table>
                        <tr id="col">
                            <td class="films_text_titre">
                                <a href="index.php?action=detailFilm&id=<?= htmlspecialchars($listFilm['id_film']) ?>"> 
                                    <?= htmlspecialchars($listFilm["titre"]) ?>
                                </a>
                            </td>
                            <td class="container_card">
                                <a href="index.php?action=detailFilm&id=<?= htmlspecialchars($listFilm["id_film"]) ?>">
                                    <img class="listFilm_img_url" src='<?= htmlspecialchars(isset($listFilm["urlImage"]) && $listFilm["urlImage"] ? $listFilm["urlImage"] : '') ?>' alt="image">
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<div class="container_form">
    <form action="index.php?action=addNouveauFilm" method="post">
        <div class="form-group">
            <label for="titreFilm">Titre du film :</label>
            <input required type="text" id="titreFilm" name="titreFilm" />
        </div>

        <div class="form-group">
            <label for="dateSortieFilm">Année du film :</label>
            <input required type="date" id="dateSortieFilm" name="dateDeSortieEnFrance" min="1800-01-01" max="<?= date('Y-m-d'); ?>" />
        </div>

        <div class="form-group">
            <label for="dureeFilm">Durée du film (en minutes) :</label>
            <input required type="number" id="dureeFilm" name="dureeFilm" />
        </div>

        <div>
            <label for="realisateur">Réalisateur :</label>
            <select id="realisateur" name="id_realisateurFilm">
                <?php foreach ($requeteReal->fetchAll() as $realisateur): ?>
                    <option value="<?= htmlspecialchars($realisateur['id_realisateur']); ?>"> 
                        <?= htmlspecialchars($realisateur['realisateur']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="urlImageFilm">URL de l'image du film :</label>
            <input required type="url" id="urlImageFilm" name="urlImageFilm" />
        </div>

        <div class="form-group">
            <label for="synopsisFilm">Synopsis du film :</label>
            <textarea required id="synopsisFilm" name="synopsisFilm"></textarea>
        </div>

        <div class="form-group">
            <label for="noteFilm">Note du film (sur 5) :</label>
            <input required type="number" id="noteFilm" name="noteFilm" min="0" max="5" />
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>


<button> <a href="index.php?action=adminFilm"> Admin </a> </button>

<?php

$titre   = "Film releases - Films";
$contenu = ob_get_clean();
require "template.php";
?>
