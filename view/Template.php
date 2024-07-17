<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="public\css\style.css"/>
    <title> <?= $titre ?> </title>
</head>

<body>

    <header">

        <div id="container_nav">

            <div class="container_titre">

            <h1> Film   <div class="color_logo">Releases</div> </h1>

            </div>

            <nav class="container_navbar">

                <ul>
                    <li> <a href="index.php?action=home">              Home         </a></li>
                    <li> <a href="index.php?action=listFilms">         Films        </a> </li>
                    <li> <a href="index.php?action=listActeurs">       Acteurs      </a> </li>
                    <li> <a href="index.php?action=listRealisateurs">  Réalisateurs </a> </li>
                    <li> <a href="index.php?action=listGenres">        Genres       </a> </li>
                </ul>

            </nav>

            <div class="container_sociaux">

            <a href="https://www.youtube.com/@KinoCheck.com" class="fa-brands fa-youtube"></a>
            <a href="https://www.youtube.com/@KinoCheck.com" class="fa-brands fa-x-twitter"></a>
            
            </div>

        </div>
    </header>

    <main>

        <div class="container_img">
            <img src="https://desvr1.com/resources/template_groups/1088.png?v=1011" alt="image">
        </div>
            
    <?= $contenu ?>

    </main>

    <footer>

        <small>Copyright &copy; 2024 | VehinysCompagny | Tous droits réservés.</small>
        <p>About Us | Contact us | Privacy Policy </p> 

    </footer>

</body>

</html>