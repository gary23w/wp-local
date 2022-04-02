jQuery(document).ready(function ($) {
  //add jQuery ui to header
  $("head").append(`
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
`);
  $("#user_add").click(function () {
    $("#user_add").prop("disabled", true);
    personname = $("#person_name").val();
    if (person_name == "") {
      $(".user_add").effect("shake");
      return;
    }
    username_new = $("#user_name").val();
    if (username_new == "") {
      $(".user_add").effect("shake");
      return;
    }
    password = $("#user_pass").val();
    if (password == "") {
      $(".user_add").effect("shake");
      return;
    }
    jQuery.ajax({
      url: ajaxurl,
      type: "POST",
      data: {
        action: "add_user_local",
        personname: personname,
        username: username_new,
        password: password,
      },
      datatype: "json",
      success: function (resp) {
        //json to array
        var resp_array = JSON.parse(resp);
        if (resp_array["user_created"] == "true") {
          console.log("user created");
          window.location.reload();
        } else {
          alert("User not added. Seems an error has occured");
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert("Request failed: " + thrownError.message + xhr + ajaxOptions);
      },
    });
    //enable button
    $("#user_add").prop("disabled", false);
  });
});
