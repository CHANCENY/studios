/**
 * numbers
 */

function counting()
{
    const token = getCookie("token_skey");
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/numbers/total", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = this.responseText;
            try{
                data = JSON.parse(data);
            }catch (e) {
                console.error(e.message);
            }
            if(data.status === 200)
            {
                document.getElementById("series-count").textContent = data.shows;
                document.getElementById("movies-count").textContent = data.movies;
                document.getElementById("seasons-count").textContent = data.seasons;
                document.getElementById("episodes-count").textContent = data.episodes;
            }
        }
    }
    xhr.send();
}

counting();

function management()
{
    const token = getCookie("token_skey");
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/management", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = this.responseText;
            try{
                data = JSON.parse(data);
            }catch (e) {
                console.error(e.message);
            }

            const show = `<div class="item">
                <div class="bar">
                  <span class="percent">${data.shows} %</span>
                  <div class="item-progress" data-percent="${data.shows}">
                    <span class="title">Series</span>
                  </div>
                </div>
              </div>`;

            const movie = `<div class="item">
                <div class="bar">
                  <span class="percent">${data.movies}%</span>
                  <div class="item-progress" data-percent="${data.movies}">
                    <span class="title">Movies</span>
                  </div>
                </div>
              </div>`;

            const mange = document.getElementById("management-percent");
            if(mange !== null)
            {
                mange.innerHTML = movie + show;
            }
        }
    }
    xhr.send();
}

management();