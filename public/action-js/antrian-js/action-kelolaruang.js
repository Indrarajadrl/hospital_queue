
function reset() {
    document.getElementById("myForm").reset();

}
function reset() {
    document.getElementById("myFormedt").reset();

}

// function closeWin() {
//     myWindow.close();
// }


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

Loadruang();
function Loadruang() {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolaruang',

        success: function (result) {
            // console.log(result);
            $('table.table-bordered > tbody').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;
                let counter = 1;


                for (x in data) {
                    var newRow = $("<tr>");
                    var cols = "";
                    cols += '<td class="">' + counter + '</td>';
                    // cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].id_poli + '</td>'
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].lantai + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_ruang + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].condition + '</td>';


                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" ><span style="padding: 7px;" class="btn btn-success btn-xs" data-toggle="modal" data-target="#Editdata" onClick="loadedit(' + data[x].id_ruang + ')">Edit</span> <span style="padding: 7px;"class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_ruang + ')">Hapus</span><span style="padding: 7px;margin-top:10px; font-size: 14px;"class="btn btn-info btn-xs"  onclick="Update(' + data[x].id_ruang + "," + data[x].id_condition + ')">Update Kondisi</span></div></td>';

                    newRow.append(cols);
                    $("#tableruang").append(newRow);
                    counter++;

                }

            } else {
                bootbox.alert({ message: 'Data Kosong', centerVertical: true });
            }
            $('#dataTable').DataTable({
                "scrollY": '500px',
                "scrollX": true,
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                columnDefs: [
                    { width: 30, targets: 0 },
                    { width: 180, targets: 1 },
                    { width: 140, targets: 2 },
                    { width: 140, targets: 3 },
                    { width: 140, targets: 4 },
                    { width: 100, targets: 5 },
                ],

            });
        },

        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
}

$('#tambahmodal').on('click', function () {
    loadpoli()

})

