// alert(id);

loaddatapasien(id)
function loaddatapasien(param) {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loaddatapasien',
        data: {
            id: param,
        },
        success: function (result) {
            // console.log(result);
            // $('.loaddata').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;

                $('#id_pasien').val(data[0].id_pasien);
                $('#no_rekam_medis').val(data[0].no_rekam_medis);
                $('#ktp').val(data[0].ktp);
                $('#nama').val(data[0].nama);
                $('#tempat_lahir').val(data[0].tempat_lahir);
                $('#tanggal_lahir').val(data[0].tanggal_lahir);
                $('#alamat').val(data[0].alamat);
                $('#no_hp').val(data[0].no_hp);


            } else {
                bootbox.alert({ message: 'Data Kosong', centerVertical: true });
            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}


function loadsisaantrian() {
    let dokter = $('#dokter option:selected').attr("value");
    let no_antrian = $("input[name=antrian]:checked").val();
    var sisa_antrian;

    // console.log(dokter);
    // console.log(no_antrian);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadsisaantrian',
        async: false,
        data: {
            id: dokter,
            no: no_antrian,
        },
        success: function (result) {
            // console.log(result);
            // $('.loaddata').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;

                // console.log(data[0].count);
                sisa_antrian = data[0].count;
                // $('#sisa_antrian').val(data[0].count);


            } else {
                alert(result.info);
            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
    return sisa_antrian;
}

$('#tambah').on('click', function () {
    loadsisaantrian()
    savedataaja()
})


function savedataaja() {
    let id = $('#id_pasien').val();
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();
    let no_rekam_medis = $('#no_rekam_medis').val();
    let poli = $("#poli option:selected").attr("value");
    let dokter = $('#dokter option:selected').attr("value");
    let sisa_antrian = loadsisaantrian()
    let ktp = $('#ktp').val();
    let no_antrian = $("input[name=antrian]:checked").val();

    // console.log(id);

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savedata',
        data: {
            iid: id,
            iktp: ktp,
            ino_rekam_medis: no_rekam_medis,
            inama: nama,
            itempat_lahir: tempat_lahir,
            itanggal_lahir: tanggal_lahir,
            ialamat: alamat,
            ino_hp: no_hp,
            ipoli: poli,
            idokter: dokter,
            ino_antrian: no_antrian,
            isisa_antrian: sisa_antrian,

        },
        success: function (response) {

            if (response['code'] == 0) {
                console.log(response);

                let $id = response.data[0].id_antrian;

                let $encodedId = btoa($id);

                window.location = (baseURL + "/queue/cetakantrian/" + $encodedId);

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
    // console.log(idpoli);
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
                bootbox.alert({ message: 'Dokter di poli ini belum ada yg didaftarkan', centerVertical: true });
            }
        }
    });
});

function loadpoli() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadregisterpoli',

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
                bootbox.alert({ message: 'Data Poli Kosong', centerVertical: true });
            }
        }
    });
}
// validasipasien()
function validasipasien(id_poli, dokter, id_pasien) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/valpilihAntrian',
        async: false,
        data: {
            id_pasien: id_pasien
        },
        success: function (result) {
            // console.log(dokter)
            var data = result.data;


            for (x in data) {
                if (id_pasien == data[x]['id_pasien']) {
                    if (id_poli == data[x]['id_poli']) {
                        if (dokter == data[x]['id_dokter']) {
                            let $idantrian = result.data[0]['id_antrian'];
                            let $encodedIdantrian = btoa($idantrian)


                            let nama = result.data[0]['nama'];
                            swal({
                                title: "Pasien dengan nama '" + nama + "' telah melakukan pendaftaran di poli dan dokter ini! \n Apakah anda ingin mencetak no antrian lagi ? ",
                                type: "warning",
                                confirmButtonClass: "success",
                                confirmButtonText: "Yes!",
                                showCancelButton: true,
                            }, function () {
                                window.location = (baseURL + "/queue/cetakantrian/" + $encodedIdantrian);
                            });
                            exist = 1;
                        }
                    }
                }
            }

        },

        error: function (xhr) {
            //alert(xhr.status+'-'+xhr.statusText);
        }
    });
    return exist;

}

$('#pilihno').on('click', function (e) {
    e.preventDefault();
    var id_pasien = $('#id_pasien').val();
    var id_poli = $('#poli option:selected').attr("value");
    var dokter = $('#dokter option:selected').attr("value");
    pilihAntrian(id_poli)

    // console.log(id_poli)
});




