/**
 * Created by Elodie Vianai on 21/04/2017.
 */

/********* GESTION DU SELECTED EN FONCTION DU CONTRAT (fonction anonyme : s'exécute à la fin de la page) ********/
$(function() {
    //#contrats = l'id du select et le val fait référence à la variable contrat (js fichier twig) et sa valeur (php fichier twig)
    $("#contrat").val(contrat);
});


/********* GESTION DU SELECTED DES DEPARTEMENTS (fonction anonyme : s'exécute à la fin de la page) ********/
$(function() {
    console.log(dep_id);
    $("#dep_id").val(dep_id);
});
