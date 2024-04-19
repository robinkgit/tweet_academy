var xhr = new XMLHttpRequest();       
    xhr.open("GET", "images/profil-image.png", true); 
    xhr.responseType = "blob";
    xhr.onload = function (e) {
            //console.log(this.response);
            var reader_img = new FileReader();
            reader_img.onload = function(event) {
               var res = event.target.result;
               localStorage.setItem("image_00", res);
            }
            var file = this.response;
            reader_img.readAsDataURL(file)
    };
    xhr.send()


function tabs (nav){
    var tabs = nav.querySelectorAll("*[data-target]");
    //console.log(tabs);
    var i = 0;
    for(i= 0 ; i< tabs.length; i++){
        var tab = tabs[i];
        tab.addEventListener("click", (e) =>{
            var id = e.target.dataset.target;
            var pane = document.getElementById(id);
            //console.log(pane);
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
        });
    }
   }
   var navs = document.querySelectorAll(".tab-list");
   for(var j = 0; j< navs.length; j++ ){
       tabs(navs[j]);
   } 


   var message = document.getElementById("message");

   message.addEventListener("click",function(){
    openForm();
   })
   function openForm() {
    document.getElementById("popupForm").style.display = "block";
  }

  function closeForm() {
    document.getElementById("popupForm").style.display = "none";
  }

   // TEST TWEET // 
   function max_char()
   {
       var area_length=document.getElementById("tweet").value.length;
       if(area_length>140)
       {
            alert("140 caractères autorisé dépassé, Veuillez raccourcir votre texte puis réessayer.");
            return false;
       }
       else
       {           
                var formData = document.getElementById("tweet").value
        
              
                $.post("./connexion.php",{tweet: formData},function(response){

                })        
        

            if(document.querySelector('input[type=file]').value !== ""){
                var img = document.querySelector('input[type=file]').files[0];
                var reader = new FileReader();
                
                reader.addEventListener("load", function () {
                  //console.log(reader.result);
                  var key =  Math.floor(Math.random() * 9000)
                    localStorage.setItem("image_" + key, reader.result);


                    $.post("model.php",{key: "image_"+key},function(){});


                }, false);
                
                if (img) {
                    reader.readAsDataURL(img);
                }

               var img_element =  document.createElement("img");
               img_element.setAttribute("src", localStorage.getItem("image_" + i));
               document.getElementById("tab2").appendChild(img_element);
            }
            
           return true;
       }
   }

   function like(dataId)
   {
    $.post("./like.php",{tweetid: dataId},function(response){
                    
    })        

          
       }
 
   function printTweets(){
    var print = true;
    $.ajax({
        type: 'GET',
        url: 'feed.php',
        data: {
            print:print,
        },
        success: function(response){
            let tweets = JSON.parse(response);
            console.log(tweets);
            var tweetsDiv = document.getElementById("tweetsDiv");
            tweetsDiv.innerHTML = '';
            tweets.forEach((element) => {
                var div = document.createElement('div');
                div.id = 'test';
                var h1 = document.createElement('h1');
                var p = document.createElement('p');
                var p1 = document.createElement('p');
                var img = document.createElement('img');
               var like = document.createElement('img');
                var br = document.createElement('br');
                var br1 = document.createElement('br');
                var nombrelike = document.createElement('p');

                var retweet = document.createElement("img");
                retweet.src = "./images/retweet.png"
                retweet.setAttribute("id","retweet");
                retweet.setAttribute('data-id', element['id']);

                like.src ='images/coeur.png';
                    like.id = 'coeur';
                    nombrelike.id ="nombrelike";
                nombrelike.innerHTML = element['likes_count'];
                    like.setAttribute('data-id', element['id']);

                var img_com = document.createElement("img");
                img_com.src="./images/commentaire.png";
                img_com.setAttribute("id","commentaire");
                img_com.setAttribute('data-id-com', element['id']);

                var key_ls_picture = element['profile_picture'];

                p.id = 'pseudo';
                p1.id = 'letweet';
                img.src =localStorage.getItem(key_ls_picture);
                img.setAttribute('id','pdp');
                var content = element['content'].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                if(element['content'].includes("image_")){
                    //alert("ok");
                   // console.log(localStorage.getItem(element['content']));
                    var img = document.createElement("img");
                    img.id = 'image';
                    img.setAttribute("src",localStorage.getItem(element['content']));
                    p.innerHTML = element['at_user_name'] + " : ";
                   // tweetsDiv.appendChild(p);
                    tweetsDiv.appendChild(img);
                }else{
                    if(element['content'].includes("#") || element['content'].includes("@")){
                        let temp = element['content'].replace(/#/g, "%§!%#");
                        let words = temp.split(/\s|%§!%/);
                        let hashtags = words.filter((word) => word.startsWith("#"));
                        // console.log(hashtags);
                        if(hashtags){
                            hashtags.forEach(function(elementBis) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'printHashtag.php',
                                    data: {
                                      saveHashtag:elementBis
                                    }
                                });
                            });
                        }
                    
                    var contentTemp = element['content'].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                    var content = contentTemp.replace(/@(\w+)/g, `<a href='profil_search.php?at=$1'>@$1</a>`);
                    h1.innerHTML = element['username'];
                    p.innerHTML = `<a href='profil_search.php?at=${element['at_user_name'].slice(1)}'>` + element['at_user_name'] + `</a>`;
                    nombrelike.innerHTML = element['likes_count'];
                    like.setAttribute('data-id', element['id']);
                    p1.innerHTML = content;
                    tweetsDiv.appendChild(div);
                    div.appendChild(img);
                    div.appendChild(br);
                    div.appendChild(h1);
                    div.appendChild(p);
                    div.appendChild(p1);
                    div.appendChild(br1);
                       div.appendChild(like);
                    div.appendChild(nombrelike);
                    div.appendChild(retweet);
                    div.appendChild(img_com);
                   // console.log(element['content']);
                    }else{
                                            // console.log(element['content']);
                    //console.log(element);
                    h1.innerHTML = element['username'];
                    p.innerHTML = `<a href='profil_search.php?at=${element['at_user_name'].slice(1)}'>` + element['at_user_name'] + `</a>`;
                     nombrelike.innerHTML = element['likes_count'];
                    like.setAttribute('data-id', element['id']);
                    p1.innerHTML = content;
                    tweetsDiv.appendChild(div);
                    div.appendChild(img);
                    div.appendChild(br);
                    div.appendChild(h1);
                    div.appendChild(p);
                    div.appendChild(p1);
                    div.appendChild(br1);
                        div.appendChild(like);
                    div.appendChild(nombrelike);
                    div.appendChild(retweet); 
                    div.appendChild(img_com); 
                   // console.log(element['content']);
                    }

                }
            });
        },
        error: function() {
            console.log("error");
        }
    });



    $.post('./feed.php',{retweet:'check_retweet'},(response) =>{
        if(Object.keys(response).length == 0){
            return;
        }
        var parse = JSON.parse(response);
        for(var i = 0 ; i < Object.keys(parse).length; i++){
            var value_parse = Object.values(parse[i]);
        ///console.log(value_parse);


            var div = document.createElement('div');
                div.id = 'test';
                var h1 = document.createElement('h1');
                var p = document.createElement('p');
                var p1 = document.createElement('p');
                var img = document.createElement('img');
                var br = document.createElement('br');
                var br1 = document.createElement('br');
                  var like = document.createElement('img');
                var nombrelike = document.createElement('p');
                var p_user_retweet = document.createElement('p');
                p_user_retweet.innerHTML = value_parse[1]['at_user_name'] + " a retweet";
                p_user_retweet.style.opacity = "0.33"
                p_user_retweet.setAttribute("id","nom_retweet");
                p_user_retweet.style.position = "relative";
                p_user_retweet.style.bottom = "1.5rem";
                p_user_retweet.style.right = "3.3rem";
                p_user_retweet.style.width= "20rem";


                var retweet = document.createElement("img");
                retweet.src = "./images/retweet.png"
                retweet.setAttribute("id","retweet");
                retweet.setAttribute('data-id', value_parse[0]['id']);
                    like.src ='images/coeur.png';
                like.id = 'coeur';
                nombrelike.id ="nombrelike";
                nombrelike.innerHTML = value_parse[0]['likes_count'];
                like.setAttribute('data-id', value_parse[0]['id']);

            
                var img_com = document.createElement("img");
                img_com.src="./images/commentaire.png";
                img_com.setAttribute("id","commentaire");
                img_com.setAttribute('data-id-com', value_parse[0]['id']);


                var key_ls_picture = value_parse[0]['profile_picture'];

                p.id = 'pseudo';
                p1.id = 'letweet';
                img.src =localStorage.getItem(key_ls_picture);
                img.setAttribute('id','pdp');
                var content = value_parse[0]['content'].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                if(value_parse[0]['content'].includes("image_")){
                    //alert("ok");
                   // console.log(localStorage.getItem(element['content']));
                    var img = document.createElement("img");
                    img.id = 'image';
                    img.setAttribute("src",localStorage.getItem(value_parse[0]['content']));
                    p.innerHTML = value_parse[0]['at_user_name'] + " : ";
                   // tweetsDiv.appendChild(p);
                    tweetsDiv.appendChild(img);
                }else{
                    if(value_parse[0]['content'].includes("#")){
                        let temp = value_parse[0]['content'].replace(/#/g, "%§!%#");
                        let words = temp.split(/\s|%§!%/);
                        let hashtags = words.filter((word) => word.startsWith("#"));
                        // console.log(hashtags);
                        if(hashtags){
                            hashtags.forEach(function(elementBis) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'printHashtag.php',
                                    data: {
                                      saveHashtag:elementBis
                                    }
                                });
                            });
                        }
                    
                    var content = value_parse[0]['content'].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                 //   console.log(content);
                                                                // console.log(element['content']);
                    //console.log(element);
                    
                    h1.innerHTML = value_parse[0]['username'];
                    p.innerHTML = value_parse[0]['at_user_name'];
                   nombrelike.innerHTML = value_parse[0]['likes_count'];

                    p1.innerHTML = content;
                    tweetsDiv.appendChild(div);
                    div.appendChild(p_user_retweet);
                    div.appendChild(img);
                    div.appendChild(br);
                    div.appendChild(h1);
                    div.appendChild(p);
                    div.appendChild(p1);
                    div.appendChild(br1);
                    div.appendChild(retweet);
                    div.appendChild(img_com);
                   // console.log(element['content']);
                    }else{
                                            // console.log(element['content']);
                    //console.log(element);
                    h1.innerHTML = value_parse[0]['username'];
                    p.innerHTML = value_parse[0]['at_user_name'];
                   nombrelike.innerHTML = value_parse[0]['likes_count'];
                    p1.innerHTML = content;
                    tweetsDiv.appendChild(div);
                    div.appendChild(p_user_retweet);
                    div.appendChild(img);
                    div.appendChild(br);
                    div.appendChild(h1);
                    div.appendChild(p);
                    div.appendChild(p1);
                    div.appendChild(br1);
                    div.appendChild(like);
                    div.appendChild(nombrelike);
                    div.appendChild(retweet); 
                    div.appendChild(img_com);  
                   // console.log(element['content']);
                    }

                }
           
        }
    })
    

}   
printTweets();
setInterval(printTweets, 1000);


   document.getElementById("post_tweet").addEventListener("submit",function(e){
    //alert("ok");
    e.preventDefault();
    max_char();

    
       //Partie photo//

   })
   
   const search = document.getElementById("search");
   search.addEventListener("keyup", function(){
       let searchDiv = document.getElementById("searchDiv");
       if (search.value === "") {
           searchDiv.innerHTML = "";
       } 
       if(search.value.startsWith("#")){
           $.ajax({
               type: 'POST',
               url: 'search.php',
               dataType: 'json',
               data: {
                 hashtag:search.value
               },
               success: function(response){
                  // console.log(response);
                   searchDiv.innerHTML = "";
                   if(response !== "error"){
                       response.forEach((element) => {
                           var p = document.createElement('p');
                           var content = element['hashtag'].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                           p.innerHTML = content;
                           searchDiv.appendChild(p);
                       });
                   }
               }
           });
           return false;
        } else if (search.value.startsWith("@")) {
            $.ajax({
                type: 'POST',
                url: 'search.php',
                data: {
                  checkAt:search.value
                },
                success: function(response){
                    if (response !== "error"){
                        responseFormate = JSON.parse(response);
                        searchDiv.innerHTML = "";
                        responseFormate.forEach((element) => {
                            var p = document.createElement('p');
                            p.innerHTML = element["at_user_name"].replace(/@(\w+)/g, `<a href='profil_search.php?at=$1'>@$1</a>`);
                            searchDiv.appendChild(p);
                        });
                    } else {
                        searchDiv.innerHTML = ""; 
                    }
                }
            });
            return false;
        }
   });
   document.addEventListener('click', function(e){
    if(e.target.id=="retweet"){

        let dataId = $(e.target).attr("data-id")
        console.log(dataId);
        retweet(dataId);

    }
  })
  
  document.addEventListener('click', function(e){
    if(e.target.id=="logocache"){
        document.getElementsByClassName("sticky").style.display = "none";
        document.getElementsByClassName("sticky").style.display = "inline";

    }
  })
  document.addEventListener('click', function(e){
    if(e.target.id=="coeur"){
        
        let dataId = $(e.target).attr("data-id");
        like(dataId);
        document.getElementById("nombrelike").style.color = "red";

    }
  })
  function retweet(dataId)
   {
    $.post("./retweet.php",{tweetid: dataId},function(response){
       // console.log(response);       
     })
}

