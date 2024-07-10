<?php ob_start(); ?>

<div class="container_home">

    <div class="container_section_one">

        <h2 class="flex_text"><div id="rectangle_red"></div>Sortie de la semaine</h2>

        <div class="container_section">

            <?php foreach($requete->fetchAll() as $film) { ?>
                <div class="container_section_requete">

                <table>

                    <tr>

                        <a href="./index.php?action=detailFilm&id=<?= $film["id_film"] ?>"> <img class="img_url"  src='<?= isset($film["urlImage"]) && $film["urlImage"] ? $film["urlImage"] : '' ?>'alt="image"> </a>
                        <a href="./index.php?action=detailFilm&id=<?= $film["id_film"] ?>"> <?= $film["titre"] ?></a>
                        
                    </tr> 

                </table>

                </div>

            <?php } ?> 

        </div>

    </div>

    <div class="container_section_two">

        <h2 class="flex_text"><div id="rectangle_red"></div>Notre selection</h2>

        <div class="container_section">

            <?php foreach($requete2->fetchAll() as $film) { ?>
                <div class="container_section_requete">

                <table>

                    <tr>

                        <a href="./index.php?action=detailFilm&id=<?= $film["id_film"] ?>"> <img class="img_url"  src='<?= isset($film["urlImage"]) && $film["urlImage"] ? $film["urlImage"] : '' ?>'alt="image"> </a>                  
                        <a href="./index.php?action=detailFilm&id=<?= $film["id_film"] ?>"> <?= $film["titre"] ?> </a>
                        
                    </tr> 

                </table>

                </div>

            <?php } ?> 

        </div>

    </div>

<?php

$titre = "Film releases - home";
$contenu = ob_get_clean();

require "template.php";