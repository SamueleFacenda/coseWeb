
class Impiccato{
    constructor(){
        this.parola = Impiccato.getParola();
        console.log(this.parola);
        this.lettere = Impiccato.getLettere();
        this.lives = 10;
        this.lunghezza = this.parola.length;
        this.lettereScoperte = [];
        this.lettereDaScoprire = [...new Set(this.parola.split(''))];
    }
    static getLettere(){
        return Array
            .from(Array(26))
            .map((e, i) => i + 97)
            .map((x) => String.fromCharCode(x));
    }
    static getParola(){
        let parola = getParolaFromAPI().toLowerCase();
        if(parola == null || parola == ''){
            parola = 'ciao';
        }
        return parola
    }

    addLettera(lettera){
        if(this.parola.includes(lettera)){
            this.lettereScoperte.push(lettera);
        }else{
            this.lives--;
        }
        this.lettere.splice(this.lettere.indexOf(lettera), 1);
    }
    isGameOver(){
        return this.lives <= 0;
    }
    isGameWon(){
        return this.lettereScoperte.length == this.lettereDaScoprire.length;
    }
    getRedactedArray(){
        return this.parola.split('').map((x) => this.lettereScoperte.includes(x) ? x : '_');
    }
    isOver(){
        return this.isGameOver() || this.isGameWon();
    }
}