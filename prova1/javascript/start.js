function inizio(){
    let nMani=parseInt(document.getElementById("in").value);
    parent.partita=new Partita(nMani);
    parent.document.getElementById("swap").src="pages/mano.html";
}
