
function upcoming() {
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/upcoming", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'].slice(0, 4);
                data.forEach((item,index)=>{
                    rows(item, index);
                })
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

upcoming();

function rows(item, index)
{
    const tr = document.createElement("tr");
    const td1 = document.createElement("td");
    td1.style.minWidth = "200px";
    const a = document.createElement("a");
    a.className ="avatar";
    a.href = "profile.html";
    const tit = item.title || item.original_title;
    a.textContent = tit[0].toUpperCase();

    const h2 = document.createElement("h2");
    const a1 = document.createElement("a");
    a1.href = "profile.html";
    a1.textContent = item.title || item.original_title;
    const span = document.createElement("span");
    span.textContent = "Rates "+item.vote_average;
    a1.appendChild(span);
    h2.appendChild(a1);
    td1.appendChild(a);
    td1.appendChild(h2);

    const td2 = document.createElement("td");
    const h5 = document.createElement("h5");
    h5.className = "time-title p-0";
    h5.textContent = item.genre;
    const p = document.createElement("p");
    p.textContent = "Votes "+item.vote_count;
    td2.appendChild(h5);
    td2.appendChild(p);

    const td3 = document.createElement("td");
    const h52 = document.createElement("h5");
    h52.className = "time-title p-0";
    h52.textContent = item.release_date;
    const p2 = document.createElement("p");
    p2.textContent = "Popularity "+item.popularity;
    td3.appendChild(h52);
    td3.appendChild(p2);

    const td4 = document.createElement("td");
    const a4 = document.createElement("a");
    a4.className = "btn btn-outline-primary take-btn";
    a4.href = "#";
    a4.id = "upcoming-"+index;
    a4.textContent = "Take Up";
    td4.appendChild(a4);

    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(td4);
    const upcomingT = document.getElementById("upcoming-table-body");
    if(upcomingT !== null)
    {
        upcomingT.appendChild(tr);
    }
}