function buttonreglage() {
    document.getElementById("settingsModal").style.display = "block";
}

function fermemodal() {
    document.getElementById("settingsModal").style.display = "none";
}

document.addEventListener('click', function(e){
    if(e.target.id=="commentaire"){
        buttonreglage();
    }
  })

var form_response = document.getElementById("response_tweet");


document.addEventListener('click', function(e){
    if(e.target.id=="commentaire"){
        var div_tweet_response_modal = document.getElementById("div_content_com");
        console.log(div_tweet_response_modal);
        div_tweet_response_modal.innerHTML = "";

        var dataId_com = $(e.target).attr("data-id-com")
        $.post('./feed.php',{quoted_tweet: 'test', id_tweet_quoted: dataId_com}, (response) =>{
            var parse_response = JSON.parse(response)
           
                for(var m = 0 ; m < Object.keys(parse_response).length; m++){
                    var value_response_parse = parse_response[m];
                    console.log(value_response_parse)
                   var value_final = Object.values(value_response_parse);
                   console.log(value_final)
                   
                   var h1 = document.createElement('h1');
                   var p = document.createElement('p');
                   var p1 = document.createElement('p');
                   var img = document.createElement('img');
                   var br = document.createElement('br');
                   var br1 = document.createElement('br');
        
        
                   var retweet = document.createElement("img");
                   retweet.src = "./images/retweet.png"
                   retweet.setAttribute("id","retweet");
                   retweet.setAttribute('data-id', value_final[1]);
        
               
                   var img_com = document.createElement("img");
                   img_com.src="./images/commentaire.png";
                   img_com.setAttribute("id","commentaire");
                   img_com.setAttribute('data-id-com', value_final[1]);
        
        
                   var key_ls_picture = value_final[4];
        
                    p.id = 'pseudo_quoted';
                    p1.id = 'letweet_quoted';
                   img.src =localStorage.getItem(key_ls_picture);
                   img.setAttribute('id','pdp');
                   var content = value_final[0].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                   if(value_final[0].includes("image_")){
                       //alert("ok");
                      // console.log(localStorage.getItem(element['content']));
                       var img = document.createElement("img");
                       img.id = 'image';
                       img.setAttribute("src",localStorage.getItem(value_final[0]));
                       p.innerHTML = value_final[2] + " : ";
                      // tweetsDiv.appendChild(p);
                       tweetsDiv.appendChild(img);
                   }else{
                       if(value_final[0].includes("#")){
                           let temp = value_final[0].replace(/#/g, "%§!%#");
                           let words = temp.split(/\s|%§!%/);
                           let hashtags = words.filter((word) => word.startsWith("#"));
                           // console.log(hashtags);
                           if(hashtags){
                               hashtags.forEach(function(elementBis) {
                                   $.ajax({
                                       type: 'POST',
                                       url: 'printHashtag.php',
                                       data: {
                                         saveHashtag:elementBis
                                       }
                                   });
                               });
                           }
                       
                       var content = value_final[0].replace(/#(\w+)/g, `<a href='printHashtag.php?hashtag=$1'>#$1</a>`);
                    //   console.log(content);
                                                                   // console.log(element['content']);
                       //console.log(element);
                       
                       h1.innerHTML = value_final[3];
                       p.innerHTML = value_final[2];
                       p1.innerHTML = content;
                       div_tweet_response_modal.append(img);_modal
                       div_tweet_response_modal.append(br);
                       div_tweet_response_modal.append(h1);
                       div_tweet_response_modal.append(p);
                       div_tweet_response_modal.append(p1);
                       div_tweet_response_modal.append(br1);
                       div_tweet_response_modal.append(retweet);
                       div_tweet_response_modal.append(img_com);
                      // console.log(element['content']);


                      
                       }else{
                                               // console.log(element['content']);
                       //console.log(element);
                       h1.innerHTML = value_final[3];
                       p.innerHTML = value_final[2];
                       p1.innerHTML = content;
                       div_tweet_response_modal.append(img);
                       div_tweet_response_modal.append(br);
                       div_tweet_response_modal.append(h1);
                       div_tweet_response_modal.append(p);
                       div_tweet_response_modal.append(p1);
                       div_tweet_response_modal.append(br1);
                       div_tweet_response_modal.append(retweet); 
                       div_tweet_response_modal.append(img_com);  
                      // console.log(element['content']);


                       }
                    }
                }
        })

        form_response.addEventListener("submit", (e) => {
            e.preventDefault();
            console.log(dataId_com)
            var value_response_tweet = document.getElementById("response_tweet_textarea").value;
            console.log(value_response_tweet);
        
             $.post('./feed.php',{response_tweet_com : value_response_tweet, id_tweet_ref: dataId_com},() => {
             })
        
        })

    }
    
  })
  document.getElementById('menu-bouton').addEventListener('click', function() {
    document.querySelector('.menu').classList.toggle('active');
    $('#search').toggle()
    $('#searchDiv').toggle()
    $('#colonnemlx').toggle()


});
function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
}

const disconnectionButton = document.getElementById("disconnectionButton");
disconnectionButton.addEventListener("click", function(){
    deleteCookie("token");
    window.location.replace('acceuil.html');
})