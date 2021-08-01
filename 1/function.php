<?php
echo "<div class='h1 text-center'>Project Euler 753</div>";

//Futási idő
$time_start = microtime(true); 

$number = 500;
$primeNumbers = primeList($number);

//Prímszámok kikeresése a 
function primeList($max){
    $primeNumbers = array();
    for($i = 2; $i <= $max; $i++){
        $prime = true;
        for($divider = 2; $divider <=  sqrt($i) ; $divider++)
        {
            if($i % $divider == 0)
            {
                $prime = false;
            }
        }
        if($prime){
            array_push($primeNumbers, $i);
        } 
    }

    return $primeNumbers;
}


function equationSolver($number){
    $sum = 0;
    for($a = 1; $a < $number; $a++){
        for($b = 1; $b < $number; $b++){
            for($c = 1; $c < $number; $c++)
             {
                // if((pow($a, 3) + pow($b, 3)) % $number == ((pow($c, 3) % $number))){
                //     $sum = $sum + 1;
                // } -> 421-ig fut le

                // if(( ($a+$b)*($a*$a - $a*$b + $b* $b) ) % $number == (($c*$c*$c) % $number)){
                //     $sum = $sum + 1;
                // } -> 453-ig fut le

                if(( ($a*$a*$a + $b*$b*$b)) % $number == (($c*$c*$c) % $number)){
                    $sum = $sum + 1;
                } // ->503-ig fut le





             }
        }
    }
    return $sum;
}


$sum = 0;
foreach ($primeNumbers as $prime)
{
    $case = equationSolver($prime);
    $sum += $case;
    echo "<div>F(" . $prime . ") = " . $case . "</div>";
    $case = 0;
}

echo "<div class='text-center'> A megadott feladat leírása szerint összesen <p class='h2'>" . $sum . "</p> esetben igaz az egyenlet <u>". $number . "-ig</u> található prímeknél. </div> <br>";

$time_elapsed_secs = microtime(true) - $time_start;

echo "<div class='text-center' style='font-size: .75rem'>Futási idő: " . round($time_elapsed_secs, 2) . " sec </div>";


?>