<?php
$nRow = 10;
$nCol = 10;
const TITOLO = "Tabella delle tabelline";

echo "<html>
<head>
<title>".TITOLO."</title>
</head>
<body>
<h1>".TITOLO."</h1>
<table border=1>";
for ($i=1; $i<=$nRow; $i++) {
    echo "<tr>";
    for ($j=1; $j<=$nCol; $j++)
        echo "<td>".($i*$j)."</td>";
    echo "</tr>";
}
echo "</table>
</body>
</html>";

?>