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
?>