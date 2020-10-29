<?php 
    $bdd = pg_connect("host=localhost dbname=drivepiceriebd user=drive_admin password=AlRaMa311621")
        or die("Connexion à la base de données impossible : " . pg_last_error());


    function chercherProduits($nomUtilisateur){
        global $bdd;
        /*
         * Nous cherchons à avoir tous les produits, mais aussi le prix promotionnel 
         * d'un produit si il existe. Nous voulons aussi avoir les favoris de l'utilisateur connecté.
         * Le tout en classant les réultats par nom
         */
        $result = pg_query($bdd, 'SELECT prod.id_produit, nom_produit, prix_produit, stock_total_produit, prix_promotion, f.nom_utilisateur FROM produit AS prod 
            LEFT OUTER JOIN promotion as prom ON prod.id_produit = prom.id_produit
			LEFT OUTER JOIN favori as f ON f.nom_utilisateur = \'' . $nomUtilisateur . '\' AND f.id_produit = prod.id_produit ORDER BY prod.nom_produit');
        if($result){
            //on transforme la requete rcupérée en tableau utilisable
            $tabProduits = pg_fetch_all($result);
            return $tabProduits;
        }else{
            return NULL;
        }
    }   
    
    function chercherCommandes($nomUtilisateur){
        global $bdd;

        //on récupère un tableau contenant les commandes
        $result = pg_query_params($bdd,
            "SELECT * FROM commande
            WHERE nom_utilisateur=$1",
            array(
                $nomUtilisateur
            ));
        //si $result == false, alors il n'y a eu aucun résultat
        if(pg_num_rows($result) < 1){
            return NULL;
        }else{
            
            $tabCommandes = pg_fetch_all($result);
            
            
            //pour chacune de ses commandes, on va lister dedans les produis commandés qui sont associés et on va garder une trace du prix total de la commande
            for ($i=0; $i < count($tabCommandes); $i++) { 
                $prixTotalCommande = 0.0;
                $resCommandeSpecifique = pg_query($bdd,
                    "SELECT pc.quantite_commande, pc.prix_total_commande, pr.nom_produit
                    FROM produit_commande as pc 
                    JOIN produit as pr ON pc.id_produit = pr.id_produit
                    WHERE id_commande=" . $tabCommandes[$i]["id_commande"]);
                
                $tabCommandes[$i]["produits_commandes"] = pg_fetch_all($resCommandeSpecifique);
                for ($j=0; $j < count($tabCommandes[$i]["produits_commandes"]); $j++) { 
                    $prixTotalCommande += floatval($tabCommandes[$i]["produits_commandes"][$j]["prix_total_commande"]);
                }
                $tabCommandes[$i]["prix_total_commande"] = $prixTotalCommande;
            }
            return $tabCommandes;
        }
    }
?>