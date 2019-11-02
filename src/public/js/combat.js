$('document').ready(() => {
    
    //affichage des données
    const participant1Def = $('#participant1Def');
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

        console.log('ok')
        $('#participant' + victime.id + 'PV').text(p2.pointVie);
        
        //TODO
        // if(p1.defensif) {
        //     participant1Def.text('( + 25%)');
        // }else{
        //     participant1Def.text('');
        // }
        
        gameMessage.text(message);
      
        //animation 
        let pb1 = victime.pointVie/victime.entite.pointVie*100;

        const hpbar1 = $('#hp_bar_participant.id').css("width",pb1+"%")
        if(pb1 < 60  && pb1 >=  30){
            hpbar1.css("background-color","orange")

        }else if (pb1 < 30) {
            hpbar1.css("background-color","red")

        }

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
