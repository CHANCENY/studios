
const subscriptionButton = document.getElementById('subcribe-button');
const subscriptionEmailInput = document.getElementById('subcription-email');
if(subscriptionButton !== null)
{
    subscriptionButton.addEventListener('click',(e)=>{
        e.preventDefault();
        console.log(e.type)
        const email = subscriptionEmailInput.value;
        if(email !== "")
        {
            sendSubscriptionEmail({email});
        }
    })

}

function sendSubscriptionEmail(body)
{
    const xhr = new XMLHttpRequest();
    xhr.open('post', 'stream-studio-subscription', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function ()
    {
        if(this.status === 200){
            let data = [];
            try {
             data = JSON.parse(this.responseText);
            }catch (e) {
                data = {status: false};
              console.error('Failed to parse json ',e.message);
            }
            if(data.status !== false){
                window.location.replace('stream-studio-subscription?subscription='+data.status);
            }else{
                alert('Failed to create subscription');
            }
        }
    }
    xhr.send(JSON.stringify(body));
}


/**
 * subscrition final
 */

const alerts = ['movie-alert','show-alert','episode-alert', 'season-alert'];
for (let i = 0; i < alerts.length; i++)
{
    const alertTag = document.getElementById(alerts[i]);
    if(alertTag !== null){
        alertTag.addEventListener('change',(e)=>{
            let name = e.target.getAttribute('data');
            let status = alertTag.checked;

            if(status === true){
                window.localStorage.setItem(alertTag.id, name);
            }else{
                window.localStorage.removeItem(alertTag.id);
            }
        })
    }
}

const saveSubs = document.getElementById('save-subscriptions');
if(saveSubs !== null){
    saveSubs.addEventListener('click',(e)=>{
        let data = [];
        for (let i = 0; i < alerts.length; i++){
            data.push(window.localStorage.getItem(alerts[i]) || null);
        }
        const cleanList = data.filter((item)=> item !== null);
        const line = cleanList.join('|');
        const idt = new URL(window.location.href)
        const id = idt.searchParams.get('subscription');
        let params = new URLSearchParams({id,line});
        saveSubscriptionNow(params);
    })

}

function saveSubscriptionNow(params)
{
    const xhr = new XMLHttpRequest();
    xhr.open('GET','stream-studio-subscription?'+params,true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function ()
    {
        if(this.status === 200){
            let data = [];
            try {
                data = JSON.parse(this.responseText);
            }catch (e) {
                data = {result: false};
                console.error(e.message);
            }
            if(data.result === true){
                for (let i = 0; i < alerts.length; i++){
                    window.localStorage.removeItem(alerts[i]);
                    window.location.replace('index');
                }
            }
        }
    }
    xhr.send();
}

const p = window.location.href;
if(!p.includes('stream-studio-subscription')){
    for (let i = 0; i < alerts.length; i++){
        window.localStorage.removeItem(alerts[i]);
    }
}