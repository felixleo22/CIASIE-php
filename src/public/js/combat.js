$('document').ready(() => {
    
    //affichage des données
    const participant1PV = $('#participant1PV');
    const participant2PV = $('#participant2PV');
    const gameMessage = $('#gameMessage');
    const hpbar1 = $("#hp_bar_1");
    const hpbar2 = $("#hp_bar_2");
    const pvmax1 = participant1PV.data("hp-max");
    const pvmax2 = participant2PV.data("hp-max");

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
            const {pv1, pv2, message, isEnd, showResult} = data;
            if(showResult) {
                window.location.reload();
                return;
            }


            updateDisplay(pv1, pv2, message, isEnd);
        });
    });   

    function updateDisplay(pv1 , pv2, message, isEnd) {
        participant1PV.text(pv1);
        participant2PV.text(pv2);
        gameMessage.text(message);
        console.log(pv1/pvmax1*100)
        console.log(pv2/pvmax2*100)
        hpbar1.css("width",pv1/pvmax1*100+"%")
        hpbar2.css("width",pv2/pvmax2*100+"%")
        if(isEnd) {
            submitBtn.val('Voir le résultat');
        }
    }
});
