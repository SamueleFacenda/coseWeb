///////////////////////////
// UPDATE FUNCTION
///////////////////////////
function update(updateChart) {
    //send request to server and fetch data on response
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // date recived, update chart
            let data = JSON.parse(this.responseText);
            let ts = data.Head.Timestamp;
            let PAC = data["Body"]["Data"]["PAC"]["Values"];
            updateChart(PAC, ts);
        }
    };
    xhttp.open("GET", "http://yoyofv.ddns.net/solar_api/GetInverterRealtimeData.cgi?Scope=System&quot", true);
    xhttp.send();
}




/////////////////////////////////////////
//          GRAFICO
/////////////////////////////////////////
window.onload = function () {

var nLine = 4

var dps = []; // dati del grafico, matrice con una riga per ogni numero
for (var i = 0; i < nLine; i++) 
    dps.push([]);

var chartData = [];
for (var i = 0; i < nLine; i++)
    chartData.push({type: "spline", dataPoints: dps[i], xValueType: "dateTime"});
    
var chart = new CanvasJS.Chart("chartContainer", {
    backgroundColor: "#12e193",
    height: 300,
    title :{
        text: "energia pannelli"
    },
    axisX: {
        title: "Time"
    },
    axisY: {
        title: "Watt"
    },
    willReadFrequently: true,
    data: chartData
});

var updateInterval = 1000;
var dataLength = 20; // lenght of the graph

var updateChart = function (pac, ts) {
    date = new Date(ts);
    for(var i = 0; i < nLine; i++) {
        dps[i].push({
            x: date,
            y: pac[(i+1).toString()]
        });
        if (dps[i].length > dataLength)
            dps[i].shift();
    }

    chart.render();
};

//////////////////////////////////
//  SET UPDATE AND INITIAL FETCH
//////////////////////////////////


setInterval(function(){update(updateChart)}, updateInterval);

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // date recived, update table
        let data = JSON.parse(this.responseText);
        for(var i=1;i<=4;i++){
            var id="day_energy"+i;
            document.getElementById(id).innerHTML=data["Body"]["Data"]["DAY_ENERGY"]["Values"][i];
            id="year_energy"+i;
            document.getElementById(id).innerHTML=data["Body"]["Data"]["YEAR_ENERGY"]["Values"][i];
            id="total_energy"+i;
            document.getElementById(id).innerHTML=data["Body"]["Data"]["TOTAL_ENERGY"]["Values"][i];
        }
    }
};
xhttp.open("GET", "http://yoyofv.ddns.net/solar_api/GetInverterRealtimeData.cgi?Scope=System&quot", true);
xhttp.send();

}    