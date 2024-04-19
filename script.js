function openSigninForm() {
  document.getElementById("signinForm").style.display = "block";
}

function closeSigninForm() {
  document.getElementById("signinForm").style.display = "none";
}

function openSignupForm() {
  document.getElementById("signupForm").style.display = "block";
}

function closeSignupForm() {
  document.getElementById("signupForm").style.display = "none";
}

function sendData(){
  var names = document.getElementById("names").value;
  var pseudo = document.getElementById("pseudo").value;
  var birthdate = document.getElementById("birthdate").value;
  var city = document.getElementById("city").value;
  var mail =document.getElementById("mail").value;
  var password =document.getElementById("password").value;
  $.ajax({
    type: 'POST',
    url: 'signup.php',
    dataType: 'json',
    data: {
      names:names,
      pseudo:pseudo,
      birthdate:birthdate,
      city:city,
      mail:mail,
      password:password
    },
    
    success: function(response){
      console.log(response);
      let responseString = JSON.parse(response);
      console.log(responseString);
      if(responseString == "error email"){
        alert("Cette adresse mail est déja associée a un compte.");
        console.log(responseString);
      }
      if(responseString == "error birthdate"){
        alert("Vous n'avez pas l'age requis pour créer un compte.");
        console.log(responseString);
      }
      if(responseString == "error pseudo"){
        alert("Ce pseudo est déja utilisé par un autre utilisateur.");
      }
      if(responseString == "no errors"){
        alert("Inscription réussie !");
      }
    }
  });
    
  return false;
}

function checkData(){
  var mailbis =document.getElementById("mailbis").value;
  var passwordbis =document.getElementById("passwordbis").value;
  // consosle.log("test");
  console.log(mailbis);
  $.ajax({
    type: 'POST',
    url: 'signin.php',
    dataType: 'json',
    data: {
      mailbis:mailbis,
      passwordbis:passwordbis
    },
    success: function(response){
      let responseString = JSON.parse(response);
      // console.log("test");
      if(responseString == "error no account"){
        alert("Ce compte n'existe pas");
      }
      if (responseString == "open session"){
        alert("Ce compte existe");
      }
    }
  });
    
  return false;
}