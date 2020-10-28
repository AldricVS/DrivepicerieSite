/**
 * Gestion de la liste des articles sur la page (tri, favoris, etc)
 */

 /**
  * Trie les cartes avec leur nom
  * @param {boolean} estCroissant si on doit trier par ordre croissant ou décroissant
  */
function triAlphabetique(estCroissant = true){
    var articlesListe = document.getElementsByClassName("liste-articles")[0];
    var elements = articlesListe.children;
    
    elements = Array.prototype.slice.call(elements, 0);
    elements.sort(function(a, b){
        var nomA = a.getElementsByClassName("article-nom")[0].innerHTML.toLowerCase();
        var nomB = b.getElementsByClassName("article-nom")[0].innerHTML.toLowerCase();
        if(estCroissant){
            return nomA <= nomB ? -1 : 1;
        }else{
            return nomA > nomB ? -1 : 1;
        }
    });

    articlesListe.innerHTML = "";
    for (var i = 0; i < elements.length; i++) {
        articlesListe.appendChild(elements[i]);
    }
}

/**
 * Trie les cartes avec leur prix. Trie par ordre croissant si aucun argument n'est passé
 * @param {boolean} estCroissant si on doit trier par ordre croissant ou décroissant
 */
function triPrix(estCroissant = true){
    var articlesListe = document.getElementsByClassName("liste-articles")[0];
    var elements = articlesListe.children;
    
    elements = Array.prototype.slice.call(elements, 0);
    elements.sort(function(a, b){
        var nomA = a.getElementsByClassName("article-prix")[0].innerHTML;
        var nomB = b.getElementsByClassName("article-prix")[0].innerHTML;
        if(estCroissant){
            return parseFloat(nomA) <= parseFloat(nomB) ? -1 : 1;
        }else{
            return parseFloat(nomA) > parseFloat(nomB) ? -1 : 1;
        }
    });

    articlesListe.innerHTML = "";
    for (var i = 0; i < elements.length; i++) {
        articlesListe.appendChild(elements[i]);
    }
}

/**
 * Place les favoris en haut de la page, par ordre alphabétique
 */
function triFavoris(){
    //on trie d'abors par ordre alphabétique
    triAlphabetique(true);

    //deux tableaux, l'un contenant les favoris, l'autre le reste
    var favoris = [];
    var nonFavoris = [];

    var articlesListe = document.getElementsByClassName("liste-articles")[0];
    var elements = articlesListe.children;
    elements = Array.prototype.slice.call(elements, 0);

    for(var i = 0; i < elements.length; i++){
        //si la case "favori" est cochée, on le met dans le tableau contenant les favoris, sinon, on le met dans l'autre
        var favoriCheckbox = elements[i].getElementsByClassName("favori")[0];
        if(favoriCheckbox.checked){
            favoris.push(elements[i]);
        }else{
            nonFavoris.push(elements[i]);
        }
    }

    //on vide la liste des articles et on ajoute dans l'ordre les favoris puis les non-favoris
    for(var i = 0; i < favoris.length; i++){
        articlesListe.appendChild(favoris[i]);
    }
    for(var i = 0; i < nonFavoris.length; i++){
        articlesListe.appendChild(nonFavoris[i]);
    }
}