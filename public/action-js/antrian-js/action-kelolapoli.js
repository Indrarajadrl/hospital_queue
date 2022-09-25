function reset() {
    document.getElementById("myForm").reset();

}

Loadpoli(null);
function Loadpoli() {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapoli',

        success: function (result) {
            // console.log(result);

            $('table.table-bordered > tbody').empty();

            console.log(result);

            if (result.code == 0) {

                let data = result.data;
                let counter = 1;

                for (x in data) {
                    // console.log(data);
                    // var newRow = $("<tr>");
                    var cols = "";
                    cols += '<tr>'
                    cols += '<td class="">' + counter + '</td>';
                    // cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].id_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 ">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 ">' + data[x].password + '</td>';
                    cols += '<td class="tdCenterText bgtd1 ">' + data[x].kode_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 ">' + data[x].deskripsi_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 ">' + data[x].image_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" ><span style="padding: 7px;" class="btn btn-success btn-xs" data-toggle="modal" data-target="#editData" onClick="loadedit(' + data[x].id_poli + ')">Edit</span> <span style="padding: 7px;"class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_poli + ')">Hapus</span></div></td>';
                    cols += '</tr>'
                    //newRow.append(cols);
                    $("#tablepoli").append(cols);
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
                    { width: 100, targets: 1 },
                    { width: 100, targets: 2 },
                    { width: 80, targets: 3 },
                    { width: 200, targets: 4 },
                    { width: 80, targets: 5 },
                    { width: 100, targets: 6 },
                ],

            });
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}


