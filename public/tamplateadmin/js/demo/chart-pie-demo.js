// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Exampl

loadpoli()
function loadpoli() {

  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: baseURL + '/api/loadPoli',

    success: function (result) {


      if (result.code == 0) {
        let data = result.data;
        y = ' <div  class="dropdown-header">Poli</div>';
        for (x in data) {
          // console.log(data[x]['nama_poli']);

          y += ` <a onclick="loadChart(` + data[x]['id_poli'] + `)" class="dropdown-item">` + data[x]['nama_poli'] + `</a>`;



        }
        $("#poli").append(y);

      } else {
        // alert(result.info);
      }
    },
    // error: function (xhr) {
    //   alert(xhr.status + '-' + xhr.statusText);
    // }
  });
}

$(document).ready(function () {
  loadChart(12)
});

function loadChart(id) {
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: baseURL + '/api/loadChart',
    data: {
      id: id,

    },

    success: function (result) {
      console.log(result)
      var data = [];
      var label = [];
      var data1 = result.data
      var nama_dokter = '';
      var colour = ['primary', 'success', 'info', 'danger', 'secondary'];

      if (result.code == 0) {

        for (x in data1) {
          data.push(data1[x].total);
          label.push(data1[x].nama_dokter)
        }
        $('#nama_dokter').empty();
        $('#myPieChart').empty();


        for (x in label) {
          nama_dokter += '<span class="mr-2"> <i class="fas fa-circle text-' + colour[x] + '"></i>' + label[x] + '</span>';
        }

        $('#nama_dokter').append(nama_dokter)
        // console.log(data)
        var ctx = document.getElementById("myPieChart");

        var myPieChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: label,
            datasets: [{
              data: data,
              backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#dc3545', '#6c757d'],
              hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#a60d1c', '#444a4f'],
              hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
          },
          options: {
            maintainAspectRatio: false,
            tooltips: {
              backgroundColor: "rgb(255,255,255)",
              bodyFontColor: "#858796",
              borderColor: '#dddfeb',
              borderWidth: 1,
              xPadding: 15,
              yPadding: 15,
              displayColors: false,
              caretPadding: 10,
            },
            legend: {
              display: false
            },
            cutoutPercentage: 80,
          },
        });

      } else {
        var ctx = document.getElementById("myPieChart");
        $('#nama_dokter').empty();
        var myPieChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: null,
            data: null,
          },

        });

        // bootbox.alert({ message: 'Data Kosong', centerVertical: true });
      }
    },



    error: function (xhr) {

      if (xhr.status != 200) {
        //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
      } else {
        alert('gagal error');
      }
    }


  });
}


