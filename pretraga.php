<?php
error_reporting( E_ALL );
include('funkcije.php');
$dom = new DOMDocument();
$dom->load("podaci.xml");

$xp = new DOMXPath($dom);
$xp->registerNamespace('php', 'http://php.net/xpath');
$xp->registerPhpFunctions();
$queryArray = array();
$init = "/kolekcija-nebodera/neboder";

if(!empty($_GET['naziv'])) {
    $queryArray[] = containsFunc(trim($_GET['naziv']), "./naziv/osnovni-naziv/text()");
}

if(!empty($_GET['mjesto'])) {
    $queryArray[] = containsFunc(trim($_GET['mjesto']), "./adresa/mjesto/text()");
}

if(!empty($_GET['materijal'])) {
    $queryArray[] = containsFunc(trim($_GET['materijal']), "./gradnja/@materijal");
}

if(!empty($_GET['vlasnik'])) {
    $queryArray[] = containsFunc(trim($_GET['vlasnik']), "./vlasnik/text()");
}

if(!empty($_GET['arhitekt'])) {
    $queryArray[] = "./arhitekt[contains(php:functionString('mb_strtolower', ./text()), '" . mb_strtolower($_GET['arhitekt']) . "')]";
}

if(isset($_GET['mulselect'])) {
    $queryArray[] = mulSelFunc($_GET['mulselect'], "./gradnja/status");
}

if(isset($_GET['radio'])) {
    $queryArray[] = intervalFunc($_GET['radio'], "./visina/text()");
}

if(isset($_GET['checkbox'])) {
    $queryArray[] = selectFunc($_GET['checkbox'], "./@funkcija");
}

if(!empty($_GET['select2'])) {
    $queryArray[] = intervalFunc($_GET['select2'], "./povrsina");
}

if(!empty($_GET['select3'])) {
    $queryArray[] = intervalFunc($_GET['select3'], "./cijena");
}

if(!empty($queryArray)) {
    $query = implode(" and ", $queryArray);
    $result = $xp->query($init . "[" . $query . "]");
    //echo $init . "[" . $query . "]";
} else {
    $result = $xp->query($init);
    /* echo "<pre>"; 
    print_r(wikiMediaActionApi("Ping_An_Finance_Centre"));
    echo "</pre>"; */
}
?>

<!DOCTYPE html>
<html lang="hr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="dizajn.css"/>
    <title>Najviši neboderi na svijetu | Rezultati</title>
