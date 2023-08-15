

const startYrs = document.getElementById('filter__years-start');
const endYrs = document.getElementById('filter__years-end');
const startRates = document.getElementById('filter__imbd-start');
const endRates = document.getElementById('filter__imbd-end');
const genreInput = document.getElementById('genre');

const filterButton = document.getElementById('filter-butn');

if(filterButton !== null)
{
    filterButton.addEventListener('click', (e)=>{
        if(startRates !== null && endRates !== null && startYrs !== null && endYrs !== null && genreInput !==  null)
        {
            const data = { rating: startRates.textContent+'-'+endRates.textContent,
                           years:  startYrs.textContent+'-'+endYrs.textContent,
                           genre: genreInput.value
                         };
            const params = new URLSearchParams(data);
            window.location.replace("filtering-stream?"+params);
        }

    })
}