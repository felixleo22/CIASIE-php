$(document).ready(function(){
    $("#vainqueur").show();
    $("#perdant").hide();
    $("#stat_gagant").hide();

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
        $("#stat_gagant").show();
        $("#titre").text("Vainqueur") ;

    })
});

