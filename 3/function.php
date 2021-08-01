<?php

//Megkapott dátum és pontos idő
$begin = new DateTime($_POST['date']);
$end   = new DateTime("now");

$counter = 0;

//Ha a megadott dátum kisebb mint a mai, akkor lefut
if ($begin <= $end) {

    echo "<button class='btn btn-dark' type='button' data-bs-toggle='collapse' data-bs-target='#datelist' aria-expanded='false' aria-controls='datelist'>Dátumok</button>";
    echo "<ul class='list-group col-12 col-lg-4 collapse' id='datelist'>";

    //A kezdeti dátumtól a mai napig
    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {

        $timestamp = strtotime($i->format("Y-m-d"));
        $dayName = date('l', $timestamp);
        $day = date('d', $timestamp);

        if ($dayName == "Sunday" && $day === "01") {
            echo "<li class='list-group-item'>". ($counter+1) . ". " .$i->format("Y-m-d") . "</li>";
            $counter++;
        }
    }

    echo "</ul>";
    echo "<div>" . $_POST['date'] . " óta összesen eddig <span class='h2'>" . $counter . "</span> ilyen nap volt.</div>";   
} 

    else {
        echo "Adj meg a mai napnál korábbi dátumot";
}