</head>
<body>
    <div id="main-cnt">
        <header id="header">
            <div id="h1-div">
                <h1 id="main-title">Najviši neboderi svijeta</h1>
            </div>
            <div id="img-div" role="button" onclick="location.href='index.html'"></div>
        </header>
        <div id="main-inner-cnt">
            <div id="left-inner-cnt">
                <nav id="nav">
                    <ul id="list-cnt">
                        <li class="list"><a href="index.html">Početna</a></li>
                        <li class="list"><a href="obrazac.html">Pretraživanje</a></li>
                        <li class="list"><a href="http://www.fer.unizg.hr/predmet/or">Predmet OR</a></li>
                        <li class="list"><a href="http://www.fer.unizg.hr/" target="_blank">Sjedište FER-a</a></li>
                        <li class="list"><a href="mailto:mm50180@fer.hr">Mail autoru</a></li>
                        <li class="list"><a href="podaci.xml">XML podaci</a></li>
                    </ul>
                </nav>
            </div>
            <div id="right-inner-cnt">
                <div id="search-table-div">
                    <form class="form-cnt" method="GET" action="pretraga.php">
                        <div class="input-div">
                            <label for="name-inp">Naziv nebodera:</label>
                            <br/>
                            <input type="text" id="name-inp" name="naziv" placeholder="Upišite naziv, npr. 'Burj Khalifa'"/>
                            <br/>
                            <label for="location-inp">Mjesto nebodera:</label>
                            <br/>
                            <input type="text" id="location-inp" name="mjesto" placeholder="Upišite mjesto, npr. 'Dubai'"/>
                            <br/>
                            <label for="owner-inp">Materijal:</label>
                            <br/>
                            <input type="text" id="material" name="materijal" placeholder="Upišite materijal, npr. 'ojačani-beton'"/>
                            <br/>
                            <label for="height-inp">Vlasnik nebodera:</label>
                            <br/>
                            <input type="text" id="height-inp" name="vlasnik" placeholder="Upišite vlasnika, npr. 'Emaar Properties'"/>
                            <br/>
                            <label for="architect-inp">Arhitekt nebodera:</label>
                            <br/>
                            <input type="text" id="architect-inp" name="arhitekt" placeholder="Upišite arhitekta, npr. 'Hyder Consulting'"/>
                        </div>
                        <div class="multiple-select-div-and-radio">
                            <label for="multiple-select">Status<br/>gradnje:</label>
                            <select id="multiple-select" multiple="multiple" name="mulselect[]" style="width: 9.5em;">
                                <option id="zavrsena-gradnja" value="Završena gradnja">Završena gradnja</option>
                                <option id="u-tijeku" value="U tijeku">U tijeku</option>
                                <option id="nije-dovrseno" value="Nije dovršeno">Nije dovršeno</option>
                            </select>
                            <div class="radio-cnt">
                                <p style="font-weight: bolder;">Visina(m):</p>
                                <input type="radio" id="0-299" name="radio" value="0-299"/>
                                <label for="0-299">0-299</label><br/>
                                <input type="radio" id="300-629" name="radio" value="300-629"/>
                                <label for="300-629">300-629</label><br/>
                                <input type="radio" id="630-829" name="radio" value="630-829"/>
                                <label for="630-829">630-829</label>                                 
                            </div>
                        </div>
                        <div class="checkbox-div">
                            <label >Funkcija nebodera:</label>
                            <div class="function-div" id="function-cnt">
                                <label for="office">Uredska</label>
                                <input type="checkbox" id="office" value="uredska" name="checkbox[]"/>
                                <label for="office">Rezidencijska</label>
                                <input type="checkbox" id="residential" value="rezidencijska" name="checkbox[]"/>
                                <label for="office">Hotelska</label>
                                <input type="checkbox" id="hotel" value="hotelska" name="checkbox[]"/>
                                <label for="office">Trgovačka</label>
                                <input type="checkbox" id="retail" value="trgovačka" name="checkbox[]"/>
                            </div>
                        </div>
                        <div>
                        <label for="sel-area" style="font-weight: bolder;">Površina(m&sup2;):</label>
                            <select id="sel-area" name="select2" style="width: 6em; margin-right: 10px;">
                                <option value="" selected="selected" disabled="disabled" style="background-color: #a8a8a8;"></option>
                                <option id="0-305000" value="0-305000">0-305000</option>
                                <option id="306000-379000" value="306000-379000">306000-380000</option>
                                <option id="380000-390000" value="380000-390000">&gt;380000</option>
                            </select>
                            <label for="sel-cost" style="font-weight: bolder; margin-left: 10px;">Cijena(US&#36;):</label>
                            <select id="sel-cost" name="select3" style="width: 6.5em;">
                                <option value="" selected="selected" disabled="disabled" style="background-color: #a8a8a8;"></option>
                                <option id="0-1.5" value="0-1.5">0-1.5 mlrd</option>
                                <option id="1.6-2.4" value="1.6-2.4">1.6-2.4 mlrd</option>
                                <option id="2.5-15.1" value="2.5-15.1">&gt;2.5 mlrd</option>
                            </select>
                        </div>
                        <div class="buttons">
                            <input type="submit" value="Pretraži"/>
                            <input type="reset" value="Obriši sva polja"/>
                        </div>
                    </form>
                    <div id="search-title-div">
                        <?php if($result->length == 0): ?>
                        <h2 style="text-align: center; margin-top: 1em;">Za traženi upit nema rezultata.</h2>
                        <?php else: ?>
                        <table class="form-results" style="font-size: 14px;">
                            <br/>
                            <tr>
                                <th>Naziv</th>
                                <th>Visina</th>
                                <th>Slika</th>
                                <th>Sažetak</th>
                                <th>WikiREST koordinate</th>
                                <th>Nominatim koordinate</th>
                            </tr>
                            <?php
                                foreach($result as $node) {
                                    $wikiRest = wikiRESTApi($node->getAttribute('wikihandle'));
                                    $orgImage = $wikiRest['originalimage']['source'];
                                    $thmbImage = $wikiRest['thumbnail']['source'];
                                    $summary = substr($wikiRest['extract'], 0, 70) . "...";
                                    $lat = $wikiRest['coordinates']['lat'];
                                    $lon = $wikiRest['coordinates']['lon'];
                                    $nominatimCoords = wikiMediaActionApi($node->getAttribute('wikihandle'));
                                    $nominatimLat = $nominatimCoords[0];
                                    $nominatimLon = $nominatimCoords[1];
                                    echo "<tr><td>";
                                    print($node->getElementsByTagName('naziv')->item(0)->getElementsByTagName('osnovni-naziv')->item(0)->nodeValue);
                                    echo "</td><td>";
                                    print($node->getElementsByTagName('visina')->item(0)->nodeValue);
                                    echo "</td><td>";
                                    echo("<a href='".$orgImage."' target='_blank'><img src='".$thmbImage."' width='40px' height='auto'></img></a>");
                                    echo("</td><td style='max-width: 100px; font-size: 10px;'>");
                                    echo($summary);
                                    echo "</td><td>";
                                    echo "Lat: ".$lat."<br/>Lon: ".$lon;
                                    echo "</td><td>";
                                    echo "Lat: ".$nominatimLat."<br/>Lon: ".$nominatimLon;
                                    echo "</td><tr>";
                                }
                            ?>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="footer">
                    <label>Autor:</label><strong> Moris Može</strong>
                    <p id="about">Web sjedište nekoliko najviših nebodera na svijetu.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>