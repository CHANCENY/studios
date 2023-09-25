$(document).ready(function() {
	
	// Bar Chart

	const data = uploadsListings();
	const now = new Date();
	const month = now.getMonth();
	var barChartData = {
		labels: data[0].slice(0, month + 1),
		datasets: [{
			label: 'Movies',
			backgroundColor: 'rgba(0, 158, 251, 0.5)',
			borderColor: 'rgba(0, 158, 251, 1)',
			borderWidth: 1,
			data: data[1].slice(0, month + 1)
		}, {
			label: 'Series',
			backgroundColor: 'rgba(255, 188, 53, 0.5)',
			borderColor: 'rgba(255, 188, 53, 1)',
			borderWidth: 1,
			data: data[3].slice(0, month + 1)
		}]
	};

	var ctx = document.getElementById('bargraph').getContext('2d');
	window.myBar = new Chart(ctx, {
		type: 'bar',
		data: barChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			}
		}
	});

	// Line Chart

	var lineChartData = {
		labels: data[0],
		datasets: [{
			label: "Movies uploads",
			backgroundColor: "rgba(0, 158, 251, 0.5)",
			data: data[1]
		}, {
		label: "Series uploads",
		backgroundColor: "rgba(255, 188, 53, 0.5)",
		fill: true,
		data: data[3]
		}]
	};
	
	var linectx = document.getElementById('linegraph').getContext('2d');
	window.myLine = new Chart(linectx, {
		type: 'line',
		data: lineChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			}
		}
	});
	
	// Bar Chart 2
	
    barChart();
    
    $(window).resize(function(){
        barChart();
    });
    
    function barChart(){
        $('.bar-chart').find('.item-progress').each(function(){
            var itemProgress = $(this),
            itemProgressWidth = $(this).parent().width() * ($(this).data('percent') / 100);
            itemProgress.css('width', itemProgressWidth);
        });
    };
});


function uploadsListings() {
	const token = getCookie("token_skey");
	const xhr = new XMLHttpRequest();
	xhr.open("GET", "https://api.streamstudios.online/dashboard/numbers/uploads", false);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.setRequestHeader("s-key", token);
	let r = [];
	xhr.onload = function (){
		if(this.status === 200)
		{
			let data = [];
			try {
			   data = JSON.parse(this.responseText);
			}catch (e) {
				console.error(e.message);
			}

			if(data.status === 200)
			{
				const movies = data.movies;
				const shows = data.shows;

				const moviesKeys = Object.keys(movies);
				const showsKeys = Object.keys(shows);

				const moviesValues = Object.values(movies);
				const showsValues = Object.values(shows);

				r = [
					moviesKeys,
					moviesValues,
					showsKeys,
					showsValues
				];
			}
		}
	}
	xhr.send();
	return r;
}