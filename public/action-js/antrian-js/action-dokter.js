loaddokter();
function loaddokter() {

    //let $id_dokter = $('#dokter').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/tampildokter',

        success: function (result) {
            // console.log(result);
            //console.log(result.data);
            let data = result.data;
            let counter = 0;
            // console.log(data[]['nama_dokter'])
            // console.log(result);
            for (dokter in data) {
                var cols = "";
                y = '';
                y += ` <div class="col">
                    <div class="card-hide" style="height:330px;width:200px;  " >
                    <div class="card-body text-center " >
                        <h5 class="card-title "> <img style="width:180px; height:200px;" src="/tamplate/img/doctor/`+ data[counter]['image_dokter'] + `" ></h5>
                            <h4 class="card-text font-weight-bold">`+ data[counter]['nama_dokter'] + `</h4>
                            <h6 class="card-text">`+ `Kode Dokter : ` + data[counter]['kode_dokter'] + `</h6> 
                            <h5 class="card-text">`+ `Poli  ` + data[counter]['nama_poli'] + `</h5> 
                           
                    </div>
                    
                    </div>
                    <br>
                </div>`;
                counter++;

                $(".dokter").append(y);
            }

        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
}