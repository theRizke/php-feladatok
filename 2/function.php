<?php

require('team.php');
require('group.php');
require('match.php');

//LOGFÁJL NYITÁS
$date = date('Y_m_d_h_i_s', time());
$logfile =  fopen("./log/result_" . $date . ".txt", "w") or die("Unable to open file!");

//Az egész lefuttatása
playEuro2020();

//LOGFÁJL ZÁRÁS
fclose($logfile);
echo "Az eredmények logja megtalálható a result_" . $date . ".txt fájlban.";


//A szimuláció
function playEuro2020(){
    echo "<div class='results d-flex flex-lg-row flex-column'>";
        //Csoportmérkőzések
    echo "<div class='groupstage col-lg-2 col-12'>";
    echo "<p class='h2'>Csoporteredmények</p>";
        $teamsData = readTeamsFromFile();
        $groups = sortTeams($teamsData);
        playGroupStage($groups);
    echo "</div>";

    //Kieséses szakasz
        $stages = ["Nyolcaddöntők", "Negyeddöntők", "Elődöntők", "Döntő"];
        $status = 0;
        $prevRound = nextRoundTeams($groups, 2);
    
        for($i = 8; $i >= 1; $i /= 2){
    echo "<div class='finalstage col-lg-2 col-12 d-flex flex-column p-2 m-2 border-right'>";
    echo "<p class='h2'>". $stages[$status] ."</p>";
            $prevRound = playFinalStage($prevRound);
            $status++;
    echo "</div>";
    }
    echo "</div>";
}

//Előre elkészített data.csv-ből beolvassa az adatokat
function readTeamsFromFile(){
    $teams = [];
    $csvFile = file('data.csv');
    foreach ($csvFile as $line) {
        $teamdata = explode(";", $line);
        array_push($teams, $teamdata);
    }
    return $teams;
}

//A beolvasott adatokat csoportokba osztja
function sortTeams($teamsData){
    
    $groups = [];

    $groupA = new Group("A");
    $groupB = new Group("B");
    $groupC = new Group("C");
    $groupD = new Group("D");
    $groupE = new Group("E");
    $groupF = new Group("F");

    foreach ($teamsData as $team) {
        ${$team[0]} = new Team($team[0], trim($team[2], " \t\n\r\0\x0B"));
        switch ($team[1]) {
            case 'A':
                $groupA->addTeam(${$team[0]});
                break;
            case 'B':
                $groupB->addTeam(${$team[0]});
                break;
            case 'C':
                $groupC->addTeam(${$team[0]});
                break;
            case 'D':
                $groupD->addTeam(${$team[0]});
                break;
            case 'E':
                $groupE->addTeam(${$team[0]});
                break;
            case 'F':
                $groupF->addTeam(${$team[0]});
                break;
            default:
                break;
        }
    }

    //A létrejött csoportokat egy tömbbe rakja
    array_push($groups, $groupA, $groupB, $groupC, $groupD, $groupE, $groupF);
    return $groups;
}

//Csoportmérkőzések lejátszása
function playGroupStage($groups){

    //Végig fut az összes csoporton, és létrehozza és lejátsza az összes csoportmeccset
    // és beállítja az első 3 helyezettet továbbjutónak;
    for($i = 0; $i < count($groups); $i++){
        $group = $groups[$i];
        $teams = $group->getGroupTeams();
        $groupName = $group->getGroupName();

        $match1 = new GroupMatch("match_". $groupName . "_1", $teams[0], $teams[1]);
        $teams[0]->addMatch($match1); $teams[1]->addMatch($match1);

        $match2 = new GroupMatch("match_". $groupName . "_2", $teams[2], $teams[3]);
        $teams[2]->addMatch($match2); $teams[3]->addMatch($match2);

        $match3 = new GroupMatch("match_". $groupName . "_3", $teams[0], $teams[2]);
        $teams[0]->addMatch($match3); $teams[2]->addMatch($match3);

        $match4 = new GroupMatch("match_". $groupName . "_4", $teams[1], $teams[3]);
        $teams[1]->addMatch($match4); $teams[3]->addMatch($match4);

        $match5 = new GroupMatch("match_". $groupName . "_5", $teams[0], $teams[3]);
        $teams[0]->addMatch($match5); $teams[3]->addMatch($match5);

        $match6 = new GroupMatch("match_". $groupName . "_6", $teams[1], $teams[2]);
        $teams[1]->addMatch($match6); $teams[2]->addMatch($match6);

        $group->sortTeamsByPoint();
        
        //1. 2. 3. csoporthelyezettek statusának növelése
        $group->selectNextRoundTeams(1);
        $group->selectNextRoundTeams(2);
        $group->selectNextRoundTeams(3);

        //Csoporteredmény kiírása
        $group->showResults();
        
    }
    //Legrosszabb 3. helyezettek visszaállítása 0-ra;
    //A groups végére kerül a két leggyengébb csoport; buborékrendezés
    for($i = 0; $i < count($groups); $i++){
        for($j = 0; $j < count($groups)-1; $j++)
        {
            $currentTeam = $groups[$j]->getGroupTeam(3);
            $nextTeam = $groups[$j+1]->getGroupTeam(3);

            $currentTeam_point = $currentTeam->getPoints();
            $nextTeam_point = $nextTeam->getPoints();

            $currentTeam_diff = $currentTeam->getDiff();
            $nextTeam_diff = $nextTeam->getDiff();

            if($currentTeam_point < $nextTeam_point){
                $temp = $groups[$j];
                $groups[$j] = $groups[$j+1];
                $groups[$j+1] = $temp;
            }
            else if( $currentTeam_point == $nextTeam_point && $currentTeam_diff < $nextTeam_diff){
                $temp = $groups[$j];
                $groups[$j] = $groups[$j+1];
                $groups[$j+1] = $temp;
            }
        }
    }

    //Az utolsó két 3. helyezett visszaállítása
    for($i = count($groups)-1; $i > count($groups)-3; $i--){
        $groups[$i]->setBackTeam3Status();
    }
}

//Kieséses szakas lejátszása
function playFinalStage($teams){
    $nextRoundTeams = [];
    for($i = 0; $i < count($teams)/2; $i++){
        $teamA = $teams[$i];
        $teamB = $teams[count($teams)-1-$i];

        $match = new FinalStageMatch($teamA, $teamB);

        $winner = $match->getWinner();
        $winner->setNextStatus();

        array_push($nextRoundTeams, $winner);
    }
    return $nextRoundTeams;
}

//Kiválasztja a következő kör csapatait a Team->status alapján
function nextRoundTeams($groups, $round){
    $roundTeams = [];
    foreach($groups as $group){
        $teams = $group->getGroupTeams();
        foreach($teams as $team){   
            if($team->getStatus() == $round){
                array_push($roundTeams, $team);
            }
        }
    }
    return $roundTeams;
}



?>

