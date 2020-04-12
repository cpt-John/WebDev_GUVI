$(() => {
  $("#messages").hide();
  $("#register").click(registerUser);
  $("#mainPage").click(reDirLogin);
  $("input").focus(function () {
    $("#messages").hide();
    $(this).css("background-color", "rgb(119, 234, 236)");
  });
  $("input").blur(function () {
    $(this).css("background-color", "rgb(167, 225, 247)");
  });
});
function getResponse(regObj) {
  let params = `regDetails=${regObj}`;
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/register.php", true);
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
function handleResponse(detailObj) {
  if (detailObj.message == "1") {
    $("#messages").show();
    $("#messages").css({
      "background-color": "#dfffcc",
      "border-color": "#63c728",
      color: "#63c728",
    });
    $("#messages").html("success ... redirecting to login");
    $("#inputsDiv").empty();
    setTimeout(() => {
      console.log("timed out");
      window.location.href = "login.html";
    }, 3000);
  } else {
    $("#messages").show();
    $("#messages").html("ERROR : " + detailObj.message);
  }
}
function reDirLogin() {
  window.location.href = "login.html";
}
