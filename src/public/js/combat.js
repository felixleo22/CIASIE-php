$('document').ready(() => {
    
    //affichage des données
    const participant1PV = $('#participant1PV');
    const participant1Def = $('#participant1Def');
    const participant2PV = $('#participant2PV');
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
            const {p1, p2, message, typeOfNext, showResult} = data;
            if(showResult) {
                window.location.reload();
                return;
            }
            
            updateDisplay(p1, p2, typeOfNext, message);
        });
    }
    
    //mise a jour de l affichage
    function updateDisplay(p1, p2 ,typeOfNext, message) {
        participant1PV.text(p1.pointVie);
        participant2PV.text(p2.pointVie);
        
        if(p1.defensif) {
            participant1Def.text('( + 25%)');
        }else{
            participant1Def.text('');
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