$('#tambah').on('click', function () {
    let nama_ruang = $('#nama_ruang').val();
    let lantai = $('#lantai').val();

    if (nama_ruang == null || nama_ruang == "") {
        bootbox.alert({ message: 'Nama Ruang tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (lantai == null || lantai == "") {
        bootbox.alert({ message: 'Lantai tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (validasiruang(lantai, nama_ruang) == 3) {
        bootbox.alert({ message: 'Nama ruang  dan lantai sudah ada!', centerVertical: true });
    }
    else if (validasiruang(lantai, nama_ruang) == 2) {
        bootbox.alert({ message: 'Nama ruang  dan lantai sudah ada!', centerVertical: true });
    }
    else {
        saveruang()
    }
})


function saveruang() {
    let nama_ruang = $('#nama_ruang').val();
    let lantai = $('#lantai').val();
    let poli = $("#poli option:selected").attr("value");


    // let image = $('#kode_poli').val();

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savekelolaruang',
        data: {
            inama_ruang: nama_ruang,
            ilantai: lantai,
            ipoli: poli,

        },

        success: function (result) {
            // console.log(result)



            if (result['code'] == 0) {
                $('#addData').modal('hide');
                swal({
                    title: "",
                    text: "Data Berhasil Ditambahkan!",
                    icon: "succes",
                    button: "ok",
                }, function () {
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

// validasiruang()
function validasiruang(lantai, nama_ruang) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolaruang',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {

            var data = result.data;
            console.log(data[0])
            var ruangcounter = 0

            for (x in data) {


                // if (lantai == data[x]['lantai']) {
                //     if (nama_ruang == data[x]['nama_ruang']) {

                //     }
                // }

                if (lantai == data[x]['lantai']) {
                    if (nama_ruang == data[x]['nama_ruang']) {
                        exist = 2;
                    }
                }
                if (lantai == data[x]['lantai']) {
                    if (nama_ruang == data[x]['nama_ruang']) {
                        ruangcounter += 1;
                        if (ruangcounter > 1) {
                            exist = 3;
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



$('#edit').on('click', function () {
    let id_ruang = $('#id_ruang').val();
    let nama_ruang = $('#nama_ruangedt').val();
    let lantai = $('#lantaiedt').val();
    let poli = $("#poliedt option:selected").attr("value");
    // console.log(poli)

    if (nama_ruang == null || nama_ruang == "") {
        bootbox.alert({ message: 'Nama Ruang tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (lantai == null || lantai == "") {
        bootbox.alert({ message: 'Lantai tidak boleh kosong', centerVertical: true });
        return false;
    } else if (validasiruang(lantai, nama_ruang) == 3) {
        bootbox.alert({ message: 'Nama ruang dan lantai harus berbeda!', centerVertical: true });
    } else {
        editdataruang(id_ruang)
    }

})
function editdataruang(id_ruang) {
    let poli = $("#poliedt option:selected").attr("value");
    let nama_ruang = $('#nama_ruangedt').val();
    let lantai = $('#lantaiedt').val();


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/editkelolaruang',
        data: {
            id: id_ruang,
            inama_ruang: nama_ruang,
            ipoli: poli,
            ilantai: lantai,

        },

        success: function (response) {
            //console.log(respoonse);

            if (response['code'] == 0) {
                $('#Editdata').modal('hide');

                swal({
                    title: "",
                    text: "Data Berhasil DiUbah!",
                    icon: "succes",
                    button: "ok",
                }, function () {
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




function loadedit(id_ruang) {


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolaruang',
        data: {
            id: id_ruang,
        },
        success: function (result) {
            // console.log(result);
            // $('.loaddata').empty();
            // console.log(result);

            if (result.code == 0) {
                var data = '';
                var data = result.data;

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: baseURL + '/api/loadRegisterEdit',
                    async: false,
                    success: function (result) {



                        if (result['code'] == 0) {
                            // console.log(result);
                            var res = result['data'];

                            optionseg = '';
                            optionsegc = '';

                            for (var i = 0; i < res.poli.length; i++) { //POli
                                optionseg += '<option value="' + res.poli[i].id_poli + '">' + res.poli[i].nama_poli + '</option>';
                            }
                            $("select#poliedt").html(optionseg);

                            $(document).ready(function () {
                                $("select#poliedt").val(data[0].id_poli).change();
                            });



                        }
                    }
                });
                $('#id_ruang').val(data[0].id_ruang);
                $('#nama_ruangedt').val(data[0].nama_ruang);
                $('#lantaiedt').val(data[0].lantai);

            } else {
                bootbox.alert({ message: 'Data Kosong', centerVertical: true });
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
        url: baseURL + '/api/loadkelolaruang',
        data: {
            id: param,
        },
        success: function (result) {
            // console.log(result);
            let nama_ruang = result.data[0].nama_ruang;
            let lantai = result.data[0].lantai;
            swal({
                title: "Apakah yakin mengahapus ruang '" + nama_ruang + " ' lantai '" + lantai + "'? ",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/deletekelolaruang',
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

function Update(id_ruang, id_condition) {

    // console.log(id_dokter)
    // console.log(id_condition)
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/updateconditionruang',
        data: {
            id_ruang: id_ruang,
            id_condition: id_condition,
        },
        success: function (result) {
            console.log(result);
            // console.log(result)
            if (result.code == 0) {
                location.reload();
            } else {
                bootbox.alert({ message: 'Gagal ', centerVertical: true });

            }


        },

        error: function () {
            bootbox.alert({ message: 'Data dokter masih terdaftar dalam poli ', centerVertical: true });
        }
    });

}

$('#update').on('click', function () {
    setruang()

})

function setruang() {
    param = null
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/updateconditionseluruhruang',
        data: {
            id: param,
        },
        success: function (result) {
            console.log(result);
            // console.log(result)
            if (result.code == 0) {
                location.reload();
            } else {
                bootbox.alert({ message: 'Gagal ', centerVertical: true });

            }


        },

        error: function () {
            bootbox.alert({ message: 'Data dokter masih terdaftar dalam poli ', centerVertical: true });
        }
    });

}