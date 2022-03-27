jQuery(document).ready(function ($) {
  //check for login cookie
  var login_cookie = getCookie("gary-login");
  if (!login_cookie) {
    //hide everything
    $("#wpbody-content").hide();
    //create the login form
    $("#wpbody").append(`
        <style>
        input[type=text], input[type=password] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
        }

        button {
        background-color: #2271b1;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        }

        button:hover {
        opacity: 0.8;
        }

        .imgcontainer {
        text-align: center;
        margin: 24px 0 12px 0;
        }

        img.avatar {
        width: 40%;
        border-radius: 50%;
        }

        .container {
        padding: 16px;
        }

        span.psw {
        float: right;
        padding-top: 16px;
        }
        </style>
        <div id="login-form" style="margin-top: 15%;">
        <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" placeholder="Enter username">
        </div>
        <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary" onclick="login_check(jQuery(\'#username\').val(), jQuery(\'#password\').val())">Submit</button>
        </div>
    `);
    // center login-form
    $("#login-form").css({
      position: "absolute",
      top: "50%",
      left: "30%",
    });
  }
});

function getCookie(c_name) {
  var c_value = document.cookie,
    c_start = c_value.indexOf(" " + c_name + "=");
  if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
  if (c_start == -1) {
    c_value = null;
  } else {
    c_start = c_value.indexOf("=", c_start) + 1;
    var c_end = c_value.indexOf(";", c_start);
    if (c_end == -1) {
      c_end = c_value.length;
    }
    c_value = unescape(c_value.substring(c_start, c_end));
  }
  return c_value;
}

function login_check(username, password) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "login_check",
      username: username,
      password: password,
    },
    success: function (resp) {
      if (resp == "true0") {
        console.log("success");
        //set 24 hour cookie
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
        var expires = "; expires=" + date.toUTCString();
        document.cookie = "gary-login=true" + expires + "; path=/";
        //reload page
        location.reload();
      } else {
        console.log("fail");
        alert("Incorrect username or password");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert("Request failed: " + thrownError.message + xhr + ajaxOptions);
    },
  });
}
