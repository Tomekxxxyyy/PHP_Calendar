<!DOCTYPE html>
<html>   
<head>
<title>Dodawanie terminów</title>
<meta charset = "utf-8">
</head>
<body>
<h1>Terminy</h1>
<?php
$mysqli = mysqli_connect("localhost", "tomek", "", "kalendarz");

if($_POST){
    $m = $_POST["m"];
    $d = $_POST["d"];
    $r = $_POST["r"];
    
    $data_terminu = $r."-".$m."-".$d." ".$_POST["czas_terminu_gg"].":".$_POST["czas_terminu_mm"].":00";
    $wstawTermin_sql = "INSERT INTO kalendarz_terminy(nazwa_terminu, opis_terminu, data_rozpoczecia) VALUES('"
            .$_POST["nazwa_terminu"]."', '".$_POST["opis_terminu"]."', '$data_terminu')";
    $wstawTermiin_rez = mysqli_query($mysqli, $wstawTermin_sql) or die(mysqli_error($mysqli));
}else{
    $m = $_GET["m"];
    $d = $_GET["d"];
    $r = $_GET["r"];
}
$pobierzTermin_sql = "SELECT nazwa_terminu, opis_terminu, date_format(data_rozpoczecia, '%l:%i:%p') "
        . "as data_sformatowana FROM kalendarz_terminy WHERE month(data_rozpoczecia) = '$m' "
        . "AND dayofmonth(data_rozpoczecia) = '$d' AND year(data_rozpoczecia) = '$r' ORDER BY data_rozpoczecia";
$pobierzTermin_rez = mysqli_query($mysqli, $pobierzTermin_sql) or die(mysqli_error($mysqli));

if(mysqli_num_rows($pobierzTermin_rez) > 0){
    $tekst_terminu = "<ul>";
    while($ev = mysqli_fetch_array($pobierzTermin_rez)){
        $nazwa_terminu = stripslashes($ev["nazwa_terminu"]);
        $opis_terminu = stripslashes($ev["opis_terminu"]);
        $data_sformatowania = $ev["data_sformatowana"];
        $tekst_terminu .= "<li><strong>".$data_sformatowania."</strong>:".$nazwa_terminu."<br>".$opis_terminu."</li>";
    }
    $tekst_terminu .= "</ul>";
    mysqli_free_result($pobierzTermin_rez);
}else{
    $tekst_terminu = "";
}
mysqli_close($mysqli);

if($tekst_terminu != ""){
    echo "<p><strong>Dzisiejsze terminy:</strong></p>$tekst_terminu<hr />";
}
echo "<form method = 'POST' action = ''>"
    . "<p><strong>Czy chcesz dodać nowy termin ?</strong></p>"
    . "<p>Wypełnij poniższy formularz i naciśnij przycisk Dodaj, po czym odśwież bieżące okno</p>"
    . "<p><strong>Nazwa terminu</strong><br><input type = 'text' name = 'nazwa_terminu' size = '25' maxlength = '25'></p>"
    . "<p><strong>opis terminu:</strong><br><input type = 'text' name = 'opis_terminu' size = '25' maxlength = '255'></p>"
    . "<p><strong>Czas rozpoczęcia (gg:mm):</strong>"
    . "<select name = 'czas_terminu_gg'>";
    for($x=1; $x<=24; $x++){
        echo "<option value = '$x'>$x</option>";
    }
echo "</select>"
    . "<select name = 'czas_terminu_mm'>"
    . "<option value = '00'>00</option><option value = '15'>15</option>"
    . "<option value = '30'>30</option><option value = '45'>45</option></select>"
    . "<input type = 'hidden' name = 'm' value = '$m'>"
    . "<input type = 'hidden' name = 'd' value = '$d'>"
    . "<input type = 'hidden' name = 'r' value = '$r'>"
    . "<br>"
    . "<input type = 'submit' name = 'submit' value = 'Dodaj'>"
    . "</form>";
?>
</body>
</html>



