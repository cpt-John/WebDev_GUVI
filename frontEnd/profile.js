document.addEventListener("DOMContentLoaded", function (event) {
  getResponse();
  document.getElementById("edit").addEventListener("click", reDirEdit);
  document.getElementById("logout").addEventListener("click", reDirLogin);
});

function getResponse() {
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/profile.php", true);

  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onerror = function () {
    console.log("errored");
  };
  xhr.onload = function () {
    let responseMsg = JSON.parse(this.responseText);
    setVals(responseMsg);
  };
  xhr.send();
}

function setVals(detailObj) {
  if (detailObj.hasOwnProperty("message")) {
    document.getElementById("messages").innerHTML = detailObj.message;
  } else {
    document.getElementById("f_name").innerHTML = detailObj.first_name;
    document.getElementById("l_name").innerHTML = detailObj.last_name;
    document.getElementById("dob").innerHTML = detailObj.DOB;
    document.getElementById("details").innerHTML = detailObj.details;
    document.getElementById("email").innerHTML = detailObj.email;
  }
}
function reDirEdit() {
  window.location.href = "edit.html";
}
function reDirLogin() {
  window.location.replace("login.html");
}
function Logout() {
  let xhr = new XMLHttpRequest();
  //   console.log(xhr);
  xhr.open("post", "../backEnd/logout.php", true);

  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onerror = function () {
    console.log("errored");
  };
  xhr.onload = function () {};
  xhr.send();
}
