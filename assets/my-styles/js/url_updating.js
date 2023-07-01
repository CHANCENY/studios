const loadingImage = "assets/images/loading.gif";
const tbody = document.getElementById('all');
let ids = [];

if(tbody !== null){
    ids = tbody.getAttribute('data').split(',');
}

for (let i = 0; i < ids.length; i++){
    const saveForm = document.getElementById("edit-episode-"+ids[i]);
    if(saveForm !== null){
        saveForm.addEventListener('click', (e)=>{
            e.preventDefault();
            const urlTag = document.getElementById('url-'+ids[i]);
            const epTag = document.getElementById('id-episode-'+ids[i]);
            const publishTag = document.getElementById('publish-'+ids[i]);
            statusIcon(true);
            setTimeout(()=>{
                sendRequests(urlTag, publishTag, epTag);
            }, 5000)
        })
    }
}

function sendRequests(url, publish, eid){
    const data = {episode_id: eid.value, publish: publish.value, url: url.value};
    const params = new URLSearchParams(data);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'url-tv-attachment?'+params, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function (){
        if(this.status === 200){
            let datain = [];
           try {
            console.log(this.responseText);
               datain = JSON.parse(this.responseText);
           }catch (e) {
              console.error("Failed to parse json");
           }
           if(datain.status === true){
               statusIcon(false);
           }
        }
    }
    xhr.send();
}

function statusIcon(flag){
    let idr = Math.random();
   if(flag){
       let img = document.createElement('img');
       img.id = idr;
       img.style.width = "2rem";
       img.src = loadingImage;

       const tdImg = document.getElementById('msg');
       if(tdImg !== null){
           tdImg.appendChild(img);
       }
   }else{
       let span = document.createElement('span');
       span.textContent = "Changes Saved";
       span.id = idr;
       const tdImg = document.getElementById('msg');
       tdImg.innerHTML = "";
       tdImg.appendChild(span);
       setTimeout(()=>{
           span.remove();

           window.location.reload();
       }, 1000);
   }
}

const buttonPublished = document.getElementById('publish');
const buttonUnPublished = document.getElementById('un-publish');

if(buttonPublished !== null){
    buttonPublished.addEventListener('click', (e)=>{
        statusIcon(true);
        setTimeout(()=>{
            const p = new URLSearchParams({seasonId: buttonPublished.getAttribute('data'), action:'publish'})
            sendOnButtonClicked(p);
        }, 5000)
    })
}

if(buttonUnPublished !== null){
    buttonUnPublished.addEventListener('click', (e)=>{
        statusIcon(true);
        setTimeout(()=>{
            const p = new URLSearchParams({seasonId: buttonUnPublished.getAttribute('data'), action:'unpublish'})
            sendOnButtonClicked(p);
        }, 5000)
    })
}

function sendOnButtonClicked(params){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'url-tv-attachment?'+params, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function (){
        if(this.status === 200){
            const data = JSON.parse(this.responseText);
            if(data.status === true){
                statusIcon(false);

            }
        }
    }
    xhr.send();

}