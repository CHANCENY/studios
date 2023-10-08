
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
                    let t = JSON.parse(this.responseText)['results'];
                    let total = t.length === 0 ? 0 : t.length - 1;
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
                <a href="/notifications?id=${item.id}">
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

function notificationsListing(id) {
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/alert?id="+id, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function () {
        if (this.status === 200) {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'];
                data.forEach((item, index) => {
                    notificationCard(item, index);
                })
            } catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

function notificationCard(item, index){

    const l = item.title[0].toUpperCase();
    const html = `<div class="activity-user">
                    <a href="/notification?id=${item.id}" class="avatar" title="${item.title}" data-toggle="tooltip">${l}</a>
                  </div>
                  <div class="activity-content">
                    <div class="timeline-content">
                      <p>${item.title}</p>
                      <span class="time">${item.time}</span>
                    </div>
                  </div>
                  <a class="activity-delete" href="#" onclick="deleteNotification(${item.id})" title="Delete">&times;</a>`;
    const noti = document.getElementById("all-notification");
    const li = document.createElement("li");
    li.id = "notification-"+index;
    li.innerHTML = html;
    if(noti !== null)
    {
        noti.appendChild(li);
    }
}

function deleteNotification(id)
{
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("DELETE", "https://api.streamstudios.online/dashboard/alert/delete?id="+id, false);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function ()
    {
        if(this.status === 200)
        {
            window.location.reload();
        }
    }
    xhr.send();
}

let id = 0;
if(params.get("id") !== null)
{
    id = params.get("id");
}

const urlN = window.location.href;
if(urlN.includes("notification"))
{
    notificationsListing(id);
}
