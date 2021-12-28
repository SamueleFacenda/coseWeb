let esito="";
if(parent.partita.getPuntiCPU()===parent.partita.getPuntiGiocatore())
    esito="pareggio";
else if(parent.partita.getPuntiCPU() > parent.partita.getPuntiGiocatore())
    esito="ha vinto la CPU";
else
    esito="ha vinto "+parent.partita.getNome();
document.getElementById("esito").innerHTML=esito;
parent.partita.reset();
function goOn(){
    parent.document.getElementById("swap").src="pages/start.html";
}