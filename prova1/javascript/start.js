
let partita=null;
function update(){
    if(partita!=null){
        document.getElementById("cpu").innerHTML=partita.getPuntiCPU();
        document.getElementById("giocatore").innerHTML=partita.getPuntiGiocatore();
        document.getElementById("mani").innerHTML="mani rimaste"+partita.getManiRimanenti();
    }
}
function inizio(nMani){
    partita=new Partita(nMani);
    document.getElementById("swap").setAttribute(src,"../pages/mano.html");
    update();
}
function gioca(scelta){
    if(partita != null){
        if(!partita.gioca(scelta)){
            update();
            document.getElementById("esito").innerHTML="ha vinto "+(partita.lastWinnerIsCPU()?"la CPU":"il giocatore");
        }else{
            document.getElementById("esito").innerHTML="pareggio";
        }
        if(partita.isEndend()){
            document.getElementById("swap").src="pages/end.html";
            let esito="";
            if(partita.getPuntiCPU()===partita.getPuntiGiocatore())
                esito="pareggio";
            else if(partita.getPuntiCPU() > partita.getPuntiGiocatore())
                esito="hai perso";
            else
                esito="hai vinto";
            document.getElementById("esito").innerHTML=esito;
            partita=null;
        }
    }
}