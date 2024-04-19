function onglets(evt, tabName) {
    var i, ongletcontent, ongletbutton;
    ongletcontent = document.getElementsByClassName("ongletcontent");
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
document.addEventListener("DOMContentLoaded", function() {
    document.getElementsByClassName("ongletbutton")[0].click();
});
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

document.addEventListener("DOMContentLoaded", function() {
    var sauvegardebg = localStorage.getItem('backgroundColor');
    if (sauvegardebg) {
        document.body.style.backgroundColor = sauvegardebg;
    }
    var sauvegardefs = localStorage.getItem('fontSize');
    if (sauvegardefs) {
        document.body.style.fontSize = `${sauvegardefs}px`;
    }
    document.getElementsByClassName("ongletbutton")[0].click();
});



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
    document.getElementsByClassName("ongletbutton")[0].click();
});

function notif() {
    var modal = document.getElementById("notificationsModal");
    if (modal.style.display === "block") {
        modal.style.display = "none";
    } else {
        modal.style.display = "block";
    }
}

//var img = document.getElementById("img-profil");
//localStorage.getItem()

