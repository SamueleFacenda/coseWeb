class Cell extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            value: null,
        };
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick(){
        placePiece(this.props.col);
        reload()
    }

    render(){
        var classe = colors[board[this.props.row][this.props.col]];
        return (
            <button className={"cell "+classe} onClick={this.handleClick} >
            </button>
        );
    }
}

class Row extends React.Component{
    renderCell(i){
        return <Cell row={this.props.row} col={i} key={"row"+this.props.row+"col"+i}/>;
    }

    render(){
        return (
            <div className="row">
                {[...Array(7).keys()].map(i => this.renderCell(i))}
            </div>
        );
    }
}

class Board extends React.Component{
    renderRow(i){
        return <Row row={i} key={"row"+i}/>;
    }

    render(){
        var prog = [...Array(6).keys()]
        prog = prog.map(i => this.renderRow(i))
        return (
            <div className="board">
                {prog}
            </div>
        );
    }
}

var root = ReactDOM.createRoot(document.getElementById('root'));

function reload(){
    root.render(<Board/>);
}


root.render(
    <Board/>
);

