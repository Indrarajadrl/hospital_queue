
$(document).ready(function () {
    $("#tanggal_lahir").change(function () {
        //console.log("berubah nih!");
        var x = new Date($("#tanggal_lahir").val());
        var Cnow = new Date();
        if (Cnow.getFullYear() < x.getFullYear()) {
            bootbox.alert({
                title: "<span min-height='100px'></span>",
                message: "Tanggal Lahir tidak Boleh Melebihi Tanggal Hari ini!",
                callback: function () {
                    console.log('This was logged in the callback!');
                },
                centerVertical: true
            })
            //$("#tanggal_lahir").val('');
        }
    });
});






$('#tambahdata').on('click', function () {
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();
    let KTP = $('#ktp').val();

    if (KTP == null || KTP == "") {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (nama == null || nama == "") {
        bootbox.alert({ message: 'Nama tidak boleh kosong', centerVertical: true });
        return false;
    } else if (tempat_lahir == null || tempat_lahir == "") {
        bootbox.alert({ message: 'Tempat lahir tidak boleh kosong', centerVertical: true });
        return false;
    } else if (tanggal_lahir == null || tanggal_lahir == "") {
        bootbox.alert({ message: 'Tanggal lahir tidak boleh kosong', centerVertical: true });
        return false;
    } else if (alamat == null || alamat == "") {
        bootbox.alert({ message: 'Alamat tidak boleh kosong', centerVertical: true });
        return false;
    } else if (no_hp == null || no_hp == "") {
        bootbox.alert({ message: 'No hp tidak boleh kosong', centerVertical: true });
        return false;
    } else if (KTP.toString().length > 16) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh lebih dari 16 angka', centerVertical: true });
        return false;
    } else if (KTP.toString().length < 16) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh kurang dari 16 angka', centerVertical: true });
        return false;
    } else if (no_hp.toString().length > 13) {
        bootbox.alert({ message: 'No HP tidak boleh lebih dari 13 angka', centerVertical: true });
        return false;
    }
    else if (validasipoli(KTP) == 1) {
        bootbox.alert({ message: 'KTP Sudah Digunakan!', centerVertical: true });
    }
    else {
        validasinorekammedis();
    }
})

function makeid(length) {
    let result = '';
    let characters =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function makeidnumber(length) {
    let result = '';
    let characters =
        '1234567890';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function validasinorekammedis() {
    let norekamedis = makeid(2) + makeidnumber(6);

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadpasien',
        data: {
            no_rekam_medis: norekamedis
        },
        success: function (result) {
            if (result.info == 'Not Found') {
                SaveDataAja(norekamedis);
            } else {
                validasinorekammedis();
            }

        }
    });
}

function SaveDataAja(norekamedis) {
    let no_rekam_medis = norekamedis;
    let id_pasien = $('#id_pasien').val();
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();
    let ktp = $('#ktp').val();


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savedatapasien',
        data: {
            id_pasien: id_pasien,
            iktp: ktp,
            inama: nama,
            itempat_lahir: tempat_lahir,
            itanggal_lahir: tanggal_lahir,
            ialamat: alamat,
            ino_hp: no_hp,
            inorm: no_rekam_medis
        },

        success: function (response) {
            // alert("success");
            // response[data];


            if (response['code'] == 0) {
                console.log(response);
                let $id = response.data['id_pasien'];
                let $encodedId = btoa($id);

                window.location.href = ("datapasien/" + $encodedId);

            } else {
                bootbox.alert({ message: 'Gagal Ditambahkan', centerVertical: true });
            }

        }, error: function (xhr) {

            if (xhr.status != 200) {
                //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
            } else {
                alert('dadas');
            }
        }
    });
}



function validasipoli(KTP) {

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

            var data = result.data;

            for (x in data) {
                if (KTP == data[x]['ktp']) {
                    exist = 1;
                }
            }


        },

        error: function (xhr) {
            //alert(xhr.status+'-'+xhr.statusText);
        }
    });
    return exist;

}


var inputQuantity = [];
$(function () {
    $("#ktp").each(function (i) {
        inputQuantity[i] = this.defaultValue;
        $(this).data("idx", i); // save this field's index to access later
    });
    $("#ktp").on("keyup", function (e) {
        var $field = $(this),
            val = this.value,
            $thisIndex = parseInt($field.data("idx"), 10); // retrieve the index
        //        window.console && console.log($field.is(":invalid"));
        //  $field.is(":invalid") is for Safari, it must be the last to not error in IE8
        if (this.validity && this.validity.badInput || isNaN(val) || $field.is(":invalid")) {
            this.value = inputQuantity[$thisIndex];
            return;
        }
        if (val.length > Number($field.attr("maxlength"))) {
            val = val.slice(0, 16);
            $field.val(val);
        }
        inputQuantity[$thisIndex] = val;
    });
});