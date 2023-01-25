<?php
$citta = 'Milano';
$cronologia = null;
if(isset($_COOKIE['cronologia'])){
    $cronologia = $_COOKIE['cronologia'];
    $cronologia = json_decode($cronologia);
}else{
    $cronologia = array();
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['citta'])){
    $citta = $_POST['citta'];
    if(!in_array($citta, $cronologia))
        $cronologia[] = $citta;
}

const NEXT = 5;
const SOLE = 'data:image/png;base64,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';
const PIOGGIA = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAgVBMVEX///8AAABDQ0NNTU3j4+P39/fy8vL6+vro6OjV1dXd3d3s7OxVVVW8vLyCgoImJiavr693d3eNjY2lpaVtbW3IyMghISG1tbVISEigoKDDw8MyMjI5OTl8fHw+Pj6KioqTk5MbGxsTExPNzc1nZ2dbW1ssLCwNDQ0cHByampppaWnOP0WsAAAKz0lEQVR4nO1d61rqOhC1UFpAQBC5q9wEdL//Ax4VuXTWpJ2kTZqer+vn3kIytJlZc83Dg0u0uv3x+mN+3J6C4HSavvZGX+NlGDvdgz2Es7d5wGP18tV5LHt/ObFfvyuku+A0X08q+zDbm12GeBcchlHZmzXAsikU74zmuGLv6+xVS77zk5xV50n2V/ry/WD31i176yJ0Gmby/eJ9Wfb2M9Fa55DvB9Nxq2wZUjF5yingj4wLj2Uc5JfvB6dx2YIo8Jhl3+XY9csWhsPkVJiA3+j5p1fHRcr3g+eyJSIQ6NDp6v39dSXlckFw7JQt1D3e0rb6PhpOwpuCjLqT4ag5Fci4KU0ewItyk7tRv81+JOoOR8csEeehY0FUGKl22Oun+kZxOFZ5jxf4oVRVAo74p5dEa9ZLFdEHhaNQMm9ih+gx9Ul+lO4iL9h9adqzjvJFD4JGySxuye5qqP090ZdSxKdS3eMut6Wm2ZYWKgtyKlGltjhv11g5tL4UzG8n0Vl28FHIG3pDpHBPTmWFODgyus/3lSHvoUzLEZE7hPl9Ap7DN0oxGszPXYTTE7Lm8V8B36yLDW6jIHeAJRGbYr5bAx3cRM4zeANrZZ0H4tBQ5NGiBCEX03JsM1AhDIr8+oiJu/aKXCATj7B+s9gFYib18VXsEukAqrwrnD0eUESH9A1NoQU9gJ7jvPhFVIDfd2RhkRhzWAUqs3Ts4R21Qjki0Kg7V+wNFN3EzjohPERHUQ14hNYoVR9EdOMOgwqwZ4vBnbJx4AHA1zYWFwN674LZQADY5mLwcxZKnXi06Zp2833P7k8i9W2mltdzeSb4FW1bYapPj7bdfbrgk/WILeXgth1Fup59wj8hKx7sLgc0wwGPovbX7ltDo+8uLDB9iHZPPg2EOXHZSC61YGc7CfqSuvHYaMikUJPYisLJbLz4xng8m7RpCHFW5FpKxGTVolLD7f3mZZ5RJuMoufcvuepLEd+5HEhqnJww/QfQNavcRn/JJZU4FBYDzkCLrJtPv8FZU8M+n7mA+DN5yH6oLo5BuHpJHx5mRS0cpZQMMHCXSiBW6tX0e9TlAjwcJvWI3jM7Hh3dGnuXiQRyeIwSlboPMAgWRYuRgmFyaQNqGjNpgiy4rI8k8ZqN9heEknJIisLFSEGUXPpD9/MQ45XAbVVdkj4eNT9tJOCn2woQ4rbpfdhIQNe1WKQOWeuzTM3BDdPmv/V4OZnsJ5PlcPPSu1iUD9c1PKQIUsdFxIT1BatBP6SCxO3OYrAeui+mI+ZCxyCqes02vlRZn0GimBpezT9WvIZ3bWREWcj3N+Tk87FNjmgLcSADs6yBWzYmRmgoIVOYs/Lr/F1AUl7SEBhT6lhIlMcCzCSMUMC13X2aw+wtxaJjHzo4eHRNJEQ14yCFbAqiS2XaHoIybqv/9EBqTkUVPJCQ33ncZEyjbSLvG06hq/iuEQg1kZg0Gkf2V43+gsSpJWE+agunPr+jEGyTeE80duisstEM1AXKbv+jfq/tspi8oGcqO95G9YwfDalKcC7CNnXPMcmNP7naqiFotcIZabEU+pJuXG3VEHwrZxoDp0nC8lr8ZFDGA19UdoNoUst1RvlBixVuOPLGn5p7NzUVeZCS2WQ5Kq2/8/0lZX3ZKzhTTopEHbZqGCMtcL3BPyfH0MvYE0U3ZfwCcmryB157FTfs1WUUG/KntG+phN2aoaXMVJPXkNjPKhzDC+KFYopPksIRe+9v/IlDpBg2kkjWkPfZc8cJwLd7J6rdiFbyb7JWFth0y30wmxiL6sydvIJ9jLejGG0T/+F5/IJHzNG463tK4ohVUqV3YIb/XA0/cZjLmMhQBGYo4sXPIAbfXRFlwUARL6IQCd9K3WYeYHbwz0kiEnqcj8kCTPv7O4n/Hwmhw+WPYv9v3lImyrjk/rmymuYHNKR2JjYh948VRbzlXlNi8a12SlkHNfy/mUUyjqGSrO0KGmc892KQMpoKMu87EFfwXD5MirorEqZRgERGG7//SBJPlQi1KUEiqefpdURsn2swskHn2PwSNxpdLXuT+UAO4tm/IBJ6nh7NACGn55gMoXPep55SseYkpGbS/8xMClgJKWH1u5YmAwNOwoctEbFid9kkQGJSf5EMOvalyj5iMvp7+ntY4FdVLyp8QYu0RF1YNk3GrUrdZR6Qh3X1lCBOVVllQ8JRV283ho7Dqpp94kbc0kyY3PDqzgwxaAnDnRQ48qKS2oZmhe/+iwmKV/AptkhKOEFAmYaZ6p1FatgT2V5u5PimnH0aA6pskhEZmGf3jV6l+FuLjjilXhI7RcHXW8E4wDxl2mnCd8iuqqJwYqiSQmamKEw9VELGUDRomL8n5vs4ethGSsC0FrIzFZV3+n0++9lq+Yc+p0N4Y5dSmjp93nsZ8I9DvrxNFRVNvTUt6A2G3TAq/dalP7Qew854pGqwVxq67Msnt6v3hgd4fz2mXb6XUrxW+O2FpSA10Ytzs6uHjLqnMM89vl4ge3Zcur7xHpJpOfsCbvMtDStRkjfmXI1qoCe1ZqF0IqRn0KniTutp8BU7zSR9hx9Y4y8M4rztZ8Mr7svAyMw9iCd6MzBLwyBP3nMy8N169PIHBsP+oOmnmLv5YFmUXxeFneHz2+jj0Cwb89en4+q99/K26XcrFQ+UI/5G2XuoUaNGjRo1atSoUaNGjRo1atSoUaOGBJ3BKjh9mMyum4w+g+lI/3ryaNEMgsaXq+joJZWhXdf3eMnYHTS3eq1Ed9MBepdY1BPx8ZYYOGo1HN+V3G20VjRDoj9M65P3JaA6zaqJCmb7dYTJgmmdtybZ9KCRrk1U5jW0d6yL5MVuGh1EZMimfAQHKQu1XSdJu0/kmS2SahX/NrR62fL4UZisKU690tGxW+kHaVWI5eJzGMgofoa0zFV6NSNUZ9t9hjCHWfyuQY21tJFza/zWGAFKM6SvDLYCCE0pVGfZHb8Cd8vupJ+E8iNhVzw28+gzPg3ANRhi84tVq0KdD7+M3RlBcLml9HbFFvRwfsk+iE11VsfL4HLSQw9jRoUKCi7esGsqok+6nJSy4WESUjawTXYpG9Txi+/HhLpq4WHC+fJWW5TwQUiXwzZj4WGCO93tDkMAUyilzjhCXTjKF7phdlbLhKARYyfVanCYhDYbbZPVxl3kJNKZyniYhH3iYJvsTngyfRDM2y08TNgPadUxxJtPpMsB0TvJPtfa0g8KSYIhYDlpTTy+3UKiByTh1aqagQfxJF2uIowb7x+SMm5jomdKEgwBD0I6MhqJnvAwGZMEM+CDkEasgegJGbcxSTAD3s1jzriFRA86IOxOUoflpDEkY6KHtsnqMBm8tkbKuE2JXgy/jN2xTqDVpIzbmOhh/7nx5iWAKKB1xm1MEsyABF8akDUmemCbpNEgM0CjpZhxQ/BJeJiMw3JmKJBxT2VEzzgsZ4ij6XJos4WHCdSM3QmAYClWUsYNtEtK9GBOid35OMDXxAQfaIKQ6EGE1PKkf/rKyLUaDUFIiR59u0+WR6jTd02+HDGG4sNEn6HtG7bIOdQIqRPGJj9MSd1mf7x4wr/TCaknmYLGxSDJfKH96U0J86ul1e5dw61Go250P6XSxT13dzpR80jcMXatn+YuiuhmtviVm+gWCLQuyuZJ06LtL+OR3hz1+rY330a4sTDQ2p3BMTgdZtr7jIeHXXAcFOL1/gdZoYA7pM8vNwAAAABJRU5ErkJggg==';
const NUVOLE = 'data:image/png;base64,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';
const TEMPO = array(SOLE, NUVOLE, PIOGGIA);

