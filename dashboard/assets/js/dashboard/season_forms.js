
function addSeasonForm()
{
   let seasonCount = localStorage.getItem("season_count");
   if(seasonCount === null)
   {
      seasonCount = 1;
   }

   const tbody = document.getElementById("season_body_fields");
   if(tbody !== null)
   {
      seasonCount = parseInt(seasonCount) + 1;
      const tds = buildField(seasonCount);
      const tr = document.createElement("tr");
      tr.innerHTML = tds;
      tbody.appendChild(tr);
      localStorage.setItem("season_count", seasonCount.toString());
   }
}

function removeSeasonForm(evt)
{
   let seasonCount = localStorage.getItem("season_count");
   if(parseInt(seasonCount) > 1)
   {
      var row = evt.closest("tr");
      if(row !== null)
      {
        row.remove();
        seasonCount = parseInt(seasonCount) - 1;
        localStorage.setItem("season_count", seasonCount.toString());
      }
   }
}

function buildField(index)
{
   return  `
                                            <td>${index}</td>
                                            <td>
                                                <input name="season_name_${index}" class="form-control" type="text">
                                            </td>
                                            <td>
                                                <textarea name="season_decription_${index}" class="form-control"></textarea>
                                            </td>
                                            <td>
                                                <input name="season_episode_count_${index}" class="form-control" type="text">
                                            </td>
                                            <td>
                                                <div class="cal-icon">
                                                    <input name="season_air_date_${index}" class="form-control datetimepicker" type="text">
                                                 </div>
                                            </td>
                                            <td>
                                                 <input name="season_image_${index}" class="form-control" type="file">
                                            </td>
                                            <td>
                                            <a href="javascript:void(0)" onclick="addSeasonForm()" class="text-success font-18" title="Add"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" onclick="removeSeasonForm(this)" class="text-danger font-18" title="Remove"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        `;
}


function searchShow(evt)
{
   const inputText = evt.value;
   if(inputText !== null)
   {
      const total = inputText.length;
      if((total % 2) === 0)
      {
         const xhr = new XMLHttpRequest();
         xhr.open("GET", "/seasons/show/search?title="+inputText, true);
         xhr.setRequestHeader("Content-Type", "application/json");
         xhr.onload = function ()
         {
            if(this.status === 200)
            {
              let data = [];
              try {
                data = JSON.parse(this.responseText);
              }catch (e) {
                console.error(e.message);
              }
               const searchResults = document.getElementById("show-search-result");
              if(data.length > 0)
              {
                 searchResults.innerHTML = "";
                 data.forEach((item, index)=>{
                    const div = document.createElement("div");
                    div.className = "col-md-6";
                    const a = document.createElement("a");
                    a.className = "text-decoration-none";
                    a.textContent = item.name;
                    a.href = "javascript:void(0);";
                    a.setAttribute("onClick", `setShowID(${item.id}, '${item.name}')`);
                    div.appendChild(a);
                    searchResults.appendChild(div);
                 })
              }else{
                 searchResults.innerHTML = "<div><span>No show found</span></div>"
              }
            }
            if(this.status === 404)
            {
               console.log(this.responseText);
            }
         }
         xhr.onerror = function ()
         {
            console.log("network error")
         }
         xhr.send();
      }
   }
}

function setShowID(showID, showName)
{
    document.getElementById("show-search-result").innerHTML = "";
    const form = document.getElementById("form-add-season");
    if(form !== null)
    {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "show_id";
        input.value = showID;
        form.appendChild(input);

        const fieldSearch = document.getElementById("field-search-show");
        if(fieldSearch !== null)
        {
            fieldSearch.value = `${showName} (${showID})`;
            document.getElementById("card-title").textContent = showName;
        }
    }
}

