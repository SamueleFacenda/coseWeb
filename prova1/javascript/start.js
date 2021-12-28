function inizio(){
    let nMani=parseInt(document.getElementById("in").value);
    let nome=document.getElementById("inNome").value;
    if(nMani>0 && nome.length>0){
        parent.partita=new Partita(nMani,nome);
        parent.document.getElementById("swap").src="pages/mano.html";
    }else
        alert("input non valido");
}
