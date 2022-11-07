class Tris{
    constructor(){
        this.table = [
            [0,0,0],[0,0,0],[0,0,0]
        ];
        this.turno = 1;
        this.play=0;
    }
    changeTurno(){
        this.turno = this.turno == 1? 2 : 1;
    }
    trovaVincitore() {
      for(let player=1;player<=2;player++){
        //check rows
        for(let i=0;i<3;i++){
          if(this.table[i][1] == player && this.table[i][0] == player && this.table[i][2] == player)
            return player
        }
        //check columns
        for(let i=0;i<3;i++){
          if(this.table[0][i] == player && this.table[1][i] == player && this.table[2][i] == player)
            return player
        }
        if(this.table[0][0] == player && this.table[1][1] == player && this.table[2][2] == player)
          return player
        if(this.table[0][2] == player && this.table[1][1] == player && this.table[2][0] == player)
          return player
      }
      return null;
    }
    gioca(x, y){
        if(this.table[y][x] == 0)
            this.table[y][x] = this.turno;
    }

}