$('document').ready(() => {
    
    //affichage des données
    const gameMessage = $('#gameMessage');

    //formulaire
    const playNextForm = document.getElementById('playNextForm');
    const submitBtn = $('#submitBtn');
    
    const chooseActionForm = document.getElementById('chooseActionForm');
    const attaquerBtn = $('#attaquerBtn');
    const defendreBtn = $('#defendreBtn');
    
    const startForm = document.getElementById('startForm');
    
    //initialiser les infos du combats
    startForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = startForm.getAttribute('action');  
        
        fetch(url, {
            method: 'POST',
        })
        .then((response) => {
            if(!response.ok) {
                console.error(response);
                return;
            }
            
            return response.json();
        })
        .then((data) => {
            console.log(data);
            const {message, typeOfNext, showResult} = data;
            if(showResult) {
                window.location.reload();
                return;
            }

            if(typeOfNext === 'personnage') {
                showChooseActionForm();
            }else{
                showPlayNextForm();
            }
            gameMessage.text(message);
        });
        startForm.remove();
    });
    
    //jouer un tour avec choix
    defendreBtn.on('click', () => {
        const url = chooseActionForm.getAttribute('action');
        
        play(url, 'defendre');
    });
    
    attaquerBtn.on('click', () => {
        const url = chooseActionForm.getAttribute('action');
        
        play(url, 'attaquer');
    });
    
    //jouer un tour normal
    playNextForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = playNextForm.getAttribute('action');
        play(url);
    });   
    
    //requete ajax
    function play(url, action = 'none') {
        const data = new FormData();
        data.append('chosenAction', action);
        
        fetch(url, {
            method: 'POST',
            body: data,
        })
        .then((response) => {
            if(!response.ok) {
                console.error(response);
                return;
            }
            
            return response.json();
        })
        .then((data) => {
            // console.log(data)
            const {attaquant, victime, message, typeOfNext, showResult} = data;
            if(showResult) {
                window.location.reload();
                return;
            }
            
            updateDisplay(attaquant, victime, typeOfNext, message);
        });
    }
    
    //mise a jour de l affichage
    function updateDisplay(attaquant, victime ,typeOfNext, message) {

        const pvText = $('#participant' + victime.id + 'PV');
        console.log(pvText);
        pvText.text(victime.pointVie);
        
        let pb1 = (victime.pointVie/victime.entite.pointVie) * 100;

        const hpbar1 = $('#hp_bar_' + victime.id).css("width",pb1+"%")
        if(pb1 < 60  && pb1 >=  30){
            hpbar1.css("background-color","orange")

        }else if (pb1 < 30) {
            hpbar1.css("background-color","red")

        }

        const perso = attaquant.entite.type === 'personnage' ? attaquant : victime;
        const defText = $('#participant' + perso.id + 'Def');
        if(perso.defensif) {
           defText.text('( + 25%)');
        }else{
            defText.text('');
        }

        gameMessage.text(message);

        switch (typeOfNext) {
            case 'ended':
            submitBtn.val('Voir le résultat');
            showPlayNextForm();
            break;
            case 'monstre':
            showPlayNextForm();                
            break;
            case 'personnage':
            showChooseActionForm();
            break; 
        }
    }
    
    function showPlayNextForm() {
        playNextForm.style.display = 'block';
        chooseActionForm.style.display = 'none';        
    }
    
    function showChooseActionForm() {
        chooseActionForm.style.display = 'flex';
        playNextForm.style.display = 'none';
    }
});
