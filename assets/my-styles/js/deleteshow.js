const totalTag = document.getElementById('total');
let total = 0;
if(totalTag !== null){
    total = parseInt(totalTag.getAttribute('data'));
}

for (let i = 0; i < total; i++){
    const deleteTag = document.getElementById("delete-link-"+i);
    if(deleteTag !== null){
        deleteTag.addEventListener('click',(e)=>{
            if(window.confirm("You Clicked delete link are you sure you want to delete this show If yes please click confirm" +
                " Note this will delete all data related to this shows") === false){
                e.preventDefault();
            }
        })
    }
}
