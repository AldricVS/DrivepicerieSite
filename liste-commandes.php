<?php
session_start();
require_once("includes/bdd.php");
include_once("includes/misc.php");
if (!isset($_SESSION["utilisateur"])) {
    header("Location: index.php");
}

$tabCommandes = chercherCommandes($_SESSION["utilisateur"]);
$nbreCommandes = 0;
if(!is_null($tabCommandes)){
    $nbreCommandes = count($tabCommandes);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Drivepicerie - Commandes de <?= $_SESSION["utilisateur"] ?></title>
    <meta charset="utf-8" />
    <meta name="description" content="Page affichant les commandes réalisées par l'utlisateur et en attente" />
    <link rel="stylesheet" href="style/bootstrap.min.css" />
    <link rel="stylesheet" href="style/reset.css" />
    <link rel="stylesheet" type="text/css" href="style/style.css" />
    <link rel="stylesheet" type="text/css" href="style/style-commande.css" />
</head>

<body>
    <header>

        <div id="user">
            <ul>
                <li><a id="commander" href="index.php">Passer une commande</a></li>
                <li><a id="deconnexion" href="deconnexion.php">Déconnexion</a></li>
            </ul>

        </div>

        <div id="main-title">
            <a href="index.php" style="color: white;">
                <h1>Drivepicerie</h1>
                <h2>Faites vos courses autrement</h2>
            </a>
        </div>

    </header>

    <!--<div id="pop-up" class="pop-up pop-up-background hidden">
        <div class="pop-up pop-up-foreground">
            <svg onclick="fermerPopUp();" class="fermer-popup hidden" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewbox="0 0 100 100">
                <line x1="0" y1="0" x2="100" y2="100" stroke="white" stroke-width="10" />
                <line x1="100" y1="0" x2="0" y2="100" stroke="white" stroke-width="10" />
            </svg>

            <div class="loading"></div>

            <p class="contenu hidden">

            </p>
        </div>
    </div>-->

    <main>
        <section>
            <?php if($tabCommandes == NULL):?>
            <h2>Il n'y a aucune commande passé par <?= $_SESSION["utilisateur"] ?> <a href="index.php">Pour l'instant</a></h2>
            <?php else:?>
            <h2>Nombre de commandes en cours de <?= $_SESSION["utilisateur"] ?> : <?=$nbreCommandes?></h2>
            <div id="commandes-liste">
                <?php for ($i=0; $i < $nbreCommandes; $i++):
                            $commande = $tabCommandes[$i];
                ?>
                <div class="commande" id="commande">
                    <div class="commande-resume">
                        <span>pour le <?=formaliserDate($commande["date_recuperation_commande"])?></span>
                        <span>faite le <?=formaliserDate($commande["date_finalisation_commande"])?></span>
                        <span>Prix total de la commande : <?=$commande["prix_total_commande"]?>€</span>
                        <span>Nombre de produits : <?=$commande["nombre_produit_commande"]?></span>
                        <form action="supprimer-commande.php" method="POST">
                            <input type="hidden" name="id-commande" value="<?=$commande["id_commande"]?>"/>
                            <input type="hidden" name="date-finalisation" value="<?=$commande["date_recuperation_commande"]?>"/>
                            <button onclick="return annulerCommande(this);">Annuler commande</button>
                        </form>
                        
                    </div>
                    <ul class="commande-resume-detail">
                        <?php
                            $nombreProduitsDansCommande = count($commande["produits_commandes"]);
                            for($j = 0; $j < $nombreProduitsDansCommande; $j++):
                                $produitCommande = $commande["produits_commandes"][$j];
                        ?>
                        <li class="commande-resume-item">
                            <span><?=$produitCommande["nom_produit"]?></span>
                            <span><?=$produitCommande["quantite_commande"]?></span>
                            <span><?=$produitCommande["prix_total_commande"]?>€</span>
                        </li>
                        <?php endfor;?>
                    </ul>
                </div>
                <?php endfor;?>
            </div>
            <?php endif;?>
        </section>
    </main>

    <footer>

    </footer>


    <!--Librairies Javascript-->
    <script src="scripts/jquery-3.5.1.min.js"></script>
    <script src="scripts/pop-up.js"></script>
    <script src="scripts/commandes.js"></script>
</body>

</html>