var l = 0;
function onglets(evt, tabName) {
    var i, ongletcontent, ongletbutton;
    ongletcontent = document.getElementsByClassName("ongletcontent");
   // console.log(ongletcontent);
    for (i = 0; i < ongletcontent.length; i++) {
        ongletcontent[i].style.display = "none";
    }
    ongletbutton = document.getElementsByClassName("ongletbutton");
    for (i = 0; i < ongletbutton.length; i++) {
        ongletbutton[i].className = ongletbutton[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

//
function buttonreglage() {
    document.getElementById("settingsModal").style.display = "block";
}

function fermemodal() {
    document.getElementById("settingsModal").style.display = "none";
}

function changeBackgroundColor(color) {
    document.body.style.backgroundColor = color;
    localStorage.setItem('backgroundColor', color);
    fermemodal();

}

// document.addEventListener("DOMContentLoaded", function() {
//     var sauvegardebg = localStorage.getItem('backgroundColor');
//     if (sauvegardebg) {
//         document.body.style.backgroundColor = sauvegardebg;
//     }
//     var sauvegardefs = localStorage.getItem('fontSize');
//     if (sauvegardefs) {
//         document.body.style.fontSize = `${sauvegardefs}px`;
//     }
//     //document.getElementsByClassName("ongletbutton")[0].click();
// });



function paramsize(fontSize) {
    document.body.style.fontSize = `${fontSize}px`;
    localStorage.setItem('fontSize', fontSize);
}

function incremsize() {
    var currentSize = parseFloat(localStorage.getItem('fontSize') || window.getComputedStyle(document.body).fontSize);
    var newSize = currentSize + 1;
    paramsize(newSize);
}

function decremsize() {
    var currentSize = parseFloat(localStorage.getItem('fontSize') || window.getComputedStyle(document.body).fontSize);
    var newSize = currentSize - 1;
    paramsize(newSize);
}
document.addEventListener("DOMContentLoaded", function() {
    var enregistresize = localStorage.getItem('fontSize');
    if (enregistresize) {
        paramsize(parseFloat(enregistresize));
    }
    //document.getElementsByClassName("ongletbutton")[0].click();
});

function notif() {
    var modal = document.getElementById("notificationsModal");
    if (modal.style.display === "block") {
        modal.style.display = "none";
    } else {
        modal.style.display = "block";
    }
}

var upload = document.getElementById("post_tweet");

$(":file").change(function(e){

    $("[name=upload_pp]").submit(test(e));

    function test(e){
        e.preventDefault();

        var img = document.querySelector('input[type=file]').files[0];
                var reader = new FileReader();
                
                reader.addEventListener("load", function () {
                  //console.log(reader.result);
                  var key =  Math.floor(Math.random() * 9000)
                    localStorage.setItem("image_" + key, reader.result);


                    $.post("/php1/change_pp.php",{key: "image_"+key},function(){
                        var balise_img = document.getElementById("img_profil");
                        balise_img.src = localStorage.getItem("image_"+key);
                    });

                }, false);
                
                if (img) {
                    reader.readAsDataURL(img);
                }
    }
})
function nouvelledicussion(){
    document.getElementById("nouvelle-discussion").style.display = "block";
}
function fermemodal_discussion(){
    document.getElementById("nouvelle-discussion").style.display = "none";
}


function tabs (nav){
    //console.log(nav)
    var i = 0;
    for(i= 0 ; i< nav.length; i++){
        var tab = nav[i];
        tab.addEventListener("click", (e) =>{
           // alert("ok");
            var id = e.target.dataset.target;
            var element = e.target
            var pane = document.getElementById(id);
           // console.log(element);
            pane.classList.add("active");

            var next = pane.nextElementSibling;
            while(next){
                next.classList.remove("active");
                next = next.nextElementSibling;
            }
            next = pane.previousElementSibling;
            while(next){
                next.classList.remove("active");
                next = next.previousElementSibling;
            }
                $.post("model_message.php",{id_convo : "id_convo", name_convo: element.innerHTML}, (response) => {
                    var div = document.getElementsByClassName("active");
                    var pase2 = JSON.parse(response)
                    div[0].innerHTML = "";
                    for(var k = 0 ; k < Object.keys(pase2).length; k++){
                        var value = Object.values(pase2[k]);
                        var div = document.getElementsByClassName("active");
                        var p = document.createElement("p");
                        p.innerHTML = value[0] + ":"+" "+value[1] + " " + value[2];
                        div[0].append(p);
            
                    }
                })
            return test = element.innerHTML;
        });
    }
   }

   var form_discussion = document.getElementById("nv_discussion");
 // console.log(document.getElementsByClassName('ongletbutton'))

//var length = document.getElementById("onglet");

//var doc = document.getElementById("divide-y").getElementsByTagName("li")
//console.log(doc.length);


form_discussion.addEventListener("submit",(e) => {
   var i = document.getElementById("divide-y").getElementsByTagName("li").length
    e.preventDefault();

    var name_chat = document.getElementById("name_chat").value;
    var name_personne = document.getElementById("nom_personne").value;

    if(name_chat == ""){
        name_chat = "Discussion_" + i;
        //i++
    }

    $.post("model_messagerie.php",{name_chat: name_chat, name_personne: name_personne},() =>{
        var new_li = document.createElement("li");
        var ul = document.getElementById("divide-y");
        new_li.setAttribute("class","ongletbutton discussion p-4 hover:bg-gray-50 cursor-pointer data-discussion=");
        new_li.innerHTML = name_chat;
        ul.append(new_li);
        new_li.setAttribute("data-target","tab"+i)

        var div = document.createElement("div");
            tab_pane.append(div);

            div.setAttribute("data-content","");
            div.setAttribute("id","tab"+i);
            div.setAttribute("class","tab-content");
            i++;
            //f++;
    })

//     var navs = document.getElementById("divide-y")
//     var tab = navs.querySelectorAll("*[data-target]");
//    // console.log(tab);
// //    for(var j = 0; j< navs.length; j++ ){
//        tabs(tab);
})




   $.post("recup.php",(response) => {
   // console.log(response)
    var parse = JSON.parse(response);
   // console.log(parse)

    for(var i = 0; i < Object.keys(parse).length; i++){
        var new_li = document.createElement("li");
        var ul = document.getElementById("divide-y");
        new_li.setAttribute("data-target","tab"+i)
        new_li.setAttribute("class","ongletbutton discussion p-4 hover:bg-gray-50 cursor-pointer data-discussion=");
        new_li.innerHTML = parse[i];
        ul.append(new_li);
        
        var div = document.createElement("div");
        tab_pane.append(div);

        div.setAttribute("data-content","");
        div.setAttribute("id","tab"+i);
        div.setAttribute("class","tab-content")
        new_li.setAttribute("id","onglet");
         
        //test 
        
        l++;
    }
    var navs = document.getElementById("divide-y")
    var tab = navs.querySelectorAll("*[data-target]");
   // console.log(tab);
//    for(var j = 0; j< navs.length; j++ ){
       tabs(tab);
   //} 
    //document.getElementsByClassName("ongletbutton")[0].click();
})







function affichage(test){
    $.post("model_message.php",{id_convo : "id_convo", name_convo: test}, (response) => {
        var pase2 = JSON.parse(response)
        var div = document.getElementsByClassName("active");
        div[0].innerHTML = "";
        var div_content_message = document.querySelectorAll('.tab-content');
        console.log(div_content_message)
        for(var a = 0 ; a < div_content_message.length; a++){
            div_content_message[a].innerHTML = "";
        }   
        //div_content_message.innerHTML="";
        for(var k = 0 ; k < Object.keys(pase2).length; k++){
            var value = Object.values(pase2[k]);
            var div = document.getElementsByClassName("active");
            var p = document.createElement("p");
            p.innerHTML = value[0] + ":"+" "+value[1] + " " + value[2];
            div[0].append(p);

        }
    })
}   

$(document).click(function(event) {
    var element_clicked = $(event.target);
    //console.log(element_clicked[0])
   //console.log(element_clicked[0].innerHTML)
   //console.log(element_clicked)
   if(element_clicked[0] == null){
    return;
   }else if (element_clicked[0].getAttribute("class") == null){
    return;
   } 
    else if(element_clicked[0].getAttribute("class").includes('ongletbutton')){
        var h2 = document.getElementById("h2_nom_convo");
        setInterval(affichage(element_clicked[0].innerHTML),1000);
        h2.innerHTML = "";
        h2.innerHTML = element_clicked[0].innerHTML;
    }
   // return test1;
});

$("#chatbox").submit(function(e){
    e.preventDefault();
    message = $(this).find("input[name=message]").val();
    //id = $(this).find("input[name=number_id]").val();
    //console.log(test1);

    //var navs = document.getElementById("divide-y")
    // var tab = document.getElementsByClassName("active");
     //console.log(test)

    $.post("model_message.php",{message: message,name_convo: test},function(response){
       var parse = JSON.parse(response);
       var div = document.getElementsByClassName("active");
       var p = document.createElement("p");

       var value = Object.values(parse[0]);
        p.innerHTML = value + ":" +message;
        //console.log(div[0]);
         div[0].append(p);

    })
     
})

var hamburger_convo = document.getElementById("menu_hamburger_convo");
var croix_close = document.getElementById('croix_close');
var div_content = document.getElementById("div_message_content");
var twitter_convo = document.getElementById("twitter_convo");
var close_navbar = document.getElementById("close_navbar");
var navbar = document.getElementById("navbar");

hamburger_convo.addEventListener("click",() => {
    var div_convo = document.getElementById("div_convo");
    div_convo.classList.remove('hidden');
    div_convo.classList.add('w-1/2');
    croix_close.classList.remove('hidden');
    div_content.classList.add('hidden');
})


croix_close.addEventListener("click",() => {
    var div_convo = document.getElementById("div_convo");
    croix_close.classList.add('hidden');
    div_convo.classList.remove('w-1/2');
    div_convo.classList.add('hidden');
    div_content.classList.remove('hidden');
    
})

twitter_convo.addEventListener("click",() =>{
    navbar.classList.remove('hidden')
})

close_navbar.addEventListener("click",() => {
    navbar.classList.add('hidden');
})



