//connect 4 game, html in index.html

//global variables
var player = 1;
var board = [[0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0]];
var gameover = false;
var winner = 0;
var moves = 0;

//initialization
window.onload = function() {
    var anchors = document.getElementsByClassName('circle');
    for(var i = 0; i < anchors.length; i++) {
        anchors[i].onclick = function(obj) {
            col = obj.srcElement.parentElement.className;
            col = col.substring("col-".length, col.length);
            col = parseInt(col) - 1;
            placePiece(col);
        }
    }
}

//function that get a div and set the color of the son at yellow or red
function setCell(row, col, player){
    var anchors = document.getElementsByClassName('circle');
    var cell = anchors[row*7+col];
    if(player == 1){
        cell.style.backgroundColor = "yellow";
    }else{
        cell.style.backgroundColor = "red";
    }
}

//function to check if a player has won at connect 4
function checkWin(){
    //check horizontal
    for(var i = 0; i < 6; i++){
        for(var j = 0; j < 4; j++){
            if(board[i][j] == player && board[i][j+1] == player && board[i][j+2] == player && board[i][j+3] == player){
                gameover = true;
                winner = player;
                return;
            }
        }
    }
    //check vertical
    for(var i = 0; i < 3; i++){
        for(var j = 0; j < 7; j++){
            if(board[i][j] == player && board[i+1][j] == player && board[i+2][j] == player && board[i+3][j] == player){
                gameover = true;
                winner = player;
                return;
            }
        }
    }
    //check diagonal
    for(var i = 0; i < 3; i++){
        for(var j = 0; j < 4; j++){
            if(board[i][j] == player && board[i+1][j+1] == player && board[i+2][j+2] == player && board[i+3][j+3] == player){
                gameover = true;
                winner = player;
                return;
            }
        }
    }
    //check diagonal
    for(var i = 0; i < 3; i++){
        for(var j = 3; j < 7; j++){
            if(board[i][j] == player && board[i+1][j-1] == player && board[i+2][j-2] == player && board[i+3][j-3] == player){
                gameover = true;
                winner = player;
                return;
            }
        }
    }
}

//function to check if the board is full
function checkFull(){
    if(moves == 42){
        gameover = true;
        winner = 0;
        return;
    }
}

//function to change the player
function changePlayer(){
    if(player == 1){
        player = 2;
    }else{
        player = 1;
    }
}

//function to place a piece on the board
function placePiece(col){
    if(gameover == false){
        for(var i = 5; i >= 0; i--){
            if(board[i][col] == 0){
                board[i][col] = player;
                setCell(i, col, player);
                moves++;
                displayBoard();
                checkWin();
                checkFull();
                changePlayer();
                if(gameover == true){
                    displayWinner();
                    reset();
                }
                return;
            }
        }
    }
}

//function to reset the game
function reset(){
    player = 1;
    board = [[0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0],
                [0,0,0,0,0,0,0]];
    gameover = false;
    winner = 0;
    moves = 0;
    circles = document.getElementsByClassName('circle');
    for(var i = 0; i < circles.length; i++){
        circles[i].style.backgroundColor = "white";
    }
}

//function to display the board
function displayBoard(){
    var boardString = "";
    for(var i = 0; i < 6; i++){
        for(var j = 0; j < 7; j++){
            boardString += board[i][j] + " ";
        }
        boardString += "\n";
    }
    console.log(boardString);
}

//function to display the winner
function displayWinner(){
    if(winner == 0){
        console.log("Tie game!");
        alert("Tie game!");
    }else{
        console.log("Player " + winner + " wins!");
        alert("Player " + (winner == 1? "yellow" : "red") + " wins!");
    }
}