const searchInput = document.getElementById('search');
const resultBoxTag = document.getElementById('result-box');

const tvInputTag = document.getElementById('tv-search');


if(searchInput !== null){
    searchInput.addEventListener('blur', (e)=>{
        const textString = e.target.value;
        requestSend(textString, 'discover');
    })
}

if(tvInputTag !== null){
    tvInputTag.addEventListener('blur', (e)=>{
        const textString = e.target.value;
        requestSend(textString, 'tv');
    })
}

function requestSend(string, type){
    resultBoxTag.innerHTML = "";
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'discovery-movie?string='+string+'&type='+type, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function (){
        if(this.status === 200){
            let data = [];
            try{
                data = JSON.parse(this.responseText);
            }catch (e) {
                console.log(e)
            }

            if(data.error === ""){
                const body = data.body.results;
                console.log(body);
                body.forEach((item)=>{
                    createCards(item, type);
                })
            }
        }
    }
    xhr.send()
}


function createCards(item, type='tv'){
    console.log(item)
    let div = document.createElement('div');
    div.className = "card m-auto bg-dark";
    div.style.width = "12rem";

    let img = document.createElement('img');
    let imge = item.poster_path !== null ? item.poster_path : item.backdrop_path;
    img.src = "https://image.tmdb.org/t/p/w500"+imge;
    img.className = "card-img-top";
    img.loading = "lazy";

    let  h5 = document.createElement('h5');
    h5.className = "card-title";
    h5.textContent = item.original_title || item.name;

    let p = document.createElement('p');
    p.textContent = item.release_date || item.first_air_date;
    p.className = "card-text";

    let bodydiv = document.createElement('div');
    bodydiv.className = "card-body";

    const params = new URLSearchParams(item);

    let link = document.createElement('a');
    link.href = type === 'discover' ? "tm-form-add?"+params : "tm-tv-save?"+params;
    link.target = "_blank";
    link.textContent = type === 'discover' ? "ADD MOVIE" : "ADD SHOW";
    link.className = "btn btn-primary";

    bodydiv.appendChild(h5);
    bodydiv.appendChild(p);
    bodydiv.appendChild(link);

    div.appendChild(img);
    div.appendChild(bodydiv);

    if(resultBoxTag !== null){
        resultBoxTag.appendChild(div);
    }
}