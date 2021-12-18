var gioco=0
var puntiP=0,puntiG=0
function forb(){
    gioco=0
    gioca()
}
function car(){
    gioco=1
    gioca()
}
function sas(){
    gioco=2
    gioca()
}
function decode(num){
    switch (num) {
        case 0:
            return "forbici"
        case 1:
            return "carta"
        case 2:
            return "sasso"
        default:
            return "non valido"
    }
}
function update(){
    document.getElementById("punti").innerHTML=puntiG+"-"+puntiP
}
function gioca(){
    if(gioco==-1)
        document.getElementById("scelta").innerHTML="input non valido"
    else{
        let pc=Math.floor(Math.random()*3)
        let esito=""
        if(gioco==pc)
            esito="pareggio"
        else if((gioco==0&&pc==1)||(gioco==1&&pc==2)||(gioco==2&&pc==0)){
            esito="vittoria"
            puntiG++
        }else{
            esito="perdita"
            puntiP++
        }
        document.getElementById("scelta").innerHTML="pc: "+decode(pc)+"<br>giocatore: "+decode(gioco)+"<br>esito: "+esito
        update()
    }
}