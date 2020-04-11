document.addEventListener("DOMContentLoaded", function (event) {
  $("#subButton").click(getResponse);
  $("#regButton").click(reDirRegister);
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
  if (detailObj.hasOwnProperty("message")) {
    $("#messages").html(detailObj.message);
  } else {
    window.location.href = "profile.html";
  }
}
function reDirRegister() {
  window.location.href = "register.html";
}
