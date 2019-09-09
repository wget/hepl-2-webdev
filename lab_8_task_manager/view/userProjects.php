<?php ob_start(); ?>

<form class="needs-validation" id="registration-form" novalidate>
  <h2 class="mb-3">User projects</h4>

<?php

if ($projects === null) {
    echo "<p>No project for current user</p>";
} else {
    foreach ($projects as $project) {
      echo "<p>$project[description]</p>";
    }
}
?>

<script>
$(function() {

    var messages = {};
    messages.usernameMissingRequired        = "Specifiying an username is a required.";
    messages.emailMissingRequired           = "Specifying a valid email address is required.";
    messages.languageInvalid                = "The language you specified is invalid.";
    messages.passwordMissingRequired        = "Specyfying a password is required.";
    messages.passwordDoNotMatch             = "The passwords do not match.";
    messages.languageMissingRequired        = "Specifying a language is required.";
    messages.usernameInvalid                = "The username you specified contains invalid chars. Must be alphanum (space ._-' allowed).";
    messages.usernameNotUnique              = "The username you specified already exists.";
    messages.emailInvalid                   = "The email address you specified is invalid.";
    messages.emailNotUnique                 = "The email you specified already exists.";
    messages.originCityInvalid              = "The origin city you specified is invalid.";
    messages.bioInvalid                     = "The bio you specified contains invalid chars. Must be alphanum (space ._-' allowed).";
    messages.profilePictureMissing          = "The server received a filename for the profile picture, but the profile picture payload is missing.";
    messages.profilePictureFilenameMissing  = "The server received a profile picture payload, but the profile picture filename is missing.";
    messages.profilePictureServerSaveFailed = "The profile picture cannot be saved on the server.";

    // Send POST request when clicking on register
    $("#registration-form").submit(function(e) {

        // Prevent the HTML form to be submitted (default functionality)
        e.preventDefault();

        var formData = getFormData($("#registration-form"));

        // If we have a profile picture filename, add the profile picture and
        // profile picture filename
        if (document.querySelector('input[type="file"]').files[0]) {
            formData.profilePictureFilename =
                document.querySelector('input[type="file"]').files[0].name;
            formData.profilePicture =
                $("#profile-picture-render-img").attr("src");
        }

        $.ajax({
            url: "./index.php?action=registerValidate",
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
                // var dataJson = JSON.parse(data);
                console.log("SUCCESS: " + data["success"]);
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
    getFormFields($("#registration-form")).forEach(function(item) {
        $("#" + item).on('input', function() {
            $("#" + item + "+ .invalid-feedback").css("display", "none");
        });
    });

    // Add user profile picture on UI without sending it to the server
    var profilePicture = $("#profile-picture-file-picker");
    var reader = new FileReader();
    // reader.readAsText(file, 'UTF-8');
    reader.onload = function(e) {
        // Get size of current default profile logo + padding
        var height = $("#profile-picture-render-img").outerHeight()

        // Replace <i> tag by real img
        $("#profile-picture-render-img")
            .replaceWith("<img id=\"profile-picture-render-img\" src=\"\" alt=\"\" />");

        // Set the img src to base64 value of the image
        $("#profile-picture-render-img").attr("src", this.result);

        // Resize the img to the previous size
        $("#profile-picture-render-img").css("height", height);
    };
    $("#profile-picture-file-picker").change(function() {
        reader.readAsDataURL($("#profile-picture-file-picker")[0].files[0]);
    });

    // Also reset the profile picture if reset button is clicked
    $("button[type=reset]").click(function() {
        $("#profile-picture-render-img")
            .replaceWith("<i id=\"profile-picture-render-img\" class=\"far fa-user\"></i>");
    });


});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
