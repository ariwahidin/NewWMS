<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <title>Surat Perintah Kirim</title>
    <style type="text/css">
        body {
            font-family: Tahoma, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 850px;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px double black;
            padding: 10px 0;
        }

        .title {
            /* text-align: center; */
            font-size: 14px;
            font-weight: bold;
            /* text-decoration: underline; */
        }

        .info {
            margin: 20px 0;
            font-size: 14px;
            display: flex;
            gap: 20px;
        }

        .info label {
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .remarks {
            margin-top: 20px;
            font-size: 14px;
        }

        .contact {
            max-width: 320px;
            border: 1px solid #000;
            padding: 10px;
            /* margin-top: 20px; */
        }

        .footer {
            margin-top: 30px;
            border-top: 2px solid #000;
            text-align: left;
            padding-top: 10px;
        }

        #logo {
            float: left;
            margin-right: 10px;
            height: 45px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div id="">
            <img id="logo" src="<?= base_url('jar/html/default/') ?>assets/images/yusen-kotak.jpg" alt="Header">
        </div>

        <div class="title">
            PT. PUNINAR YUSEN LOGISTICS <br>
            <span style="font-size: 12px;">Jl. Inspeksi Cakung Drain, Cilincing, North Jakarta City, Jakarta 14130, Indonesia</span>
        </div>
        <div class="header">
        </div>

        <h1 style="text-align: center; font-size: 18px;">Surat Perintah Kirim</h1>



        <div class="info">

            <div class="col-info-1">
                <table style="white-space: nowrap;">
                    <tr>
                        <td>No SPK/Order</td>
                        <td>:</td>
                        <td><?= $header->spk_number ?></td>
                    </tr>
                    <tr>
                        <td>Tgl Order</td>
                        <td>:</td>
                        <td><?= $header->order_date ?></td>
                    </tr>
                    <tr>
                        <td>Transporter</td>
                        <td>:</td>
                        <td><?= $header->transporter_name ?></td>
                    </tr>
                    <tr>
                        <td>Tipe Order/Batch</td>
                        <td>:</td>
                        <td><?= $header->order_type ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Truck</td>
                        <td>:</td>
                        <td><?= $header->truck_name ?></td>
                    </tr>
                    <tr>
                        <td>Nopol</td>
                        <td>:</td>
                        <td><?= $header->truck_no ?></td>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <td>:</td>
                        <td><?= $header->remarks ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-info-2">
                <table style="white-space: nowrap;">
                    <tr>
                        <td>Tgl Muat</td>
                        <td>:</td>
                        <td><?= date('Y-m-d', strtotime($header->spk_date)) ?></td>
                    </tr>
                    <tr>
                        <td>Jam Mulai Muat</td>
                        <td>:</td>
                        <td><?= date('H:i:s', strtotime($header->start_loading)) ?></td>
                    </tr>
                    <tr>
                        <td>Jam Selesai Muat</td>
                        <td>:</td>
                        <td><?= date('H:i:s', strtotime($header->finish_loading)) ?></td>
                    </tr>
                    <tr>
                        <td>Tgl tiba di customer</td>
                        <td>:</td>
                        <td><?= date('Y-m-d', strtotime($header->truck_arival_date)) ?></td>
                    </tr>
                    <tr>
                        <td>Jam tiba di customer</td>
                        <td>:</td>
                        <td><?= date('H:i:s', strtotime($header->truck_arival_time)) ?></td>
                    </tr>
                    <tr>
                        <td>Jam keluar di customer</td>
                        <td>:</td>
                        <td></td>
                    </tr>

                </table>
            </div>
            <div class="col-info-3">
                <div class="contact">
                    <p>Jika saat pengiriman terjadi masalah, harap menghubungi nomor telepon PIC Control Tower teams dibawah ini:</p>
                    <p>AFRIYANTO: 0877-5362-0458</p>
                    <p>ROBIN: 0821-6498-9960</p>
                </div>
            </div>
        </div>

        <div>
            <h4>Kelengkapan Dokumen Berangkat</h4>
            <h4>Kelengkapan Dokumen Kembali</h4>
        </div>


        <table class="info-table">
            <thead>
                <tr>
                    <th>Ship To</th>
                    <th>Ship To Name</th>
                    <th>City</th>
                    <th>DO</th>
                    <th>Total Box/Koli</th>
                    <th>Total Qty Pcs</th>
                    <th>Total Volume</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $no = 1;
                foreach ($detail as $key  => $data) {
                ?>
                    <tr>
                        <td>
                            <?php
                            if ($key == 0 || $detail[$key - 1]->ship_to != $data->ship_to) {
                                echo $data->ship_to;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            // Cek apakah $key lebih dari 0 sebelum melakukan pengecekan pada $detail[$key - 1]
                            if ($key == 0 || $detail[$key - 1]->ship_to != $data->ship_to) {
                                echo $data->ship_to_name;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($key == 0 || $detail[$key - 1]->ship_to != $data->ship_to) {
                                echo $data->city;
                            }
                            ?>
                        </td>
                        <td><?= $data->do_number ?></td>
                        <td><?= $data->total_box ?></td>
                        <td><?= $data->total_pcs ?></td>
                        <td><?= $data->total_volume ?></td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>

        <div class="footer">
            <p>Terima kasih atas kerjasamanya.</p>
        </div>

    </div>
</body>

</html>