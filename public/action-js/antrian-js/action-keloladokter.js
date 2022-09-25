
function reset() {
    document.getElementById("myForm").reset();

}


function loadpoli() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadRegisterpoli',

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


Loaddokter(null);
function Loaddokter() {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkeloladokter',

        success: function (result) {
            // console.log(result)

            $('table.table-bordered > tbody').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;
                let counter = 1;


                for (x in data) {
                    // console.log(data);
                    var newRow = $("<tr>");
                    var cols = "";
                    // console.log(data[x].id_register);
                    // onClick="openmyprofile('+reg[x].userid+',\''+reg[x].named+'\')">
                    cols += '<td class="">' + counter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].kode_dokter + '</td>';

                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].image_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].condition + '</td>';
                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" ><span style="padding: 7px;" class="btn btn-success btn-xs" data-toggle="modal" data-target="#Editdata" onClick="loadedit(' + data[x].id_dokter + ')">Edit</span> <span style="padding: 7px;"class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_dokter + ')">Hapus</span>  <span style="padding: 7px;margin-top:10px; font-size: 14px;"class="btn btn-info btn-xs"  onclick="Update(' + data[x].id_dokter + "," + data[x].id_condition + ')">Update Kondisi</span></div></td>';

                    newRow.append(cols);
                    $("#tabledokter").append(newRow);
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
                    { width: 120, targets: 1 },
                    { width: 120, targets: 2 },
                    { width: 120, targets: 3 },
                    { width: 120, targets: 4 },
                    { width: 90, targets: 5 },
                    { width: 90, targets: 6 },
                ],

            });
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}

