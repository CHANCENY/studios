const showsTotal = document.getElementById("shows");
const moviesTotal = document.getElementById("movies");


const sTotal = parseInt(showsTotal.getAttribute('data'));
const mTotal = parseInt(moviesTotal.getAttribute('data'));


for (let i = 0; i < sTotal; i++){
    const categoryId = document.getElementById("category-show-"+i);
    if(categoryId !== null){
        categoryId.addEventListener('change', (e)=>{
            const category_id = e.target.value;
            let list = showsTotal.getAttribute('ids');
            list = list.split(",");
            const entity_id = list[i];
            const bundle = "Shows";

            const data = {category_id, entity_id, bundle};
            saveEntity(data, categoryId);
        })
    }
}

for (let i = 0; i < mTotal; i++){
    const categoryId = document.getElementById("category-movie-"+i);
    if(categoryId !== null){
        categoryId.addEventListener('change', (e)=>{
            const category_id = e.target.value;
            let list = moviesTotal.getAttribute('ids');
            list = list.split(",");
            const entity_id = list[i];
            const bundle = "Movies";

            const data = {category_id, entity_id, bundle};
            saveEntity(data, categoryId);
        })
    }
}

function saveEntity(body, tag){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "stream-categories", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function (){
        if(this.status === 200){
            let data = [];
            try {
               data = JSON.parse(this.responseText);
           }catch (e) {
               console.error(e.message);
           }

           if(data.status > 0){
              tag.parentElement.parentElement.remove();
           }
        }
    }
    xhr.send(JSON.stringify(body));
}
