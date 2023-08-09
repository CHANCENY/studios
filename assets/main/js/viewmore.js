const viewMoreURL = "jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj";

const buttonViewMore = document.getElementById("view-more");
if(buttonViewMore !== null)
{
    buttonViewMore.addEventListener("click", (e)=>{
        e.preventDefault();

        let index = localStorage.getItem('indexAt');
        if(index === null){
            index = 0;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", viewMoreURL+"?index="+index, true);
        xhr.onload = function (){
            if(this.status === 200)
            {
                let data = [];
                try {
                    data = JSON.parse(this.responseText);
                }catch (e) {
                    localStorage.removeItem('indexAt');
                    window.scrollTo({top:0,behavior: "smooth"});
                    window.location.reload();
                    console.error(e.message);
                }
                buildCards(data.body);
                localStorage.setItem('indexAt', JSON.stringify(parseInt(index) + 1));
            }
        }

        xhr.onerror = function (){
            if(this.status === 404){
                let data = [];
                try {
                  data = JSON.parse(this.responseText);
                }catch (e) {
                    console.error(e.message)
                }
                if(data.status === 200){
                    localStorage.removeItem('indexAt');
                    window.scrollTo({top:0,behavior: "smooth"});
                    window.location.reload();
                }
            }
        }
        xhr.send();
    })
}

function buildCards(data)
{
    if(data.length > 0)
    {
       data.forEach((item)=>{
           const thisDiv = card(item);
           const rowDiv = document.getElementById("row-premier");
           rowDiv.appendChild(thisDiv);
       })
    }

}

function card(item){
    const divBox = document.createElement("div");
    divBox.className = "col-6 col-sm-4 col-lg-3 col-xl-2";

    const cardDiv = document.createElement("div");
    cardDiv.className = "card";

    const cardCover = document.createElement("div");
    cardCover.className = "card__cover";

    const imgTag = document.createElement("img");
    imgTag.src = "https://image.tmdb.org/t/p/w500"+ item.poster_path || item.backdrop_path;
    imgTag.alt = item.title || item.original_title;

    const aTag = document.createElement("a");
    aTag.href ="expected-premiere?id="+item.id;
    aTag.className  ="card__play";

    const iTag = document.createElement("i");
    iTag.className = "icon ion-ios-play";

    aTag.appendChild(iTag);
    cardCover.appendChild(imgTag);
    cardCover.appendChild(aTag);

    const contentDiv = document.createElement("div");
    contentDiv.className = "card__content";

    const h3Tag = document.createElement('h3');
    h3Tag.className = "card__title";

    const h3A = document.createElement('a');
    h3A.href = "expected-premiere?id="+item.id;
    h3A.title = "Expected premiere - "+item.title || item.original_title;
    h3A.rel = "nofollow";
    h3A.textContent = item.title || item.original_title;

    h3Tag.appendChild(h3A);
    contentDiv.appendChild(h3Tag);


    const span = document.createElement("span");
    span.className = "card__category";
    let linG = genre(item.genre);
    for (let i = 0; i < linG.length; i++){
        const spanA = document.createElement("a");
        spanA.href = linG[i].link;
        spanA.title = linG[i].title;
        spanA.textContent = linG[i].text;
        spanA.rel = "index";
        span.appendChild(spanA);
    }

    const spanRate = document.createElement("span");
    spanRate.className =  "card__rate";
    const iSpanRate = document.createElement('i');
    iSpanRate.className = "icon ion-ios-star";
    spanRate.appendChild(iSpanRate);
    spanRate.appendChild(document.createTextNode(item.vote_average.toFixed(1)));

    contentDiv.appendChild(span);
    contentDiv.appendChild(spanRate);

    cardDiv.appendChild(cardCover);
    cardDiv.appendChild(contentDiv);

    divBox.appendChild(cardDiv);
    return divBox;

}

function genre(genre){
    let list = genre.split("|");
    let list2 = [];

    for(let i = 0; i < list.length; i++){
        if(i <= 1){
            const l = {link : "/genres?genre="+encodeURI(list[i]), title: list[i], text: list[i]};
            list2.push(l);
        }
    }
    return list2;
}