<?php


$multiArray = array(
    "musicals" => array(
        "Oklahoma", "The Music Man", "South Pacific"
    ),
    "dramas" => array(
        "Lawrence of Arabia", "To Kill a Mockingbird", "Casablanca"
    ),
    "mysteries" => array(
        "The Maltese Falcon", "Rear Window", "North by Northwest"
    )
);

echo "Before Sorting: " ;
foreach($multiArray as $key => $array) {
    echo strtoupper($key);
    echo "<br/>";
    foreach($array as $index => $item) {
        echo "----> $index = $item";
        echo "<br/>";
    }
}

echo "<br/>";

krsort($multiArray);

echo "After Sorting: "  ;
foreach($multiArray as $key => $array) {
    echo strtoupper($key);
    echo "<br/>";
    foreach($array as $index => $item) {
        echo "----> $index = $item";
        echo "<br/>";
    }
}