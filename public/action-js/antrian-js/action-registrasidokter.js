function antrianadmin() {
    window.location.href = "antrianadmin";
}

$(document).ready(function () {
    $('#addData').on('hidden.bs.modal', function () {
        $('#addData form')[0].reset();

    });

});

$(document).ready(function () {
    $("#jam_mulai").clockTimePicker({
        required: true,
        separator: ':',
        duration: true,
        minimum: '06:00',
        maximum: '24:00',
        durationNegative: true
    });
});
//
$(document).ready(function () {
    $("#waktu_antrian").clockTimePicker({
        required: true,
        separator: ':',
        duration: true,
        minimum: '00:01',
        maximum: '00:59',
        durationNegative: true
    });
});
$(document).ready(function () {
    $("#jam_mulaiedt").clockTimePicker({
        required: true,
        separator: ':',
        duration: true,
        minimum: '00:00',
        maximum: '24:00',
        durationNegative: true
    });
});

$(document).ready(function () {
    $("#waktu_antrianedt").clockTimePicker({
        required: true,
        separator: ':',
        duration: true,
        minimum: '00:01',
        maximum: '00:59',
        durationNegative: true
    });
});


loadpoli();
$('#poli').on('change', function () {
    var idpoli = $('#poli').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadRegisterDokter',
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

                optionseg = '<option disable selected>Kosong</option>';
                $("select#dokter").html(optionseg);
                $("select#ruang").html(optionseg);

                // bootbox.alert({ message: 'Data Dokter Kosong', centerVertical: true });

            }
        }
    });
});


$('#poli').on('change', function () {
    var idpoli = $('#poli').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadRegisterRuang',
        data: {
            id_poli: idpoli
        },
        success: function (result) {
            if (result['code'] == 0) {

                var res = result['data'];

                optionseg = '';
                optionseg += '<option value="Pilih" disabled selected>Pilih</option>';
                for (var i = 0; i < res.length; i++) {
                    optionseg += '<option value="' + res[i].id_ruang + '">' + res[i].nama_ruang + '</option>';
                }
                $("select#ruang").removeAttr('disabled');
                $("select#ruang").html(optionseg);

            } else {
                // bootbox.alert({ message: 'Data Ruang Kosong', centerVertical: true });

            }
        }
    });
});

function loadpoli() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadRegisterPoli',

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


function reset() {
    document.getElementById("myForm").reset();

}




LoadDadta();
function LoadDadta() {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianregister',


        success: function (result) {
            console.log(result);

            $('table.table-bordered > tbody').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;

                let counter = 1;
                for (x in data) {
                    console.log(data);
                    var newRow = $("<tr>");
                    var cols = "";
                    // console.log(data[x].id_register);
                    cols += '<tr>'
                    cols += '<td class="">' + counter + '</td>';
                    // cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].id_register + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_ruang + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].antrian_all + '</td>';

                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].jam_mulai + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].waktu_antrian + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].estimasi_selesai + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].create_date + '</td>';

                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" ><span style="padding: 7px;" class="btn btn-success btn-xs" data-toggle="modal" data-target="#editData" onClick="loadedit(' + data[x].id_register + ')">Edit</span>  <span style="padding: 7px;" class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_register + ')">Hapus</span></div></td>';
                    cols += '</tr>'


                    newRow.append(cols);
                    $("#tableregisterdokter").append(cols);
                    counter++;

                }

            } else {

            }
            $('#dataTable').DataTable({
                "scrollY": '600px',
                "scrollX": true,
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                columnDefs: [
                    { width: 20, targets: 0 },
                    { width: 100, targets: 1 },
                    { width: 100, targets: 2 },
                    { width: 50, targets: 3 },
                    { width: 50, targets: 4 },
                    { width: 50, targets: 5 },
                    { width: 50, targets: 6 },
                    { width: 50, targets: 7 },
                    { width: 50, targets: 8 },
                    { width: 100, targets: 9 },
                ],

            });
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}



$('#tambahmodal').on('click', function () {

    $('#addData').modal('hide');


})



$('#tambah').on('click', function () {
    let ruang = $("#ruang option:selected").attr("value");
    let poli = $('#poli option:selected').attr("value");
    let dokter = $('#dokter option:selected').attr("value");

    if (validasijadwal(poli, ruang, dokter) == 1) {
        bootbox.alert({ message: 'Dokter dipoli  sudah ada!', centerVertical: true });
    }
    if (validasijadwal(poli, ruang, dokter) == 2) {
        bootbox.alert({ message: 'Ruangan dipoli sudah ada!', centerVertical: true });
    } else {
        SaveDataRegister()
    }

})


