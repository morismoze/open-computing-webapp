<?php
function containsFunc($input, $path) {
    $queryArray = "contains(php:functionString('mb_strtolower', ". $path ."), '" . mb_strtolower($input) . "')";
    return $queryArray;
}

function intervalFunc($input, $path) {
    $limits = explode("-", $input);
    $lowerLimit = $limits[0];
    $upperLimit = $limits[1];
    $queryArray = "php:functionString('strtok', ". $path .", 'm') >= '" .$lowerLimit. "' and 
    php:functionString('strtok', ". $path .", 'm') <= '" .$upperLimit. "'";
    return $queryArray;
}

function selectFunc($input, $path) {
    $mulString = implode(" ", $input); 
    $queryArray = "contains('" . mb_strtolower($mulString) . "', $path)";
    return $queryArray;
}

function mulSelFunc($input, $path) {
    $queryArray = $path ."='";
    $queryArray .= implode("' or ". $path ."='", $input);
    $queryArray .= "'";
    return $queryArray;
}

function radioFunc($input, $path) {
    $queryArray = "php:functionString('mb_strtolower', ". $path .") = '" . mb_strtolower($input) . "'";
    return $queryArray; 
}

function wikiRESTApi($handle) {
    $url = "https://en.wikipedia.org/api/rest_v1/page/summary/" . $handle;
    $json = file_get_contents($url); 
    $data = json_decode($json, true);
    return $data;
}

function wikiMediaActionApi($handle) {
    $page_id = wikiRESTApi($handle)['pageid'];
    $url = "https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles=".$handle."&format=json";
    $json = file_get_contents($url); 
    $data = json_decode($json, true);
    $revision = $data['query']['pages'][$page_id]['revisions']['0']['*'];
    $location = "";
    if($handle == "Burj_Khalifa") { 
        preg_match('/address\s*=\s*(.*?)\s*\|/', $revision, $addrMtch);
        preg_match('/location\s*=\s*\[*(.*?)\]*\s*\|/', $revision, $cityMtch);
        preg_match('/location_country\s*=\s*\[*(.*?)\]*\s*\|/', $revision, $cntryMtch);
        $addr = str_replace("Sheikh", "Shaikh", $addrMtch[1]);
        $location = $addr." ".$cityMtch[1]." ".$cntryMtch[1];
    } else if($handle == "Shanghai_Tower") { 
        preg_match('/location\s*=\s*(.*?),\s*\[*(.*?)\]*,\s*\[*(.*?)\]*,\s*\[*(.*?)\]*\s*\|/', $revision, $locMtch);
        $location = $locMtch[1]." ".$locMtch[4];
    } else if($handle == "Abraj_Al_Bait") {
        $xml_podaci = simplexml_load_file("./podaci.xml");
        $addr = (string) $xml_podaci->xpath("neboder/adresa/ulica[../../naziv/osnovni-naziv = 'Abraj Al Bait Clock Tower']")[0];
        preg_match('/location\s*=\s*\[*(.*?)\]*,\s*(.*?)\s*\|/', $revision, $locMtch);
        $location = $addr." ".$locMtch[1]." ".$locMtch[2];
    } else if($handle == "Ping_An_Finance_Centre") {
        preg_match('/location\s*=\s*\w+\.\s*(\d*)\s*\[*.*?\|(.*?)\]*,\s*\[*(.*?)\]*,\s*\[*(.*?)\]*,\s*\[*(.*?)\]*,\s*(.*?)\s*\|/', $revision, $locMtch);
        $location = $locMtch[1].", ".$locMtch[2].", ".$locMtch[3].", ".$locMtch[4].", ".$locMtch[5].", ".$locMtch[6];
    } else if($handle == "Goldin_Finance_117") {
        //ova zgrada nema broj, pa ju je nemoguće pretražiti te stoga pretragu radim po imenu
        $location = "goldin finance 117";
    } else {
        $xml_podaci = simplexml_load_file("./podaci.xml");
        $addr = (string) $xml_podaci->xpath("neboder/adresa/ulica[../../naziv/osnovni-naziv = 'Lotte World Tower']")[0];
        preg_match('/location\s*=\s*\[*(.*?)\]*,\s*\[*(.*?)\]*\s*\|/', $revision, $locMtch);
        $location = $addr." ".$locMtch[1]." ".$locMtch[2];
    }

    //konacno, vrati koordinate sa Nominatima
    return nominatimApi($location);
}

function nominatimApi($location) {
    $options = array('http' => array('user_agent' => 'mm50180@fer.hr'));
    $context = stream_context_create($options);
    $url = "http://nominatim.openstreetmap.org/search?q=".urlencode($location)."&format=xml";
    $response = file_get_contents($url, false, $context);
    $xmlresponse = simplexml_load_string($response);
    $coordinates = array();
    $lat = (string) $xmlresponse->xpath("//@lat")[0];
    $lon = (string) $xmlresponse->xpath("//@lon")[0];
    array_push($coordinates, $lat, $lon);

    return $coordinates;
}
?>