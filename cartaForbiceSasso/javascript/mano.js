function update(){
    if(! parent.partita.isEndend()){
        document.getElementById("cpu").innerHTML= parent.partita.getPuntiCPU();
        document.getElementById("giocatore").innerHTML= parent.partita.getPuntiGiocatore();
        document.getElementById("mani").innerHTML=parent.partita.getManiRimanenti();
    }
}
update();
function gioca(scelta){
    if(! parent.partita.isEndend()){
        if(! parent.partita.tira(scelta)){
            update();
            document.getElementById("esito").innerHTML=parent.partita.getHandString(scelta);
        }else{
            document.getElementById("esito").innerHTML="pareggio";
        }
        if( parent.partita.isEndend())
            parent.document.getElementById("swap").src="pages/end.html";
    }
}