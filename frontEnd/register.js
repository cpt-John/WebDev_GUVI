document.addEventListener("DOMContentLoaded", function (event) {
  $("#register").click(registerUser);
  $("#mainPage").click(reDirLogin);
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
  let docDetails = document.getElementsByTagName("input");
  let regDetails = {};
  for (i = 0; i < docDetails.length; i++) {
    regDetails[docDetails[i].id] = docDetails[i].value;
  }
  getResponse(JSON.stringify(regDetails));
}
function handleResponse(msgObj) {
  if (msgObj.message == "1") {
    $("#messages").html("success ... redirecting to login");
    $("#inputsDiv").empty();
    setTimeout(() => {
      console.log("timed out");
      window.location.href = "login.html";
    }, 3000);
  } else {
    $("#messages").html(msgObj.message);
  }
}
function reDirLogin() {
  window.location.href = "login.html";
}
