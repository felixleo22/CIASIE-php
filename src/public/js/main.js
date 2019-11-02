/**
 * Type de combat a créer (1v1 ou 3v3);
 */
let currentMode = '1v1';

/**
 * Personnages séléctionnées
 */
let currentPersoChecked = [];

/**
 * Monstres séléctionés
 */
let currentMonsterChecked = [];

/**
 * Utilité : Permet à l'utilisateur de visualiser le choix d'un perso
 * @param type - Le type de perso concerné
 * @param id - L'ID du perso sélectionné
 */
function border(type, id) {
    const cards = $("#deck-" + type + " div.card");
    cards.css("border-color", "");

    const card = $("#card-" + id);

    card.css("border-color", "dodgerblue");
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
    border(type, id);
}

function toggleStats(target){
    $("h1#titre").text(target)
    $("div.card-container").hide();
    $("div#" + target).show();
    if (target === "Perdant"){
        target = "Vainqueur";
    } else {
        target = "Perdant";
    }
    $("input#btn-show-stats")
        .val("Afficher les stats du " + target)
        .data("target", target);
}

$(document).ready(function () {
    /**
     * Detecte le changement du combat en 1v1 ou 3v3
     */
    $('input.selectCombatMode[type=radio]').change(function() {
        // on change le mode
       if(!this.checked) return;
       currentMode = this.value;

       //on reset la selection
       currentMonsterChecked = [];
       currentPersoChecked = [];

       //on reset l'affichage de la selection
       const checkboxes = $(`input.check`);
       checkboxes.prop("checked", false);
       const cards = $("div.card.selectable");
       cards.css("border-color", "");


    });
    
    $("input.btn-select").click(function () {
        const type = $(this).data("type");
        const id = $(this).prop("id");
        select(type, id);
    });

    $("input#btn-show-stats").click(function () {
        const target = $(this).data("target");
        toggleStats(target);
    })

});
