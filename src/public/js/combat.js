$('document').ready(() => {
    
    //affichage des données
    const participant1PV = $('#participant1PV');
    const participant2PV = $('#participant2PV');
    const gameMessage = $('#gameMessage');
    
    //formulaire
    const playNextForm = document.getElementById('playNextForm');
    const submitBtn = $('#submitBtn');
    
    const chooseActionForm = document.getElementById('chooseActionForm');
    const attaquerBtn = $('#attaquerBtn');
    const defendreBtn = $('#defendreBtn');
    
    //jouer un tour avec choix
    defendreBtn.on('click', () => {
        const url = chooseActionForm.getAttribute('action');
        
        play(url);
    });
    
    attaquerBtn.on('click', () => {
        const url = chooseActionForm.getAttribute('action');
        
        play(url);
    });
    
    //jouer un tour normal
    playNextForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = playNextForm.getAttribute('action');
        play(url);
    });   
    
    //requete ajax
    function play(url) {
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
            const {pv1, pv2, message, typeOfNext, showResult} = data;
            if(showResult) {
                window.location.reload();
                return;
            }
            
            updateDisplay(pv1, pv2, typeOfNext, message);
        });
    }
    
    //mise a jour de l affichage
    function updateDisplay(pv1 , pv2,typeOfNext, message) {
        participant1PV.text(pv1);
        participant2PV.text(pv2);
        gameMessage.text(message);
        
        
        switch (typeOfNext) {
            case 'ended':
            submitBtn.val('Voir le résultat');
            showPlayNextForm();
            break;
            case 'monstre':
            submitBtn.val('Jouer le prochain coup');
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
