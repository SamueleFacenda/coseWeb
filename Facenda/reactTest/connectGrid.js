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

class Field extends React.Component{
    render(){
        return (
            <label>
                {this.props.label}
                <input type={this.props.type} value={this.props.value} onChange={this.props.onChange}/>
            </label>
        )
    }
}

class Review extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            value: 'Please write a review of this game',
            email: 'Please enter your email'
        };

        this.handleChange = this.handleChange.bind(this);
        this.handleChangeEmail = this.handleChangeEmail.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        this.setState({value: event.target.value});
    }

    handleSubmit(event) {
        alert('An essay was submitted: ' + this.state.value);
        event.preventDefault();
    }

    handleChangeEmail(event) {
        this.setState({email: event.target.value});
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <label>
                    Essay:
                    <textarea value={this.state.value} onChange={this.handleChange} />
                </label>
                <label>
                    email:
                    <textarea value={this.state.value} onChange={this.handleChangeEmail} />
                </label>
                <label>

                    <input type=number value={this.state.value} onChange={this.handleChangeEmail} />
                </label>

                <input type="submit" value="Submit" />
            </form>
        );
    }
}

var root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<Board/>);

function reload(){
    root.render(<Board/>);
}