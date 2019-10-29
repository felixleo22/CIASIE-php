$('document').ready(() => {
    
    //affichage des données
    const participant1PV = $('#participant1PV');
    const participant2PV = $('#participant2PV');
    const gameMessage = $('#gameMessage');

    //formulaire
    const playNextForm = document.getElementById('playNextForm');
    const submitBtn = $('#submitBtn');
    
    playNextForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = playNextForm.getAttribute('action');
        
        fetch(url, {
            method: 'POST',
        })
        .then((response) => {
            if(!response.ok) {
              //TODO faire qq chose en cas d'erreur
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
    });   

    function updateDisplay(pv1 , pv2,typeOfNext, message) {
        participant1PV.text(pv1);
        participant2PV.text(pv2);
        gameMessage.text(message);

        if(typeOfNext === 'ended') {
            submitBtn.val('Voir le résultat');
        }else{
            submitBtn.val('Jouer le prochain coup');
        }
    }
});
