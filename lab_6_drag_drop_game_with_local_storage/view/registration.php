<?php ob_start(); ?>

  <div id="warning" class="alert alert-danger" role="alert"></div>

  <h3 class="mb-3">Default shapes</h3>
  <div class="row">
    <div class="col-md-12 mb-6">
      <p>Here are the default shapes you can drag and drop to the canva zone below</p>
    </div>
  </div>
  <div id="default-shapes" class="row">
    <div class="col"><div class="shape-square" draggable="true"></div></div>
    <div class="col"><div class="shape-rectangle" draggable="true"></div></div>
    <div class="col"><div class="shape-circle" draggable="true"></div></div>
    <div class="col"><div class="shape-oval" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-up" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-down" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-left" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-right" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-top-left" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-top-right" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-bottom-left" draggable="true"></div></div>
    <div class="col"><div class="shape-triangle-bottom-right" draggable="true"></div></div>
    <div class="col"><div class="shape-trapezoid" draggable="true"></div></div>
    <div class="col"><div class="shape-parallelogram" draggable="true"></div></div>
    <div class="col"><div class="shape-star-six" draggable="true"></div></div>
    <div class="col"><div class="shape-star-five" draggable="true"></div></div>
    <div class="col"><div class="shape-pentagon" draggable="true"></div></div>
    <div class="col"><div class="shape-hexagon" draggable="true"></div></div>
    <div class="col"><div class="shape-octagon" draggable="true"></div></div>
    <div class="col"><div class="shape-heart" draggable="true"></div></div>
    <div class="col"><div class="shape-diamond" draggable="true"></div></div>
    <div class="col"><div class="shape-burst-12" draggable="true"></div></div>
  </div>
  <hr class="mb-4 separator">

  <h3 class="mb-3">Canva zone</h3>
  <div id="canva-area"></div>

  <!-- Modal dialog box for scores -->
  <div class="modal fade" id="scoreDialog" tabindex="-1" role="dialog" aria-labelledby="scoreDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scoreDialogTitle">End of game!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>You scored: <span class="score-value"></span></p>
            <p>Previous scores:</p>
            <table id="score-history"></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary newGameButton">New game!</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal dialog box for username -->
  <div class="modal fade" id="usernameDialog" tabindex="-1" role="dialog" aria-labelledby="scoreDialogTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="scoreDialogTitle">New game</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger" role="alert"></div>
            <p>Specify your username here: <input type="text" id="inputUsername" name="username" placeholder="Your username"/></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary newGameButton">New game!</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Points toast appearing on scroll only -->
  <div id="points-scroll-only">My points : <span class="points-value">0</span></div>

