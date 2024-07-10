<?php ob_start(); ?>

<form action="index.php?action=addNouveauFilm" method="post">
    <!-- édition du libellé du film -->
    <label for="nomFilm">Titre du film :</label><br>
    <input required="required" type="text" id="addFilm" name="titreFilm" /><br><br>
    
    <!-- ajout de la durée du film -->
    <label for="dureeFilm">Durée du film</label><br>
    <input required="required" type="number" id="dureeFilm" name="dureeFilm" /><br><br>
    
    <input type='submit' name='submit'>
</form>


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
$titre = "Film releases - Films";
$contenu = ob_get_clean();
require "template.php";
