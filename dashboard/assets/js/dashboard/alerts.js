
function alertListings() {
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/alerts", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'].slice(0, 5);
                const countN = document.getElementById("notification-count");
                if(countN !== null)
                {
                    let total = JSON.parse(this.responseText)["results"].length - 1;
                    countN.textContent = total.toString();
                }
                data.forEach((item,index)=>{
                    alertRows(item, index);
                })
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

alertListings();
function alertRows(item, index)
{
    const l = item.title[0].toUpperCase();
    const html = `
                <a href="activities.html?id=${item.id}">
                  <div class="media">
                    <span class="avatar">${l}</span>
                    <div class="media-body">
                      <p class="noti-details"><span class="noti-title">${item.title}</span></p>
                      <p class="noti-time"><span class="notification-time">${item.time}</span></p>
                    </div>
                  </div>
                </a>
               `;
    const noti = document.getElementById("notification-list");
    const li = document.createElement("li");
    li.className = "notification-message";
    li.id = "notification-"+index;
    li.innerHTML = html;
    if(noti !== null)
    {
        noti.appendChild(li);
    }
}