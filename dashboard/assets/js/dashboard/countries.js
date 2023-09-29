
const inputCountry = document.getElementById("country-select");

if(inputCountry !== null)
{
    inputCountry.addEventListener("change", (e)=>{
        const selectedCountry = e.target.value;
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "countries?country_code="+selectedCountry, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function ()
        {
            if(this.status === 200)
            {
                console.log(this.responseText);
            }
        }
        xhr.send();
    })
}