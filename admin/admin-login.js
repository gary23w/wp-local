jQuery(document).ready(function ($) {
  $("#wpbody-content").hide();
  var login_cookie = getCookie("gary-login");
  if (
    login_cookie === null ||
    login_cookie === "" ||
    login_cookie === "false"
  ) {
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
        <button type="submit" class="btn btn-primary" onclick="login_check(jQuery(\'#username\').val(), jQuery(\'#password\').val())">Login</button>
        </div>
    `);
    // center login-form
    $("#login-form").css({
      position: "absolute",
      top: "50%",
      left: "30%",
    });
  } else {
    if (check_cookie_hash(login_cookie)) {
      quickset_cookie("false");
      alert("bad credentials detected");
      window.location.href = "/wp-admin/admin.php?page=analytics_mail";
    } else {
      $("#wpbody-content").show();
      $.getScript(pathPlu + "/admin/admin-user.js");
      $(".logout_btn_").click(function () {
        deleteCookie("gary-login");
        location.reload();
      });
    }
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
      if (resp_array["login"] == "true") {
        quickset_cookie(resp_array["hash"]);
        window.location.reload();
      } else {
        alert("Incorrect username or password");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert("Request failed: " + thrownError.message + xhr + ajaxOptions);
    },
  });
}

function check_cookie_hash(cookie) {
  jQuery.ajax({
    url: ajaxurl,
    type: "POST",
    data: {
      action: "hash_check",
      cookie_id: cookie,
    },
    datatype: "json",
    success: function (resp) {
      //parse json response
      var resp_array = JSON.parse(resp);
      if (resp_array["hash"] == "true") {
        console.log("[*] hash check success.");
        return true;
      }
      return false;
    },
  });
}

function save_creds() {
  var server = document.getElementById("destination_api").value;
  var port = document.getElementById("destination_port").value;
  var secure = document.getElementById("destination_secure").value;
  var user = document.getElementById("_user").value;
  var password = document.getElementById("_pass");
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

function setCookie(params) {
  var name = params.name,
    value = params.value,
    expireDays = params.days,
    expireHours = params.hours,
    expireMinutes = params.minutes,
    expireSeconds = params.seconds;

  var expireDate = new Date();
  if (expireDays) {
    expireDate.setDate(expireDate.getDate() + expireDays);
  }
  if (expireHours) {
    expireDate.setHours(expireDate.getHours() + expireHours);
  }
  if (expireMinutes) {
    expireDate.setMinutes(expireDate.getMinutes() + expireMinutes);
  }
  if (expireSeconds) {
    expireDate.setSeconds(expireDate.getSeconds() + expireSeconds);
  }

  document.cookie =
    name +
    "=" +
    escape(value) +
    ";domain=" +
    window.location.hostname +
    ";path=/" +
    ";expires=" +
    expireDate.toUTCString();
}

function quickset_cookie(params) {
  var date = new Date();
  date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
  var expires = "; expires=" + date.toUTCString();
  document.cookie = "gary-login=" + params + expires + "; path=/";
}

function deleteCookie(name) {
  setCookie({ name: name, value: "", seconds: 1 });
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
