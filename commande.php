<?php
session_start();
//on ne veut pas afficher de messages d'erreurs php à l'utilisateur (à enlever si besoin de debugger)
error_reporting(E_ERROR | E_PARSE);
require_once("includes/bdd.php");
global $bdd;

if (!isset($_POST["panierJson"]) || !isset($_POST["date"]) || !isset($_SESSION["utilisateur"])) {

    echo "Vous n'êtes pas autorisés à être ici n'est-ce pas?";
    var_dump($_POST);
} else {
    $stringCommandeReussie = "Commande réalisée avec succès";
    $erreurs = "";

    //afin d'avoir un tableau d'objets transposé en PHP, on a du utiliser la méthode JSON.stringify en javascript
    //il faut donc le décoder avant de pouvoir l'utiliser 
    extract($_POST);
    $panier = json_decode($panierJson);

    //récupère le nombre de produits au total, au passage on vérifie si il y a assez de quantité pour chaque produit
    $nombreProduitsTotal = 0;
    for ($i = 0; $i < count($panier); $i++) {
        $idProduit = $panier[$i]->idProduit;
        $nomProduit = $panier[$i]->nomProduit;
        $quantiteProduit = $panier[$i]->quantiteProduit;
        $nombreProduitsTotal += intval($quantiteProduit);

        //on va vérifier si il y a assez de quantité pour ce produit
        $quantiteResult = pg_query_params(
            $bdd,
            "SELECT stock_total_produit FROM produit WHERE id_produit = $1",
            array($idProduit)
        );
        $quantiteEnStock = pg_fetch_result($quantiteResult, 0, 0);
        //si il n'y en a pas assez, il faut tout annuler et indiquer l'erreur au client
        if ($quantiteEnStock < $quantiteProduit) {
            $erreurs .= "Il n'y a que " . $quantiteEnStock . " de " . $nomProduit . " en stock.</br>";
        }
    }

    //si il y a assez de stock pour satisfaire la commande, on peut commencer à travailler vraiment sur la base de données
    if (empty($erreurs)) {

        //on créé une nouvelle commande pour l'utilisateur et on récupère l'id de la commande pour après
        $result = pg_query_params(
            $bdd,
            "INSERT INTO commande(date_recuperation_commande, nombre_produit_commande, nom_utilisateur) VALUES($1, $2, $3) RETURNING id_commande",
            array(
                $date,
                $nombreProduitsTotal,
                $_SESSION["utilisateur"]
            )
        );
        //si result == FALSE, alors il y a eu un problème lors de l'ajout de la commande    
        if (!$result) {
            $erreurs .= "Une erreur surprenante a eu lieu lors de la création de votre commande.\n";
        } else {
            $idCommande = pg_fetch_result($result, 0, 0);
            //Maintenant on s'amuse : on créé un produit_commandé pour chaque... produit commandé
            for ($i = 0; $i < count($panier); $i++) {
                $idProduit = $panier[$i]->idProduit;
                $quantiteProduit = $panier[$i]->quantiteProduit;
                $prixTotalProduit = $panier[$i]->prixTotal;
                $nomProduit = $panier[$i]->nomProduit;

                pg_query_params(
                    $bdd,
                    "INSERT INTO produit_commande(id_commande, id_produit, quantite_commande, prix_total_commande) VALUES($1, $2, $3, $4)",
                    array(
                        $idCommande,
                        $idProduit,
                        $quantiteProduit,
                        $prixTotalProduit
                    )
                );

                
            }
            //si tout est réussi on va enlever cette même quantité du stock total, 
            //sous peine d'avoir des clients qui ne pourront commander quelque chose qui n'existe pas
            for ($i = 0; $i < count($panier); $i++) {
                $idProduit = $panier[$i]->idProduit;
                $quantiteProduit = $panier[$i]->quantiteProduit;

                pg_query_params(
                    $bdd,
                    "UPDATE produit SET stock_total_produit = stock_total_produit - $1 WHERE id_produit = $2",
                    array(
                        $quantiteProduit,
                        $idProduit
                    )
                );
            }
            //la commande est maintenant réalisée
        }
    }


    if (empty($erreurs)) {
        echo $stringCommandeReussie;
    } else {
        echo $erreurs;
        echo "Commande annulée.";
    }
}


unset($_POST["panier"]);
unset($_POST["date"]);
