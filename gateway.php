<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jualan Madu - Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

<form method="POST">
    <div style="padding-top: 3rem; padding-bottom: 2rem;">
        <div class="container" style="background-color: #fafafa; padding: 25px; border-radius: 1.2rem;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0" style="background-color: #f3f2ee; border-radius: 1.2rem;">
                        <div class="card-body" style="padding: 3rem;">
                            <h3 class="font-weight-bold">Pilihan Pengiriman</h3>
                            <div class="progress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                <div class="progress-bar bg-dark" style="width: 50%"></div>
                            </div>
                            <div class="form-group pb-5">
                                <label for="exampleFormControlSelect1">Provinsi</label>
                                <select id="provinsi" class="form-control" name="provinsi">
                                    <option value="">Pilih Provinsi</option>
                                    <?php

                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "GET",
                                        CURLOPT_HTTPHEADER => array(
                                            "key: 405743274ec9d6588d323183dde189a1"
                                        ),
                                    ));

                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);

                                    curl_close($curl);

                                    $data = json_decode($response, true);

                                    foreach ($data['rajaongkir']['results'] as $result) {
                                        ?>
                                        <option value="<?php echo $result['province_id']; ?>"><?php echo $result['province']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group pb-5">
                                <label for="exampleFormControlSelect1">Kabupaten / Kota</label>
                                <select class="form-control" name="kabupaten" id="kabupaten">
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="form-group pb-5">
                                <label for="exampleFormControlSelect1">Layanan Kurir</label>
                                <select class="form-control" name="kurir">
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS Indonesia</option>
                                </select>
                            </div>
                            <div><button type="submit" name="submit" class="btn btn-primary">Cek Ongkos Kirim</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if (isset($_POST['submit'])) { ?>

    <form method="POST" action="checkout.php">
    <div style="padding-bottom: 3rem;">
        <div class="container" style="background-color: #fafafa; padding: 25px; border-radius: 1.2rem;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0" style="background-color: #f3f2ee; border-radius: 1.2rem;">
                        <div class="card-body" style="padding: 3rem;">
                            <h3 class="font-weight-bold">Pilihan Jenis Pengiriman</h3>
                            <div class="progress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="height: 5px">
                                <div class="progress-bar bg-dark" style="width: 50%"></div>
                            </div>
                            <input type="hidden" name="pengiriman" value="<?php echo $_POST['kurir']; ?>">
                            <?php
                            $provinsi = $_POST['provinsi'];
                            $kabupaten = $_POST['kabupaten'];
                            $kurir = $_POST['kurir'];


                            $curl = curl_init();

                            curl_setopt_array($curl, array(
                                CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 30,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "POST",
                                CURLOPT_POSTFIELDS => "origin=8&destination=$kabupaten&weight=1000&courier=$kurir",
                                CURLOPT_HTTPHEADER => array(
                                    "content-type: application/x-www-form-urlencoded",
                                    "key: 405743274ec9d6588d323183dde189a1"
                                ),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            $data = json_decode($response, true);

                            foreach ($data['rajaongkir']['results'][0]['costs'] as $cost) {
                                ?>
                                <div style="padding-bottom: 2rem;">
                                <table style="padding: 1rem; width: 100%; background-color: #fafafa; border-radius: 1.2rem;">
                                    <tr>
                                        <td style="width: 5%;"><input type="radio" name="ongkirx" value="<?php echo $cost['cost'][0]['value']; ?>"></td>
                                        <td style="width: 20%;">Layanan</td>
                                        <td>: <?php echo $cost['service']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Deskripsi</td>
                                        <td>: <?php echo $cost['description']; ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Waktu</td>
                                        <td>: <?php echo $cost['cost'][0]['etd']; ?> Hari</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Harga</td>
                                        <td>: Rp <?php echo number_format($cost['cost'][0]['value']); ?></td>
                                    </tr>
                                </table>
                                </div>
                                <?php
                            }
                            ?>
                        <div style="padding: 1rem 0 0 0;"><button type="submit" name="submit2" class="btn btn-primary">Lanjutkan</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
    <?php
} ?>

<!-- Footer Section End -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function(){
        $("#provinsi").change(function(){
            var provinsi_id = $(this).val();
            $.ajax({
                url: 'getkota.php',
                method: 'POST',
                data: {provinsi_id: provinsi_id},
                success: function(data){
                    $("#kabupaten").html(data);
                }
            });
        });
    });
</script>
</html>