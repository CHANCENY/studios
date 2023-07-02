const requestSearchTag = document.getElementById('search-request');

if(requestSearchTag !== null){
    requestSearchTag.addEventListener('blur', (e)=>{
        const params = new URLSearchParams({q: e.target.value});
        requestSender(params);
    })
}

function requestSender(params){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'request-show-movie?'+params, true);
    xhr.setRequestHeader('Content-Type','application/json');
    xhr.onload = function (){
        if(this.status === 200){
            const data = JSON.parse(this.responseText);
            const result = data.body.results;
            if(result.length > 0){
                document.getElementById('request-search-boxes').innerHTML = "";
                result.forEach((item)=>{
                    cards(item);
                })
            }

        }
    }
    xhr.send();
}

function cards(item){
    const div = document.createElement('div');
    div.className = "card bg-dark mx-1 mt-3";
    div.style.width = "10rem";

    const img = document.createElement('img');
    img.className = "card-img-top m-auto zoom";
    img.src ="https://image.tmdb.org/t/p/w500"+ item.poster_path || item.backdrop_path;

    const div2 = document.createElement('div');
    div2.className = "card-body";

    const p = document.createElement('p');
    p.className = "card-text text-white-50";
    p.textContent = item.title || item.original_title;


    const a = document.createElement('a');
    a.className = "text-decoration-none btn btn-warning w-100 text-dark";
    a.textContent = "Request";
    const params = new URLSearchParams(item);
    a.href = "request-show-movie?"+params;

    div2.appendChild(p);
    div2.appendChild(a);
    div.appendChild(img);
    div.appendChild(div2);

    const row = document.getElementById('request-search-boxes');
    if(row !== null)
    {
        row.appendChild(div);
    }
}