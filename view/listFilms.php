<?php ob_start(); ?>

<div class="container_form">
    <form action="index.php?action=addNouveauFilm" method="post">

        <!-- Titre du film -->
        <div class="form-group">
            <label for="titreFilm">Titre du film :</label>
            <input required="required" type="text" id="titreFilm" name="titreFilm" />
        </div>

        <!-- Durée du film -->
        <div class="form-group">
            <label for="dureeFilm">Durée du film (en minutes) :</label>
            <input required="required" type="number" id="dureeFilm" name="dureeFilm" />
        </div>

        <!-- URL de l'image du film -->
        <div class="form-group">
            <label for="urlImageFilm">URL de l'image du film :</label>
            <input required="required" type="url" id="urlImageFilm" name="urlImageFilm" />
        </div>

        <!-- Synopsis du film -->
        <div class="form-group">
            <label for="synopsisFilm">Synopsis du film :</label>
            <textarea required="required" id="synopsisFilm" name="synopsisFilm"></textarea>
        </div>

        <!-- Note du film -->
        <div class="form-group">
            <label for="noteFilm">Note du film (sur 5) :</label>
            <input required="required" type="number" id="noteFilm" name="noteFilm" min="0" max="5" />
        </div>

        <!-- Bouton submit -->
        <button type="submit" class="btn btn-primary">Ajouter</button>

    </form>
</div>

<div class="container_listFilms">

    <div class="container_section_film">

        <h2 class="flex_text_film"><div id="rectangle_red"></div>Les films</h2>

        <div class="container_section_film">

            <?php foreach($requete->fetchAll() as $listFilm) { ?>

                <div class="container_section_film_requete">

                    <table>

                        <tr id="col">

                            <td class="films_text_titre">

                                <a href="index.php?action=detailFilm&id=<?= $listFilm['id_film']?>"> 

                                    <?= $listFilm["titre"] ?>

                                </a>

                            </td>

                            <td class="container_card">

                                <a href="./index.php?action=detailFilm&id=<?= $listFilm["id_film"] ?>">

                                    <img class="listFilm_img_url" src='<?= isset($listFilm["urlImage"]) && $listFilm["urlImage"] ? $listFilm["urlImage"] : '' ?>' alt="image">

                                </a>

                            </td>

                        </tr>

                    </table>

                </div>

            <?php } ?>

        </div>

    </div>

</div>


<?php

$titre   = "Film releases - Films";
$contenu = ob_get_clean();
require "template.php";
?>
