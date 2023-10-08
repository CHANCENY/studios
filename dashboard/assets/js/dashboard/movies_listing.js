const movieDisplay = document.getElementById("movie-listing-display");

// Get the URL string
const urlString1 = window.location.href;

// Create a URL object
const url1 = new URL(urlString1);

// Use URLSearchParams to get the parameters
const params1 = new URLSearchParams(url1.search);

function movieListings(page = 0)
{
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/movies-groups?page="+page, false);
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
                    tr.id = "movie-tr-"+index;
                    tr.innerHTML = row;
                    if(movieDisplay !== null)
                    {
                        movieDisplay.appendChild(tr);
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
    const status = item.active === 1 ? "active" : "inactive";
    const statusClass = item.active === 1 ? "status-green" : "status-grey";
    const htmlRow = ` <td>
                        <img width="28" height="28" src="${item.image}" class="rounded-circle" alt="">
                        <h2>${item.name}</h2>
                      </td>
                       <td>M-${item.id}</td>
                      <td>${item.time}min</td>
                      <td>${item.date}</td>
                      <td>
                        <span class="custom-badge ${statusClass}">${status}</span>
                      </td>
                      <td class="text-right">
                        <div class="dropdown dropdown-action">
                           <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                           <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="/movies/edit-movie?movie-id=${item.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                              <a onclick="prepareDeleteMovie(${item.id})" class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                           </div>
                        </div>
                      </td>`;
    return htmlRow;
}


function movieListingPager()
{
    const pageNumber = params1.get("page") || 0;
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
                                    <a href="/movies/listing?page=${previous}" aria-controls="DataTables_Table_0" data-dt-idx="${previous}" tabindex="0" class="page-link">Previous</a>
                                </li>
                                 <li class="paginate_button page-item active">
                                    <a href="/movies/listing?page=${currentPage}" aria-controls="DataTables_Table_0" data-dt-idx="${currentPage}" tabindex="0" class="page-link">${currentPage}</a>
                                </li>
                                <li class="paginate_button page-item">
                                    <a href="/movies/listing?page=${first}" aria-controls="DataTables_Table_0" data-dt-idx="${first}" tabindex="0" class="page-link">${first}</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="/movies/listing?page=${second}" aria-controls="DataTables_Table_0" data-dt-idx="${second}" tabindex="0" class="page-link">${second}</a>
                                </li>
                                <li class="paginate_button page-item next" id="DataTables_Table_0_next">
                                    <a href="/movies/listing?page=${next}" aria-controls="DataTables_Table_0" data-dt-idx="${next}" tabindex="0" class="page-link">Next</a>
                                </li>
                            `;
    const area = document.getElementById("pager-movies");
    if(area !== null)
    {
        const url = document.createElement("ul");
        url.className = "pagination";
        url.innerHTML = htmlp;
        area.appendChild(url);
    }
}

movieListings(params1.get("page") || 0);

movieListingPager();


function prepareDeleteMovie(movieID)
{
    localStorage.setItem("movie", movieID);
}


function deleteMovieEntirely()
{
    const movie = localStorage.getItem("movie");
    if(movie !== null)
    {
        localStorage.removeItem("movie");
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/movies/delete-movie", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function (){
            if(this.status === 200)
            {
                window.location.reload();
            }else{
                document.getElementById("message-deletes").textContent = "Failed to delete this movie";
                setTimeout(()=>{
                    window.location.reload();
                }, 3000)
            }

        }
        xhr.send(JSON.stringify({movie}));
    }
}


function searchingMovies(){

    const searchMovie = document.getElementById("movie-filter-search");
    if(searchMovie !== null)
    {
        searchMovie.addEventListener("click", (e)=>{
            e.preventDefault();

            const name = document.getElementById("movie-filter-name");
            const id = document.getElementById("movie-filter-id");
            let searchName = name.value || "no-value";
            let searchID = id.value || "no-value";

            const para = new URLSearchParams({searchName, searchID});
            const xhr = new XMLHttpRequest();

            xhr.open("GET", "/movies/search?"+para, true);
            xhr.onload = function ()
            {
                if(this.status === 200)
                {
                    try {
                        const data = JSON.parse(this.responseText);
                        movieDisplay.innerHTML = "";
                        data['results'].forEach((item, index)=>{
                            const row = buildRows(item);
                            const tr = document.createElement("tr");
                            tr.id = "movie-tr-"+index;
                            tr.innerHTML = row;
                            if(movieDisplay !== null)
                            {
                                movieDisplay.appendChild(tr);
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
searchingMovies();