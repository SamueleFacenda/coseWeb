function update(){
    if(! parent.partita.isEndend()){
        document.getElementById("cpu").innerHTML= parent.partita.getPuntiCPU();
        document.getElementById("giocatore").innerHTML= parent.partita.getPuntiGiocatore();
        document.getElementById("mani").innerHTML="mani rimaste"+ parent.partita.getManiRimanenti();
    }
}
update();
function gioca(scelta){
    if(! parent.partita.isEndend()){
        if(! parent.partita.gioca(scelta)){
            update();
            document.getElementById("esito").innerHTML="ha vinto "+( parent.partita.lastWinnerIsCPU()?"la CPU":"il giocatore");
        }else{
            document.getElementById("esito").innerHTML="pareggio";
        }
        if( parent.partita.isEndend())
            parent.document.getElementById("swap").src="pages/end.html";
    }
}