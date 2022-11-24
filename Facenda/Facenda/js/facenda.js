class FormInizio extends React.Component{
    constructor(props){
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleNameChange = this.handleNameChange.bind(this);
        this.state={
            nome1: "",
            nome2: ""
        };
    }
    handleSubmit(event){
        event.preventDefault();
        this.props.submit(this.state.nome1, this.state.nome2);
    }
    handleNameChange(event){
        this.setState({
            [event.target.name]: event.target.value
        })
    }
    render(){
        return <form onSubmit={this.handleSubmit} >
            <div>
                <label>nome primo giocatore</label>
                <input type="text" name="nome1"  required onChange={this.handleNameChange}/>
                <span className="highlight"></span>
                <span className="bar"></span>
            </div>
            <div>
                <label>nome secondo giocatore</label>
                <input type="text" name="nome2" required onChange={this.handleNameChange}/>
                <span className="highlight"></span>
                <span className="bar"></span>
            </div>
            <br></br>
            <input type="submit" value="procedi" className="bottoneBello"/>
        </form>
    }
}


function Cell(props){
    return <a onClick={(e) =>{ props.click(props.x, props.y)}}>
        <img src={"img/"+props.player+".png"}/>
    </a>
}

function Row(props){
    return <tr>
        {[0,1,2].map(num => 
            <td>
                <Cell x={num} y={props.y} player={props.values[num]} click={props.click}/>
            </td>)
            }
    </tr>
}

function Table(props){
    return <table>
        {[0,1,2].map((num => <Row y={num} values={props.table[num]} click={props.click}/>))}
    </table>
}

class Gioco extends React.Component{
    constructor(props){
        super(props);
        this.restart = this.restart.bind(this);
    }
    restart(){
        this.props.restart();
    }
    render(){
        return <div>
            <Table table={this.props.table} click={this.props.click}/>
            <br></br>
            <div className="testo">
                {
                    this.props.winner == null?
                    <span>Turno di: {this.props.turno}</span>
                    :
                    <div>
                        <span>vincitore: {this.props.winner}</span>
                        <br></br>
                        <a onClick={e => this.restart()} className="bottoneBello">
                            rincomincia
                        </a>
                    </div>
                }
            </div>
        </div>
    }
}

class App extends React.Component{
    constructor(props){
        super(props);
        this.gioco = new Tris();
        this.state={
            formCompilato: false,
            canPlace: true,
            nome: ["", ""],
            table: this.gioco.table
        }
        this.compilaForm = this.compilaForm.bind(this);
        this.piazzaCroce = this.piazzaCroce.bind(this);
        this.restart = this.restart.bind(this);
    }
    piazzaCroce(x, y){
        console.log("click: " + x + "  " + y + "  canplace:" + this.state.canPlace);
        if(this.state.canPlace){
            this.gioco.gioca(x, y);
            this.gioco.changeTurno();
            let win = this.gioco.trovaVincitore();
            this.setState({
                table: this.gioco.table,
                canPlace: win == null,
                winner: win
            });
        }
    }
    restart(){
        this.gioco = new Tris();
        this.setState({
            formCompilato: false,
            canPlace: true,
            table: this.gioco.table
        });
    }
    compilaForm(nome1, nome2){
        console.log("nomi:"+nome1+nome2)
        this.setState({
            formCompilato: true,
            nome: [nome1, nome2]
        })
    }
    render(){
        return <div>
            <h1>Gioco del tris di Samuele Facenda</h1>
            {
            this.state.formCompilato ?
            <Gioco 
                restart={this.restart}
                turno={this.state.nome[this.gioco.turno-1]} 
                table={this.state.table} 
                winner={this.state.winner!=null? this.state.nome[this.state.winner-1]:null} click={this.piazzaCroce}
            />
            :
            <FormInizio submit={this.compilaForm}/>
            }
        </div>
    }
}

var root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <div className="center">
        <App/>
    </div>
)