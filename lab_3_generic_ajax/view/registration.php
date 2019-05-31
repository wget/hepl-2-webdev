<?php ob_start(); ?>

<form class="needs-validation" id="request-form" novalidate>
  <h2 class="mb-3">Database viewer</h2>

  <h3 class="mb-3">Authentication</h3>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="host">Server<span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="host" name="host" placeholder="localhost" value="localhost" required>
      <div class="invalid-feedback">
        Valid host name required (letters only).
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="username">Username<span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="username" name="username" placeholder="hepl" value="hepl" autocomplete="username" required>
      <div class="invalid-feedback">
        Valid username required (letters only).
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="password">Password<span class="text-muted">*</span></label>
      <input type="password" class="form-control" id="password" name="password" placeholder="" value="12345" autocomplete="password" required>
      <div class="invalid-feedback">
        Valid password required.
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="database">Database name <span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="database" name="database" placeholder="hepl_2_webdev_lab_2" value="hepl_2_webdev_lab_2" required>
      <div class="invalid-feedback">
        Valid database name required (format letters and numbers with underscores only).
      </div>
    </div>
  </div>

  <h3 class="mb-3">Request area</h3>
  <div class="row">

    <div class="col-md-12 mb-6">
      <label for="request">Request <span class="text-muted">*</span></label>
      <textarea class="form-control" rows="5" id="request" name="request" placeholder="Describe yourself here..."></textarea>
      <div id="request-message" class="alert alert-success"></div>
    </div>
  </div>

  <h3 class="mb-3 view-area">View area</h3>
  <div class="row view-area">
    <div class="col-md-12 mb-6">
      <div id="view-area-table"></div>
    </div>
    <div id="request-message" class="alert alert-success"></div>
  </div>
  
  <hr class="mb-4">
  <button id="request-button" class="btn btn-primary btn-lg float-right col-6 col-md-3" type="submit"><i id="request-spinner" class="fas fa-spinner"></i><span id="request-button-text">Execute request</span></button>
  <button class="btn btn-secondary col-4 col-md-3" type="reset">Reset</button>
</form>

<!-- SHA-512 js implementation -->
<!-- src.: https://github.com/emn178/js-sha512 -->
<script type="text/javascript" src="<?php echo ROOT_WEB ?>/public/js/sha512.min.js"></script>
<script>
$(function() {

    var messages = {};
    messages.requestMalformed           = "The request is malformed.";
    messages.emailMissingRequired       = "The email is missing from the request.";
    messages.emailInvalid               = "The email address is invalid.";
    messages.emailNotUnique             = "A user with this email address already exists.";
    messages.buttonTextExecuteRequest   = "Executing request...";
    messages.buttonTextExecuteRequest   = "Execute request";
    messages.buttonTextrequestCompleted = "Request completed!";
    messages.requestSuccess             = "The request returned with successful!";
    messages.requestFailed              = "The request has failed with error message:";

    function showLoader() {
        $("#request-spinner").css("display", "inline-block");
        $("#request-button-text").text(messages.buttonTextExecuteRequest);
    }

    function hideLoader() {
        $("#request-spinner").css("display", "none");
        $("#request-button-text").text(messages.buttonTextExecuteRequest);
    }

    function requestCompleted() {
        $("#request-message").attr('class').split(" ").forEach(function(item) {
            if (item == "alert-danger") {
                $("#request-message").toggleClass("alert-danger alert-success");
            }
        });
        $("#request-message").text(messages.requestSuccess);
        $("#request-message").css("display", "block");
    }
   
    function requestFailed(errorMsg) {
        $("#request-message").attr('class').split(" ").forEach(function(item) {
            if (item == "alert-success") {
                $("#request-message").toggleClass("alert-success alert-danger");
            }
        });
        $("#request-message").text(messages.requestFailed + " " + errorMsg);
        $("#request-message").css("display", "block");
    }

    function requestHideMessage() {
        $("#request-message").css("display", "none");
    }

    // src.: https://www.encodedna.com/javascript/populate-json-data-to-html-table-using-javascript.htm
    function createTableFromJson(destination, jsonArray) {

        // Extract values for header
        var col = [];
        for (var i = 0; i < jsonArray.length; i++) {
            for (var key in jsonArray[i]) {
                if (col.indexOf(key) === -1) {
                    col.push(key);
                }
            }
        }

        // Create dynamic table
        var table = document.createElement("table");

        // Create table header from extracted headers from above
        var tr = table.insertRow(-1);
        for (var i = 0; i < col.length; i++) {
            var th = document.createElement("th");
            th.innerHTML = col[i];
            tr.appendChild(th);
        }

        // Populate data
        for (var i = 0; i < jsonArray.length; i++) {
            tr = table.insertRow(-1);
            for (var j = 0; j < col.length; j++) {
                var tabCell = tr.insertCell(-1);
                tabCell.innerHTML = jsonArray[i][col[j]];
            }
        }

        // Add the table to the destination container
        var divContainer = document.getElementById(destination);
        divContainer.innerHTML = "";
        divContainer.appendChild(table);
        divContainer.style.display = "block";
        $("#" + destination).addClass("table");

        // Show view area
        $(".view-area").css("display", "block");
    }

    // Send POST request when clicking on register
    $("#request-form").submit(function(e) {

        // Prevent the HTML form to be submitted (default functionality)
        e.preventDefault();

        showLoader();
        requestRequest = $.ajax({
            url: "./index.php?action=viewDatabase",
            type: 'post',
            // Type of the answer
            dataType: 'json',
            // Type of the data to send
            contentType: "application/json",
            // Prevent a,uthing other than a string from being converted as an
            // url encoded query string.
            processData: false,
            data: JSON.stringify(getFormData($("#request-form"))),
            success: function(data) {
                if (data["success"] === "true") {
                    requestCompleted();
                    createTableFromJson("view-area-table", data["messages"]);
                } else {
                    var errorMsg = "";
                    data["messages"].forEach(function(item) {
                        errorMsg += item + " ";
                    });
                    requestFailed(errorMsg);
                }
                hideLoader();
            }
        });
    });

    // Reset invalid feedback when re-editing field
    getFormFields($("#request-form")).forEach(function(item) {
        $("#" + item).on('input', function() {
            if (item === "request") {
                requestHideMessage();
            }
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });
    });

    // Also reset invalid feedback when clicking on the reset button
    $("button[type=reset]").click(function() {
        getFormFields($("#request-form")).forEach(function(item) {
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });

        // Abort the request request if it is taking too much time.
        requestRequest.abort();
        hideLoader();
    });
});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
