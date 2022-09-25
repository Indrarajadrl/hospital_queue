function antrianadmin() {
    window.location.href = "antrianadmin";
}


LoadDadta();
function LoadDadta() {

    /* save data */
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadantrianregisterall',

        success: function (result) {
            // console.log(result);

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
                    cols += '<tr>'
                    cols += '<td class="">' + counter + '</td>';
                    // cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].id_register + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_poli + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_dokter + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].nama_ruang + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].antrian_all + '</td>';

                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].jam_mulai + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].waktu_antrian + '</td>';
                    cols += '<td class="tdCenterText bgtd1 tdBorder-left">' + data[x].create_date + '</td>';

                    cols += '<td class="tdCenterText bgtd1 "> <div class="text-center" >  <span style="padding: 7px;" class="btn btn-danger btn-xs"  onclick="Delete(' + data[x].id_register + ')">Hapus</span></div></td>';
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
                    { width: 100, targets: 8 },
                ],

            });
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
        url: baseURL + '/api/loadantrianregisterall',
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
                                bootbox.alert({ message: 'Data Kosong', centerVertical: true });

                            }
                        },
                        error: function (xhr) {
                            alert(xhr.status + '-' + xhr.statusText);
                        }
                    });
                },
                function () {
                    // This function will run if the user clicked "cancel"
                    window.location.href = "<?php echo Yii::$app->request->baseUrl;?>/todo/index/";
                }
            );

        }
    });
}
