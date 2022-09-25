
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



$('#cetak').on('click', function (e) {
    //alert("kepanggil");
    e.preventDefault();
    var w = window.open('', '', 'width=300,height=400');
    w.document.write('<html><body><table border="" >' +
        '<tr><th><h1>SELAMAT DATANG</h1><h2> Di Rumah Sakit</h2></th></tr></thead>' +
        '<tbody><tr>' +
        '<th><h2>ANTRIAN</h2>' +
        '<h1>' + kode_dokter_cetak + + no_antrian_cetak + '</h1>' +
        '<h3>Nama Pasien : ' + nama_pasien_cetak + '</h3>' +
        '<h3>Poli : ' + kode_poli_cetak + '</h3>' +
        '<h3>Nama Dokter : ' + nama_dokter_cetak + '</h3>' +
        '</th></tr></tbody></table></body></html>');
    w.window.print();
    w.document.close();
    w.window.onafterprint = function () {

        setTimeout(function () {
            w.close()
            window.location.href = baseURL + "/queue/beranda"
        }, 3000);
    }

})




$('#tambah').on('click', function () {
    SaveDataAja();
})

function SaveDataAja(param) {
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();
    let poli = $("#poli option:selected").attr("value");
    let dokter = $('#dokter option:selected').attr("value");
    let ktp = $('#ktp').val();
    let no_antrian = $("input[name=antrian]:checked").val();

    // console.log(no_antrian);

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savedata',
        data: {
            iktp: ktp,
            //id_pasien: param,
            inama: nama,
            itempat_lahir: tempat_lahir,
            itanggal_lahir: tanggal_lahir,
            ialamat: alamat,
            ino_hp: no_hp,
            ipoli: poli,
            idokter: dokter,
            ino_antrian: no_antrian,

        },

        success: function (response) {
            // alert("success");
            // response[data];

            if (response['code'] == 0) {
                let $id = response.data.id_pasien;
                console.log(response.data);
                // alert($id);
                let $encodedId = btoa($id);
                console.log($encodedId);
                window.location.href = ("cetakantrian/" + $encodedId);

            } else {
                bootbox.alert({ message: ' gagal', centerVertical: true });
            }

        },
        error: function (xhr) {

            if (xhr.status != 200) {
                //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
            } else {
                alert('dadas');
            }
        }
    });
}


loadpoli();
$('#poli').on('change', function () {
    var idpoli = $('#poli').val();
    console.log(idpoli);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadDokterWaktu',
        data: {
            id_poli: idpoli
        },
        success: function (result) {

            if (result['code'] == 0) {

                var res = result['data'];
                optionseg = '';
                optionseg += '<option value="Pilih" disabled selected>Pilih</option>';
                for (var i = 0; i < res.length; i++) {
                    optionseg += '<option value="' + res[i].id_dokter + '">' + res[i].nama_dokter + '</option>';
                }
                $("select#dokter").removeAttr('disabled');
                $("select#dokter").html(optionseg);
            } else {
                bootbox.dialog({
                    message: "<span class='bigger-110'>" + result['info'] + "</span>",
                    buttons:
                    {
                        "OK":
                        {
                            "label": "<i class='icon-ok'></i> OK ",
                            "className": "btn-sm btn-danger",
                            "callback": function () {
                                notifyCancel('ERROR: ' + result['info'] + '. Bila ada kesulitan dimohon untuk menghubungi Admin terkait');
                            }
                        }
                    }
                });
            }
        }
    });
});

function loadpoli() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadpoli',

        success: function (result) {
            if (result['code'] == 0) {
                //console.log (result);
                var res = result['data'];

                optionseg = '';
                optionseg += '<option value="Pilih" disabled selected>Pilih</option>';
                for (var i = 0; i < res.length; i++) {
                    optionseg += '<option value="' + res[i].id_poli + '">' + res[i].nama_poli + '</option>';
                }

                $("select#poli").html(optionseg);
            } else {
                bootbox.dialog({
                    message: "<span class='bigger-110'>" + result['info'] + "</span>",
                    buttons:
                    {
                        "OK":
                        {
                            "label": "<i class='icon-ok'></i> OK ",
                            "className": "btn-sm btn-danger",
                            "callback": function () {
                                notifyCancel('ERROR: ' + result['info'] + '. Bila ada kesulitan dimohon untuk menghubungi Admin terkait');
                            }
                        }
                    }
                });
            }
        }
    });
}

