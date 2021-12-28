class Partita{
      #mani=0;
      #puntiCPU=0;
      #puntiGiocatore=0;
      #lastPlay=true;
      #lastCPU="";
    constructor(mani) {
        this.#mani=mani;
        this.#puntiCPU=0;
        this.#puntiGiocatore=0;
        this.#lastPlay=false;//false cpu, true giocatore(l'ultimo che ha vinto)
    }
      getPuntiCPU(){
        return this.#puntiCPU;
    }
      getPuntiGiocatore(){
        return this.#puntiGiocatore;
    }
      getManiRimanenti(){
        return this.#mani;
    }
      getManiGiocate(){
        return this.#puntiGiocatore+this.#puntiCPU;
    }

    /**
     * 0 carta, 1 forbice, 2 sasso
     * @param giocata scelta del giocatore
     * @returns {boolean} se ha pareggiato
     */
      gioca(giocata){
        if(this.#mani>0){
            this.#lastCPU=(Math.random()*3)|0;
            if(this.#lastCPU===giocata){
                return true;
            }else{
                if((this.#lastCPU===1 && giocata===0) || (this.#lastCPU===2 && giocata === 1) || (this.#lastCPU=== 0 && giocata === 2)) {
                    this.#puntiCPU++;
                    this.#lastPlay=true;
                }else{
                    this.#puntiGiocatore++;
                    this.#lastPlay=false;
                }
                this.#mani--;
            }
        }
    }
      isEndend(){
        return this.#mani<=0;
    }
      lastWinnerIsCPU(){
        return this.#lastPlay;
    }
      reset(){
        this.#mani=0;
        this.#puntiGiocatore=0;
        this.#puntiCPU=0;
    }
      getLastCPU(){
          return this.#lastCPU;
    }
      static decodifica(val){
          switch (val){
              case 0:
                  return "carta";
              case 1:
                  return "forbice";
              case 2:
                  return "sasso";
              default:
                  return "input non valido";
          }
    }
     getHandString(scelta){
          return "hai giocato "+Partita.decodifica(scelta)+" contro "+Partita.decodifica(this.#lastCPU)+". Hai "+(this.#lastPlay?"perso":"vinto");
     }
}