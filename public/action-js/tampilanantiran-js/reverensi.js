if (result.code != 0) {

    bootbox.alert({ message: "No rekam medis atau NIK tidak sesuai!", centerVertical: true });
}
else if (result.code == 0) {
    console.log(result.data['datapasien'][0]['no_rekam_medis']);
    // alert('berhasil');
    if (result.data['datapasien'][0]['no_rekam_medis'] == null) {
        let $id = result.data['datapasien'][0]['id_pasien'];
        let $encodedId = btoa($id);
        window.location.href = ("datapasien/" + $encodedId);
    }
    if (result.data['datapasien'][0]['no_rekam_medis'] != null && (result.data['datapasien'][0]['status_code'] == 10)) {
        let $idantrian = result.data['datapasien'][0]['id_antrian'];
        let $encodedIdantrian = btoa($idantrian)

        let no_rekam_medis = result.data['datapasien'][0]['no_rekam_medis'];
        let nama = result.data['datapasien'][0]['nama'];
        swal({
            title: " Nama pasien '" + nama + "' sedang dalam antrian. \n Apakah anda ingin mencetak no antrian lagi ? ",
            type: "error",
            confirmButtonClass: "success",
            confirmButtonText: "Yes!",
            showCancelButton: true,
        }, function () {
            window.location = (baseURL + "/queue/cetakantrian/" + $encodedIdantrian);

        });
    } else if (result.data['datapasien'][0]['status_code'] == 20) {
        bootbox.alert({ message: "Pasien Sedang  Dalam Antrian", centerVertical: true });

    } else if (result.data['datapasien'][0]['status_code'] == 30) {
        bootbox.alert({ message: "Pasien Sedang  Dalam Panggilan Antrian", centerVertical: true });

    } else if (result.data['datapasien'][0]['status_code'] == 40) {
        bootbox.alert({ message: "Pasien Sedang Diperika Dokter", centerVertical: true });

    } else if (result.data['datapasien'][0]['status_code'] == 50) {
        bootbox.alert({ message: "Pasien Telah Selesai Diperiksa", centerVertical: true });

    } else if (result.data['datapasien'][0]['status_code'] == 60) {
        bootbox.alert({ message: "Pasien Sedang Telah Terlewat Silahkan Hubungi Petugas", centerVertical: true });
    }

    // } else if (result.data['validasiinput'] == 1) {
    //     bootbox.alert({ message: "No Induk Kependudukan tidak sesuai", centerVertical: true });

} else {
    // bootbox.alert({ message: "Pasien Tidak Terdaftar <br> Silahkan Daftar Terlebih Dahulu", centerVertical: true });

}