<?php ob_start(); ?>

  <h3 class="mb-3">Date injection</h3>
  <div class="row">
    <div class="col-md-12 mb-6">
      <p>Paragraph injection: <span id="date-injection"></span></p>
    </div>
  </div>
  <button id="inject-button" class="btn btn-primary btn-lg float-right col-6 col-md-3" type="submit">Inject date</button>
  <hr class="mb-4 separator">

  <h3 class="mb-3">Tree view</h3>
  <div class="row">
    <div class="col-md-12 mb-6">

      <ul id="treeview" class="col-md-6 mb-6"></ul>
      <div id="treeview-area" class="col-md-6 mb-6"></div>

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

    // Feature 1: inject date on the press of a button
    $("#inject-button").click(function() {
        $("#date-injection").text(Date());
    });

    // Feature 2: populate tree elements when category clicked

    // Function to populate direct children
    function treeProcessItem(itemDOM, item) {

        if (item.type == "item") {

            itemDOM.append("<li id=\"treeitem-item-" + item.id + "\">" + item.name + "</li>");
            $("#treeitem-item-" + item.id).click(function(e) {
                treeDisplayItem(item);
                e.stopPropagation();
            });

        } else if (item.type == "cat") {

            // Get children (if any) of current item
            var children = tree.filter(function(itemChild) {
                return itemChild.parent == item.id;
            });

            if (children.length) {
                itemDOM.append("<li id=\"treeitem-cat-" + item.id + "\" class=\"caret\">" + item.name + "<ul class=\"nested\"></lu></li>");
                $("#treeitem-cat-" + item.id).click(function(e) {
                    console.log("Adding click event to " + item.id);
                    children.forEach(function(childItem) {
                        // if ($("#treeitem-" + childItem.type + "-" + childItem.id).length) {
                        //     return;
                        // }
                        treeProcessItem($("#treeitem-cat-" + item.id + " > .nested"), childItem);
                    });
                    // Prevent parent's onclick event from firing when a child
                    // anchor is clicked
                    // src.: https://stackoverflow.com/a/1369080/3514658
                    e.stopPropagation();
                });

            } else {
                itemDOM.append("<li id=\"treeitem-cat-" + item.id + "\">" + item.name + "</li>");
            }
        }
    }

    function treeDisplayItem(item) {

        var itemToDisplay;
        console.log("HERE");
        for (i in tree) {
            if (i.id == item.id) {
                itemToDisplay = i
            }
        }

        $("#treeview-area").html("<img src=\"" + item.picture + "\" alt=\"" + item.name + "\" />");
    }

    // Populate root elements
    tree.forEach(function(item) {
        if (item.parent === null) {
            $("#treeview").click(treeProcessItem($("#treeview"), item));
        }
    });

    // Populate active carret when needed
    var toggler = document.getElementsByClassName("caret");
    var i;
    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }

});
</script>

<?php $pageContent = ob_get_clean(); ?>
<?php require(ROOT_CGI . "/view/template.php"); ?>
