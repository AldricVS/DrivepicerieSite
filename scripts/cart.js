/**
 * Gestion du panier d'achat
*/

const CART_OPENED_CLASS = "ouvert";
const CART_RELATIVE_CLASS = "cart-relative";
var cartList = document.getElementById("panier-liste");  
var cartIcon = document.getElementById("cart");

document.addEventListener("click", function(event){
    var isCartListOpened = cartList.classList.contains(CART_OPENED_CLASS);
    //on regarde si il a cliqué sur le panier
    if(event.target.parentNode == cartIcon){
        if(isCartListOpened){
            cartList.classList.remove(CART_OPENED_CLASS);
        }else{
            cartList.classList.add(CART_OPENED_CLASS);
        }
    }else if(!event.target.classList.contains(CART_RELATIVE_CLASS)){
        //si on clique dehors, on regarde si la liste est ouverte et on la ferme sinon
        if(isCartListOpened){
            cartList.classList.remove(CART_OPENED_CLASS);
        }
    }
    
});

//on récupère toutes les cartes d'article
var articles = document.getElementsByClassName("article-carte");

var articleQuantiteInputs = document.getElementsByClassName("article-quantite");

for (var i = 0; i < articleQuantiteInputs.length; i++) {
    var quantiteInput = articleQuantiteInputs[i];
    quantiteInput.onchange = function(event){
        var valeurQuantite = parseInt(event.target.value);
        /*si le nombre dans le champs est plus grand que le max, on ne fait rien*/
        if(valeurQuantite <= parseInt(event.target.max)){
            /*sinon, on va chercher dans le panier voir si l'article est déja référencé*/
            //on retrouve l'article en recherchant l'ancetre le plus proche ayant la classe "article-classe"
            var articleCourant = event.target.closest(".article-carte");
            //on récupère son numéro identifiant (id = "article-numId")
            var articleId = articleCourant.id.split('-')[1];
            var articleNom = articleCourant.getElementsByClassName("article-nom")[0].innerHTML;
            var articlePrix = articleCourant.getElementsByClassName("article-prix")[0].innerHTML;


            /*Si l'article est dans le panier, on met juste à jour ses informations, sinon on l'ajoute*/
            var panierUl = document.getElementById("panier-liste").children[0];
            var panierElts = panierUl.children;

            var articleTrouve = false;
            for (var i = 0; i< panierElts.length; i++) {
                var elt = panierElts[i];
                var id = elt.id.split('-')[2];
                if(articleId === id){
                    articleTrouve = true;
                    /*on modifie les infos (la quantité) de l'article en conséquence.
                     * si la quantité devient 0, on supprime cet article   
                     */
                    if(valeurQuantite === 0){
                        panierUl.removeChild(elt);
                    }else{
                        var quantiteSpan = elt.getElementsByClassName("panier-item-quantite")[0];
                        //on modifie le prix total
                        var prixSpan = elt.getElementsByClassName("panier-item-prix")[0];
                        var prix = parseFloat(prixSpan.innerHTML);
                        quantiteSpan.innerHTML = valeurQuantite;
                        
                        var prixTotalSpan = elt.getElementsByClassName("panier-item-total")[0];
                        prixTotalSpan.innerHTML = (prix * valeurQuantite).toFixed(2);
                    }
                    miseAJourPanier();
                    //plus besoin de chercher
                    break;
                }
            }

            if(!articleTrouve){
                ajouteArticleDansPanier(articleId, articleNom, articlePrix, valeurQuantite);
                miseAJourPanier();
            }
            
        }
    }
}

function ajouteArticleDansPanier(id, nom, prix, quantite){
    if(quantite > 0){
        var panier = document.getElementById("panier-liste").children[0];

        var li = document.createElement("li");
        li.id = "panier-item-" + id;
        li.classList.add("cart-relative");
        li.innerHTML = "<span class=\"panier-item-nom\">" + nom +
                         "</span> - quantité : <span class=\"panier-item-quantite\">" + quantite +
                             "</span> - prix : <span class=\"panier-item-prix\">" + prix + "</span>€ - prix total : <span class=\"panier-item-total\">" + (parseFloat(prix) * parseFloat(quantite)).toFixed(2)  + "</span> €";
        panier.appendChild(li);
    }
}

/**
 * Met à jour le prix total et affiche (ou non) le bouton pour effectuer la commande
 */
function miseAJourPanier(){
    var panierPrixTotalSpan = document.getElementById("panier-prix-total");
    var panierListe = document.getElementById("panier-liste").children[0].children;

    var prixTotal = 0.0;
    for(var i = 1; i < panierListe.length; i++){
        var prixTotalItemSpan = panierListe[i].getElementsByClassName("panier-item-total")[0];
        var prixTotalItem = prixTotalItemSpan.innerHTML;
        prixTotal = (parseFloat(prixTotal) + parseFloat(prixTotalItem)).toFixed(2);
    }

    panierPrixTotalSpan.innerHTML = prixTotal;
}

/**
 * Vide tous les champs "quantité" des produits et vide le panier
 */
function viderQuantite(){
    //on enlève tous les produits du panier
    var panierListe = document.getElementById("panier-liste").children[0];
    while(panierListe.children.length > 1){
        console.log(panierListe.lastChild);
        panierListe.removeChild(panierListe.lastChild);
    }
    //on remet le prix total à 0 et on cache cet element
    document.getElementById("panier-prix-total").innerHTML = "0";
    panierListe.children[0].classList.add("hidden");

    //on met à 0 toutes les quantités des cartes article
    var produitsListe = document.getElementsByClassName("liste-articles")[0].children;
    for (let i = 0; i < produitsListe.length; i++) {
        produitsListe[i].getElementsByClassName("article-quantite")[0].value = 0;
    }

}

/**
 * Récupère les infos du panier et fait un requête ajax pour pouvoir communiquer avec la base de données.
 */
function envoyerPanier(){
    var panier = [];

    var panierListe = document.getElementById("panier-liste").children[0].children;
    //le premier element ne compte pas, il s'agit du récapitulatif 
    for(var i = 1; i < panierListe.length; i++){
        panier[i - 1] = [];
        var article = panierListe[i];
        //l'id du li est "panier-liste-[vrai id de l'article]"
        var idArticle = article.id.split('-')[2];
        var nomArticle = article.getElementsByClassName("panier-item-nom")[0].innerHTML;
        var quantiteArticle = parseInt(article.getElementsByClassName("panier-item-quantite")[0].innerHTML);
    
        //on met le tout dans une ligne du tableeau
        panier[i - 1].idArticle = idArticle;
        panier[i - 1].nomArticle = nomArticle;
        panier[i - 1].quantiteArticle = quantiteArticle;
    }

    //on s'occupe d'afficher le chargement 
    apparitionPopUp();
    $.post("commande.php", {panier: panier}, function(data){
        apparitionTextePopUp(data);
        
        //si la commande à été bien effectuée, on vide tous les champs au passage
        if(data == "Commande réalisée avec succès"){
            console.log("vider champs");
            viderQuantite();
        }
    });

    
}