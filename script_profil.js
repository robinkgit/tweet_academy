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

   var menu_profil = document.getElementById("menu_profile")
   var close_menu = document.getElementById('close');

   menu_profil.addEventListener("click",() => {
    var menu = document.getElementsByClassName('menu');
   
    menu[0].classList.remove('hidden');
    menu[0].classList.add('mr-16');
    menu[0].classList.add('mb-4');
    close_menu.classList.remove('hidden')
    menu_profil.classList.add('hidden');

})
close_menu.addEventListener("click", () => {
    var menu = document.getElementsByClassName('menu');
    menu[0].classList.add('hidden');
    menu[0].classList.remove('mr-16');
    menu[0].classList.remove('mb-4');
    close_menu.classList.add('hidden')
    menu_profil.classList.remove('hidden');
})