function validasidokter(poli, nama_dokter, kode_dokter, fileupload) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkeloladokter',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {

            var data = result.data;
            var namacounter = 0;
            var kodecounter = 0;

            for (x in data) {
                if (poli == data[x]['id_poli']) {
                    // console.log(data[x]['id_poli'])
                    if (nama_dokter == data[x]['nama_dokter']) {
                        exist = 1;
                    }
                }

                if (poli == data[x]['id_poli']) {
                    if (kode_dokter == data[x]['kode_dokter']) {
                        exist = 2;
                    }
                }
                if (poli == data[x]['id_poli']) {
                    if (fileupload == data[x]['image_dokter']) {
                        exist = 3;
                    }
                }

                if (poli == data[x]['id_poli']) {
                    if (nama_dokter == data[x]['nama_dokter']) {
                        namacounter += 1;
                        if (namacounter > 1) {
                            exist = 4;
                        }
                    }
                }
                if (poli == data[x]['id_poli']) {
                    if (kode_dokter == data[x]['kode_dokter']) {
                        kodecounter += 1;
                        if (kodecounter > 1) {
                            exist = 5;
                        }
                    }
                }
                if (poli == data[x]['id_poli']) {
                    if (fileupload == data[x]['image_dokter']) {
                        kodecounter += 1;
                        if (kodecounter > 1) {
                            exist = 6;
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

function validasigambar() {
    var exist = 0;

    let inputString = $('#gambar_dokter').val();
    let inputStringedt = $('#gambar_dokteredt').val();

    var imageReg = /[\/.](gif|jpg|jpeg|tiff|png)$/i;

    if (!imageReg.test(inputString)) {
        // bootbox.alert({ message: 'Harus berupa gambar', centerVertical: true });
        exist = 1;
    }
    if (!imageReg.test(inputStringedt)) {
        // bootbox.alert({ message: 'Harus berupa gambar', centerVertical: true });
        exist = 2;
    }

    return exist;
}

$('#tambahmodal').on('click', function () {
    loadpoli()
})

$('#tambah').on('click', function () {
    let nama_dokter = $('#nama_dokter').val();
    let kode_dokter = $('#kode_dokter').val();
    let poli = $("#poli option:selected").attr("value");
    const fileupload = $('#gambar_dokter').prop('files')[0];

    if (nama_dokter == null || nama_dokter == "") {
        bootbox.alert({ message: 'Nama dokter tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (kode_dokter == null || kode_dokter == "") {
        bootbox.alert({ message: 'Kode dokter tidak boleh kosong', centerVertical: true });
        return false;
    } else if (poli == null) {
        bootbox.alert({ message: 'Poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (fileupload == null) {
        bootbox.alert({ message: 'Gambar dokter tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (poli == null) {
        bootbox.alert({ message: 'Kode dokter tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (validasidokter(poli, nama_dokter, kode_dokter, fileupload) == 1) {
        bootbox.alert({ message: 'Nama dokter sudah ada!', centerVertical: true });
    }
    else if (validasidokter(poli, nama_dokter, kode_dokter, fileupload) == 2) {
        bootbox.alert({ message: 'Kode dokter sudah ada!', centerVertical: true });
    }
    else if (validasidokter(poli, nama_dokter, kode_dokter, fileupload) == 3) {
        bootbox.alert({ message: 'Gambar dokter sudah ada!', centerVertical: true });

    }
    else if (validasigambar() == 1) {
        bootbox.alert({ message: 'File Harus berupa gambar', centerVertical: true });
    } else {
        savedokter()
    }


})


function savedokter() {
    let nama_dokter = $('#nama_dokter').val();
    let kode_dokter = $('#kode_dokter').val();
    let poli = $("#poli option:selected").attr("value");
    const fileupload = $('#gambar_dokter').prop('files')[0];

    let formData = new FormData();

    formData.append('inama_dokter', nama_dokter);
    formData.append('ikode_dokter', kode_dokter);
    formData.append('ipoli', poli);
    formData.append('fileupload', fileupload); // set file ke tipe data binary


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savekeloladokter',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,

        success: function (response) {
            //console.log(poli)
            if (response['code'] == 0) {
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




$('#edit').on('click', function () {
    let id_dokter = $('#id_dokter').val();
    let nama_dokter = $('#nama_dokteredt').val();
    let kode_dokter = $('#kode_dokteredt').val();
    let poli = $("#poliedt option:selected").attr("value");
    const fileupload = $('#gambar_dokteredt').prop('files')[0];


    if (nama_dokter == null || nama_dokter == "") {
        bootbox.alert({ message: 'Nama dokter tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (kode_dokter == null || kode_dokter == "") {
        bootbox.alert({ message: 'Kode dokter tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (poli == null) {
        bootbox.alert({ message: 'Poli tidak boleh kosong', centerVertical: true });
        return false;
    }

    else if (validasidokter(poli, nama_dokter, kode_dokter) == 4) {
        bootbox.alert({ message: 'Nama dokter harus berbeda!', centerVertical: true });

    }
    else if (validasidokter(poli, nama_dokter, kode_dokter) == 5) {
        bootbox.alert({ message: 'Kode dokter harus berbeda!', centerVertical: true });

    } else if (validasigambar() == 2) {
        if (fileupload == null || fileupload == '') {
            editdatadokter(id_dokter);
        } else {
            bootbox.alert({ message: 'File Harus berupa gambar', centerVertical: true });
        }
    } else {
        editdatadokter(id_dokter)
    }



})
function editdatadokter(id_dokter) {
    let poli = $("#poliedt option:selected").attr("value");

    let nama_dokter = $('#nama_dokteredt').val();
    let kode_dokter = $('#kode_dokteredt').val();
    const fileupload = $('#gambar_dokteredt').prop('files')[0];

    let formData = new FormData();

    formData.append('id_dokter', id_dokter);
    formData.append('inama_dokter', nama_dokter);
    formData.append('ikode_dokter', kode_dokter);
    formData.append('ipoli', poli);

    formData.append('fileupload', fileupload); // set file ke tipe data binary


    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/editkeloladokter',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            // console.log(response);
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




function loadedit(id_dokter) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkeloladokter',
        async: false,
        data: {
            id: id_dokter,
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

                        console.log(data);

                        if (result['code'] == 0) {

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



                $('#id_dokter').val(data[0].id_dokter);
                $('#nama_dokteredt').val(data[0].nama_dokter);
                $('#kode_dokteredt').val(data[0].kode_dokter);

                // $("#gambar_dokteredt").val(data[0].image_dokter);
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
        url: baseURL + '/api/loadkeloladokter',
        data: {
            id: param,
        },
        success: function (result) {
            // console.log(result.data[0].nama_dokter);
            let nama_dokter = result.data[0].nama_dokter;
            swal({
                title: "Apakah yakin mengahapus dokter '" + nama_dokter + "'? ",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/deletekeloladokter',
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
                            bootbox.alert({ message: 'Data dokter masih terdaftar dalam poli ', centerVertical: true });
                        }
                    });
                }
            );

        }
    });

}

function Update(id_dokter, id_condition) {

    // console.log(id_dokter)
    // console.log(id_condition)
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/updateconditiondok',
        data: {
            id_dokter: id_dokter,
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
        url: baseURL + '/api/updateconditionseluruhdokter',
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