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

/**
* Deselectionn toutes les entites
*/
function deselectAll() {
    //on reset la selection
    currentMonsterChecked = 0;
    currentPersoChecked = 0;
    
    //on reset l'affvichage de la selection
    const checkboxes = $(`input.check`);
    checkboxes.prop("checked", false);
    const cards = $("div.card.selectable");
    cards.css("border-color", "");
}

/**
* Choisi aléatoirement les entites
*/
function randomSelect() {
    deselectAll(); 

    const persoInputs = $('#personnage input.btn-select');
    const monstreInputs = $('#monstre input.btn-select');

    const numberOfEach = currentMode === '3v3' ? 3 : 1;

    if(persoInputs.length < numberOfEach || monstreInputs.length < numberOfEach) return;
    
    const selected = [];
    for (let index = 0; index < numberOfEach; index++) {
        //choix d un personnage
        let choosePerso = null;
        while(choosePerso == null || selected.includes(choosePerso)) {
            choosePerso = persoInputs[Math.floor(Math.random() * persoInputs.length)];
        }
        selected.push(choosePerso);
        choosePerso.click();

        //choix d un monstre
        let chooseMonstre = null;
        while(chooseMonstre == null || selected.includes(chooseMonstre)) {
            chooseMonstre = monstreInputs[Math.floor(Math.random() * monstreInputs.length)];
        }
        selected.push(chooseMonstre);
        chooseMonstre.click();
    }


}

/**
* Permet de verifier si on peut selectionner une entite
*/
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

/**
* Permet de vérifier si le formulaire peut etre soumis
*/
function updateFormStatus() {
    if(currentMode === '3v3' && currentMonsterChecked === 3 && currentPersoChecked === 3){
        $('#valid').removeClass('disabled');
        return true;
    }
    if(currentMode === '1v1' && currentMonsterChecked === 1 && currentPersoChecked === 1){
        $('#valid').removeClass('disabled');
        return true;
    }
    $('#valid').addClass('disabled');  
    return false;  
}

$('#formulaire').on('submit', (event) => {
    if(!updateFormStatus()){
        event.preventDefault();
    }
});

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
        deselectAll();
    });
    
    /**
    * selection d'une entite
    */
    $("input.btn-select").click(function () {
        const type = $(this).data("type");
        const id = $(this).prop("id");
        select(type, id);
        updateFormStatus();
    });
    
    /**
     * selection aleatoire
     */
    $("#randomSelect").on('click', () => {
        randomSelect();
        updateFormStatus();
    });


    $("input#btn-show-stats").click(function () {
        const target = $(this).data("target");
        toggleStats(target);
    })
    
});
