/**
 * Script utilisé dans la page liste-commandes.php
 */

 /**
  * Demande à l'utilisateur si il veut vraiment supprimer sa commande en lui indiquant la date
  * @param {HTMLButtonElement} bouton le bouton qui a lancé cette action
  */
function annulerCommande(bouton){
    //on cherche la date de la commande
    var commandeDiv = bouton.closest(".commande");
    var dateCommande = commandeDiv.getElementsByTagName("span")[1].innerHTML;
    //si l'utilisateur met "non" ou ferme le pop-up, alors on empeche le formulaire de s'exécuter (en renvoyant "false")
    return confirm("Voulez-vous vraiment annuler la commande " + dateCommande +  " ?\nCette action sera irréversible.");
}