$('#tambah').on('click', function (e) {

    e.preventDefault();
    let nama_poli = $('#nama_poli').val();
    let kode_poli = $('#kode_poli').val();
    let deskripsi_poli = $('#deskripsi_poli').val();
    const fileupload = $('#gambar_poli').prop('files')[0];
    // console.log(fileupload.name)

    if (nama_poli == null || nama_poli == "") {
        bootbox.alert({ message: 'Nama poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (kode_poli == null || kode_poli == "") {
        bootbox.alert({ message: 'Kode poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (deskripsi_poli == null || deskripsi_poli == "") {
        bootbox.alert({ message: 'Deskripsi poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (fileupload == null || fileupload == "") {
        bootbox.alert({ message: ' Gambar tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (kode_poli.toString().length > 5) {
        bootbox.alert({ message: 'Kode Poli tidak boleh lebih dari 5 angka', centerVertical: true });
        return false;
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 1) {
        bootbox.alert({ message: 'Nama poli sudah ada!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 2) {
        bootbox.alert({ message: 'Kode poli sudah ada!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 3) {
        bootbox.alert({ message: 'Deskripsi Poli sudah ada!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload.name) == 4) {
        bootbox.alert({ message: 'Gambar Poli sudah ada!', centerVertical: true });
    }
    else if (validasigambar() == 1) {
        bootbox.alert({ message: 'File Harus berupa gambar', centerVertical: true });
    } else {
        savedatapoli();
    }


})

function savedatapoli() {
    let nama_poli = $('#nama_poli').val();

    let kode_poli = $('#kode_poli').val();
    let deskripsi_poli = $('#deskripsi_poli').val();
    let password = $('#password').val();
    const fileupload = $('#gambar_poli').prop('files')[0];
    let formData = new FormData();

    formData.append('inama_poli', nama_poli);
    formData.append('ipassword', password);
    formData.append('ikode_poli', kode_poli);
    formData.append('ideskripsi_poli', deskripsi_poli);
    formData.append('fileupload', fileupload); // set file ke tipe data binary
    console.log(id_poli)

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/savekelolapoli',
        data: formData,
        cache: false,
        processData: false,
        contentType: false,

        success: function (response) {


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


function validasigambar() {
    var exist = 0;

    let inputString = $('#gambar_poli').val();
    let inputStringedt = $('#gambar_poliedt').val();

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

function validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapoli',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {

            var data = result.data;
            console.log(data)
            var counternama = 0;
            var counterkode = 0;
            var counterdesk = 0;
            var counterimage = 0;


            for (x in data) {
                if (nama_poli == data[x]['nama_poli']) {
                    exist = 1;
                }
                if (kode_poli == data[x]['kode_poli']) {
                    exist = 2;
                }
                if (deskripsi_poli == data[x]['deskripsi_poli']) {
                    exist = 3;

                }
                if (fileupload == data[x]['image_poli']) {
                    exist = 4;
                }
                if (nama_poli == data[x]['nama_poli']) {
                    counternama += 1;
                    if (counternama > 1) {
                        exist = 5;
                    }

                }
                if (kode_poli == data[x]['kode_poli']) {
                    counterkode += 1;
                    if (counterkode > 1) {
                        exist = 6;
                    }
                }
                if (deskripsi_poli == data[x]['deskripsi_poli']) {
                    counterdesk += 1;
                    if (counterdesk > 1) {
                        exist = 7;
                    }
                }
                if (fileupload == data[x]['image_poli']) {
                    counterimage += 1;
                    if (counterimage > 1) {
                        exist = 8;
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
    let id_poli = $('#id_poli').val();
    let iduser = $('#iduser').val();
    console.log(iduser)
    let nama_poli = $('#nama_poliEdt').val();
    let kode_poli = $('#kode_poliEdt').val();
    let deskripsi_poli = $('#deskripsi_poliEdt').val();
    const fileupload = $('#gambar_poliedt').prop('files')[0];
    console.log(fileupload);

    if (nama_poli == null || nama_poli == "") {
        bootbox.alert({ message: 'Nama poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (kode_poli == null || kode_poli == "") {
        bootbox.alert({ message: 'Kode poli tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (deskripsi_poli == null || deskripsi_poli == "") {
        bootbox.alert({ message: 'Deskripsi poli tidak boleh kosong', centerVertical: true });
        return false;
    }

    else if (kode_poli.toString().length > 5) {
        bootbox.alert({ message: 'Kode Poli tidak boleh lebih dari 5 angka', centerVertical: true });
        return false;
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 5) {
        bootbox.alert({ message: 'Nama poli harus berbeda!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 6) {
        bootbox.alert({ message: 'Kode poli harus berbeda!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 7) {
        bootbox.alert({ message: 'Deskripsi Poli harus berbeda!', centerVertical: true });
    }
    else if (validasipoli(nama_poli, kode_poli, deskripsi_poli, fileupload) == 8) {
        bootbox.alert({ message: 'Gambar Poli harus berbeda!', centerVertical: true });
    }
    else if (validasigambar() == 2) {
        if (fileupload == null || fileupload == '') {
            editdatapoli(id_poli)
        } else {
            bootbox.alert({ message: 'File Harus berupa gambar', centerVertical: true });
        }
    } else {
        editdatapoli(id_poli)

    }

})

function editdatapoli(id_poli) {

    let nama_poli = $('#nama_poliEdt').val();
    let kode_poli = $('#kode_poliEdt').val();
    let deskripsi_poli = $('#deskripsi_poliEdt').val();
    let password = $('#passwordedt').val();
    let iduser = $('#iduser').val();
    const fileupload = $('#gambar_poliedt').prop('files')[0];
    let formData = new FormData();
    formData.append('id_poli', id_poli);
    formData.append('iduser', iduser);
    // console.log(fileuploadedt)
    formData.append('inama_poli', nama_poli);
    formData.append('ikode_poli', kode_poli);
    formData.append('ipassword', password);
    formData.append('ideskripsi_poli', deskripsi_poli);
    formData.append('fileupload', fileupload); // set file ke tipe data binary

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/editkelolapoli',
        async: false,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,

        success: function (response) {

            if (response['code'] == 0) {
                $('#editData').modal('hide');
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


function loadedit(param) {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapoli',
        data: {
            id: param,
        },
        success: function (result) {
            console.log(result);
            // $('.loaddata').empty();



            if (result.code == 0) {

                let data = result.data;

                $('#id_poli').val(data[0].id_poli);
                $('#iduser').val(data[0].iduser);
                $('#passwordedt').val(data[0].password);
                // console.log(data[0].iduser)
                $('#nama_poliEdt').val(data[0].nama_poli);
                $('#nama_poli2').val(data[0].nama_poli);
                $('#kode_poliEdt').val(data[0].kode_poli);
                $('#deskripsi_poliEdt').val(data[0].deskripsi_poli);

            } else {
                alert(result.info);
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
        url: baseURL + '/api/loadkelolapoli',
        data: {
            id: param,
        },
        success: function (result) {
            // console.log(result.data[0].nama_poli);
            let nama_poli = result.data[0].nama_poli;
            swal({
                title: "Apakah yakin mengahapus poli '" + nama_poli + "'? \nJika anda menghapus Poli ini \nmaka Dokter Dan Ruangan \ndengan nama poli '" + nama_poli + "' akan terhapus",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/deletekelolapoli',
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
                        }
                    });
                }
            );

        }
    });

}


function changetype() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
function changetypee() {
    var x = document.getElementById("passwordedt");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}