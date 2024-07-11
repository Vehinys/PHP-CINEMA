<?php ob_start(); ?>

<div class="container_form">

    <form action="index.php?action=addNouveauFilm" method="post">
        
        <!-- édition du libellé du film -->

        <div>
            <label for="nomFilm">Titre du film :</label><br>

            <input required="required" type="text" id="titreFilm" class="styleFormFilm" name="titreFilm" /><br><br>
        </div>

        <!-- ajout de la durée du film -->

        <div>
            <label for="dureeFilm">Durée du film</label><br>

            <input required="required" type="number" id="dureeFilm" class="styleFormFilm" name="dureeFilm" /><br><br>
            
        </div>

        <!-- ajout de le realisateur du film -->

        <div>
            <label for="idRealisateur">Réalisateur :</label><br>

            <select required="required" id="idRealisateur" class="styleFormFilm" name="id_realisateurFilm">

                <?php foreach ($realisateurs as $realisateur): ?>

                    <option value="<?= $realisateur['id_realisateur']; ?>"> <?= htmlspecialchars($realisateur['nom']); ?> </option>

                <?php endforeach; ?>

            </select> <br><br>

        </div>

        <!-- ajout de l'url du film -->

        <div>

            <label for="urlFilm">url du film</label><br>

            <input required="required" type="url" id="urlFilm" class="styleFormFilm" name="urlImageFilm" /><br><br>

        </div>

        <!-- ajout du synopsis du film -->

        <div>

            <label for="synopsisFilm">Synopsis du film :</label><br>

            <textarea required="required" id="synopsisFilm" class="styleFormFilm" name="synopsisFilm"></textarea><br><br>

        </div>

        <!-- ajout de la note du film -->

        <div>

            <label for="noteFilm">Note du film (sur 5) :</label><br>

            <input required="required" type="number" id="noteFilm" class="styleFormFilm" name="noteFilm" min="0" max="5" /><br><br>

        </div>

        <!-- bouton submit -->

        <input type='submit' name='submit'>

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
