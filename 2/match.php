<?php

//Absztrakt meccs osztály (nem példányosítható közvetlen)
abstract class PlayMatch{

    public $matchID;
    public Team $teamA;
    public Team $teamB;

    public $goalTeamA;
    public $goalTeamB;
    public $winner;

    public $matchResult;

    //Meccs eredménye
    function getMatchResult(){
        return $this->matchResult;
    }

    //Meccs győztese
    function getWinner(){
        return $this->winner;
    }

    //Meccs eredmény mentése: TeamA - TeamB: 1-1
    function saveMatchInfo(){
        $teamA = $this->teamA->getName();
        $teamB = $this->teamB->getName();

        $resultString = $teamA . " - " . $teamB . " " . $this->goalTeamA . ":" . $this->goalTeamB;
        fwrite($GLOBALS['logfile'], $resultString . "\n");
        $this->matchResult = $resultString;
    }


    //Mérkőzés góljainak megadása
    function setGoals($teamA, $teamB, $result){
        //Ha A csapat nyer
        if($result == "A"){
            $this->winner = $teamA;
            $this->goalTeamA = rand(1,5);
            $this->goalTeamB = rand(0,$this->goalTeamA-1);

            $this->teamA->setPoints(3); //+3pont
            $this->teamA->setDiff($this->goalTeamA - $this->goalTeamB ); //Lőtt gól - kapott gól
            $this->teamB->setDiff($this->goalTeamB - $this->goalTeamA );
        }

        //Ha B csapat nyer
        if($result == "B"){
            $this->winner = $teamB;
            $this->goalTeamB = rand(1,5);
            $this->goalTeamA = rand(0,$this->goalTeamB-1);

            $this->teamB->setPoints(3);
            $this->teamA->setDiff($this->goalTeamA - $this->goalTeamB); //Lőtt gól - kapott gól
            $this->teamB->setDiff($this->goalTeamB - $this->goalTeamA);
        }

        //Ha döntetlen
        if($result == "X"){
            $this->winner = "X";
            $this->goalTeamA = rand(0,3);
            $this->goalTeamB = $this->goalTeamA;
            $this->teamA->setPoints(1); //+1 pont
            $this->teamB->setPoints(1);
        }

        $this->saveMatchInfo();
    }
 
}

//Csoportmérkőzés osztály
class GroupMatch extends PlayMatch
{

    //Konstruktor
    function __construct($matchID, Team $teamA, Team $teamB)
    {
        $this->teamA = $teamA;
        $this->teamB = $teamB;
        $this->matchID = $matchID;
        $this->playMatch(); 
    }

    //Mérkőzés lejátszása
    function playMatch(){
        $this->setResult($this->teamA, $this->teamB);
    }

    //Győztes kiválasztása a rankok alapján
    function setResult($teamA, $teamB){
        
        $rankA = $teamA->getRank();
        $rankB = $teamB->getRank();

        $chanceOfTeamA = $rankB;  // TeamA nyerési esélye = rankB / max 
        $chanceOfTeamB = $rankA; // TeamB nyerési esélye = rankA / max 

        //Döntetlen esélye = rankA+rankB * (1 / |rankA - rankB|) * 0.25
        $chanceOfDraw = ($rankA + $rankB) * ( 1 / (sqrt(abs($rankA - $rankB)))) * 0.25;  

        // max = 100%
        $MaxOfChance = $chanceOfTeamA + $chanceOfTeamB + $chanceOfDraw;

        //Rangek alapján az eredmény kiválasztása
        $rnd = rand(0, $MaxOfChance);

        //Gólok átadása
        if ($rnd < $chanceOfTeamA) {
            $this->setGoals($teamA, $teamB, "A");
        } else if ($rnd >= $chanceOfTeamA + $chanceOfTeamB) {
            $this->setGoals($teamA, $teamB, "X");
        } else {
            $this->setGoals($teamA, $teamB, "B");
        }      

    }

}

//Kieséses szakasz meccsei
class FinalStageMatch extends PlayMatch{

    //Konstruktor
    function __construct(Team $teamA, Team $teamB){
        $this->teamA = $teamA;
        $this->teamB = $teamB;
        $this->playMatch();
    }

    //Meccs lejátszása és kiírása
    function playMatch(){    
        $this->setResult($this->teamA, $this->teamB);
        $this->writeOutResult();

    }

    //Győztes kiválasztása a rankok alapján
    function setResult($teamA, $teamB)
    {
        $rankA = $teamA->getRank();
        $rankB = $teamB->getRank();

        $chanceOfTeamA = $rankB;  // TeamA nyerési esélye = rankB / max 
        $chanceOfTeamB = $rankA; // TeamB nyerési esélye = rankA / max 

        $MaxOfChance = $chanceOfTeamA + $chanceOfTeamB;

        //Rangek alapján az eredmény kiválasztása
        $rnd = rand(0, $MaxOfChance);

        if ($rnd < $chanceOfTeamA) {
            $this->setGoals($teamA, $teamB, "A");
        } else {
            $this->setGoals($teamA, $teamB, "B");
        }      

    }

    //Meccs eredmény kiírása
    function writeOutResult(){
        echo "<div class='w-100'>";

        echo "<table class='table table-dark table-hover table-sm'>";
        echo "<tr><td class='col-10'>" . $this->teamA->getName() . "</td><td class='col-2'>" .$this->goalTeamA . "</td></tr>";
        echo "<tr><td>" . $this->teamB->getName() . "</td><td>" .$this->goalTeamB . "</td></tr>";

        echo "</table></div>";
    }




}


?>