<?php ob_start(); ?>

<form class="needs-validation" id="login-form" novalidate>
  <h2 class="mb-3">Login process</h4>

  <div id="loginView">
      <h3 class="mb-3">Authentication</h4>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="username">Username<span class="text-muted">*</span></label>
          <input type="text" class="form-control" id="username" name="username" placeholder="" value="" autocomplete="username" required>
          <div class="invalid-feedback"></div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password">Confirm password<span class="text-muted">*</span></label>
          <input type="password" class="form-control" id="password" name="password" placeholder="" value="" autocomplete="new-password" required>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <hr class="mb-4">
      <button class="btn btn-primary btn-lg float-right col-6 col-md-3" type="submit">Login</button>
  </div>
</form>

<script>
    // Send POST request when clicking on register
    $("#login-form").submit(function(e) {

        // Prevent the HTML form to be submitted (default functionality)
        e.preventDefault();

        var formData = getFormData($("#login-form"));

        $.ajax({
            url: "./index.php?action=loginValidate",
            type: 'post',
            // Type of the answer
            dataType: 'json',
            // Type of the data to send
            contentType: "application/json",
            // Prevent anything other than a string from being converted as an
            // url encoded query string.
            processData: false,
            data: JSON.stringify(formData),
            success: function(data) {

                if (data["success"] == "true") {
                    $("#loginView").replaceWith("<div class=\"alert alert-success\">Authenticated</div>");
                    document.getElementById('register-login-buttons').innerHTML = "<div id=\"register-login-buttons\"><a class=\"btn btn-outline-primary\" href=\"<?php echo ROOT_CGI . "/index.php?action=viewProjects" ?>\">Projects</a></div>";
                }

                if (data["messages"]) {
                    data["messages"].forEach(function(item) {
                        // Split camel case on case in order to get the first
                        // part corresponding to the field.
                        // src.: https://stackoverflow.com/a/18379358/3514658
                        var field = item.replace(/([a-z0-9])([A-Z])/g, '$1 $2').split(" ")[0];
                        console.log("FIELD: " + field);
                        // Select invalid-feedback that are just AFTER
                        // (symbolized with the css selector +).
                        $("#" + field + " + .invalid-feedback").css("display", "block");
                        var errorText = $("#" + field + " + .invalid-feedback").text();
                        errorText += " " + eval("messages." + item);
                        $("#" + field + " + .invalid-feedback").text(errorText);
                    });
                }
            }
        });
    });

    // Remove the warning of the field when the user modifies his/her input
    getFormFields($("#login-form")).forEach(function(item) {
        $("#" + item).on('input', function() {
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });
    });

</script>
  
<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
