Pusher.logToConsole = true;

$(document).ready(function () {
    loadantrian(id_poli);
});


var pusher = new Pusher('6b79efb8b6f3090d226f', {
    cluster: 'ap1'
  });

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function (data) {
    if (data.message === 'success') {
        loadantrian(id_poli);
    }
});

function loadantrian(param) {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + `/api/loadantrian`,
        data: {
            id_poli: param,
        },
        success: function (result) {
            console.log(result);
            // $('.loaddata').empty();
            // console.log(result);
            if (result['code'] == 0) {
                var res = result['data'];
                $("#listdata").empty();
                $('#no_antrian').empty();

                let hasil = '';


                for (var i = 0; i < res.length; i++) {

                    $('#no_antrian').empty();

                    if (res[i]['status_code'] == 30) {

                        valTemp = res[i]['kode_dokter'] + res[i]['no_antrian'];

                        $('#no_antrian_panggil').empty();
                        $('#no_antrian_panggil').append(valTemp);

                        ruang = res[i]['nama_ruang'];
                        $('#ruang_panggil').empty();
                        $('#ruang_panggil').append(ruang);

                    }



                    hasil =
                        `      
                                    <div class="card col-sm-2" style="border-color: #e94c72;background: #faebd73b; width: 20rem;">
                                            <div class="alert alert-info alert-styled-left alert-arrow-left alert-component"  style="background: #faebd73b;">
                                                <div><input type="hidden" value="nomer antrian" id="text-to-speech" placeholder="Enter text to speak..."/></div>
                                                <h1 class="error-title" style=" font-weight:;font-size:53px;font-family: Times New Roman, Times, serif;color:	#fff"><span > `+ res[i]['kode_dokter'] + `-` + res[i]['no_antrian'] + ` </span></h1>
                                            </div>
                                                <div><input type="hidden" value="no_antrian" id="no_antrian" /></div>
                                                <div><input type="hidden" value="nama_ruang" id="nama_ruang" /><span style="visibility:hidden" id="nama_ruang"></span></div>
                                    </div>  
                            
                        `
                    $("#listdata").append(hasil);



                }
            } else {
                $('#no_antrian').empty();
                $('#no_antrian').append("-");
            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });


}