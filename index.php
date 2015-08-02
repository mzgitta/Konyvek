<!DOCTYPE html>
<?php
session_start();

if (isset($_POST['iro'])) {


    $db = mysqli_connect('localhost', 'root', 'Lyel_Sz00', 'konyvek');
    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    mysqli_set_charset($db, 'utf8');

    $iro = mysqli_real_escape_string($db, $_POST['iro']);
    $cime = mysqli_real_escape_string($db, $_POST['cim']);
    $sorozat = mysqli_real_escape_string($db, $_POST['sorozat']);
    $resz = mysqli_real_escape_string($db, $_POST['resz']);
    $megjegyzes = mysqli_real_escape_string($db, $_POST['megjegyzes']);
    $keresett = mysqli_real_escape_string($db, $_POST['keresett']);

    $mobi = (isset($_POST['mobi'])) ? 1 : 0;
    $azw = (isset($_POST['azw'])) ? 1 : 0;
    $pdf = (isset($_POST['pdf'])) ? 1 : 0;

// TODO: fájl feltöltése
    $kep = $_FILES['kep']['name'];
    move_uploaded_file($_FILES['borito']['tmp_name'], 'boritokepek/' . $kep);

// SQL lekérdezés futtatása:
    $sql = "INSERT INTO `konyvek` "
            . "(iro,cim,sorozat,resz,megjegyzes,mobi,azw,pdf,borito) VALUES "
            . "('$iro','$cime','$sorozat','$resz','$megjegyzes',$mobi,$azw,"
            . "$pdf,'$kep')";

//        echo $sql;
    mysqli_query($db, $sql);
    if (mysqli_errno($db)) {
        die(mysqli_error($db));
    }

// Adatbázis kapcsolat bezárása:
    mysqli_close($db);
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Könyveim</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
        <header class="container-fluid" id="header">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <h1 class="col-sm-4">Könyveim</h1>
                        <nav class="col-sm-8">
                            <a href="https://www.dropbox.com/home">Dropbox</a>
                            <a href="https://drive.google.com/drive/u/0/my-drive">Drive</a>
                            <a href="http://www.canadahun.com/forums/leg%C3%A1lis-e-bookok.199/">CanadaHun</a>
                            <a href="http://e-konyvtar.com/library/index.php?op=5">e-könyvtár</a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>>

        <form method="POST" enctype="multipart/form-data">
            <div class="container" >
                <div class="row">
                    <div class="col-md-4 col-xs-12 col-sm-4" id="bevitel">
                        <h2>Bevitel</h2>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 col-sm-8" id="tartalom">
                                    <label>Író </label>
                                    <input type="text"  name="iro" id="nev">                                 

                                    <label>Cím</label>
                                    <input type="text" name="cime" id="cim">

                                    <label>Sorozat</label>
                                    <input type="text" name="sorozat" id="sorozat">

                                    <label>Sorozatrész</label>
                                    <input type="text" name="resz" id="resz">

                                    <label>
                                        <input type="checkbox" id="check" name="mobi">mobi 
                                        <input type="checkbox" id="check" name="azw">azw 
                                        <input type="checkbox" id="check" name="pdf">pdf
                                    </label>

                                    <label>Borítókép:</label>
                                    <input type="file" name="kep" id="kep">

                                    <textarea name="megjegyzes" id="megjegyzes"></textarea>

                                    <input class="btn btn-default" type="submit" id="kuld" value="Küldés">


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container" >
                        <div class="row">
                            <div class="col-md-4 col-xs-12 col-sm-4" id="kereso">
                                <h2>Keresés</h2>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-8" id="tartalom">
                                            <label>Író </label>
                                            <input type="text"  name="keresett" id="keresett">                                 



                                            <input type="submit" name="keres" id="keres" value="Keres">

                                            <button onclick="kereso()">Keres</button>

                                            <?php
                                            $keresett = mysqli_real_escape_string($db, $_POST['keresett']);

                                            $db = mysqli_connect('localhost', 'root', 'Lyel_Sz00', 'konyvek');
                                            if (mysqli_connect_errno()) {
                                                die(mysqli_connect_error());
                                            }
                                            mysqli_set_charset($db, 'utf8');



                                          

                                            echo '<h2>Könyvek</h2>';
                                            $query = "SELECT * FROM konyvek WHERE iro LIKE '%". $_POST['keresett'] ."%'";
                                            $result = mysqli_query($db, $query);
                                            if (mysqli_errno($db)) {
                                                echo mysqli_error($db);
                                            }
                                            // eredmény feldolgozása:
                                            echo '<table>'; // !! border helyett css !!!
                                            echo '<tr><th>Cím<th>Író<th>Sorozat<th>Sorozatrész<th>mobi<th>azw<th>pdf'; // *
                                            while ($row = mysqli_fetch_object($result)) { // *
                                                echo '<tr><td>' . $row->konyvcim; // *
                                                echo '<td>' . $row->nev; // *
                                                echo '<td>' . $row->sorozatcim;
                                                echo '<td>' . $row->sorozatresz;
                                                echo '<td>' . $row->formatum; // *
                                            }
                                            echo '</table>';
                                            
                                            ?> 

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>    
</html>
