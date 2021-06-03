<?php
/*
    file:   Koodihaaste/index.php
    desc:   Koodihaasteen yksinkertainen ratkaisu käyttäen php:ta

*/

$kulutusKerroin=1.009;
function sekunnitAjaksiKertoen($ss) {
  $s = $ss%60;
  $m = floor(($ss%3600)/60);
  $h = floor(($ss%86400)/3600);
  $d = floor(($ss%2592000)/86400);
  $palautus='';
  
  if($d<>0)$palautus.=$d.' päivää ';
  $palautus.=$h.' tuntia ';
  $palautus.=$m.' minuuttia ';
  $palautus.=$s.' sekuntia';
  return($palautus);
}

if(isset($_GET['auto1'])) $auto1=$_GET['auto1']; else $auto1='';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Matkalaskuri</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <p></p> 
	<h3 class="alert alert-info">Matkalaskuri</h3>
    <form action="valinta.php" method="post">   
      <h4>Valitse auto, nopeus ja matka</h4>
      <input type="hidden" name="auto1" required>
      <label for="auto1">Valitse auto:</label>
        <select class="form-group" name="auto1">
            <option value=''>--Valitse auto--</option>
            <option value="3"<?php if($auto1==3) echo ' selected'?>>Auto A: Kulutus 3l/100km @1km/h</option>
            <option value="3.5"<?php if($auto1==3.5) echo ' selected'?>>Auto B: Kulutus 3.5l/100km @1km/h</option>
            <option value="4"<?php if($auto1==4) echo ' selected'?>>Auto C: Kulutus 4l/100km @1km/h</option>
        </select>
        
        <div class="form-group">
          <label for="matka">Anna matka kilometreinä:</label>
          <input type="number" class="form-control"  min="1" name="matka" value="<?php if(isset($_GET['matka'])) echo $_GET['matka'];else echo ''?>" required>
        </div>
        <div class="form-group">
          <label for="nopeus1">Nopeus yksi:</label>
          <input type="number" class="form-control" min="1" max="500" name="nopeus1" value="<?php if(isset($_GET['nopeus1'])) echo $_GET['nopeus1'];else echo ''?>" required>
        </div>
        <div class="form-group">
          <label for="nopeus2">Nopeus kaksi:</label>
          <input type="number" class="form-control" min="1" max="500" name="nopeus2" value="<?php if(isset($_GET['nopeus2'])) echo $_GET['nopeus2'];else echo ''?>" required>
        </div>
      <button type="submit" class="button">Suorita valinnat</button>      
    </form>
  <p>
    <?php
      if(isset($_GET['auto1'])) $auto1=$_GET['auto1'];
      if(isset($_GET['nopeus1'])) $nopeus1=$_GET['nopeus1'];
      if(isset($_GET['nopeus2'])) $nopeus2=$_GET['nopeus2']; 
      if(isset($_GET['matka'])) $matka=$_GET['matka']; else $matka='';
      if(!empty($auto1)&&(!empty($nopeus1))&&(!empty($nopeus2))&&(!empty($matka))){
          // asetetaan kovemman nopeus ensimmäiseen muuttujaan niin logiikka pysyy samana loppuun asti
          if ($nopeus2<$nopeus1) {
            $laskentaNopeus1=$nopeus1;$laskentaNopeus2=$nopeus2;
          }else{$laskentaNopeus1=$nopeus2;$laskentaNopeus2=$nopeus1;}

          // lasketaan ajat sekunteina ja matkat metreinä si-standardin arvoja käyttäen
          $aika1=($matka*1000)/($laskentaNopeus1/3.6);
          $aika2=($matka*1000)/($laskentaNopeus2/3.6);

          // lasketaan käytetty polttoainemäärä nopeuksille
          $kulutus1=$auto1*pow($kulutusKerroin,($laskentaNopeus1-1));
          $kulutus2=$auto1*pow($kulutusKerroin,($laskentaNopeus2-1));
          $litrat1=($matka*$kulutus1)/100;
          $litrat2=($matka*$kulutus2)/100;
          $aikaErotus=$aika2-$aika1;
          $kulutusErotus=$litrat1-$litrat2;          
          if($aika1<>$aika2){
            echo ('<div class="alert alert-warning" role="alert">');
            echo ('Nopeamman auton kulutus on '.round($kulutus1,3).' l/100km. Kokonaiskulutus matkalla oli '.round($litrat1,3).' litraa <br>');
            echo ('Aikaa matkaan meni '.sekunnitAjaksiKertoen($aika1).'.');
            echo ('</div>');
            echo ('<div class="alert alert-success" role="alert">');
            echo ('Taloudellisemman matkavauhdin kulutus on '.round($kulutus2,3).' l/100km/h. Kokonaiskulutus matkalla oli '.round($litrat2,3).' litraa<br>');
            echo ('Aikaa matkaan meni '.sekunnitAjaksiKertoen($aika2).'.');
            echo ('</div>');
            echo ('<div class="alert alert-info" role="alert">');
            echo ('Valittu kovempi nopeus kulutti '.round($kulutusErotus,3).' litraa enemmän polttoainetta matkalla.<br>');
            echo (' Perillä oltiin '.sekunnitAjaksiKertoen($aikaErotus).' nopeammin.');
            echo ('</div>');
          }else{
            echo ('<div class="alert alert-primary" role="alert">');
            echo ('Autojen kulutus on '.round($litrat1,2).' litraa.<br> Aikaa matkaan meni '.sekunnitAjaksiKertoen($aika1).'. ');
            echo ('Valitsit saman nopeuden autoille!<br>Valinnoilla ei synny kulutus- tai aikaeroa matkalle!</div>');
          }
        }else{
          ?>
          <div class="alert alert-danger" role="alert">
            Valitse auto!
          </div>
          <?php
      }
    ?>
  </p>  
  </div>
</body>
</html>
