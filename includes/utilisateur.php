<?php
require_once("includes/bdd.php");

    function verifConnexion(){
        global $bdd;
        if(isset($_POST["conn-login"]) || isset($_POST["conn-password"])){
            $erreurs = "";
            $result = pg_query_params($bdd,
             "SELECT mot_de_passe_utilisateur FROM utilisateur WHERE nom_utilisateur=$1", array($_POST["conn-login"]));
            //si il y a une erreur dans la selection
            if(!$result){
                $erreurs .= "Il y a eu une erreur lors de la recherche\n";
            }else{
                //on vérfie si on a trouvé quelque chose
                if(pg_num_rows($result) > 0){
                    //on doit voir si le mdp est le bon
                    $mdpCrypte = pg_fetch_result($result, 0, 0);
                    if(password_verify($_POST["conn-password"], $mdpCrypte)){
                        //si c'est le cas, on indique que l'on est connecté
                        $_SESSION["utilisateur"] = $_POST["conn-login"];
                    }else{
                        $erreurs .= "Le mot de passe n'est pas le bon\n";
                    }
                }else{
                    $erreurs .= "Ce nom d'utilisateur n'existe pas\n";
                }
            }
            if(empty($erreurs)){
                header("Location: index.php");
            }else{
                return $erreurs;
            }
        }
    }

    function verifInscription(){ 
        global $bdd;
        if(isset($_POST["ins-login"]) && isset($_POST["ins-email"]) && isset($_POST["ins-password"]) && isset($_POST["ins-password-confirm"])){
            $erreurs = "";
            //on regarde si le mdp et la confirmation sont les mêmes
            if($_POST["ins-password"] === $_POST["ins-password-confirm"]){
                //on va ensuite chercher si il y a un utilisateur qui  a déja ce nom
                $result = pg_query_params($bdd,
                "SELECT Count(*) FROM utilisateur WHERE nom_utilisateur=$1", array($_POST["ins-login"]));
                if((int)pg_fetch_result($result, 0, 0) > 0){
                    $erreurs .= "Un utilisateur a déja ce nom\n";
                }else{

                    //on peut inscrire l'utilisateur et se connecter
                    $result = pg_query_params($bdd,
                    "INSERT INTO utilisateur(nom_utilisateur, mot_de_passe_utilisateur, email_utilisateur) VALUES($1, $2, $3)",
                     array(
                       $_POST["ins-login"],
                       password_hash($_POST["ins-password"], PASSWORD_BCRYPT),
                       $_POST["ins-email"]
                     ));

                    if(!$result){
                        $erreurs .= "La requête n'a pas pu aboutir.";
                    }else{
                        $_SESSION["utilisateur"] = $_POST["ins-login"];
                    }
                }
            }else{
                $erreurs .= "Le mot de passe et celui de confirmation ne sont pas les mêmes\n";
            }
            if(empty($erreurs)){
                header("Location: index.php");
            }else{
                return $erreurs;
            }
        }
    }

    /**
     * Permet d'avoir la date dans une semaine, formalisée afin d'être utilisée par l'élément input de HTML
     * (format yyyy-mm-dd, ex : 2020-10-29)
     */
    function dateDansUneSemaine(){
        return date("Y-m-d", strtotime("+7 days"));
    }

?>