$('#pilihno').on('click', function () {

    // let nama = $('#nama').val();
    // let tempat_lahir = $('#tempat_lahir').val();
    // let tanggal_lahir = $('#tanggal_lahir').val();
    // let alamat = $('#alamat').val();
    // let no_hp = $('#no_hp').val();
    // let poli = $("#poli option:selected").attr("value");
    // let dokter = $('#dokter option:selected').attr("value");
    // let ktp = $('#ktp').val();
    // //  let $kode_poli = $("#poli option:selected").attr("code");
    // let $no_antrian = $('#no_antrian').val();
    // // console.log(nama);


    // if (ktp == null || ktp == "") {
    //     bootbox.alert({ message: 'Ktp tidak boleh kosong', centerVertical: true });
    //     return false;
    // }
    // else if (nama == null || nama == "") {
    //     bootbox.alert({ message: 'Nama tidak boleh kosong', centerVertical: true });
    //     return false;
    // } else if (tempat_lahir == null || tempat_lahir == "") {
    //     bootbox.alert({ message: 'Tempat lahir tidak boleh kosong', centerVertical: true });
    //     return false;
    // } else if (tanggal_lahir == null || tanggal_lahir == "") {
    //     bootbox.alert({ message: 'Tanggal lahir tidak boleh kosong', centerVertical: true });
    //     return false;
    // } else if (alamat == null || alamat == "") {
    //     bootbox.alert({ message: 'Alamat tidak boleh kosong', centerVertical: true });
    //     return false;
    // } else if (no_hp == null || no_hp == "") {
    //     bootbox.alert({ message: 'No hp tidak boleh kosong', centerVertical: true });
    //     return false;
    // }
    // else if (poli == null || poli == "") {
    //     bootbox.alert({ message: 'Poli tidak boleh kosong', centerVertical: true });
    //     return false;
    // }
    // else if (dokter == null || dokter == "") {
    //     bootbox.alert({ message: 'Dokter tidak boleh kosong', centerVertical: true });
    //     return false;
    // } else if (ktp.toString().length > 16) {
    //     bootbox.alert({ message: 'KTP tidak boleh lebih dari 16 angka', centerVertical: true });
    //     return false;
    // } else if (ktp.toString().length < 16) {
    //     bootbox.alert({ message: 'KTP tidak boleh kurang dari 16 angka', centerVertical: true });
    //     return false;
    // } else if (no_hp.toString().length > 13) {
    //     bootbox.alert({ message: 'No HP tidak boleh lebih dari 13 angka', centerVertical: true });
    //     return false;
    // } else {
    pilihAntrian()
    // }



});

function pilihAntrian() {
    var dokter = $('#dokter option:selected').attr("value");
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadPilihAntrian',
        data: {
            iddok: dokter,
        },

        success: function (response) {
            console.log(response.data);
            var $antrian = (response.data);

            $('.antrian').empty();
            // console.log($antrian);
            // console.log(response);
            if ($antrian) {
                var hour = 0;;

                var splithour = [];
                splithour[0] = $antrian[0]['jam_mulai'].split(':');



                hour += parseInt(splithour[0][0]);
                minute = parseInt(splithour[0][1]);




                for (var i = 0; i < $antrian.length; i++) {
                    if ($antrian[i]['id_pasien'] === null) {
                        let y = '';


                        var splittime = [];
                        splittime[i] = $antrian[i]['waktu_antrian'].split(':');



                        if (minute >= 60) {
                            hour += Math.floor(minute / 60);
                            minute = minute % 60;
                        }

                        if (minute.toString().length === 1) {
                            y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label style="text-align:center` + i + `">` + hour + ':' + '0' + minute + `</label>
                                    </div>
                                </div > `;
                            $('.antrian').append(y);
                        } else {
                            y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label style="text-align:center"` + i + `">` + hour + ':' + minute + `</label>
                                    </div>
                                </div > `;
                            $('.antrian').append(y);
                        }


                        minute += parseInt(splittime[i][1]);

                    } else if ($antrian[i]['status_code'] == 10 || $antrian[i]['status_code'] == 20 || $antrian[i]['status_code'] == 30 || $antrian[i]['status_code'] == 40) {
                        var splittime = [];
                        splittime[i] = $antrian[i]['waktu_antrian'].split(':');
                        let y = '';




                        if (minute >= 60) {
                            hour += Math.floor(minute / 60);
                            minute = minute % 60;
                        }

                        if (minute.toString().length === 1) {
                            y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label for="antrian` + i + `">` + hour + ':' + '0' + minute + `</label>
                                    </div>
                                </div > `;
                            $('.antrian').append(y);
                        } else {
                            y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label for="antrian` + i + `">` + hour + ':' + minute + `</label>
                                    </div>
                                </div > `;
                            $('.antrian').append(y);
                        }

                        minute += parseInt(splittime[i][1]);
                    }
                    else if ($antrian[i]['status_code'] == 60) {

                        let y = '';
                        y += `<div class="col-lg-1">
                                <div class="buttons" >
                                    <input type="done" name="antrian" value="` + $antrian[i] + `" id="antrian` + i + `" />
                                    <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                  
                                </div>
                            </div > `;
                        $('.antrian').append(y);
                    } else if ($antrian[i]['status_code'] == 50) {

                        var splittime = [];
                        splittime[i] = $antrian[i]['waktu_antrian'].split(':');





                        if (minute >= 60) {
                            hour += Math.floor(minute / 60);
                            minute = minute % 60;
                        }

                        let y = '';
                        y += `<div class="col-lg-1">
                                <div class="buttons" >
                                    <input type="done" name="antrian" value="` + $antrian[i] + `" id="antrian` + i + `" />
                                    <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                  
                                </div>
                            </div > `;
                        $('.antrian').append(y);

                        minute += parseInt(splittime[i][1]);
                    }


                }
            } else {

            }
        },
        error: function (xhr) {

            if (xhr.status != 200) {
                //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
            } else {
                alert('Cannot load queue');
            }
        }
    });
}

$(document).ready(function () {
    $('#close').on('click', function () {
        document.getElementById("seat").innerHTML = "";
    })
});



