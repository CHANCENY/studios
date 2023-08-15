/**
 * buttons
 */

let total = document.getElementById('t');

if(total !== null){
    total = parseInt(total.getAttribute('data'));

    for (let i = 0; i <= total; i++){
        const likeButton = document.getElementById('like'+i);
        const dislikeButton = document.getElementById('dislike'+i);
        likes(likeButton);
        dislikes(dislikeButton);
    }
}


function likes(likeButton){
    if(likeButton !== null){
        likeButton.addEventListener('click', (e)=>{
            e.preventDefault();
            const uid = likeButton.getAttribute('uid');
            const entity = likeButton.getAttribute('entity');
            const cid = likeButton.getAttribute('cid');

            const xhr = new XMLHttpRequest();
            xhr.open('POST',"ccccpppppppprrrrrrrrrrrrrrrrrrrrr", true);
            xhr.setRequestHeader('Content-Type', "application/json");
            xhr.onload = function (){
                if(this.status === 200){
                    let data = [];
                    try {
                        data = JSON.parse(this.responseText);
                    }catch (e) {
                        console.error(e.message);
                    }

                    if(data.status !== null){
                        //<i class="icon ion-md-thumbs-up"></i>
                        const i = document.createElement('i');
                        i.className = "icon ion-md-thumbs-up";
                        likeButton.textContent ="";
                        likeButton.innerHTML = "";
                        likeButton.appendChild(i);
                        likeButton.appendChild(document.createTextNode(data.status));
                    }
                }
            }
            xhr.send(JSON.stringify({uid,entity,cid, likes: true}));
        })
    }
}

function dislikes(dislikeButton){
    if(dislikeButton !== null){
        dislikeButton.addEventListener('click', (e)=>{
            e.preventDefault();
            const uid = dislikeButton.getAttribute('uid');
            const entity = dislikeButton.getAttribute('entity');
            const cid = dislikeButton.getAttribute('cid');

            const xhr = new XMLHttpRequest();
            xhr.open('POST',"ccccpppppppprrrrrrrrrrrrrrrrrrrrr", true);
            xhr.setRequestHeader('Content-Type', "application/json");
            xhr.onload = function (){
                if(this.status === 200){
                    let data = [];
                    try {
                        data = JSON.parse(this.responseText);
                    }catch (e) {
                        console.error(e.message);
                    }

                    if(data.status !== null){
                        //<i class="icon ion-md-thumbs-up"></i>
                        const i = document.createElement('i');
                        i.className = "icon ion-md-thumbs-down";
                        dislikeButton.textContent ="";
                        dislikeButton.innerHTML = "";
                        dislikeButton.appendChild(i);
                        dislikeButton.appendChild(document.createTextNode(data.status));
                    }
                }
            }
            xhr.send(JSON.stringify({uid,entity,cid, dislikes: true}));
        })
    }
}