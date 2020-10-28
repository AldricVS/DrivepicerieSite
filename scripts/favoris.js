function sauvegarderFavoris(){
    //d'abord, on créé un tableau de correspondance entre l'id de l'article et si il a été choisi comme favori
    // clé = id, valeur = booléen
    var favori = [];
    var listeProduits = document.getElementById("liste-articles").children;
    for(var i = 0; i < listeProduits.length; i++){
        var produitId = listeProduits[i].id.split('-')[2];
        var favoriCheckbox = listeProduits[i].getElementsByClassName("favori")[0];
        favori[produitId] = favoriCheckbox.checked;
    }
}