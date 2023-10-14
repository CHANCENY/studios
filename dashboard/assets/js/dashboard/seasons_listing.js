const seasonDisplay = document.getElementById("season-listing-display");

// Get the URL string
const urlString3 = window.location.href;

// Create a URL object
const url3 = new URL(urlString3);

// Use URLSearchParams to get the parameters
const params3 = new URLSearchParams(url3.search);

function seasonListings(page = 0)
{
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/seasons/season-groups?page="+page, false);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function ()
    {
        if(this.status === 200)
        {
            try{
                const data = JSON.parse(this.responseText);
                data['results'].forEach((item, index)=>{
                    const row = buildRows(item);
                    const tr = document.createElement("tr");
                    tr.id = "season-tr-"+index;
                    tr.innerHTML = row;
                    if(seasonDisplay !== null)
                    {
                        seasonDisplay.appendChild(tr);
                    }
                })
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

function buildRows(item){
    const htmlRow = ` <td>
                        <img width="28" height="28" src="${item.image}" class="rounded-circle" alt="">
                        <h2>${item.name}</h2>
                      </td>
                       <td>S-${item.id}</td>
                      <td>${item.number}</td>
                      <td>
                        ${item.date}
                      </td>
                       <td>
                        ${item.episodes}
                      </td>
                      <td class="text-right">
                        <div class="dropdown dropdown-action">
                           <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                           <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="/shows/edit-show?show-id=${item.entity_id}&season-id=${item.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                               <a class="dropdown-item" href="/search/display/full?season-id=${item.id}"><i class="fa fa-folder-open m-r-5"></i> View</a>
                              <a onclick="prepareDeleteSeason(${item.id})" class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                           </div>
                        </div>
                      </td>`;
    return htmlRow;
}


function seasonListingPager()
{
    const pageNumber = params3.get("page") || 0;
    const currentPage = parseInt(pageNumber);
    let previous = 0;
    let first = 0;
    let second = 0;
    let next = 0;
    if(currentPage === 0)
    {
        previous = 0;
        first = 1;
        second = 2;
        next = 3;
    }else{
        previous = currentPage - 1;
        first  = currentPage + 1;
        second = currentPage + 2;
        next = currentPage + 3;
    }

    const htmlp = `
                                <li class="paginate_button page-item previous" id="DataTables_Table_0_previous">
                                    <a href="/seasons/listing?page=${previous}" aria-controls="DataTables_Table_0" data-dt-idx="${previous}" tabindex="0" class="page-link">Previous</a>
                                </li>
                                 <li class="paginate_button page-item active">
                                    <a href="/seasons/listing?page=${currentPage}" aria-controls="DataTables_Table_0" data-dt-idx="${currentPage}" tabindex="0" class="page-link">${currentPage}</a>
                                </li>
                                <li class="paginate_button page-item">
                                    <a href="/seasons/listing?page=${first}" aria-controls="DataTables_Table_0" data-dt-idx="${first}" tabindex="0" class="page-link">${first}</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="/seasons/listing?page=${second}" aria-controls="DataTables_Table_0" data-dt-idx="${second}" tabindex="0" class="page-link">${second}</a>
                                </li>
                                <li class="paginate_button page-item next" id="DataTables_Table_0_next">
                                    <a href="/seasons/listing?page=${next}" aria-controls="DataTables_Table_0" data-dt-idx="${next}" tabindex="0" class="page-link">Next</a>
                                </li>
                            `;
    const area = document.getElementById("pager-seasons");
    if(area !== null)
    {
        const url = document.createElement("ul");
        url.className = "pagination";
        url.innerHTML = htmlp;
        area.appendChild(url);
    }
}

seasonListings(params3.get("page") || 0);

seasonListingPager();


function prepareDeleteSeason(seasonID)
{
    localStorage.setItem("season", seasonID);
}


function deleteSeasonEntirely()
{
    const movie = localStorage.getItem("season");
    if(movie !== null)
    {
        localStorage.removeItem("show");
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "/shows/delete-show?type=season&id="+movie, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function (){
            if(this.status === 200)
            {
                window.location.reload();
            }else{
                document.getElementById("message-deletes").textContent = "Failed to delete this Season";
                setTimeout(()=>{
                    window.location.reload();
                }, 3000)
            }

        }
        xhr.send();
    }
}


function searchingSeason(){

    const searchMovie = document.getElementById("season-filter-search");
    if(searchMovie !== null)
    {
        searchMovie.addEventListener("click", (e)=>{
            e.preventDefault();

            const name = document.getElementById("season-filter-name");
            const id = document.getElementById("season-filter-id");
            let searchName = name.value || "no-value";
            let searchID = id.value || "no-value";

            const para = new URLSearchParams({searchName, searchID});
            const xhr = new XMLHttpRequest();

            xhr.open("GET", "/seasons/search?"+para, true);
            xhr.onload = function ()
            {
                if(this.status === 200)
                {
                    try {
                        const data = JSON.parse(this.responseText);
                        seasonDisplay.innerHTML = "";
                        data['results'].forEach((item, index)=>{
                            const row = buildRows(item);
                            const tr = document.createElement("tr");
                            tr.id = "season-tr-"+index;
                            tr.innerHTML = row;
                            if(seasonDisplay !== null)
                            {
                                seasonDisplay.appendChild(tr);
                            }
                        })
                    }catch (e) {
                        console.error(e.message)
                    }
                }
            }
            xhr.send();
        })
    }
}
searchingSeason();
