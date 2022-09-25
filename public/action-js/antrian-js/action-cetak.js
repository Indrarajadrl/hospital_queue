
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
        '<h3>Jumlah yang sedang dalam antrian : ' + sisa_antrian + '</h3>' +
        '<h4>waktu : ' + new Date().toLocaleString("en-US", {timeZone: "Asia/Jakarta"}) + '</h4>' +
        '</th></tr></tbody></table></body></html>');
    w.window.print();
    w.document.close();
    w.window.onafterprint = function () {

        setTimeout(function () {
            w.close()
            window.location = baseURL + "/queue/beranda"
        }, 3000);
    }

})