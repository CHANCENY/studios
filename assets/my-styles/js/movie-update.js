const loadingImage = "assets/images/loading.gif";
const totalTag = document.getElementById('total-movie');
let total = 0;
if(totalTag !== null){
    total = parseInt(totalTag.getAttribute('data'));
}

for(let i= 0; i < total; i++){
    const saveLink = document.getElementById('save-'+i);
    if(saveLink !== null){
        saveLink.addEventListener('click', (e)=>{
            e.preventDefault();
            const movieUrl = document.getElementById('movie-url-'+i).value;
            const movieId = document.getElementById('movie-id-'+i).value;
            statusIcons(true);
            sendUpdate(new URLSearchParams({movieUrl, movieId}));
        })
    }
}

function statusIcons(flag){
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


function sendUpdate(params){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'change-movie-url?'+params, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function (){
        if(this.status === 200){
            let data = [];
            try {
                data = JSON.parse(this.responseText);
            }catch (e) {
                console.error('Failed to parse json ');
            }
            if(data.status === true){
                statusIcons(false);
            }
        }
    }
    xhr.send();
}