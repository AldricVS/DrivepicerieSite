/**
 * Fait apparaitre le pop-up informatif avec une icone de chargement
 */
function apparitionPopUp(){
    const HIDDEN_CLASS = "hidden";
    //de base, le pop up est affiché avec la croix et le texte de réponse cachés
    var popUp = $("#pop-up");
    popUp.removeClass(HIDDEN_CLASS);
    popUp.find(".loading").removeClass(HIDDEN_CLASS);
    popUp.find(".fermer-popup").addClass(HIDDEN_CLASS);
    popUp.find(".contenu").addClass(HIDDEN_CLASS);
}

/**
 * Fait disparaitre l'icone de chargement et fait aparaitre la croix et le texte à afficher à la place.
 * Le pop-up est normalement déja affiché lors de l'appel de cette fonction
 * @param {String} texte Le texte à afficher
 */
function apparitionTextePopUp(texte){
    const HIDDEN_CLASS = "hidden";

    var popUp = $("#pop-up");
    popUp.removeClass(HIDDEN_CLASS);
    popUp.find(".loading").addClass(HIDDEN_CLASS);
    popUp.find(".fermer-popup").removeClass(HIDDEN_CLASS);

    var contenu = popUp.find(".contenu");
    contenu.removeClass(HIDDEN_CLASS);
    contenu.html(texte);
}

/**
 * Ferme le pop-up. Utilisé lorsque l'utilisateur clique sur la croix du pop-up.
 */
function fermerPopUp(){
    const HIDDEN_CLASS = "hidden";
    
    var popUp = $("#pop-up");
    popUp.addClass(HIDDEN_CLASS);
}