function SaveDataRegister(param) {

    let ruang = $("#ruang option:selected").attr("value");
    let poli = $('#poli option:selected').attr("value");
    let dokter = $('#dokter option:selected').attr("value");
    let antrian_all = $('#antrian_all').val();
    let waktu_antrian = $('#waktu_antrian').val();
    let jam_mulai = $('#jam_mulai').val();
    let estimasi_selesai = $('#estimasi_selesai').val();

    console.log(estimasi_selesai);

    if (ruang == null || ruang == "") {
        swal({
            title: "",
            text: "Field ruang is empty!",
            icon: "error",
            button: "ok",
        });
        return false;

    } else if (antrian_all == null || antrian_all == "") {
        swal({
            title: "",
            text: "Field antrian seluruh is empty!",
            icon: "error",
            button: "ok",
        });
        return false;


    } else if (waktu_antrian == null || waktu_antrian == "") {
        swal({
            title: "",
            text: "Field waktu antrian is empty!",
            icon: "error",
            button: "ok",
        });
        return false;
    }
    else if (poli == null || poli == "") {
        swal({
            title: "",
            text: "Field poli is empty!",
            icon: "error",
            button: "ok",
        });
        return false;
    }
    else if (dokter == null || dokter == "") {
        swal({
            title: "",
            text: "Field dokter is empty!",
            icon: "error",
            button: "ok",
        });
        return false;

    }
    else if (parseInt(antrian_all) > 50) {
        swal({
            title: "",
            text: "antrian seluruh tidak boleh lebih dari 30!",
            icon: "error",
            button: "ok",
        });
        return false;
    } else if (parseInt(waktu_antrian) > 10) {
        swal({
            title: "",
            text: "waktu antrian per orang tidak lebih dari 10 menit",
            icon: "error",
            button: "ok",
        });
        return false;
    }
    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/saveregister',
        data: {
            iruang: ruang,
            id: param,
            iantrian_all: antrian_all,//variable json diisi oleh variable yang menyimpan data inputan
            iwaktu_antrian: waktu_antrian,
            ipoli: poli,
            idokter: dokter,
            ijam_mulai: jam_mulai,
            iestimasi_selesai: estimasi_selesai,
        },
        success: function (result) {

            if (result.code == 0) {
                $('#addData').modal('hide');
                swal({
                    title: "<span style='color:#222'>Berhasil!</span>",
                    confirmButtonColor: "#66BB6A",
                    html: true,
                    type: "success"
                }, function () {
                    location.reload();
                });
                // loadpoli();
                // reset()

            } else {
                swal({
                    title: "",
                    text: "data gagal ditambahkan!",
                    icon: "succes",
                    button: "ok",
                });
                // bootbox.alert({ message: 'Gagal', centerVertical: true });

            }
        },
        error: function (xhr) {

            if (xhr.status != 200) {
                //bootbox.alert(xhr.status + "-" + xhr.statusText + " <br>Silahkan coba kembali :) ");
            } else {
                bootbox.alert('gagal error');
            }
        }
    });
}


