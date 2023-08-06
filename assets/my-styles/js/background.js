const bodyTag = document.getElementById("background");

if(bodyTag !== null){
    render();
    refreshImages();
}


function render(){
    let storage = window.localStorage.getItem("backgroundImages");

    if(storage === null){
        fetchBackGroundImages();
        setTimeout(()=>{
            storage = window.localStorage.getItem("backgroundImages");
        }, 2000)
    }

    const images = JSON.parse(storage);
    setInterval(()=>{
        const locationIndex = timetoken(images.length);
        const thisImageNow = images[locationIndex];
        bodyTag.style.backgroundImage="url("+thisImageNow.image+")";
    }, 5000)
}


function fetchBackGroundImages(){
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "img", true);
    xhr.onload = function (){
        if(this.status === 200)
        {
            const data = this.responseText;
           try {
               JSON.parse(data);
               window.localStorage.setItem("backgroundImages", data);
           }catch (e) {
               console.error("Failed to parse background images");
           }

        }
    }
    xhr.send();
}

function timetoken(total = 10){
    const token = window.localStorage.getItem("timetoken");
    if(token === null){
        window.localStorage.setItem("timetoken", 0);
        return 0;
    }

    const newToken = parseInt(token) + 1;
    if(newToken === total - 1){
        window.localStorage.setItem("timetoken", 0);
        return 0;
    }
    return newToken;
}

function refreshImages(){
    setInterval(()=>{
        fetchBackGroundImages();
    }, 10000)
}