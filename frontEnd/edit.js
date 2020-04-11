document.addEventListener("DOMContentLoaded", function (event) {
  getDetailsResponse();
  document.getElementById("register").addEventListener("click", registerUser);
  document.getElementById("profilePg").addEventListener("click", reDirProfile);
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
    document.getElementById(
      "messages"
    ).innerHTML = `success ... redirecting to login`;
    let nodes = document.getElementById("inputsDiv").getElementsByTagName("*");
    for (let i = 0; i < nodes.length; i++) {
      nodes[i].disabled = true;
    }
    setTimeout(() => {
      console.log("timed out");
      window.location.href = "login.html";
    }, 2000);
  } else {
    document.getElementById("messages").innerHTML = msgObj.message;
  }
}
function fillDetails(detailObj) {
  if (detailObj.hasOwnProperty("message")) {
    document.getElementById("messages").innerHTML = detailObj.message;
  } else {
    document.getElementById("f_name").value = detailObj.first_name;
    document.getElementById("l_name").value = detailObj.last_name;
    document.getElementById("dob").value = detailObj.DOB;
    document.getElementById("details").value = detailObj.details;
  }
}
function reDirProfile() {
  window.location.href = "profile.html";
}
