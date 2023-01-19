<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- form with name and password -->
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_POST["name"]);?>">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($_POST["password"]);?>">
        <input type="submit" value="Submit">
    </form>
    <?php
        // if the form is submitted
        //validate name and password wuth regex
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $password = $_POST["password"];
            //regex for password, at least 8 characters, at least one number, at least one uppercase and lowercase letter
            $regexPassword = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";
            //regex for name
            $regexName = "/^[a-zA-Z ]*$/";
            //print error if name is not valid
            $trueFileds = 0;
            if (!preg_match($regexName, $name)) {
                echo "Name is not valid";
            }else{
                $trueFileds++;
            }
            //print error if password is not valid
            if (!preg_match($regexPassword, $password)) {
                echo "Password is not valid";
            }else{
                $trueFileds++;
            }
            //if all fields are valid
            if($trueFileds == 2){
                //create a file with name and password
                $file = fopen("users.txt", "a");
                fwrite($file, $name . " " . $password . "\n");
                //print name and password in a table
                echo "<table border=1>
                        <tr>
                            <th>Name</th>
                            <th>Password</th>
                        </tr>
                        <tr>
                            <td>$name</td>
                            <td>$password</td>
                        </tr>
                    </table>";
            }
        }
    ?>
</body>
</html>