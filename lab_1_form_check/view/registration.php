<?php ob_start(); ?>

<form class="needs-validation" id="registration-form" novalidate>
  <h2 class="mb-3">Registration process</h4>

  <h3 class="mb-3">Authentication</h4>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="lastname">Last name<span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Doe" value="" autocomplete="lastname" required>
      <div class="invalid-feedback">
        Valid last name required (letters only).
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="firstname">First name<span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="firstname" name="firstname" placeholder="John" value="" autocomplete="firstname" required>
      <div class="invalid-feedback">
        Valid first name required (letters only).
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="date-of-birth">Date of birth <span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="date-of-birth" name="date-of-birth" placeholder="30/12/1992" value="" required>
      <div class="invalid-feedback">
        Valid date required (format DD/MM/YYY).
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="email">Email address<span class="text-muted">*</span></label>
      <input type="email" class="form-control" id="email" name="email" placeholder="john@example.org" value="" required autocomplete="username">
      <div class="invalid-feedback">
        Valid email required.
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="phone-number">Phone number <span class="text-muted">*</span></label>
      <input type="text" class="form-control" id="phone-number" name="phone-number" placeholder="+32123456789" value="" required>
      <div class="invalid-feedback">
        Valid phone number required (format +32123456789).
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="password">Password<span class="text-muted">*</span></label>
      <input type="password" class="form-control" id="password" name="password" placeholder="" value="" autocomplete="new-password" required>
      <div class="invalid-feedback">
        Valid password required.
      </div>
    </div>
  </div>
  
  <hr class="mb-4">
  <p class="text-muted">Note: Wrt. GDPR agreement, you need to be at least 13 years old to register.</p>
  <button class="btn btn-primary btn-lg float-right col-6 col-md-3" type="submit">Register</button>
  <button class="btn btn-secondary col-4 col-md-3" type="reset">Reset</button>
</form>

<!-- SHA-512 js implementation -->
<!-- src.: https://github.com/emn178/js-sha512 -->
<script type="text/javascript" src="<?php echo ROOT_WEB ?>/public/js/sha512.min.js"></script>
<script>
$(function() {

    function isLastFirstNameValid(s) {
        return s.match(/^[a-zA-Z ]+$/) ? true : false;
    }

    // We cannot use date.js not moments.js, let's use a plain vanilla js trick
    // src.: https://stackoverflow.com/a/5812341/3514658
    function isDateValid(s) {
      var bits = s.split('/');
      var d = new Date(bits[2], bits[1] - 1, bits[0]);
      return d && (d.getMonth() + 1) == bits[1];
    }

    // Second regex taken. We aren't mad enough to write our own address.
    // src.: https://stackoverflow.com/a/46181/3514658
    function isEmailValid(s) {
        var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        return re.test(String(email).toLowerCase());
    }

    function isPhoneNumberValid(s) {
        // We are using string litteral to improve performances since they are compiled.
        // src.: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions#Creating_a_regular_expression
        return s.match(/\+[0-9]{11}/);
    }

    function isPasswordValid(s) {
        return s.length > 5 ? true : false;
    }

    function hashPassword(s) {
        return sha512(s);
    }


    // Remove the warning of the field when the user modifies his/her input
    function resetInvalidFeedback() {
    }

    // Send POST request when clicking on register
    $("#registration-form").submit(function(e) {

        // Prevent the HTML form to be submitted (default functionality)
        e.preventDefault();

        if (!isLastFirstNameValid($("#lastname").val())) {
            $("#lastname + .invalid-feedback").css("display", "block");
        }

        if (!isLastFirstNameValid($("#firstname").val())) {
            $("#firstname + .invalid-feedback").css("display", "block");
        }

        if (!isDateValid($("#date-of-birth").val())) {
            $("#date-of-birth + .invalid-feedback").css("display", "block");
        }

        if (!isPhoneNumberValid($("#phone-number").val())) {
            $("#phone-number + .invalid-feedback").css("display", "block");
        }

        if (!isPasswordValid($("#password").val())) {
            $("#password + .invalid-feedback").css("display", "block");
        }
    });

    // Reset invalid feedback when re-editing field
    getFormFields($("#registration-form")).forEach(function(item) {
        $("#" + item).on('input', function() {
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });
    });

    // Also reset invalid feedback when clicking on the reset button
    $("button[type=reset]").click(function() {
        getFormFields($("#registration-form")).forEach(function(item) {
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });
    });
});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
