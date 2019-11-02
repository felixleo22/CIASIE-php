/**
* Type de combat a créer (1v1 ou 3v3);
*/
let currentMode = '1v1';

/**
* Personnages séléctionnées
*/
let currentPersoChecked = 0;

/**
* Monstres séléctionés
*/
let currentMonsterChecked = 0;

/**
* Utilité : Permet de selectionner ou déselectioner une entite
* @param id - L'ID du perso sélectionné
*/
function select(type, id) {
    //selection ou deselection
    const checkbox = $("input#" + id + ".check");
    const card = $("#card-" + id);
    const isChecked = checkbox.prop("checked");
    
    if(isChecked) {
        checkbox.prop("checked", false);
        card.css("border-color", "");
        if(type === 'monstre') {
            currentMonsterChecked--;
        }else{
            currentPersoChecked--;
        }
    }else {
        if(!couldCheck(type)) return;
        checkbox.prop("checked", true);
        card.css("border-color", "dodgerblue");
        if(type === 'monstre') {
            currentMonsterChecked++;
        }else{
            currentPersoChecked++;
        }
    }
}

function couldCheck(type) {
    if(type === 'personnage') {
        if(currentMode === '3v3') {
            return currentPersoChecked < 3;
        }
        else {
            return currentPersoChecked < 1;
        }
    }
    
    if(type === 'monstre') {
        if(currentMode === '3v3') {
            return currentMonsterChecked < 3;
        }
        else {
            return currentMonsterChecked < 1;
        }
    }
    return false;
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
        currentMonsterChecked = 0;
        currentPersoChecked = 0;
        
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
