$('#cekdata').on('click', function () {
    let no_rekam_medis = $('#no_rekam_medis').val();



    if (no_rekam_medis == null || no_rekam_medis == "") {
        bootbox.alert({ message: 'No Rekam Medis tidak boleh kosong', centerVertical: true });
        return false;


    } else if (no_rekam_medis.toString().length > 8) {
        bootbox.alert({ message: 'No Rekam Medis tidak boleh lebih dari 8 angka', centerVertical: true });
        return false;
    } else if (no_rekam_medis.toString().length < 8) {
        bootbox.alert({ message: 'No Rekam Medis tidak boleh kurang dari 8 angka', centerVertical: true });
        return false;

    }
    else {
        cekpasien(no_rekam_medis);
    }

})

function cekpasien(no_rekam_medis) {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/cekpasien',
        data: {
            ino_rekam_medis: no_rekam_medis,

        },
        success: function (result) {
            // let data = result.data['datapasien'][0]['no_rekam_medis'];
            // let validasi = result.data['validasiinput'];
            console.log(result);
            if (result.code != 0) {

                bootbox.alert({ message: "No rekam medis tidak sesuai!", centerVertical: true });
            }
            else if (result.code == 0) {
                console.log(result.data['datapasien'][0]['no_rekam_medis']);

                let $id = result.data['datapasien'][0]['id_pasien'];
                let $encodedId = btoa($id);
                window.location.href = ("datapasien/" + $encodedId);

            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}


function validasidokter(no_rekam_medis, ktp) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadpasien',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {
            // console.log(result)
            var data = result.data;

            for (x in data) {
                if (no_rekam_medis == data[x]['no_rekam_medis']) {
                    exist = 1;
                }
                if (ktp == data[x]['ktp']) {
                    exist = 2;
                }
            }


        },

        error: function (xhr) {
            //alert(xhr.status+'-'+xhr.statusText);
        }
    });
    return exist;

}