function validasijadwal(poli, ruang, dokter) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianregister',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {
            console.log(result)
            var data = result.data;


            for (x in data) {


                if (poli == data[x]['id_poli']) {
                    if (dokter == data[x]['id_dokter']) {
                        exist = 1;
                    }
                }
                if (poli == data[x]['id_poli']) {
                    if (ruang == data[x]['id_ruang']) {
                        exist = 2;

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


$('#edit').on('click', function () {
    let id_register = $('#id_register').val();


    editregister(id_register)



})


function sumestimasi(param){   
  
    if(param == 'ADD'){
        var waktu_antrian = $('#waktu_antrian').val();
        var jam_mulai = $('#jam_mulai').val();
        var antrian_all = $('#antrian_all').val();
    } 
    //splithour[0] = $antrian[0]['jam_mulai'].split(':');
    var hour = 0;

    var splitjammulai = [];
    splitjammulai[0] = jam_mulai.split(":");

    // console.log(splitjammulai[0][0]); //jam
    // console.log(splitjammulai[0][1]); //menit

    hour += parseInt(splitjammulai[0][0]);
    minute = parseInt(splitjammulai[0][1]);
    

    for(var i=0; i <= antrian_all; i++){
    
        var splitwaktuantrian = [];
        splitwaktuantrian[0] = waktu_antrian.split(":");

        if (minute >= 60) {
            hour += Math.floor(minute / 60);
            minute = minute % 60;
           
        }
        if (hour >= 24) {
            hour = 0;
        }
        var result = hour + ':'+ minute;


        if(minute.toString().length === 1){

            var result = hour + ':0'+ minute;
        }

       if (antrian_all == null || antrian_all == "") {
           if(param = 'ADD'){
            document.getElementById('estimasi_selesai').value = "";
           }
          
       }else if( antrian_all != null || antrian_all != ""){
           if(param = 'ADD'){
            document.getElementById('estimasi_selesai').value = result;
           }
         
           
       }
       
        minute += parseInt(splitwaktuantrian[0][1]);
    }
    // console.log(waktu_antrian);
    // console.log(jam_mulai);
    // console.log(antrian_all);
    // console.log(estimasi_selesai);
}


function sumestimasiedt(param){   
  
    if(param == 'EDIT'){
        var waktu_antrian = $('#waktu_antrianedt').val();
        var jam_mulai = $('#jam_mulaiedt').val();
        var antrian_all = $('#antrian_alledt').val();
    } 
    //splithour[0] = $antrian[0]['jam_mulai'].split(':');
    var hour = 0;

    var splitjammulai = [];
    splitjammulai[0] = jam_mulai.split(":");

    // console.log(splitjammulai[0][0]); //jam
    // console.log(splitjammulai[0][1]); //menit

    hour += parseInt(splitjammulai[0][0]);
    minute = parseInt(splitjammulai[0][1]);
    

    for(var i=0; i <= antrian_all; i++){
    
        var splitwaktuantrian = [];
        splitwaktuantrian[0] = waktu_antrian.split(":");

        if (minute >= 60) {
            hour += Math.floor(minute / 60);
            minute = minute % 60;
           
        }
        if (hour >= 24) {
            hour = 0;
        }
        var result = hour + ':'+ minute;


        if(minute.toString().length === 1){

            var result = hour + ':0'+ minute;
        }

       if (antrian_all == null || antrian_all == "") {
           if(param = 'EDIT'){
            document.getElementById('estimasi_selesaiedt').value = "";
           }
          
       }else if( antrian_all != null || antrian_all != ""){
           if(param = 'EDIT'){
            document.getElementById('estimasi_selesaiedt').value = result;
           }
       }
        minute += parseInt(splitwaktuantrian[0][1]);
    }
    // console.log(waktu_antrian);
    // console.log(jam_mulai);
    // console.log(antrian_all);
    // console.log(estimasi_selesai);
}

function editregister(id_register) {
    let poli = $('#id_poli').val();
    let ruang = $('#id_ruang').val();
    let dokter = $('#id_dokter').val();

    let antrian_all = $('#antrian_alledt').val();
    let waktu_antrian = $('#waktu_antrianedt').val();
    let jam_mulai = $('#jam_mulaiedt').val();
    let antrian_before = $('#antrian_before').val();
    console.log(antrian_before);
    let tanggal_before = $('#tanggal_before').val();
    let estimasi_selesai = $('#estimasi_selesaiedt').val();

    console.log(poli)
    if (antrian_all == null || antrian_all == "") {
        swal({
            title: "",
            text: "Field antrian seluruh is empty!",
            icon: "error",
            button: "ok",
        });
        return false;


    } else if (waktu_antrian == null || waktu_antrian == "") {
        swal({
            title: "",
            text: "Field waktu antrian is empty!",
            icon: "error",
            button: "ok",
        });
        return false;
    }



    else if (parseInt(antrian_all) > 100) {
        swal({
            title: "",
            text: "antrian seluruh tidak boleh lebih dari 30!",
            icon: "error",
            button: "ok",
        });
        return false;
    } else if (parseInt(waktu_antrian) > 10) {
        swal({
            title: "",
            text: "waktu antrian per orang tidak lebih dari 10 menit",
            icon: "error",
            button: "ok",
        });
        return false;
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/editdataregister',
        async: false,
        data: {
            id: id_register,
            ipoli: poli,
            idokter: dokter,
            iruang: ruang,
            iantrial_all: antrian_all,

            iwaktu_antrian: waktu_antrian,
            ijam_mulai: jam_mulai,
            iantrian_before: antrian_before,
            itanggal_before: tanggal_before,
            iestimasi_selesai: estimasi_selesai,
        },

        success: function (result) {

            if (result.code == 0) {
                $('#editData').modal('hide');
                swal({
                    title: "",
                    text: "Data Berhasil DiUbah!",
                    icon: "succes",
                    button: "ok",
                },
                    function () {
                        location.reload();
                    });
            } else {
                swal({
                    title: "",
                    text: "Data Gagal Ditambahkan!",
                    icon: "succes",
                    button: "ok",
                });

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

loadedit()
function loadedit(id_register) {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianregister',
        data: {
            id: id_register,
        },
        success: function (result) {

            // $('.loaddata').empty();
            console.log(result);

            if (result.code == 0) {

                let data = result.data;


                optdok = `<p class="card-text">` + data[0].nama_dokter + `</p>`
                optpol = `<p class="card-text">` + data[0].nama_poli + `</p>`
                optrua = `<p class="card-text">` + data[0].nama_ruang + `</p>`
                console.log(data[0].nama_poli);
                $('#id_register').val(data[0].id_register);
                $('#tanggal_before').val(data[0].create_date);
              
                console.log(data[0].create_date)
                $('#poliedt').html(optpol);
                $('#dokteredt').html(optdok);
                $('#ruangedt').html(optrua);


                $('#antrian_alledt').val(data[0].antrian_all);

                $('#id_dokter').val(data[0].id_dokter);
                $('#id_poli').val(data[0].id_poli);
                $('#id_ruang').val(data[0].id_ruang);
                $('#antrian_before').val(data[0].antrian_all);

                $('#jam_mulaiedt').val(data[0].jam_mulai);
                $('#waktu_antrianedt').val(data[0].waktu_antrian);
                $('#estimasi_selesaiedt').val(data[0].estimasi_selesai);




            } else {
                // bootbox.alert({ message: 'Data Kosong', centerVertical: true });

            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}




function Delete(param) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianregister',
        data: {
            id: param,
        },
        success: function (result) {
            // console.log(result.data[0].nama_poli);
            let id_register = result.data[0].id_register;
            let nama_poli = result.data[0].nama_poli;
            let nama_dokter = result.data[0].nama_dokter;
            let nama_ruang = result.data[0].nama_ruang;
            swal({
                title: "Apakah yakin mengahapus Registrasi Dokter dengan Nama Poli '" + nama_poli + "', Nama Dokter '" + nama_dokter + "' dan Nama Ruangan '" + nama_ruang + "' ini  ? ",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/deletedataregister',
                        data: {
                            id: param,
                        },
                        success: function (result) {
                            // console.log(result)
                            if (result.code == 0) {
                                swal({
                                    title: "",
                                    text: "Data Berhasil Dihapus!",
                                    icon: "succes",
                                    button: "ok",
                                }, function () {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: "",
                                    text: "Data Gagal Dihapus!",
                                    icon: "succes",
                                    button: "ok",
                                });
                            }
                        },
                        error: function () {
                            bootbox.alert({ message: 'Data ruang masih terdaftar dalam poli ', centerVertical: true });
                        }
                    });
                },


            );

        }
    });

}



// function differenceday(){
//     date = new Date();

//     $.ajax({
//         type: 'POST',
//         dataType: 'json',
//         url: baseURL + '/api/loadRegisterRuang',
//         data: {
//             id_poli: null
//         },
//         success: function (result) {
//             if (result['code'] == 0) {

//                 var res = result['data'];
//                 for(x in res){
//                     date = res[x]['date'];

//                     date = new Date(date);
//                     now  = new Date();

//                     if( date.getDate() == now.getDate() ){      

//                     }else if(date.getDate() == now.getDate() && date.getMonth() != now.getMonth()){
//                         setruang();
//                     }
//                     else if(date.getDate() != now.getDate()){
//                         setruang();
//                     }
//                 }


//             } else {
//                 bootbox.alert({ message: 'Data Ruang Kosong', centerVertical: true });

//             }
//         }
//     });

//     // 

// }


// function setruang(){
//     param = null
//     $.ajax({
//         type: 'POST',
//         dataType: 'json',
//         url: baseURL + '/api/updateconditionruang',
//         data: {
//             id: param,
//         }
//     })
// }