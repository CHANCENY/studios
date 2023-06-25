/**
 * Handles menu click to set underline and white color active class
 */

for(let i = 0; i < 20; i++){
    const menu = document.getElementById('menu-'+i);
    if(menu !== null){
        menu.addEventListener('click', (e)=>{
           let id = e.target.id;
           for(let j = 0; j < 20; j++){
               const allmenus = document.getElementById('menu-'+j);
               if(allmenus !== null){
                   if(allmenus.id !== id){
                       allmenus.removeAttribute('class');
                       allmenus.setAttribute('class', "nav-link text-white-50");
                   }
               }
            }
           if(menu.id !== 'menu-0'){
               localStorage.setItem('clicked', menu.id);
               menu.removeAttribute('class');
               menu.setAttribute('class', 'nav-link text-white');
           }else{
               localStorage.setItem('clicked', null);
           }


        })
    }
}

const clicked = localStorage.getItem('clicked');
if(clicked !== null){
    const url = window.location.href;
    if(url.includes('index')){
        localStorage.removeItem('clicked');
    }
    const active = document.getElementById(clicked);
    active.removeAttribute('class');
    active.setAttribute('class', 'nav-link text-white');
}