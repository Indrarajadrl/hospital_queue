
/* JS comes here */
/* JS comes here */
function textToAudio() {
    // let msg1 = "nomor antrian";
    let no_antrian = document.getElementById("no_antrian").innerHTML;
    let voice = "nomor antrian " + no_antrian;
    console.log(voice)
    let speech = new SpeechSynthesisUtterance();
    speech.lang = "id";
    speech.text = voice;
    speech.volume = 1;
    speech.rate = 0.70;
    speech.pitch = 1;

    window.speechSynthesis.speak(speech);
}

function textToAudioRuang() {
    // let msg1 = "nomor antrian";
    let ruang = document.getElementById("nama_ruang").innerHTML;
    var res = ruang.replace(".", " ");
    document.getElementById("nama_ruang").innerHTML = res;

    let voice = "silahkan menuju ke ruang " + res;

    console.log(voice)
    let speech = new SpeechSynthesisUtterance();
    speech.lang = "id";

    speech.text = voice;
    speech.volume = 1;
    speech.rate = 0.70;
    speech.pitch = 1;

    window.speechSynthesis.speak(speech);
}
console.log(id_poli)
loaddokter()
function loaddokter() {
    var idpoli = id_poli;
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
                bootbox.alert({ message: 'Data Dokter di poli ini kosong', centerVertical: true });
            }
        }
    });
}

function loadantrian() {
    var iddokter = $('#dokter').val();
    // console.log(iddokter)
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadAntrianAdmin',
        data: {
            iddok: iddokter

        },

        success: function (result) {
            console.log(result.data);

            $('table.table-bordered > tbody').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;
                console.log(data);
                let counter = 1;

                for (x in data) {
                    var newRow = $("<tr>");
                    var cols = "";
                    // onClick="openmyprofile('+reg[x].userid+',\''+reg[x].named+'\')">
                    cols = '<td class="">' + counter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].no_antrian + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].status_name + '</td>';

                    newRow.append(cols);
                    $("#antrianadmin").append(newRow);
                    counter++;


                    // $('.loaddata').append('' + x + '<span>' + data[x].nama + '</span>');
                }
                // console.log(data);
            } else {
                bootbox.alert({ message: 'Data Antrian Kosong', centerVertical: true });
            }


        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}


$('#dokter').on('change', function () {

    loadantrian();
    tampilangka();
});



