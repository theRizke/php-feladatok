<?php 

class Team
{
    public $name;
    public $rank;

    public $matches = [];
    public $points = 0;
    public $diff = 0;

    // 1 - csoport; 2 - nyolcaddöntő; 3 - negyeddöntő; 4 - előddöntő; 5 - döntő
    public $status = 1;

    //Konstruktor
    function __construct($name, $rank){
        $this->name = $name;
        $this->rank = $rank;
    }

    //Név lekérése
    function getName(){
        return $this->name;
    }

    //Rank lekérése
    function getRank(){
        return $this->rank;
    }

    //Pontszám lekérése
    function getPoints(){
        return $this->points;
    }

    //Gólkülönbség lekérése
    function getDiff(){
        return $this->diff;
    }

    //Status lekérése
    function getStatus(){
        return $this->status;
    }

    //Status növelése
    function setDiff($i){
        $this->diff += $i;
    }

    //Pont hozzáadása
    function setPoints($point){
        $this->points += $point;
    }

    //Status növelése
    function setNextStatus(){
        $this->status += 1;
    }

    //Status csökkentése
    function setPreviousStatus(){
        $this->status -= 1;
    }

    //Meccs hozzáadása a csapathoz
    function addMatch($match){  
        array_push($this->matches, $match);
    }


}

?>