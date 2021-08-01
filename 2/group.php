<?php

//Csoport osztály
class Group
{
    public $groupname;
    public $teams = [];
    public $matches = [];

    //Konstruktor
    function __construct($groupname){
        $this->groupname = $groupname;
    }

    //Csoportnév beolvasás
    function getGroupName(){
        return $this->groupname;
    }

    //Csoport csapatai
    function getGroupTeams(){
        return $this->teams;
    }

    //CSoport n-edik csapata
    function getGroupTeam($pos){
        return $this->teams[$pos-1];
    }

    //Csoportmeccsek
    function getMatches(){
        return $this->matches;
    }

    //Csapat hozzáadása a csoporthoz
    function addTeam(Team $team){
        $teams = $this->getGroupTeams();
        array_push($teams, $team);
        $this->teams = $teams;
    }

    //Csoport tagjainak kiírása
    function writeOutGroup(){
        $groupname = $this->groupname;
        $teams = $this->getGroupTeams();
        echo "<b>" . $groupname . " csoport </b><br>";

        foreach ($teams as $team) {
            echo $team->getName() . "<br>";
        }

        echo "<br><p>";
    }

    //Csoport meccs hozzáadása
    function addMatch($match){
        array_push($this->matches, $match);
    }

    //Csoport tabella kiírása
    function showResults(){
        echo "<p class='h4 m-1 text-center'>". $this->groupname ." csoport</p>";
        echo "<table class='table table-hover table-dark table-sm'><thead><tr><td>Poz.</td><td>Ország</td><td>+/-</td><td>Pont</td></tr></thead>";

        foreach ($this->teams as $i => $team) {
            $name = $team->getName();
            $diff = $team->getDiff();
            $point = $team->getPoints();
            echo "<tr><td>". ($i+1) .".</td><td>" . $name . "</td><td>" . $diff . "</td><td>" . $point . "</td></tr>";
        }
        echo "</table>";

    }

    //Csoporttabella rendezése
    function sortTeamsByPoint(){
        $teams = $this->teams;

        //rendezés
        for ($i = 0; $i < count($teams); $i++) {
            for ($j = 0; $j < count($teams) - 1; $j++) {
                if ($teams[$j]->getPoints() < $teams[$j + 1]->getPoints()) {
                    $temp = $teams[$j];
                    $teams[$j] = $teams[$j + 1];
                    $teams[$j + 1] = $temp;
                }

                if ($teams[$j]->getPoints() == $teams[$j + 1]->getPoints()) {
                    if ($teams[$j]->getDiff() < $teams[$j + 1]->getDiff()) {
                        $temp = $teams[$j];
                        $teams[$j] = $teams[$j + 1];
                        $teams[$j + 1] = $temp;
                    }
                }
            }
        }

        $this->teams = $teams;
    }

    //Megadott pozíciójú csapat statusának növelése
    function selectNextRoundTeams($pos){
        $this->teams[$pos-1]->setNextStatus();
    }

    //A csoport harmadik helyezett statusának visszaállítása
    function setBackTeam3Status(){
        $this->teams[2]->setPreviousStatus();
    }
}