function pilihAntrian(id_poli) {

    var dokter = $('#dokter option:selected').attr("value");
    var id_pasien = $('#id_pasien').val();
    // console.log(id_pasien);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadPilihAntrian',
        data: {
            iddok: dokter,
            id_poli: id_poli,

        },

        success: function (response) {

            var $antrian = (response.data);


            if (validasipasien(id_poli, dokter, id_pasien) == 1) {
                // console.log(validasipasien(id_poli, dokter, id_pasien))

            } else {
                if ($antrian) {
                    var hour = 0;

                    var splithour = [];
                    splithour[0] = $antrian[0]['jam_mulai'].split(':');


                    hour += parseInt(splithour[0][0]);
                    minute = parseInt(splithour[0][1]);


                    $('.antrian').empty();

                    var d = new Date();
                    var newhour     = d.getHours();
                    var newminute   = d.getMinutes();
                    

                    for (var i = 0; i < $antrian.length; i++) {
                        if ($antrian[i]['id_pasien'] === null) {//jika masih kosong
                            let y = '  ';

                            console.log()
                            var splittime = [];
                            splittime[i] = $antrian[i]['waktu_antrian'].split(':');



                            if (minute >= 60) {
                                hour += Math.floor(minute / 60);
                                minute = minute % 60;
                            }
                            if (hour >= 24) {
                                hour = 0;
                            }
    
                            if(newhour > hour){
                                if (minute.toString().length === 1) {
                                    y += `<div class="col-lg-1">
                                    <div class="buttons" >
                                                <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + '0' + minute + `</label>
                                            </div>
                                        </div > `;
                                        $('.antrian').append(y);
                                }else{
                                    y += `<div class="col-lg-1">
                                    <div class="buttons" >
                                                <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + minute + `</label>
                                            </div>
                                        </div > `;
                                        $('.antrian').append(y);
                                }
                            
                                    console.log("merah = "+hour+":"+minute);
                                
                            }else if (newhour == hour){
                                if(newminute>minute){
                                    if (minute.toString().length === 1) {
                                        y += `<div class="col-lg-1">
                                        <div class="buttons" >
                                                    <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                    <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                    <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + '0' + minute + `</label>
                                                </div>
                                            </div > `;
                                            $('.antrian').append(y);
                                    }else{
                                        y += `<div class="col-lg-1">
                                        <div class="buttons" >
                                                    <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                    <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                    <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + minute + `</label>
                                                </div>
                                            </div > `;
                                            $('.antrian').append(y);
                                    }
                                }else{
                                    if (minute.toString().length === 1) {
                                        y += `<div class="col-lg-1">
                                    <div class="buttons" >
                                                <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                <label style="display: block;text-align: center; line-height: 150%;"` + i + `">` + hour + ':' + '0' + minute + `</label>
                                            </div>
                                        </div > `;
                                        $('.antrian').append(y);
                                    } else {
                                        y += `<div class="col-lg-1">
                                    <div class="buttons" >
                                                <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                                <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                                <label style="display: block;text-align: center; line-height: 150%;"` + i + `">` + hour + ':' + minute + `</label>
                                            </div>
                                        </div > `;
                                        $('.antrian').append(y);
                                    }
                                }
                            }else{
                                if (minute.toString().length === 1) {
                                    y += `<div class="col-lg-1">
                                <div class="buttons" >
                                            <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                            <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                            <label style="display: block;text-align: center; line-height: 150%;"` + i + `">` + hour + ':' + '0' + minute + `</label>
                                        </div>
                                    </div > `;
                                    $('.antrian').append(y);
                                } else {
                                    y += `<div class="col-lg-1">
                                <div class="buttons" >
                                            <input type="radio" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                            <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                            <label style="display: block;text-align: center; line-height: 150%;"` + i + `">` + hour + ':' + minute + `</label>
                                        </div>
                                    </div > `;
                                    $('.antrian').append(y);
                                }
                            }


                            minute += parseInt(splittime[i][1]);

                        } else if ($antrian[i]['status_code'] == 10 || $antrian[i]['status_code'] == 20 || $antrian[i]['status_code'] == 30 || $antrian[i]['status_code'] == 40) {//jika antrian sudah terisi
                            var splittime = [];
                            splittime[i] = $antrian[i]['waktu_antrian'].split(':');
                            let y = '';




                            if (minute >= 60) {

                                hour += Math.floor(minute / 60);
                                minute = minute % 60;


                            }

                            if (hour >= 24) {
                                hour = 0;
                            }

                            if (minute.toString().length === 1) {
                                y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + '0' + minute + `</label>
                                    </div>
                                </div > `;
                                $('.antrian').append(y);
                            } else {
                                y += `<div class="col-lg-1">
                            <div class="buttons" >
                                        <input type="picked" name="antrian" value="` + $antrian[i]['no_antrian'] + `" id="antrian` + i + `" />
                                        <label for="antrian` + i + `">` + $antrian[i]['no_antrian'] + `</label>
                                        <label style="display: block;text-align: center; line-height: 150%;" for="antrian` + i + `">` + hour + ':' + minute + `</label>
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
                            if (hour >= 24) {
                                hour = 0;
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



var inputQuantity = [];
$(function () {
    $("#no_rekam_medis").each(function (i) {
        inputQuantity[i] = this.defaultValue;
        $(this).data("idx", i); // save this field's index to access later
    });
    $("#no_rekam_medis").on("keyup", function (e) {
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
            val = val.slice(0, 8);
            $field.val(val);
        }
        inputQuantity[$thisIndex] = val;
    });
});