Loadpasien()
function Loadpasien() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapasien',
        data: {

        },

        success: function (result) {
            // console.log(result);

            $('table.table-bordered > tbody').empty();
            // console.log(result);

            if (result.code == 0) {

                let data = result.data;
                // console.log(data);
                let counter = 1;

                for (x in data) {
                    var newRow = $("<tr>");
                    var cols = "";
                    // onClick="openmyprofile('+reg[x].userid+',\''+reg[x].named+'\')">
                    cols += '<td class="">' + counter + '</td>';
                    cols += '<td class="tdCenterText ">' + data[x].no_rekam_medis + '</td>';
                    cols += '<td class="tdCenterText">' + data[x].ktp + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].tempat_lahir + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].tanggal_lahir + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].alamat + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].no_hp + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].create_date + '</td>';
                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" ><span style="padding: 7px;" class="btn btn-success btn-xs" data-toggle="modal" data-target="#Editdata" onClick="loadedit(' + data[x].id_pasien + ')">Edit</span> <span style="padding: 7px;"class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_pasien + ')">Hapus</span></div></td>';

                    // console.log(data[x].id_pasien);
                    newRow.append(cols);
                    $("table.table-bordered").append(newRow);
                    counter++;

                    // $('.loaddata').append('' + x + '<span>' + data[x].nama + '</span>');
                }

            } else {
                bootbox.alert({ message: 'Data Antrian Kosong', centerVertical: true });
            }
            $('#dataTable').DataTable({
                "scrollY": '500px',
                "scrollX": true,
                fixedHeader: true,
                scrollCollapse: true,
                paging: true,
                columnDefs: [
                    { width: 30, targets: 0 },
                    { width: 80, targets: 1 },
                    { width: 130, targets: 2 },
                    { width: 130, targets: 3 },
                    { width: 130, targets: 4 },
                    { width: 130, targets: 5 },
                    { width: 130, targets: 6 },
                    { width: 130, targets: 7 },
                    { width: 110, targets: 8 },
                    { width: 100, targets: 9 },
                ],

            });
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}


$('#edit').on('click', function () {
    let id_pasien = $('#id_pasien').val();
    let ktp = $('#ktp').val();
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();

    if (nama == null || nama == "") {
        bootbox.alert({ message: 'Nama tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (ktp == null || ktp == "") {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (tempat_lahir == null || tempat_lahir == "") {
        bootbox.alert({ message: 'Tempat Lahir tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (tanggal_lahir == null || tanggal_lahir == "") {
        bootbox.alert({ message: 'Tanggal Lahir tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (alamat == null || alamat == "") {
        bootbox.alert({ message: 'Alamat tidak boleh kosong', centerVertical: true });
        return false;
    }
    else if (no_hp == null || no_hp == "") {
        bootbox.alert({ message: 'No HP tidak boleh kosong', centerVertical: true });
        return false;

    } else if (ktp.toString().length > 16) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh lebih dari 16 angka', centerVertical: true });
        return false;
    } else if (ktp.toString().length < 16) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) tidak boleh kurang dari 16 angka', centerVertical: true });
        return false;
    } else if (no_hp.toString().length > 13) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) HP tidak boleh lebih dari 13 angka', centerVertical: true });
        return false;
    }
    else if (validasipasien(ktp) == 1) {
        bootbox.alert({ message: 'No Induk Kependudukan (NIK) Sudah Ada!', centerVertical: true });
    } else {
        editdatapasien(id_pasien)
    }



})

function editdatapasien(id_pasien) {
    let ktp = $('#ktp').val();
    let nama = $('#nama').val();
    let tempat_lahir = $('#tempat_lahir').val();
    let tanggal_lahir = $('#tanggal_lahir').val();
    let alamat = $('#alamat').val();
    let no_hp = $('#no_hp').val();

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/editkelolapasien',
        data: {
            id_pasien: id_pasien,
            iktp: ktp,
            inama: nama,
            itempat_lahir: tempat_lahir,
            itanggal_lahir: tanggal_lahir,
            ialamat: alamat,
            ino_hp: no_hp,

        },

        success: function (response) {
            $('#Editdata').modal('hide');
            console.log(response);
            if (response['code'] == 0) {
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


function validasipasien(ktp) {

    var exist = 0;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapasien',
        async: false,
        data: {
            id: null,
        },
        success: function (result) {
            console.log(result)
            var data = result.data;
            var ktpcounter = 0;

            for (x in data) {
                if (ktp == data[x]['ktp']) {
                    ktpcounter += 1;
                    if (ktpcounter > 1) {
                        exist = 1;
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


function loadedit(id_pasien) {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapasien',
        data: {
            id_pasien: id_pasien,
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
                alert(result.info);
            }
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}

function Delete(id_pasien) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadkelolapasien',
        data: {
            id_pasien: id_pasien,
        },
        success: function (result) {
            console.log(result);
            let nama_pasien = result.data[0].nama;
            swal({
                title: "Apakah yakin mengahapus pasien '" + nama_pasien + "'? ",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/deletekelolapasien',
                        data: {
                            id_pasien: id_pasien,
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
                                alert('gagal');
                            }
                        },
                        error: function (xhr) {
                            alert(xhr.status + '-' + xhr.statusText);
                        }
                    });
                }
            );

        }
    });

}

