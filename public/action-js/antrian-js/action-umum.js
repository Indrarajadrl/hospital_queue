loadantrianumum();
function loadantrianumum() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrian',

        success: function (result) {
            // console.log(result);
            // console.log(result.data);
            $('loadantrian').empty();
            if (result.code == 0) {

                var res = result['data'];


                let hasil = '';

                for (var i = 0; i < res.length; i++) {
                    hasil += "<div class='box one" + i + "'> <div class='date'> <h4 >" + res[i]['nama_poli'] + "</h4>  <h4 >" + res[i]['nama_ruang'] + "</h4></div> <div class='poster p" + i + "'> <h4 >" + res[i]['kode_dokter'] + "" + res[i]['no_antrian'] + "</h4> </div> </div>"

                }

                $("#box").append(hasil);

            } else {
                alert(result.info);
            }
        },
        error: function (xhr) {
            console.log('ERROR AJAX:' + xhr.status + '-' + xhr.statusText);
        }
    });
}
