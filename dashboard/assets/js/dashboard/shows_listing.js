const showDisplay = document.getElementById("show-listing-display");

// Get the URL string
const urlString2 = window.location.href;

// Create a URL object
const url2 = new URL(urlString2);

// Use URLSearchParams to get the parameters
const params2 = new URLSearchParams(url2.search);

function showListings(page = 0)
{
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/shows/show-groups?page="+page, false);
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
                    tr.id = "show-tr-"+index;
                    tr.innerHTML = row;
                    if(showDisplay !== null)
                    {
                        showDisplay.appendChild(tr);
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
                        <h2>${item.title}</h2>
                      </td>
                       <td>S-${item.id}</td>
                      <td>${item.date}</td>
                      <td>
                        <span class="custom-badge ${statusClass}">${status}</span>
                      </td>
                      <td class="text-right">
                        <div class="dropdown dropdown-action">
                           <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                           <div class="dropdown-menu dropdown-menu-right">
                              <a class="dropdown-item" href="/shows/edit-show?show-id=${item.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                              <a onclick="prepareDeleteShow(${item.id})" class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                           </div>
                        </div>
                      </td>`;
    return htmlRow;
}


function showListingPager()
{
    const pageNumber = params2.get("page") || 0;
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
                                    <a href="/shows/listing?page=${previous}" aria-controls="DataTables_Table_0" data-dt-idx="${previous}" tabindex="0" class="page-link">Previous</a>
                                </li>
                                 <li class="paginate_button page-item active">
                                    <a href="/shows/listing?page=${currentPage}" aria-controls="DataTables_Table_0" data-dt-idx="${currentPage}" tabindex="0" class="page-link">${currentPage}</a>
                                </li>
                                <li class="paginate_button page-item">
                                    <a href="/shows/listing?page=${first}" aria-controls="DataTables_Table_0" data-dt-idx="${first}" tabindex="0" class="page-link">${first}</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="/shows/listing?page=${second}" aria-controls="DataTables_Table_0" data-dt-idx="${second}" tabindex="0" class="page-link">${second}</a>
                                </li>
                                <li class="paginate_button page-item next" id="DataTables_Table_0_next">
                                    <a href="/shows/listing?page=${next}" aria-controls="DataTables_Table_0" data-dt-idx="${next}" tabindex="0" class="page-link">Next</a>
                                </li>
                            `;
    const area = document.getElementById("pager-shows");
    if(area !== null)
    {
        const url = document.createElement("ul");
        url.className = "pagination";
        url.innerHTML = htmlp;
        area.appendChild(url);
    }
}

showListings(params2.get("page") || 0);

showListingPager();


function prepareDeleteShow(showID)
{
    localStorage.setItem("show", ShowID);
}


function deleteShowEntirely()
{
    const movie = localStorage.getItem("show");
    if(movie !== null)
    {
        localStorage.removeItem("show");
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/shows/delete-show", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function (){
            if(this.status === 200)
            {
                window.location.reload();
            }else{
                document.getElementById("message-deletes").textContent = "Failed to delete this Show";
                setTimeout(()=>{
                    window.location.reload();
                }, 3000)
            }

        }
        xhr.send(JSON.stringify({movie}));
    }
}


function searchingShow(){

    const searchMovie = document.getElementById("show-filter-search");
    if(searchMovie !== null)
    {
        searchMovie.addEventListener("click", (e)=>{
            e.preventDefault();

            const name = document.getElementById("show-filter-name");
            const id = document.getElementById("show-filter-id");
            let searchName = name.value || "no-value";
            let searchID = id.value || "no-value";

            const para = new URLSearchParams({searchName, searchID});
            const xhr = new XMLHttpRequest();

            xhr.open("GET", "/shows/search?"+para, true);
            xhr.onload = function ()
            {
                if(this.status === 200)
                {
                    try {
                        const data = JSON.parse(this.responseText);
                        console.log(data)
                        showDisplay.innerHTML = "";
                        data['results'].forEach((item, index)=>{
                            const row = buildRows(item);
                            const tr = document.createElement("tr");
                            tr.id = "show-tr-"+index;
                            tr.innerHTML = row;
                            if(showDisplay !== null)
                            {
                                showDisplay.appendChild(tr);
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
searchingShow();

function uploadShowImage() {
    const fileInput = document.getElementById("new-image-show");
    const previewImage = document.getElementById("new-image-preview");

    if (fileInput.files.length > 0) {
        const selectedFile = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            // Display the selected image
            previewImage.src = e.target.result;

            // Convert the image to base64
            const base64Image = e.target.result.split(",")[1]; // Extract the base64 part

            // Send the base64 image using XMLHttpRequest
            sendBase64ShowImage(selectedFile.name,base64Image);
        };

        // Read the selected file as a data URL
        reader.readAsDataURL(selectedFile);
    }
}

function sendBase64ShowImage(filename,base64Image) {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();

    // Append the base64 image data to the form data
    formData.append("image", base64Image);
    formData.append("name", filename);

    // Configure the XMLHttpRequest
    xhr.open("POST", "/shows/upload/images", true);

    // Set up the onload and onerror event handlers
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Request was successful
            const formEdit = document.getElementById("show-edit-form");
            const input = document.createElement("input");
            input.type = "hidden";
            input.value = JSON.parse(this.responseText).link;
            input.name = "new_image";
            formEdit.appendChild(input);
        } else {
            // Request failed
            console.error("Error sending image:", xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error("Network error while sending image.");
    };

    // Send the FormData containing the base64 image
    xhr.send(formData);
}