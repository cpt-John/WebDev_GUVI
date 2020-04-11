document.addEventListener("DOMContentLoaded", function (event) {
  getDetailsResponse();
  $("#register").click(registerUser);
  $("#profilePg").click(reDirProfile);
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
    let nodes = document.getElementById("inputsDiv").getElementsByTagName("*");
    for (let i = 0; i < nodes.length; i++) {
      nodes[i].disabled = true;
    }
    setTimeout(() => {
      console.log("timed out");
      window.location.href = "login.html";
    }, 2000);
  } else {
    $("#messages").html(msgObj.message);
  }
}
function fillDetails(detailObj) {
  if (detailObj.hasOwnProperty("message")) {
    $("#messages").html(detailObj.message);
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
