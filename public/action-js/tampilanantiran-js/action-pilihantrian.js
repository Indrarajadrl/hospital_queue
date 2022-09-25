loadpoli();



function loaddatadokter() {


    let poli = $("#antrian_poli option:selected").attr("value");
    console.log(poli);
    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadregisterpoli',
        data: {
            id_poli: poli,
        },

        success: function (response) {

            if (response['code'] == 0) {
                console.log(response);
                let $id = poli;
                console.log($id)
                let $encodedId = btoa($id);

                window.location.href = ("/umum/umumtampilan/" + $encodedId);

            } else {
                bootbox.alert({ message: ' gagal', centerVertical: true });
            }

        }, error: function (xhr) {

            if (xhr.status != 200) {
                //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
            } else {
                alert('data tidak ada');
            }
        }
    });
}

function loadpoli() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadregisterpoli',

        success: function (result) {
            if (result['code'] == 0) {
                console.log(result);
                var res = result['data'];
                optionseg = '';
                optionseg += '<option value="Pilih" disabled selected>Pilih</option>';
                for (var i = 0; i < res.length; i++) {
                    optionseg += '<option value="' + res[i].id_poli + '">' + res[i].nama_poli + '</option>';
                }
                $("select#antrian_poli").html(optionseg);
                let $id = result.data[0].id_poli;
                // let $id = result.data['id_poli'];
                // console.log($id);


            } else {

            }
        }
    });
}
