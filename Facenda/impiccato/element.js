
class Letter extends React.Component{
    constructor(props){
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }
    handleClick(){
        this.props.clickLetter(this.props.lettera);
    }
    render(){
        return <button className="lettera" onClick={this.handleClick}>{this.props.lettera} </button>
    }
}
function Lettere(props){
    return <div className="lettere">
        {props.lettere.map((x) => <Letter lettera={x} clickLetter={props.clickLetter} key={x}/>)}
    </div>
}
function Parola(props){
    return <div className="parola">
        {props.parola.map((x) => <span className="wordLetter">{x} </span>)}
    </div>
}

function WinningPane(props){
    return <div className="winningPane">
        {props.vinto && <h1>Hai vinto!</h1>}
        {props.perso && <h1>Hai perso!</h1>}
        {(props.vinto || props.perso) && <button onClick={props.reset}>Ricomincia</button>}
    </div>
}

class Game extends React.Component{
    constructor(props){
        super(props);
        this.game = new Impiccato();
        this.state = {
            lettere: this.game.lettere,
            vite: this.game.lives,
            letteraRedacted: this.game.getRedactedArray()
        };
        this.clickLetter = this.clickLetter.bind(this);
        this.handleReset = this.handleReset.bind(this);
    }
    updateState(){
        this.setState({
            lettere: this.game.lettere,
            vite: this.game.lives,
            letteraRedacted: this.game.getRedactedArray()
        });
    }
    clickLetter(letter){
        if(!this.game.isOver()){
            this.game.addLettera(letter.toLowerCase());
            this.updateState();
        }else{
            console.log("Game ended");
        }
    }
    handleReset(){
        this.game = new Impiccato();
        this.updateState();
    }
    render(){
        return <div className="container">
            <h1>Impiccato</h1>
            <Parola parola={this.state.letteraRedacted}/>
            <br></br>
            <Lettere lettere={this.state.lettere} clickLetter={this.clickLetter}/>
            <WinningPane vinto={this.game.isGameWon()} perso={this.game.isGameOver()} reset={this.handleReset}/>
            <Figura vite={this.state.vite} vinto={this.game.isOver()?this.game.isGameWon():null}/>
        </div>
    }
}


var root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <div>
        <Game/>
    </div>
)