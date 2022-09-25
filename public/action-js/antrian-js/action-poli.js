loadpoli();
function loadpoli() {
    // console.log(id)
    //let $id_dokter = $('#dokter').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/tampilpoli',
        data: {
            id: id

        },

        success: function (result) {
            // console.log(result);
            //console.log(result.data);

            let data = result.data;
            let counter = 0;

            //Poli Seluruh
            for (poli in data) {
                var cols = "";
                y = '';
                y += ` <div class="col-lg-4">
                            <div class="card" style="height:330px;width:300px;  " >
                                <div class="card-body text-center " >
                                    <h5 class="card-title "> <img style="width:230px; height:200px;" src="/tamplate/img/poli/`+ data[counter]['image_poli'] + `" ></h5>
                                        <h4 class="card-text font-weight-bold">` + `Poli ` + data[counter]['nama_poli'] + `</h4>
                                        <h5 class="card-text">`+ `Kode Poli : ` + data[counter]['kode_poli'] + `</h5> 
                                        <a class="card-text" href="datapoli?id=`+ data[counter]['id_poli'] + `">Deskripsi Poli Klik Disini! </a>
                                    
                                </div>
                            </div>
                            <br>
                        </div>`;
                counter++;

                $(".poli").append(y);
            }


            //DOKTER
            let data1 = result.data;
            let counter1 = 0;



            for (datapoli in data1) {
                // console.log(data[counter1])
                x = '';
                x += `
                <div class="card-hide" style="height:330px;width:200px;  "  >
                                <div class="card-body text-center " >
                                
                                <h5 class="card-title "> <img style="width:180px; height:200px; " src="/tamplate/img/doctor/`+ data1[counter1]['image_dokter'] + `" ></h5>
                                        <h4 class="card-text font-weight-bold">`+ `Nama Dokter : ` + data1[counter1]['nama_dokter'] + `</h4>
                                        <h5 class="card-text">`+ `Kode Dokter : ` + data1[counter1]['kode_dokter'] + `</h5> 
                                    
                                </div>

                        </div>`;
                counter1++;

                $(".datapoli").append(x);
            }






        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
}

loaddatapoli()
function loaddatapoli() {
    // console.log(id)
    //let $id_dokter = $('#dokter').val();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseURL + '/api/tampildatapoli',
        data: {
            id: id

        },

        success: function (result) {

            let data2 = result.data;
            let counter2 = 0;
            z = '';
            z += `   <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-12 col-md-9">
                            <div class="card " style="min-height :430px">
                                <div class="card-body">

                                    <div class="row  " style="min-height :400px">
                                        <div class="col-lg-6  text-center mt-4  ">
                                            <img style="width:300px; height:350px;" src="/tamplate/img/poli/`+ data2[counter2]['image_poli'] + `">
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="p-5">
                                                <div class="text-center card-text font-weight-bold">
                                                     <h2 >` + `Poli ` + data2[counter2]['nama_poli'] + ` (` + data2[counter2]['kode_poli'] + `)` + ` </h2>
                                                </div>
                                                <hr>
                                                <div class= "text-center card-text" >
                                                     <h4 >` + `Poli ` + data2[counter2]['deskripsi_poli'] + ` </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                            <h2 class="text-center font-weight-bold">`+ `Dokter Poli  ` + data2[counter2]['nama_poli'] + `</h2>
                        </div>
                        </div>

                    </div>
                    <br>
                  `;
            counter2++;

            $(".DP").append(z);






        },
        error: function (xhr) {
            alert(xhr.status + '-' + xhr.statusText);
        }

    });
}

