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
    datatype: "json",
    success: function (resp) {
      var resp_array = JSON.parse(resp);
      console.log(resp_array);
      if (resp_array["login"] == "true") {
        console.log("success");
        var date = new Date();
        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
        var expires = "; expires=" + date.toUTCString();
        document.cookie = "gary-login=true" + expires + "; path=/";
        //reload page
        location.reload();
      } else {
        alert("Incorrect username or password");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert("Request failed: " + thrownError.message + xhr + ajaxOptions);
    },
  });
}

function save_creds() {
  var server = document.getElementById("destination_api").value;
  var port = document.getElementById("destination_port").value;
  var secure = document.getElementById("destination_secure").value;
  var user = document.getElementById("_user").value;
  var password = document.getElementById("_pass");
  // check pass placeholder for multiple asterisks
  if (password.placeholder.indexOf("*") > -1) {
    console.log("placeholder has asterisk");
    pass = null;
  } else {
    pass = document.getElementById("_pass").value;
  }
  var list = document.getElementById("list-mail");
  var list_items = list.getElementsByTagName("li");
  var list_array = [];
  for (var i = 0; i < list_items.length; i++) {
    list_array.push(list_items[i].id);
  }
  var list_string = list_array.join(",");
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "save_creds",
      server: server,
      port: port,
      secure: secure,
      username: user,
      password: pass,
      list: list_string,
    },
    success: function (resp) {
      location.reload();
    },
  });
}

//build responsive mail list

function addItem() {
  var a = document.getElementById("list-mail");
  var candidate = document.getElementById("candidate");
  var li = document.createElement("li");
  li.setAttribute("id", candidate.value);
  li.appendChild(document.createTextNode(candidate.value));
  a.appendChild(li);
}

// Creating a function to remove item from list

function delthis() {
  var element = event.target;
  get_li = jQuery(element).closest("li");
  get_li.remove();
}

function removeItem() {
  // Declaring a variable to get select element
  var a = document.getElementById("list-mail");
  var candidate = document.getElementById("candidate");
  var item = document.getElementById(candidate.value);
  a.removeChild(item);
}
