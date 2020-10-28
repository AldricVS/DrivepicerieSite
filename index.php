<?php
session_start();
require_once("includes/bdd.php");
require_once("includes/utilisateur.php");
//on cherche à savoir si l'utilisateur est connecté 
$estConnecte = isset($_SESSION["utilisateur"]);

$errConnexion;
$errInscription;
if (!$estConnecte) {
    $errConnexion = verifConnexion();
    $errInscription = verifInscription();
} else {
    $listeProduits = chercherProduits($_SESSION["utilisateur"]);
}
unset($_POST);
?>
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
                <li><a id="utlisateur" href="deconnexion.php">Déconnexion</a></li>
            </ul>

        </div>

        <div id="main-title">
            <a href="index.php" style="color: white;">
                <h1>Drivepicerie</h1>
                <h2>Faites vos courses autrement</h2>
            </a>
        </div>

        <div id="cart" class="cart-relative">
            <img src="imgs/shopping-cart.png" class="cart-relative" alt="icone panier" /></br>
            <span class="cart-relative">Accéder au panier</span>
        </div>
    </header>

    <div id="panier-liste" class="cart-relative">
        <ul class="cart-relative">

            <li class="cart-relative hidden" id="panier-liste-commande">
                <p class="cart-relative">Prix total : <span id="panier-prix-total">0.00</span>€</p></br>
                <div class="cart-relative">Commander pour le <input type="date" class="cart-relative" id="panier-date-commande" value="<?=dateDansUneSemaine()?>"/></div>
                <button onclick="envoyerPanier();" type="button" class="cart-relative" style="margin-top: 10px;">Effectuer la commande</button>
            </li>
        </ul>

    </div>

    <div id="pop-up" class="pop-up pop-up-background hidden">
        <div class="pop-up pop-up-foreground">
        <svg onclick="fermerPopUp();" class="fermer-popup hidden" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
            <line x1="0" y1="0" x2="100" y2="100" stroke="white" stroke-width="10" />
            <line x1="100" y1="0" x2="0" y2="100" stroke="white" stroke-width="10" />
        </svg>

            <div class="loading"></div>
            
            <p class="contenu hidden">
                Salut
            </p>
        </div>
    </div>

    <main>
        <section>

            <?php
            if ($estConnecte) :
            ?>

                <h2>Bienvenue <?= $_SESSION["utilisateur"] ?>, voici la liste des produits</h2>
                <div id="articles-tri">
                    <p>Trier par :</p>
                    <button type="button" onclick="triAlphabetique(true);">Nom (A-Z)</button>
                    <button type="button" onclick="triAlphabetique(false);">Nom (Z-A)</button>
                    <button type="button" onclick="triPrix(true);">Prix (croissant)</button>
                    <button type="button" onclick="triPrix(false);">Prix (décroissant)</button>
                    <button type="button" onclick="triFavoris();">Favoris en premier</button>
                </div>
                <div><button type="button" onclick="sauvegarderFavoris()">Sauvegarder les favoris</button></div>
                <div class="liste-articles" id="liste-articles">
                    <?php
                    //liste articles triées par nom
                    for ($i = 0; $i < count($listeProduits); $i++) :
                        $idProduitCourant = $listeProduits[$i]["id_produit"];
                        $nomProduitCourant = $listeProduits[$i]["nom_produit"];
                        $prixProduitCourant = $listeProduits[$i]["prix_produit"];
                        $quantiteProduitCourant = $listeProduits[$i]["stock_total_produit"];
                        $promotionProduitCourant = $listeProduits[$i]["prix_promotion"];
                        $estFavoriProduitCourant = !empty($listeProduits[$i]["nom_utilisateur"]);
                    ?>

                        <div class="article-carte" id="article-<?= $idProduitCourant ?>">
                            <input class="favori" type="checkbox" id="fav-article-<?= $idProduitCourant ?>" <?= ($estFavoriProduitCourant) ? "checked" : ""?>/>
                            <label for="fav-article-<?= $idProduitCourant ?>"></label>
                            <p class="article-nom"><?= $nomProduitCourant ?></p>

                            <?php if (is_null($promotionProduitCourant)) : ?>
                                <div><span class="article-prix"><?= $prixProduitCourant ?></span>€</div>
                            <?php else : ?>
                                <div><span class="article-prix"><?= $promotionProduitCourant ?></span>€ (avant : <?= $prixProduitCourant ?>€)</div>
                            <?php endif; ?>

                            <div>
                                <p>J'en veux</p>
                                <input class="article-quantite" type="number" value="0" min="0" max="<?=$quantiteProduitCourant?>" />
                            </div>
                        </div>

                    <?php endfor; ?>
                </div>

            <?php
            else :
            ?>
                <h2>Vous devez être connecté pour pouvoir faire une commande</h2>
                <div class="login">
                    <form class="login" action="index.php" method="POST">
                        <h3>Se connecter</h3>
                        <label for="conn-login">Identifiant</label>
                        <input type="text" name="conn-login" maxlength="30" required />

                        <label for="conn-password">Mot de passe</label>
                        <input type="password" name="conn-password" maxlength="70" required />

                        <button type="submit">Se connecter</button>
                        <?php
                        if (!empty($errConnexion)) {
                            echo "<span style='color:red;'>" . $errConnexion . "</span>";
                        }
                        ?>
                    </form>

                    <div class="ligne-vertical"></div>
                    <form class="login" action="index.php" method="POST">
                        <h3>Pas de compte, inscrivez-vous !</h3>
                        <label for="ins-login">Identifiant ou adresse e-mail</label>
                        <input type="text" name="ins-login" id="ins-login" required />

                        <label for="ins-email">Adresse e-mail</label>
                        <input type="email" name="ins-email" id="ins-email" maxlength="80" required />

                        <label for="ins-password">Mot de passe</label>
                        <input type="password" name="ins-password" id="ins-password" maxlength="70" required />

                        <label for="ins-password-confirm">Confirmer mot de passe</label>
                        <input type="password" name="ins-password-confirm" id="ins-password-confirm" maxlength="70" required />
                        <button type="submit">S'inscrire</button>
                        <?php
                        if (!empty($errInscription)) {
                            echo "<span style='color:red;'>$errInscription</span>";
                        }
                        ?>
                    </form>
                <?php
            endif;
                ?>
                </div>
        </section>
    </main>

    <footer>

    </footer>


    <!--Librairies Javascript-->
    <script src="scripts/jquery-3.5.1.min.js"></script>
    <script src="scripts/pop-up.js"></script>
    <script src="scripts/cart.js"></script>
    <script src="scripts/articles.js"></script>
</body>

</html>