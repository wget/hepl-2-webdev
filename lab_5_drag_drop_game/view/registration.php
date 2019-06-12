<?php ob_start(); ?>

  <h3 class="mb-3">Default shapes</h3>
  <div class="row">
    <div class="col-md-12 mb-6">
      <p>Here are the default shapes you can drag and drop to the canva zone below</p>
    </div>
  </div>
  <div class="row">
    <canvas draggable="true" id="shape-canva-rectangle"></canvas>
    <canvas draggable="true" id="shape-canva-square"></canvas>
    <canvas draggable="true" id="shape-canva-triangle"></canvas>
    <canvas draggable="true" id="shape-canva-circle"></canvas>
  </div>
  <hr class="mb-4 separator">

  <h3 class="mb-3">Canva zone</h3>
  <div class="row">
    <div id="canva-area" class="col-md-12 mb-6">
        <canvas draggable="true" id="destination-shape-canva-rectangle"></canvas>
        <canvas draggable="true" id="destination-shape-canva-square"></canvas>
        <canvas draggable="true" id="destination-shape-canva-triangle"></canvas>
        <canvas draggable="true" id="destination-shape-canva-circle"></canvas>
    </div>
  </div>

<!-- SHA-512 js implementation -->
<!-- src.: https://github.com/emn178/js-sha512 -->
<script type="text/javascript" src="<?php echo ROOT_WEB ?>/public/js/sha512.min.js"></script>
<script>
$(function() {

    var messages = {};
    messages.requestMalformed                = "The request is malformed.";
    messages.emailMissingRequired            = "The email is missing from the request.";
    messages.emailInvalid                    = "The email address is invalid.";
    messages.emailNotUnique                  = "A user with this email address already exists.";
    messages.buttonTextRegistering           = "Registering";
    messages.buttonTextRegister              = "Register";
    messages.buttonTextRegistrationCompleted = "Registration completed!";

    var tree = [
        { "id":  1, "parent": null, "type": "cat" , "name": "Garden" , "picture": null                                              },
        { "id":  2, "parent": 1   , "type": "cat" , "name": "Plants" , "picture": null                                              },
        { "id":  3, "parent": 2   , "type": "item", "name": "foo"    , "picture": "<?php echo ROOT_WEB ?>/public/img/garden_1.jpg"  },
        { "id":  4, "parent": 2   , "type": "item", "name": "bar"    , "picture": "<?php echo ROOT_WEB ?>/public/img/garden_2.jpg"  },
        { "id":  5, "parent": 2   , "type": "item", "name": "hello"  , "picture": "<?php echo ROOT_WEB ?>/public/img/garden_3.jpg"  },
        { "id":  6, "parent": 2   , "type": "item", "name": "world"  , "picture": "<?php echo ROOT_WEB ?>/public/img/garden_4.jpg"  },
        { "id":  7, "parent": 1   , "type": "cat" , "name": "Outdoor", "picture": null                                              },
        { "id":  8, "parent": 7   , "type": "item", "name": "wedding", "picture": "<?php echo ROOT_WEB ?>/public/img/wedding_1.jpg" },
        { "id":  9, "parent": 7   , "type": "item", "name": "married", "picture": "<?php echo ROOT_WEB ?>/public/img/wedding_2.jpg" },
        { "id": 10, "parent": 7   , "type": "item", "name": "divorce", "picture": "<?php echo ROOT_WEB ?>/public/img/wedding_3.jpg" },
        { "id": 20, "parent": null, "type": "cat" , "name": "Home"   , "picture": null                                              }
    ];

    function drawDefaultShapes() {

        var canvas = document.getElementById("shape-canva-rectangle");
        var ctx = canvas.getContext("2d");
        ctx.fillRect(0, 0, 150, 50);
        ctx.fillStyle = "black";

        canvas = document.getElementById("shape-canva-square");
        ctx = canvas.getContext("2d");
        ctx.fillRect(0, 0, 50, 50);
        ctx.fillStyle = "black";

        canvas = document.getElementById("shape-canva-triangle");
        ctx = canvas.getContext("2d");
        ctx.moveTo(0, 150);
        ctx.lineTo(75, 75);
        ctx.lineTo(150, 150);
        ctx.lineTo(0, 150);
        ctx.fillStyle = "black";
        ctx.fill();

        canvas = document.getElementById("shape-canva-circle");
        ctx = canvas.getContext("2d");
        ctx.arc(50, 50, 50, 0, 2 * Math.PI);
        ctx.fill();
    }

    function drawDestinationShapes() {
        // Define the height to be the same as the width
        $("#canva-area").height($("#canva-area").width());

        var canvas = document.getElementById("destination-shape-rectangle");
        var ctx = canvas.getContext("2d");
        ctx.fillRect(0, 0, 150, 50);
        ctx.fillStyle = "black";

        canvas = document.getElementById("destination-shape-square");
        ctx = canvas.getContext("2d");
        ctx.fillRect(0, 0, 50, 50);
        ctx.fillStyle = "black";

        canvas = document.getElementById("destination-shape-triangle");
        ctx = canvas.getContext("2d");
        ctx.moveTo(0, 150);
        ctx.lineTo(75, 75);
        ctx.lineTo(150, 150);
        ctx.lineTo(0, 150);
        ctx.fillStyle = "black";
        ctx.fill();

        canvas = document.getElementById("destination-shape-circle");
        ctx = canvas.getContext("2d");
        ctx.arc(50, 50, 50, 0, 2 * Math.PI);
        ctx.fill();
    }

    drawDefaultShapes();
    drawDestinationShapes();
});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
