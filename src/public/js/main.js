/**
 * Utilité : Permet à l'utilisateur de visualiser le choix d'un perso
 *  On applique un filtre gris sur les images de tous les personnages d'un type
 *  On enlève ce filtre sur l'image du personnage choisi
 * @param type - Le type de perso concerné
 * @param id - L'ID du perso sélectionné
 */
function images(type, id) {
    const images = $("#deck-" + type + " img");
    console.log(images);
    images.css("filter", "grayscale(90%)");

    const image = $("#card-" + id + " img");
    console.log(image);
    image.css("filter", "grayscale(0%)");
}

/**
 * Utilité : Coche/Décoche des checkboxes
 *  On décoche toutes les checkboxes d'un type de perso
 *  On coche la checkbox associée au perso sélectionné
 * @param type - Le type de perso concerné
 * @param id - L'ID du perso sélectionné
 */
function checkboxes(type, id) {
    const checkboxes = $("#deck-" + type + " input.check");
    checkboxes.prop("checked", false);

    const checkbox = $("input#" + id + ".check");
    checkbox.prop("checked", true);
}

/**
 * Utilité : Appelle les fonctions qui s'occupent du choix des persos
 * @param type - Le type de perso concerné
 * @param id - L'ID du perso sélectionné
 */
function select(type, id) {
    checkboxes(type, id);
    images(type, id);
}

$(document).ready(function () {
    $("input.btn-select").click(function () {
        const type = $(this).data("type");
        const id = $(this).prop("id");
        select(type, id);
    });

    $("#vainqueur").show();
    $("#perdant").hide();
    $("#stat_gagant").hide();
    $("#stat_perdant").show();

    $("#stat_perdant").click(function () {
        $("#vainqueur").hide();
        $("#perdant").show();
        $("#stat_perdant").hide();
        $("#stat_gagant").show();
        $("#titre").text("Perdant") ;
    });

    $("#stat_gagant").click(function () {
        $("#vainqueur").show();
        $("#perdant").hide();
        $("#stat_gagant").hide();
        $("#stat_perdant").show();
        $("#titre").text("Vainqueur") ;
    });
});
