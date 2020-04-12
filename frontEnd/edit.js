$(function () {
  $("#messages").hide();
  getDetailsResponse();
  $("#register").click(registerUser);
  $("#profilePg").click(reDirProfile);
  $("input").focus(function () {
    $("#messages").hide();
    $(this).css("background-color", "rgb(119, 234, 236)");
  });
  $("input").blur(function () {
    $(this).css("background-color", "white");
  });
});

function getDetailsResponse() {
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/profile.php", true);

  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onerror = function () {
    console.log("errored");
  };
  xhr.onload = function () {
    let responseMsg = JSON.parse(this.responseText);
    fillDetails(responseMsg);
  };
  xhr.send();
}
function getResponse(regObj) {
  let params = `regDetails=${regObj}`;
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/edit.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onerror = function () {
    console.log("errored");
  };
  xhr.onload = function () {
    let responseMsg = JSON.parse(this.responseText);
    handleResponse(responseMsg);
  };
  xhr.send(params);
}

function registerUser() {
  let regDetails = {};
  $("input").each(function () {
    regDetails[$(this).attr("id")] = $(this).attr("value");
  });
  getResponse(JSON.stringify(regDetails));
}
function handleResponse(msgObj) {
  $("#messages").show();
  if (msgObj.message == "1") {
    $("#messages").attr(
      "class",
      "container-sm alert alert-success alert-dismissible "
    );
    $("#messages").html("success ... redirecting to login");
    $("#inputsDiv").empty();
    setTimeout(() => {
      window.location.href = "login.html";
    }, 2000);
  } else {
    $("#messages").attr(
      "class",
      "container-sm alert alert-danger alert-dismissible "
    );
    $("#messages").html(msgObj.message);
  }
}
function fillDetails(detailObj) {
  if (detailObj.hasOwnProperty("message")) {
    $("#messages").attr(
      "class",
      "container-sm alert alert-danger alert-dismissible "
    );
    $("#messages").show();
    $("#messages").html("ERROR : " + detailObj.message);
  } else {
    $("#f_name").val(detailObj.first_name);
    $("#l_name").val(detailObj.last_name);
    $("#dob").val(detailObj.DOB);
    $("#details").val(detailObj.details);
  }
}
function reDirProfile() {
  window.location.href = "profile.html";
}
