function submit(){
    var password = document.getElementById("submit").value;
    var username = document.getElementById("username").value;

    if (password == "admin123"){
        window.location.href = "../HTML/admin.html";
        document.getElementById("uname").innerHTML = "HELLO, "+username;
    }else{
        alert("Wrong password!");
    }

}