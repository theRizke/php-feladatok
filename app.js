$(document).ready(function () {

    $(document).on("click", "#euler", function () {
        $(".content").html("<div>Feladat töltődik...</div>");
        $(".result").html('');
        $.ajax({
            url: "./1/function.php",
            success: function (data) {
                $(".content").html(data);
            }
        })
    });

    $(document).on("click", "#vasarnap", function () {
        $(".content").html("<div class='card text-dark bg-light card-dark card-center text-center col-lg-3 col-12'><div class='card-header'>Adj meg egy korábbi dátumot: </div> <div class='card-body' ><input type='date' name='date' id='date' value='2021-01-01'></div><div class='card-footer'><button class='btn btn-lg btn-secondary'id='dayresults'>Vasárnapi elsejék száma</button></div></div>");
    });
    
    $(document).on("click", '#dayresults', function(event){
        event.preventDefault();
        $(".result").html('');
        let date = $('#date').val();
        $.ajax({
            url: './3/function.php',
            data: { date: date },
            dataType: 'html',
            method: 'POST',
            success: function (result) {
              $(".result").html(result);
            }
          })

    })
  
    



    $(document).on("click", "#foci", function () {
        $(".content").html("<div>Feladat töltődik...</div>");
        $(".result").html('');
        $.ajax({
            url: "./2/function.php",
            success: function (data) {
                $(".content").html(data);
            }
        })
    });

});