<script>
$(function() {

    // Global game data
    var availableShapes = [];
    var remainingMoves = 0;
    var currentScore = 0;
    var username = "";

    // src.: https://stackoverflow.com/a/22480938/3514658
    function isElementVisible(element) {

        var rect = element.get(0).getBoundingClientRect();
        var elemTop = rect.top;
        var elemBottom = rect.bottom;

        // Only completely visible elements return true:
        var isVisible = (elemTop >= 0) && (elemBottom <= $(window).innerHeight());
        // Partially visible elements return true:
        //isVisible = elemTop < window.innerHeight && elemBottom >= 0;
        return isVisible;
    }

    function getNumberDefaultShapes() {
        return $("#default-shapes .col").length;
    }

    function getRandomUniqueShape() {
        var randomIndex = Math.floor(Math.random() * availableShapes.length);
        var randomItem = availableShapes[randomIndex];
        availableShapes.splice(randomIndex, 1);
        return $("#default-shapes .col div")[randomItem].className;
    }

    function drawDestinationShapes() {

        var rows = Math.floor($("#default-shapes .col").length / 4);
        var columns = 4;

        remainingMoves = rows * columns;

        for (var i = 0; i < rows; i++) {
            $("#canva-area").append("<div class=\"row canva-area-row-" + i + "\"></div>");
            var currentRow = $(".canva-area-row-" + i);
            for (var j = 0; j < columns; j++) {
                currentRow.append("<div class=\"col\"><div class=\"shape " + getRandomUniqueShape() + "\"></div></div>");
            }
        }
    }

    // src.: https://www.w3schools.com/html/html5_draganddrop.asp
    function dragOver(e) {
        // By default, data/elements cannot be dropped in other elements. To
        // allow a drop, we must prevent the default handling of the element.
        e.originalEvent.preventDefault();
        $(e.originalEvent.target).parent().css("outline", "10px dashed black");
    }

    // Reset default border when not hovered any more
    function dragLeave(e) {
        $(e.originalEvent.target).parent().css("outline", "1px solid black");
    }

    function dragStart(e) {
        // Sets the data type and the value of the dragged data. In this case,
        // the data type is "text" and the value is the id of the draggable
        // element.
        e.originalEvent.dataTransfer.setData("text", $(e.originalEvent.target).attr("class"));
    }

    function drop(e) {
        // Reset UI
        $(e.originalEvent.target).parent().css("outline", "1px solid black");

        var classIdSource = e.originalEvent.dataTransfer.getData("text");
        // There are several classes at once, for example:
        // shape shape-circle incorrect
        var classIdDestination = $(this).attr("class").split(" ")[1];
        if (classIdSource == classIdDestination) {
            currentScore++;
            $(this).addClass("correct");
            $(e.originalEvent.target).parent().append("<div class=\"overlay\"><i class=\"fas fa-thumbs-up\"></i></div>");
            $(e.originalEvent.target).parent().css("outline", "10px dashed #28a745");
        } else {
            currentScore--;
            $(this).addClass("incorrect");
            $(e.originalEvent.target).parent().css("background", "repeating-linear-gradient(135deg, rgba(0,0,0,.075), rgba(0,0,0,.075) 10px, #dc3545 10px, #dc3545 20px)");
            $(e.originalEvent.target).parent().css("outline", "10px dashed #dc3545");
            $(e.originalEvent.target).css("z-index", "-1");
        }

        // Disable events and shapes when a move has been done
        $(this).off("drop");
        $(this).off("dragover");

        // Update points
        remainingMoves--;
        updateScoreUI(currentScore);

        saveGame();

        // This is the end of the current game, save game hstory and show score
        // results.
        if (!remainingMoves) {
            saveGameHistory();
            updateGameHistoryUI();
            $("#scoreDialog").modal("toggle");
        }

        // Avoid propagation (stopPropagation and preventDefault are both
        // called automatically
        return false;
    }

    function updateScoreUI(score) {
        $(".points-value").text(currentScore);
        $("#scoreDialog .score-value").text(currentScore);
    }

    function updateUsernameUI(username) {
        $("#menu-bar #username").css("display", "block");
        $("#menu-bar #username .username-value").text(username);
    }

    function newGame() {

        availableShapes = Array.apply(null, {length: getNumberDefaultShapes() }).map(Number.call, Number);
        $("#canva-area div").remove();

        $("#usernameDialog").modal("toggle");

        $("#usernameDialog .newGameButton").on("click", function() {

            if (!checkUsername($("#inputUsername").val())) {
                $("#usernameDialog .alert").text("Username invalid. Please specify chars only.");
                $("#usernameDialog .alert").css("display", "block");
                return;
            }

            // If we are correcting a previous error
            $("#usernameDialog .alert").css("display", "none");

            username = $("#inputUsername").val()
            updateUsernameUI(username);

            $("#usernameDialog").modal("toggle");

            drawDestinationShapes();

            rehydrateDestShapeEvents();
        });
    }

    function rehydrateDestShapeEvents() {
        // Add event to the destination shapes
        $("#canva-area .shape").on("dragover", dragOver);
        $("#canva-area .shape").on("dragleave", dragLeave);
        $("#canva-area .shape").on("drop", drop);
    }

    function restoreGame() {
        if (typeof(Storage) === "undefined") {
            $("#warning").text("Local storage is disabled/not available. The game won't be saved.");
            $("#warning").css("display", "block");
            return;
        }

        if (localStorage.getItem("currentGame") === null) {
            newGame();
            return;
        }

        var game = JSON.parse(localStorage.getItem("currentGame"));

        var rows = Math.floor(game.shapes.length / 4);
        var columns = 4;

        username = game.username;
        remainingMoves = rows * columns;

        var k = 0;
        for (var i = 0; i < rows; i++) {
            $("#canva-area").append("<div class=\"row canva-area-row-" + i + "\"></div>");
            var currentRow = $(".canva-area-row-" + i);
            for (var j = 0; j < columns; j++) {
                var shapeName = game.shapes[k].name;
                var shapeState = game.shapes[k].state;
                var shapeClasses = shapeName;
                if (shapeState !== undefined) {
                    shapeClasses += " " + shapeState;
                }
                currentRow.append("<div class=\"col\"><div class=\"" + shapeClasses + "\"></div></div>");
                var shapeCol = $(".canva-area-row-" + i + " .col");
                if (shapeState === "correct") {
                    remainingMoves--;
                    currentScore++;
                    $(shapeCol[j]).append("<div class=\"overlay\"><i class=\"fas fa-thumbs-up\"></i></div>");
                    $(shapeCol[j]).css("outline", "10px dashed #28a745");
                } else if (shapeState === "incorrect") {
                    remainingMoves--;
                    currentScore--;
                    $(shapeCol[j]).css("background", "repeating-linear-gradient(135deg, rgba(0,0,0,.075), rgba(0,0,0,.075) 10px, #dc3545 10px, #dc3545 20px)");
                    $(shapeCol[j]).css("outline", "10px dashed #dc3545");
                    $(shapeCol[j]).css("z-index", "-1");
                } else {
                    // Rehydrate event listeners only for shapes for which no
                    // element have been dropped on yet.
                    $("#canva-area ." + shapeName).on("dragover", dragOver);
                    $("#canva-area ." + shapeName).on("dragleave", dragLeave);
                    $("#canva-area ." + shapeName).on("drop", drop);
                }
                k++;
            }
        }
        updateScoreUI(currentScore);
        updateUsernameUI(game.username);
    }

    function saveGameHistory() {
        var history = JSON.parse(localStorage.getItem("history"));
        var game = { "date": new Date().toISOString(), "username" : username, "score": currentScore };
        if (!history) {
            history = [];
        }
        history.push(game);
        localStorage.setItem("history", JSON.stringify(history));
        localStorage.removeItem("currentGame");
    }

    function updateGameHistoryUI() {
        $("#score-history thead").remove();
        $("#score-history tbody").remove();
        var table = $("#score-history");
        table.append("<thead><tr><th>Date</th><th>Username</th><th>Score</th></tr></thead>");
        table.append("<tbody></tbody>");
        table = $("#score-history tbody");

        var history = JSON.parse(localStorage.getItem("history"))
        for (var i = 0; i < history.length; i++) {
            table.append("<tr><td>" + history[i].date + "</td><td>" + history[i].username + "</td><td>" + history[i].score + "</td></tr>");
        }
    }

    function saveGame() {
        var shapes = $("#canva-area .shape");
        var game = { "username" : username, "score": currentScore, "shapes": [] };
        for (var i = 0; i < shapes.length; i++) {
            var shape = shapes.get(i).className.split(" ");
            game.shapes.push({ "name": shape[1], "state": shape[2]});
        }

        // The JSON.Stringify avoids the JSON object to be considered as an
        // Object but rather as a string accessible JSON data structure.
        localStorage.setItem("currentGame", JSON.stringify(game));
    }

    function checkUsername(s) {
        return s.match(/^[a-zA-Z ]+$/) ? true : false;
    }

    function displayInstructionsRemoveCurrentGame() {
        console.log("%cRun localStorage.removeItem\(\"currentGame\"\); to remove the current game configuration.", "color: blue; font-size: large");
        console.log("%cRun localStorage.removeItem\(\"history\"\); to remove the game history.", "color: blue; font-size: large");

    }

    // Add another way to view points when the view is scrolled and the points
    // area is not visible.
    $(document).on("scroll", function() {

        if (isElementVisible($("#points"))) {
            $("#points-scroll-only").fadeOut("fast");
        } else {
            $("#points-scroll-only").fadeIn("slow");
        }
    });


    // Set username location in the bar
    $("#menu-bar").append("<div id=\"username\">Username: <span class=\"username-value\"></span></div>");

    // Set points location in the bar
    $("#menu-bar").append("<div id=\"points\">My points: <span class=\"points-value\">0</span></div>");

    // Add event to the source shapes
    $("#default-shapes div div").on("dragstart", dragStart);

    $("#scoreDialog .newGameButton").on("click", function() {
        $("#scoreDialog").modal("toggle");
        newGame();
    });

    restoreGame();
    displayInstructionsRemoveCurrentGame();
});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
