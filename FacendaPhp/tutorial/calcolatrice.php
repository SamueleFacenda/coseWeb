<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatrice Facenda</title>
</head>
<body>
    <?php
    // read uno, due, and operator from post request if they exist
    if(isset($_POST["uno"])){
        $uno = $_POST["uno"];
    }else{
        $uno = "";
    }
    if(isset($_POST["due"])){
        $due = $_POST["due"];
    }else{
        $due = "";
    }
    if(isset($_POST["operator"])){
        $operator = $_POST["operator"];
    }else{
        $operator = "";
    }
    // escape html characters
    $uno = htmlspecialchars($uno);
    $due = htmlspecialchars($due);
    $operator = htmlspecialchars($operator);
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="uno">Primo numero:</label>
        <input type="text" name="uno" id="uno" value="<?=$uno?>">
        <label for="due">Secondo numero:</label>
        <input type="text" name="due" id="due" value="<?=$due?>">
        <input type="submit" name="operator" value="+">
        <input type="submit" name="operator" value="-">
        <input type="submit" name="operator" value="*">
        <input type="submit" name="operator" value="/">
    </form>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            echo "<div> Risultato: ";
            if($operator == "+"){
                echo $uno + $due;
            }
            if($operator == "-"){
                echo $uno - $due;
            }
            if($operator == "*"){
                echo $uno * $due;
            }
            if($operator == "/"){
                echo $uno / $due;
            }
            echo "</div>";
        }
        ?>
</body>
</html>