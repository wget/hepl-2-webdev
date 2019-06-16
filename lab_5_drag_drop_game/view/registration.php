<?php ob_start(); ?>

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
          <h5 class="modal-title" id="scoreDialogTitle">Game Over!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p>You scored: <span class="score-value"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Points toast appearing on scroll only -->
  <div id="points-scroll-only">My points : <span class="points-value">0</span></div>

<script>
$(function() {

    var messages = {};

    var availableShapes = Array.apply(null, {length: getNumberDefaultShapes() }).map(Number.call, Number);
    var remainingMoves = 0;
    var currentScore = 0;

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

        var rows = $("#default-shapes .col").length / 4;
        rows = Math.floor(rows);
        var columns = 4;

        remainingMoves = rows * columns;

        for (var i = 0; i < rows; i++) {
            $("#canva-area").append("<div class=\"row canva-area-row-" + i + "\"></div>");
            var currentRow = $(".canva-area-row-" + i);
            for (var j = 0; j < columns; j++) {
                currentRow.append("<div class=\"col\"><div class=\"" + getRandomUniqueShape() + "\"></div></div>");
            }
        }
    }

    // src.: https://www.w3schools.com/html/html5_draganddrop.asp
    function dragOver(e) {
        // By default, data/elements cannot be dropped in other elements. To
        // allow a drop, we must prevent the default handling of the element.
        e.originalEvent.preventDefault();
        // $(e.originalEvent.target).parent().css("box-shadow", "0px 0px 0px 10px black inset dashed");
        // $(e.originalEvent.target).parent().css("border", "10px dashed");
        // $(e.originalEvent.target).parent().css("margin", "0px");
        $(e.originalEvent.target).parent().css("outline", "10px dashed black");
    }

    // Reset default border when not hovered any more
    function dragLeave(e) {
        // $(e.originalEvent.target).parent().css("box-shadow", "0px 0px 0px 0px");
        // $(e.originalEvent.target).parent().css("border", "1px solid");
        // $(e.originalEvent.target).parent().css("margin", "10px");
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
        var classIdDestination = $(this).attr("class");
        if (classIdSource == classIdDestination) {
            currentScore++;
            $(e.originalEvent.target).parent().append("<div class=\"overlay\"><i class=\"fas fa-thumbs-up\"></i></div>");
            $(e.originalEvent.target).parent().css("outline", "10px dashed #28a745");
        } else {
            currentScore--;
            $(e.originalEvent.target).parent().css("background", "repeating-linear-gradient(135deg, rgba(0,0,0,.075), rgba(0,0,0,.075) 10px, #dc3545 10px, #dc3545 20px)");
            $(e.originalEvent.target).parent().css("outline", "10px dashed #dc3545");
            $(e.originalEvent.target).css("z-index", "-1");
        }

        // Disable event and shapes
        $(this).off("drop");
        $(this).off("dragover");

        remainingMoves--;
        $(".points-value").text(currentScore);
        $("#scoreDialog .score-value").text(currentScore);

        if (!remainingMoves) {
            $("#scoreDialog").modal("toggle");
        }

        // Avoid propagation (stopPropagation and preventDefault are both
        // called automatically
        return false;
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

    drawDestinationShapes();

    // Set points lcoation in the bar
    $("#menu-bar").append("<div id=\"points\">My points: <span class=\"points-value\">0</span></div>");

    // Add event to the source shapes
    $("#default-shapes div div").on("dragstart", dragStart);

    // Add event to the destination shapes
    $("#canva-area div div div").on("dragover", dragOver);
    $("#canva-area div div div").on("dragleave", dragLeave);
    $("#canva-area div div div").on("drop", drop);
});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
