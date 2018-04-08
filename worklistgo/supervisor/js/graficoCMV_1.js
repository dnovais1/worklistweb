// Chart.js scripts
// -- Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';
// -- Bar Chart Example
var ctx = document.getElementById("ChartCMV");
var myLineChart = new Chart(ctx, {
   
  type: 'bar',
  data: {
    labels: [MesesCMV[0],MesesCMV[1],MesesCMV[2],MesesCMV[3],MesesCMV[4],MesesCMV[5]],
    datasets: [{
      label: "Valor(R$)",
      backgroundColor: "#246a65",
      borderColor: "#246a65",
      data: [parseFloat(mes5),parseFloat(mes4),parseFloat(mes3),parseFloat(mes2),parseFloat(mes1),parseInt(media)]
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'year'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        ticks: {
          beginAtZero:true
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});