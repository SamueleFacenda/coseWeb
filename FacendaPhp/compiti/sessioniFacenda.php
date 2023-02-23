<?php
session_start();
if(!isset($_SESSION['materie'])){
    $_SESSION['materie'] = array();
}
$materie = $_SESSION['materie'];
// regex for name, from 1 to 20 letters
$reg_name = "^[a-zA-Z]{1,20}$";
$min = 3;
$max = 10;
$wrong_data = false;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $materia = $_POST['materia'];
    $voto = $_POST['voto'];
    $showaverage = isset($_POST['showaverage']);
    //check materia with regex
    $wrong_data = !preg_match("/".$reg_name."/" , $materia) || $voto < $min || $voto > $max;
    if(!$wrong_data){
        $materie[$materia] = $voto;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        footer{
            margin-top: 200px;
        }
    </style>
</head>
<body>
    <?php
    if($wrong_data){
        ?>
        <p>dati errati</p>
        <?php
    }else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        ?>
        <table>
            <tr>
                <th>Materia</th>
                <th>Voto</th>
            </tr>
            <?php
            $somma = 0;
            $conta = 0;
            foreach($materie as $materia => $voto){
                $somma += $voto;
                $conta++;
                ?>
                <tr>
                    <td><?php echo $materia; ?></td>
                    <td><?php echo $voto; ?></td>
                </tr>
                <?php
            }
            if($showaverage){
                ?>
                <tr>
                    <td>Media:</td>
                    <td><?= $somma/$conta; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }else{
    ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <table>
                <tr>
                    <td>Materia</td>
                    <td><input type="text" name="materia" required="required" pattern="<?=$reg_name?>"></td>
                </tr>
                <tr>
                    <td>Voto</td>
                    <td><input type="number" name="voto" required="required" min="<?=$min?>" max="<?=$max?>"></td>
                </tr>
                <tr>
                    <td>Mostra media</td>
                    <td><input type="checkbox" name="showaverage"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Invia">
                    </td>
                </tr>
            </table>
        </form>
    <?php
    }
    ?>
    <footer>
        <a href="<?= $_SERVER['PHP_SELF'] ?>">Return to insert</a>
    </footer>
</body>
</html>
<?php
$_SESSION['materie'] = $materie;
?>