$('#no_antrian').html('-');
function tampilangka() {
    var iddokter = $('#dokter').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadNoAntrian',
        data: {
            id_dokter: iddokter,
        },
        async: false,

        success: function (result) {
            // console.log(result.data);
            // console.log(result);

            if (result.data) {
                var res = result['data'][0];
                var valTemp = res['kode_dokter'] + '' + res['no_antrian'];
                var Ruang = res['nama_ruang'];

                noantrian = res['no_antrian'];
                iddok = res['id_dokter'];
                if (res['status_code'] == 30) {
                    $('#no_antrian').empty();
                    $('#no_antrian').html(valTemp);
                    $('#nama_ruang').empty();
                    $('#nama_ruang').html(Ruang);
                    $('#btnNext').prop('disabled', true);
                    $('#btnCall').prop('disabled', false);
                    $('#btnServed').prop('disabled', false);
                    $('#btnMissed').prop('disabled', false);
                    $('#btnDone').prop('disabled', true);
                } else if (res['status_code'] == 40) {
                    $('#no_antrian').empty();
                    $('#no_antrian').html(valTemp);
                    $('#nama_ruang').empty();
                    $('#nama_ruang').html(Ruang);
                    $('#btnNext').prop('disabled', true);
                    $('#btnCall').prop('disabled', true);
                    $('#btnServed').prop('disabled', true);
                    $('#btnMissed').prop('disabled', true);
                    $('#btnDone').prop('disabled', false);
                } else if (res['status_code'] == 10) {
                    $('#no_antrian').empty();
                    $('#no_antrian').html(valTemp);
                    $('#nama_ruang').empty();
                    $('#nama_ruang').html(Ruang);
                    $('#btnNext').prop('disabled', true);
                    $('#btnCall').prop('disabled', false);
                    $('#btnServed').prop('disabled', true);
                    $('#btnMissed').prop('disabled', true);
                    $('#btnDone').prop('disabled', true);
                } else if (res['status_code'] == 10) {
                    $('#no_antrian').empty();
                    $('#no_antrian').html(valTemp);
                    $('#nama_ruang').empty();
                    $('#nama_ruang').html(Ruang);
                    $('#btnNext').prop('disabled', false);
                    $('#btnCall').prop('disabled', true);
                    $('#btnServed').prop('disabled', false);
                    $('#btnMissed').prop('disabled', false);
                    $('#btnDone').prop('disabled', false);
                }


            } else {

                $('#no_antrian').html('-');
            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}



$('#btnNext').on('click', function () {

    actionqueue(noantrian + 1, 20);
    loadantrian()

});

$('#btnCall').on('click', function () {
    actionqueue(noantrian, 30);

});
$('#btnDone').on('click', function () {
    actionqueue(noantrian, 50);

});
$('#btnMissed').on('click', function () {
    actionqueue(noantrian, 60);

});
$('#btnServed').on('click', function () {
    actionqueue(noantrian, 40);


});


function actionqueue(antrianno, status) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/nextantrian',
        data: {
            inoantrian: antrianno,
            idok: iddok,
            istatus: status
        },
        success: function (result) {


            if (result.data == null && status == 20) {
                swal({
                    title: "<span style='color:#222'>error!</span>",
                    text: "<span style='color:#222'>Nomer Antrian Selanjutnya Tidak ada!</span>",
                    confirmButtonColor: "#66BB6A",
                    html: true,
                    type: "warning"
                });
                $('#no_antrian').html('-');
                return false;

            }

            if (status == 20) {

                var res = result['data'][0];
                var valTemp = res['kode_dokter'] + '' + res['no_antrian'];
                noantrian = res['no_antrian'];
                iddok = res['id_dokter'];


                $('#no_antrian').empty();
                $('#no_antrian').html(valTemp);

                $('#btnNext').prop('disabled', true);
                $('#btnCall').prop('disabled', false);
                $('#btnServed').prop('disabled', true);
                $('#btnMissed').prop('disabled', true);
                $('#btnDone').prop('disabled', true);


            } else if (status == 30) {
                // SOound call


                swal({
                    title: "Call!",
                    text: "Sedang Melakukan Panggilan",
                    type: "success",
                    showConfirmButton: false,
                    timer: 14000
                });

                var flush = new Audio('../../data/tone/a1.mp3');
                flush.muted = true;

                setTimeout(function () {
                    flush.muted = false;
                    flush.play()
                })


                flush.onended = function () {
                    textToAudio()

                    setTimeout(function () {
                        textToAudioRuang()
                    }, 3000)
                };



                $('#btnNext').prop('disabled', true);
                $('#btnCall').prop('disabled', false);
                $('#btnServed').prop('disabled', false);
                $('#btnMissed').prop('disabled', false);
                $('#btnDone').prop('disabled', true);
            } else if (status == 50) {
                $('#btnNext').prop('disabled', false);
                $('#btnCall').prop('disabled', true);
                $('#btnServed').prop('disabled', true);
                $('#btnMissed').prop('disabled', true);
                $('#btnDone').prop('disabled', true);
            } else if (status == 60) {
                $('#btnNext').prop('disabled', false);
                $('#btnCall').prop('disabled', true);
                $('#btnServed').prop('disabled', true);
                $('#btnMissed').prop('disabled', true);
                $('#btnDone').prop('disabled', true);
            } else if (status == 40) {

                $('#btnNext').prop('disabled', true);
                $('#btnCall').prop('disabled', true);
                $('#btnServed').prop('disabled', true);
                $('#btnMissed').prop('disabled', true);
                $('#btnDone').prop('disabled', false);
            } else {
                $('#no_antrian').html('-');
            }

        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
}


