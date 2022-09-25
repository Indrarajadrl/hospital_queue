
Loadpasien();

function Loadpasien() {
    var id_pol = id_poli;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianmiss',
        data: {
             id: id_pol

        },

        success: function (result) {
            console.log(result);

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
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].no_antrian + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].status_name + '</td>';
                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center"> <span class="btn btn-primary btn-xs"   onClick="actionqueue(' + data[x].id_pasien + "," + data[x].id_dokter + ')">Update</span></div> </td>';

                    console.log(data[x].id_pasien);
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
                    { width: 50, targets: 1 },
                    { width: 130, targets: 2 },
                    { width: 130, targets: 3 },
                    { width: 130, targets: 4 },
                    { width: 130, targets: 5 },
                    { width: 100, targets: 6 },
                ],

            });
        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }
    });
}

function actionqueue(idpasien, iddokter) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianmiss',
        data: {
            iidpasien: idpasien,
        },
        success: function (result) {
            // console.log(result);
            let nama = result.data[0].nama;
            let no = result.data[0].no_antrian;
            let poli = result.data[0].nama_poli;
            let dokter = result.data[0].nama_dokter;
            swal({
                title: "Apakah yakin mengupdate data antrian dengan Nama '" + nama + "' di Poli '" + poli + "' dan Nama Dokter '" + dokter + "' ? ",
                type: "warning",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
            },
                function () {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: baseURL + '/api/updateantrianmiss',
                        data: {
                            iddok: iddokter,
                            iidpasien: idpasien,
                        },
                        success: function (result) {
                            console.log()
                            if (result.code == 0) {
                                swal({
                                    title: "",
                                    text: "Data Berhasil Diupdate!",
                                    icon: "succes",
                                    button: "ok",
                                }, function () {
                                    location.reload();
                                });

                            } else {
                                swal({
                                    title: "",
                                    text: "Data Gagal Diupdate!",
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

