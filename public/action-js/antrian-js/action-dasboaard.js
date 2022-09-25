jumlahpasien();
function jumlahpasien() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadjumlahpasien',
        data: {

        },
        success: function (result) {
            // console.log(result)
            var data = result.data;
            // console.log(data[0].count);
            $("#jumlah_pasien").html(data[0].count);
            // document.getElementById("jumlah_pasien") = ;
        }

    });
}

pasiendalamantrian();
function pasiendalamantrian() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadpasiendalamantrian',
        data: {

        },
        success: function (result) {
            // console.log(data[0].count)
            var data = result.data;
            // console.log(data[0].count);
            $("#pasien_antrian").html(data[0].count);
            // document.getElementById("jumlah_pasien") = ;
        }

    });
}

pasienterlewat();
function pasienterlewat() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadpasienterlewat',

        success: function (result) {
            // console.log(data)
            var data = result.data;
            // console.log(data[0].count);
            $("#pasien_terlewat").html(data[0].count);
            // document.getElementById("jumlah_pasien") = ;
        }

    });
}
jumlahterlayani()
function jumlahterlayani() {

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/loadterlayani',

        success: function (result) {
            // console.log(data)
            var data = result.data;
            // console.log(data[0].count);
            $("#jumlah_terlayani").html(data[0].count);
            // document.getElementById("jumlah_pasien") = ;
        }

    });
}