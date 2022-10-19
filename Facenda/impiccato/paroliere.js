
function getParolaFromAPI(){
    var xmlHttp = new XMLHttpRequest();
    //API di un sito a caso che ho trovato
    xmlHttp.open( "GET", "https://www.palabrasaleatorias.com/parole-casuali.php?fs=1&fs2=0&Submit=Nuova+parola", false );
    xmlHttp.send( null );
    let page = xmlHttp.responseText;
    let parolaContainer = '<div style="font-size:3em; color:#6200C5;">';
    //estraggo la parola dalla risposta
    let parola = page.substring(page.indexOf(parolaContainer) + parolaContainer.length);
    parola = parola.substring(0, parola.indexOf('<'));
    parola = parola.trim();
    console.log(parola);
    return parola;
}