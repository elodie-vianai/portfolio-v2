/**
 * Created by Elodie Vianai on 21/04/2017.
 */

/********* GESTION DU SELECTED EN FONCTION DU TYPE (fonction anonyme : s'exécute à la fin de la page) ********/
$(function() {
    //#type = l'id du select et le val fait référence à la variable type (js fichier twig) et sa valeur (php fichier twig)
    $("#type").val(type);
});

/********* GESTION DU SELECTED EN FONCTION DE LA MENTION (fonction anonyme : s'exécute à la fin de la page) ********/
$(function() {
    $("#mention").val(mention);
});

/********* GESTION DU SELECTED DES DEPARTEMENTS (fonction anonyme : s'exécute à la fin de la page) ********/
$(function() {
    $("#dep_id").val(dep_id);
});