
// Get the URL string
const urlString = window.location.href;

// Create a URL object
const url = new URL(urlString);

// Use URLSearchParams to get the parameters
const params = new URLSearchParams(url.search);


function users() {
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/users", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'].slice(0, 6);
                data.forEach((item,index)=>{
                    listings(item, index);
                })
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

function newUsers()
{
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/new-users", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'].slice(0, 6);
                data.forEach((item,index)=>{
                    listings2(item, index);
                })
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

users();

newUsers();
function listings(item, index)
{
    const html = `
                  <div class="contact-cont">
                    <div class="float-left user-img m-r-10">
                      <a href="profile.html" title="${item.firstname} ${item.lastname}"><img src="assets/img/user.jpg" alt="" class="w-40 rounded-circle"><span class="status online"></span></a>
                    </div>
                    <div class="contact-info">
                      <span class="contact-name text-ellipsis">${item.firstname} ${item.lastname}</span>
                      <span class="contact-date">${item.mail}</span>
                    </div>
                  </div>
                  `;
    const li = document.createElement("li");
    li.innerHTML = html;
    li.id = "user-"+index;
    const ul = document.getElementById('users');
    if(ul !== null)
    {
        ul.appendChild(li);
    }
}

function listings2(item, index)
{
    let verified = "Offline";
    if(item.verified === 1) {
        verified = "Online";
    }
    if(item.blocked === 1)
    {
        verified = "Blocked"
    }
    const html = `
                    <td>
                      <img width="28" height="28" class="rounded-circle" src="assets/img/user.jpg" alt="">
                      <h2>${item.firstname} ${item.lastname}</h2>
                    </td>
                    <td>${item.mail}</td>
                    <td>${item.phone}</td>
                    <td><button class="btn btn-primary btn-primary-one float-right">${verified}</button></td>
                  `;
    const tr = document.createElement("tr");
    tr.innerHTML = html;
    tr.id = "user-"+index;
    const tbodys = document.getElementById('users-tbody');
    if(tbodys !== null)
    {
        tbodys.appendChild(tr);
    }
}

function currentUser()
{
    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    const user = params.get("user");
    let url = "/current-user";
    xhr.open("GET", url, false);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                const profile = document.getElementsByClassName("user-profile");
                const editprofile = document.getElementsByClassName("user-edit");
                if(profile !== null)
                {
                    for (let i = 0; i < profile.length; i++)
                    {
                        profile[i].href = "/profile?user="+data.uid;
                    }
                }

                if(editprofile !== null)
                {
                    for (let i = 0; i < editprofile.length; i++)
                    {
                        editprofile[i].href = "edit-profile?user="+data.uid;
                    }
                }
                const imageP = document.getElementById("profile-image");
                const pTitle = document.getElementById("profile-title");
                const pFullname = document.getElementById("profile-fullname");
                const pPhone = document.getElementById("profile-phone");
                const pAddress = document.getElementById("profile-address");
                const pID = document.getElementById("profile-id");
                const pGender = document.getElementById("profile-gender");
                const pMail = document.getElementById("profile-mail");
                const pBirthday = document.getElementById("profile-birthday");
                const pRole = document.getElementById("profile-role");
                const pEdit = document.getElementById("img-profile");
                const pImage = document.getElementById("profile-imagep");
                const pEditing = document.getElementById("edit-profile");

                if(imageP !== null)
                {
                    imageP.src = data.image || "assets/img/user.jpg";
                    imageP.alt = data.firstname;
                }
                if(pTitle !== null)
                {
                    pTitle.textContent = data.firstname;
                }
                if(pPhone !== null)
                {
                    pPhone.textContent = data.phone || "770-889-6484";
                }
                if(pFullname !== null)
                {
                    pFullname.textContent = data.firstname+ " "+data.lastname;
                }
                if(pAddress !== null)
                {
                    pAddress.textContent = data.address || "714 Burwell Heights Road, Bridge City, TX, 77611";
                }
                if(pID !== null)
                {
                    pID.textContent  = "Employee ID : UID-"+ data.uid;
                }
                if(pMail !== null)
                {
                    pMail.textContent = data.mail;
                }
                if(pRole !== null)
                {
                    pRole.textContent = data.role || "Admin";
                }
                if(pGender !== null)
                {
                    pGender.textContent = data.gender || "Male";
                }
                if(pBirthday !== null)
                {
                    pBirthday.textContent = data.birthday || "3rd March";
                }
                if(pEdit !== null)
                {
                    pEdit.href = data.image;
                }
                if(pImage !== null)
                {
                    pImage.src = data.image;
                    pImage.alt = data.firstname;
                }
                if(pEditing !== null)
                {
                    pEditing.href = "edit-profile?user="+data.uid;
                }

            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

function userToEdit(uid)
{
    const token = getCookie('token_skey');
    let url = "/current-user?user="+uid;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, false);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                const imagePin = document.getElementById("profile-image-input");
                const pPhonein = document.getElementById("profile-phone-input");
                const pAddressin = document.getElementById("profile-address-input");
                const pGenderin = document.getElementById("profile-gender-input");
                const pBirthdayin = document.getElementById("profile-birthday-input");
                const pFirstnamein = document.getElementById("profile-firstname-input");
                const pLastnamein = document.getElementById("profile-lastname-input")

                if(imagePin !== null)
                {
                    imagePin.src = data.image || "assets/img/user.jpg";
                    imagePin.alt = data.firstname;
                }
                if(pPhonein !== null)
                {
                    pPhonein.value = data.phone || "770-889-6484";
                }
                if(pAddressin !== null)
                {
                    pAddressin.value = data.address || "714 Burwell Heights Road, Bridge City, TX, 77611";
                }
                if(pGenderin !== null)
                {
                    pGenderin.value = data.gender || "Male";
                }
                if(pBirthdayin !== null)
                {
                    pBirthdayin.value = data.birthday || "3rd March";
                }
                if(pFirstnamein !== null)
                {
                    pFirstnamein.value = data.firstname;
                }
                if(pLastnamein !== null)
                {
                    pLastnamein.value = data.lastname;
                }
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

currentUser();

if(urlString.includes("edit-profile?user"))
{
    userToEdit(params.get("user"));
}

function uploadCurrentUserImage() {
    const fileInput = document.getElementById("image-upload");
    const previewImage = document.getElementById("profile-image-input");

    if (fileInput.files.length > 0) {
        const selectedFile = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            // Display the selected image
            previewImage.src = e.target.result;

            // Convert the image to base64
            const base64Image = e.target.result.split(",")[1]; // Extract the base64 part

            // Send the base64 image using XMLHttpRequest
            sendBase64Image(selectedFile.name,base64Image);
        };

        // Read the selected file as a data URL
        reader.readAsDataURL(selectedFile);
    }
}

function sendBase64Image(filename,base64Image) {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();

    // Append the base64 image data to the form data
    formData.append("image", base64Image);
    formData.append("name", filename);

    // Configure the XMLHttpRequest
    xhr.open("POST", "/image/upload", true);

    // Set up the onload and onerror event handlers
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Request was successful
            const formEdit = document.getElementById("form-edit-profile");
            const input = document.createElement("input");
            input.type = "hidden";
            input.value = JSON.parse(this.responseText).link;
            input.name = "profile_image";
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

function usersGrids(page = 0) {

    const token = getCookie('token_skey');
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://api.streamstudios.online/dashboard/listing/users?page="+page, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("s-key", token);
    xhr.onload = function (){
        if(this.status === 200)
        {
            let data = [];
            try {
                data = JSON.parse(this.responseText);
                data = data['results'];
                if(data.length > 0)
                {
                    data.forEach((item,index)=>{
                        const container = document.getElementById("users-all-view");
                        if(container !== null)
                        {
                            const div = document.createElement("div");
                            div.className = "col-md-4 col-sm-4  col-lg-3";
                            div.innerHTML = gridUser(item,index);
                            container.appendChild(div);
                        }
                    })
                    const ttt = data.length - 1;
                    document.getElementById("users-all-view").setAttribute("total", ttt.toString())
                }
            }catch (e) {
                console.error(e.message);
            }
        }
    }
    xhr.send();
}

function gridUser(item, index) {
    const image = item.image || "assets/img/doctor-thumb-03.jpg";
    const html = `
                      <div class="profile-widget">
                            <div class="doctor-img">
                                <a class="avatar" href="profile?user=${item.uid}"><img alt="" src="${image}"></a>
                            </div>
                            <div class="dropdown profile-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="edit-profile?user=${item.uid}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a onclick="prepareDeleteUser(${item.uid})" class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_doctor"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                </div>
                            </div>
                            <h4 class="doctor-name text-ellipsis"><a href="profile?user=${item.uid}">${item.firstname} ${item.lastname}</a></h4>
                            <div class="doc-prof">${item.role}</div>
                            <div class="user-country">
                                <i class="fa fa-map-marker"></i> ${item.country}, ${item.state}
                            </div>
                        </div>
                   `;
    return html;
}


usersGrids();

function moreUsers() {
    const more = document.getElementById("more-users");
    if(more !== null)
    {
        more.addEventListener('click', (e)=>{
            e.preventDefault();
            let count = more.getAttribute("data");
            usersGrids(parseInt(count));
            count = parseInt(count) + 1;
            more.setAttribute("data", count.toString());
        })
    }
}
moreUsers();

function prepareDeleteUser(uid)
{
    localStorage.setItem("iu", uid);
}

function deleteUser()
{
    const uid = localStorage.getItem("iu");
    if(uid !==  null)
    {
        const icon = document.getElementById("delete-icon");
        icon.setAttribute("src","assets/img/loading.gif");
        const xhr = new XMLHttpRequest();
        xhr.open("POST","user/delete", false);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function ()
        {
            if(this.status === 200)
            {
                icon.setAttribute("src", "assets/img/sent.png");
                localStorage.removeItem("iu");
                window.location.reload();

            }
        }
        xhr.send(JSON.stringify({uid: uid}));
    }
}