function submit(){
    var password = document.getElementById("submit").value;
    var username = document.getElementById("username").value;

    if (password == "admin123"){
        window.location.href = "../HTML/admin.html";
    }else{
        alert("Wrong password!");
    }

}