$previsioni = array();
$giorni = array();
for($i = 0; $i < NEXT; $i++){
    $previsioni[] = TEMPO[rand(0, count(TEMPO)-1)];
    $giorni[] = date('l', time() + $i * 24 * 60 * 60);
}

setcookie('cronologia', json_encode($cronologia), time() + 3600);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsioni del meteo Facenda</title>
</head>
<body>
    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="citta">Città</label>
        <input type="text" name="citta" id="citta" list="cronologia">
        <datalist>
            <?php
            foreach($cronologia as $entry){
                ?>
                <option value="<?= $entry; ?>">
                <?php
            }
            ?>
        </datalist>
        <input type="submit" value="Invia">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        ?>
        <h1>Previsioni per <?= $citta; ?></h1>
        <p>Previsioni per i prossimi <?= NEXT-1; ?> giorni</p>
    
        <table>
            <tr>
                <th>Giorno</th>
                <th>Previsione</th>
            </tr>
            <?php
            for($i = 0; $i < NEXT; $i++){
                ?>
                <tr>
                    <td><?= $giorni[$i]; ?></td>
                    <td>
                        <img src="<?= $previsioni[$i]; ?>" alt="<?= $previsioni[$i]; ?>" width="64" height="64">
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    <?php
    }

    include '../footer.php';
    ?>
</body>
</html>