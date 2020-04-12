$(() => {
  $("#messages").hide();
  $("#subButton").click(getResponse);
  $("#regButton").click(reDirRegister);
  $("input").focus(function () {
    $(this).css("background-color", "rgb(119, 234, 236)");
    $("#messages").hide();
  });
  $("input").blur(function () {
    $(this).css("background-color", "rgb(167, 225, 247)");
  });
});
function getResponse() {
  let params = `email=${getParams().mail}&password=${getParams().pass}`;
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/index.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onerror = function () {
    console.log("errored");
  };
  xhr.onload = function () {
    let responseMsg = JSON.parse(this.responseText);
    logIn(responseMsg);
  };
  xhr.send(params);
}
function getParams() {
  let mail = $("#email").val();
  let pass = $("#password").val();
  return { mail: mail, pass: pass };
}

function logIn(detailObj) {
  if (detailObj.message == "1") {
    window.location.href = "profile.html";
  } else {
    $("#messages").show();
    $("#messages").html("ERROR : " + detailObj.message);
  }
}
function reDirRegister() {
  window.location.href = "register.html";
}
