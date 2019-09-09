// src.: https://stackoverflow.com/a/11339012/3514658
function getFormData($form){
    var unindexedArray = $form.serializeArray();
    var indexedArray = {};

    $.map(unindexedArray, function(n, i){
        indexedArray[n['name']] = n['value'];
    });

    return indexedArray;
}

function getFormFields($form){
    var unindexedArray = $form.serializeArray();
    var indexedArray = [];

    $.map(unindexedArray, function(n, i){
        indexedArray.push(n['name']);
    });

    return indexedArray;
}

/*
 * Registration page
 */
// Send POST request when clicking on register
$("#registration-form").submit(function(e) {

    // Prevent the HTML form to be submitted (default functionality)
    e.preventDefault();

    var formData = getFormData($("#registration-form"));

    if (formData["password"] !== formData["password-confirm"]) {
        $("#password-confirm + .invalid-feedback").text("Passwords do not match");
        $("#password-confirm + .invalid-feedback").css("display", "block");
        return;
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

            if (data["success"] == "true") {
                if (Array.isArray(data["messages"])) {
                    // Shortcut foreach as soon a true value is returned
                    // src.: https://stackoverflow.com/a/2641374/3514658
                    data["messages"].some(function(item) {
                        $("#registration-form").replaceWith("<div class=\"alert alert-success\">" + item + "</div>");
                        return true;
                    });
                } else {
                    $("#registration-form").replaceWith("<div class=\"alert alert-success\">" + data["messages"] + "</div>");
                }

            } else {
                if (Array.isArray(data["messages"])) {
                    data["messages"].some(function(item) {
                        $("#registration-form > .feedback").replaceWith("<div class=\"feedback alert alert-danger\">" + item + "</div>");
                        return true;
                    });
                } else {
                    $("#registration-form > .feedback").replaceWith("<div class=\"feedback alert alert-danger\">" + data["messages"] + "</div>");
                }
            }
        }
    });
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
