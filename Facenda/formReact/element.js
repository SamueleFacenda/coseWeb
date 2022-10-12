//react form with name and double password fields and submit button, dynamic check for password match and password regex
//language: javascript + react

class Field extends React.Component{
    constructor(props){
        super(props);
    }
    render(){
        return(
            <div>
                <label>{this.props.label}</label>
                <br/>
                <input name={this.props.name} type={this.props.type} value={this.props.value} onChange={this.props.handleChange}/>
            </div>
        );
    }
}

class Form extends React.Component{
    constructor(props){
        super(props);
        this.state = {value: "", user_valid: true, psw_match: true, psw_valid: true, valid: false, psw: "", psw2: "", user: ""};
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handlePswChange = this.handlePswChange.bind(this);
        this.handleUserChange = this.handleUserChange.bind(this);
    }
    checkPswMatch(){
        return this.state.psw == this.state.psw2;
    }
    checkPasswordRegex(){
        
    }
    handleSubmit(event){
        if(this.state.user == ""){
            this.setState({user_valid: false});
            event.preventDefault();
            return;
        }
        if(this.state.valid){
            alert("Form submitted!");
            this.setState({user: "", psw: "", psw2: ""});
            event.preventDefault();
        }else{
            alert("Invalid form!");
            event.preventDefault();
        }
    }
    handlePswChange(event){
        var psw_match = event.target.value == this.state[event.target.name.endsWith("2") ? "psw" : "psw2"];
        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        let psw_valid = regex.test(event.target.value);
        this.setState({
            [event.target.name]: event.target.value,
            psw_match: psw_match,
            psw_valid: psw_valid,
            valid: psw_match && psw_valid && this.state.user_valid
        });
    }

    handleUserChange(event){
        this.setState({
            user: event.target.value,
            user_valid: event.target.value != "",
            valid: this.state.psw_match && this.state.psw_valid && event.target.value != ""
        });
    }
    render(){
        return(
            <form onSubmit={this.handleSubmit}>
                <Field name="user" label="Name" type="text" value={this.state.user} handleChange={this.handleUserChange}/>
                {!this.state.user_valid && <div style={{color: "red"}}>"Name must not be empty"</div>}
                <Field name="psw" label="Password" type="password" value={this.state.psw} handleChange={this.handlePswChange}/>
                <Field name="psw2" label="Confirm Password" type="password" value={this.state.psw2} handleChange={this.handlePswChange}/>
                {!this.state.psw_match && <div style={{color: "red"}}>Passwords do not match!</div>}
                {!this.state.psw_valid && <div style={{color: "red"}}>Password must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter and 1 number</div>}
                <input type="submit" value="Submit"/>
            </form>
        );
    }
}

var root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <Form/>
);