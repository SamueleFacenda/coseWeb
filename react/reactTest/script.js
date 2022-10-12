//connct 4 game
var board
var player
var colors = ["white", "red", "yellow"];
var gameover
var winner
var moves

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
    }
}

//function to change the player
function changePlayer(){
    player = (player == 1? 2 : 1);
}

//function to place a piece on the board
function placePiece(col){
    if(gameover == false){
        for(var i = 5; i >= 0; i--){
            if(board[i][col] == 0){
                board[i][col] = player;
                moves++;
                reload()
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

function getArray(i){
    var out = [];
    for(var j = 0; j < i; j++)
        out.push(j);
    return out;
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
    reload();
}

//function to display the winner
function displayWinner(){
    if(winner === 0){
        console.log("Tie game!");
        alert("Tie game!");
    }else{
        console.log("Player " + winner + " wins!");
        alert("Player " + colors[winner] + " wins!");
    }
}
reset()

