<?php
error_reporting( E_ALL );
include('funkcije.php');
$dom = new DOMDocument();
$dom->load("podaci.xml");

$xp = new DOMXPath($dom);
$xp->registerNamespace('php', 'http://php.net/xpath');
$xp->registerPhpFunctions();
$init = "/kolekcija-nebodera/neboder";

if(!empty($_GET['id'])) {
    $id = $_GET['id'];
    $result = $xp->query($init."[@id='".$id."']");
}
?>

<?php   
        sleep(3);
        ob_implicit_flush(true);

        foreach($result as $node) {
            if(isset($node->getElementsByTagName('drugi-naziv')->item(0)->nodeValue)) {
                print("<div style='margin-top: 10px;'>Drugi naziv:</br>".$node->getElementsByTagName('naziv')->item(0)->getElementsByTagName('drugi-naziv')->item(0)->nodeValue."</div>");
            } else {
                print("<div style='margin-top: 10px;'>Drugi naziv:</br>nema drugog naziva</div>");
            }

            if(isset($node->getElementsByTagName('cijena')->item(0)->nodeValue)) {
                print("<div style='margin-top: 10px;'>Cijena (\$mlrd):</br>".$node->getElementsByTagName('cijena')->item(0)->nodeValue."</div>");
            } else {
                print("<div style='margin-top: 10px;'>Cijena:</br>nema cijenu</div>");
            }

            if($node->getElementsByTagName('gradnja')->item(0)->getAttribute('materijal') !== null) {
                print("<div style='margin-top: 10px;'>Materijal gradnje:</br>".$node->getElementsByTagName('gradnja')->item(0)->getAttribute('materijal')."</div>");
            } else {
                print("<div style='margin-top: 10px;'>Materijal:</br>nema materijala</div>");
            }

            if(isset($node->getElementsByTagName('povrsina')->item(0)->nodeValue)) {
                print("<div style='margin-top: 10px;'>Površina (m&#178;):</br>".$node->getElementsByTagName('povrsina')->item(0)->nodeValue."</div>");
            } else {
                print("<div style='margin-top: 10px;'>Površina:</br>nema povrsinu</div>");
            }

            if(isset($node->getElementsByTagName('datum-pocetka-gradnje')->item(0)->nodeValue)) {
                print("<div style='margin-top: 10px;'>Početak gradnje:</br>".$node->getElementsByTagName('datum-pocetka-gradnje')->item(0)->nodeValue."</div>");
            } else {
                print("<div style='margin-top: 10px;'>Početak gradnje:</br>nema povrsinu</div>");
            }
        }
?>