<?php
    $mysqli = mysqli_connect("localhost", "tomek", "", "kalendarz");

    define("DZIEN", (60*60*24));
    if(!isset($_POST["miesiac"]) || (!isset($_POST["rok"]))){
        $tablicaTeraz = getdate();
        $miesiac = $tablicaTeraz["mon"];
        $rok = $tablicaTeraz["year"];
    }else{
        $miesiac = $_POST["miesiac"];
        $rok = $_POST["rok"];
    }
    $start = mktime(12, 0, 0, $miesiac, 1, $rok);
    $tablicaPierwszyDzien = getdate($start);
    $miesiace = array("styczeńxxx", "lutyxxxx", "marzec", "kwiecień", "maj", "czerwiec", "lipiec",
        "sierpień", "wrzesień", "pażdziernik", "listopad", "grudzień");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo "Kalendarz: ".$miesiace[$tablicaPierwszyDzien["mon"] -1]." ".$tablicaPierwszyDzien["year"]; ?></title>
</head>
<body>
    <h1>Wybierz miesiąc i rok</h1>
    <form method = "POST" action = "<?php echo $_SERVER["PHP_SELF"]; ?>">
        <select name = "miesiac">
        <?php
        for($x=1; $x<=count($miesiace); $x++){
            echo "<option value ='$x'";
            if($x == $miesiac){
                echo "selected";
            }
            echo ">".$miesiace[$x - 1]."</option>";
        }
        ?>
        </select>
        <select name = "rok">
        <?php
        for($x = 1990; $x <= 2020; $x++){
            echo "<option value = '$x'";
            if($x == $rok){
                echo "selected";
            }
            echo ">$x<option>";
        }
        ?>
        </select>
        <input type = "submit" name = "submit" value = "Już!">
    </form>
    <br>
    <?php
    $dni = array("Nie", "Pon", "Wto", "Śro", "Czw", "Pią", "Sob");
    echo "<table border ='1' cellpadding = '5'><tr>";
    foreach($dni as $dzien){
        echo "<td style = 'background-color: #CCCCCC; text-align: center; width: 14%;'><strong>$dzien</strong></td>";
    }
    for($licznik=0; $licznik < (6*7); $licznik++){
        $tablicaDzien = getdate($start);
        if(($licznik % 7) == 0){
            if($tablicaDzien["mon"] != $miesiac){
                break;
            }else{
                echo "</tr><tr>";
            }
        }
        if($licznik < $tablicaPierwszyDzien["wday"] || $tablicaDzien["mon"] != $miesiac){
            echo "<td>&nbsp;</td>";
        }else{
            $sprawdzTermin = "SELECT nazwa_terminu FROM kalendarz_terminy WHERE month(data_rozpoczecia) = '"
                    .$miesiac."' AND dayofmonth(data_rozpoczecia) = '".$tablicaDzien["mday"]."' AND year(data_rozpoczecia) ='"
                    .$rok."' ORDER BY data_rozpoczecia";
            $sprawdzonyTermin = mysqli_query($mysqli, $sprawdzTermin) or die(mysqli_error($mysqli));
            if(mysqli_num_rows($sprawdzonyTermin) > 0){
                $nazwa_terminu = "<br>";
                while($termin = mysqli_fetch_array($sprawdzonyTermin)){
                    $nazwa_terminu .= stripslashes($termin["nazwa_terminu"])."<br>";
                }
                mysqli_free_result($sprawdzonyTermin);
            }
            else{
                $nazwa_terminu = "";
            }
            echo "<td valign = 'top'><a href = \"javascript:eventWindow('terminy.php?m=".$miesiac."&d="
                    .$tablicaDzien["mday"]."&r=".$rok."');\">".$tablicaDzien["mday"]."</a><br>".$nazwa_terminu."</td>";
            $start += DZIEN;
        }
    }
    echo "</tr></table>";
    ?>
    <script>
        function eventWindow(url){
            var event_popupWin = window.open(url, 'event', 'resizable=yes, scrollbars=yes, toolbar=no, width=400, height=400');
            event_popupWin.opener = self;
        }
    </script>
    </body>
</html>
