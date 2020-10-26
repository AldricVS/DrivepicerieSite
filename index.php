<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Drivepicerie - Page principale</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style/bootstrap.min.css">
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">

</head>

    <body>
        <header>
        
            <div id="user">
                <ul>
                    <li><a id="utlisateur" href="utilisateur.php">Liste des commandes</a></li>
                    <li><a id="utlisateur" href="utilisateur.php">Déconnexion</a></li>
                </ul>
                
            </div>

            <div id="main-title">
                <a href="index.php" style="color: white;">
                    <h1>Drivepicerie</h1>
                    <h2>Faites vos courses autrement</h2>
                </a>
            </div>

            <div id="cart" class="cart-relative">
                <img src="imgs/shopping-cart.png" class="cart-relative" alt="icone panier"/></br>
                <span class="cart-relative">Accéder au panier</span>
            </div>
        </header>

        <div id="panier-liste" class="cart-relative">
            <ul class="cart-relative">
                
                <li class="cart-relative">
                    <p class="cart-relative">Prix total : <span id="panier-prix-total">0.00</span>€</p></br>
                    <button type="button" class="cart-relative" onclick="envoyerPanier();">Effectuer la commande</button>
                </li>
            </ul>
              
        </div>

        <main>
            <section>
                <h2>Liste des articles</h2>
                <div id="articles-tri">
                    <p>Trier par :</p>
                    <button type="button" onclick="triAlphabetique(true);">Nom (A-Z)</button>
                    <button type="button" onclick="triAlphabetique(false);">Nom (Z-A)</button>
                    <button type="button" onclick="triPrix(true);">Prix (croissant)</button>
                    <button type="button" onclick="triPrix(false);">Prix (décroissant)</button>
                    <button type="button" onclick="triFavoris();">Favoris en premier</button>
                </div>
                <div><button type="button">Sauvegarder les favoris</button></div>
                <div class="liste-articles">
                <?php
                    //liste articles triées par nom
                    for ($i=0; $i < 22; $i++) : 
                ?>
                
                    <div class="article-carte" id="article-<?=$i?>">
                        <input class="favori" type="checkbox" id="fav-article-<?=$i?>"/>
                        <label for="fav-article-<?=$i?>"></label>
                        <p class="article-nom"><?=$i?>Nom</p>
                        <div><span class="article-prix"><?= $i?>.50</span>€ (<span class="promo">promotion</span>)</div>
                        <div>
                            <p>J'en veux</p>
                            <input class="article-quantite" type="number" value="0" min="0" max="10"/>
                        </div>
                    </div>
                
                <?php endfor;?>
                </div>
            </section>
        </main>

        <footer>

        </footer>


        <!--Librairies Javascript-->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="scripts/cart.js"></script>
        <script src="scripts/articles.js"></script>
    </body>

</html>