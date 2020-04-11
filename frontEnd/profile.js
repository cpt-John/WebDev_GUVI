document.addEventListener("DOMContentLoaded", function (event) {
  getResponse();
  $("#edit").click(reDirEdit);
  $("#logout").click(reDirLogin);
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
    $("#messages").html(detailObj.message);
  } else {
    $("#f_name").html(detailObj.first_name);
    $("#l_name").html(detailObj.last_name);
    $("#dob").html(detailObj.DOB);
    $("#details").html(detailObj.details);
    $("#email").html(detailObj.email);
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
