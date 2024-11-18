<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }

            body {
                margin: 0;
            }
        }

        .qrcode {
            width: 80px;
            height: 80px;
            display: block;
            margin: 0 auto;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            /* padding: 8px; */
        }

        .print-button {
            margin: 20px;
            text-align: center;
            display: none;
        }

    </style>
</head>

<body onload="print()">

    <div class="container mt-3">
        <h3 class="text-center ">Packing Sheet</h3>
        <?php
        // Contoh data header
        $picking_number = $picking->picking_number ?? '';
        $shipment_number = $picking->shipment_number ?? '';

        $data = $packing_detail;
        ?>


        <div class="row mb-4 mt-3">
            <div class="col">
                <table style="font-size: 14px;">
                    <tr>
                        <td>Shipment Number </td>
                        <td>:</td>
                        <td><?= $header->shipment_number ?></td>
                    </tr>
                    <tr>
                        <td>Shipment Date </td>
                        <td>:</td>
                        <td><?= $header->shipment_date ?></td>
                    </tr>
                    <tr>
                        <td>Start Loading Time </td>
                        <td>:</td>
                        <td><?= date('H:i:s', strtotime($header->start_loading)) ?? '' ?></td>
                    </tr>
                    <tr>
                        <td>Finish Loading Time </td>
                        <td>:</td>
                        <td><?= date('H:i:s', strtotime($header->finish_loading)) ?? '' ?></td>
                    </tr>
                    <tr>
                        <td>Ship Request Date </td>
                        <td>:</td>
                        <td><?= $header->ship_request_date ?></td>
                    </tr>
                    <tr>
                        <td>Remarks </td>
                        <td>:</td>
                        <td><?= $header->remarks ?></td>
                    </tr>
                </table>
            </div>
            <div class="col">
                <table style="font-size: 14px;">
                    <tr>
                        <td>Customer </td>
                        <td>:</td>
                        <td><?= $header->customer_name ?></td>
                    </tr>
                    <tr>
                        <td>Address </td>
                        <td>:</td>
                        <td>
                            <?= $header->ship_to_address1 ?>
                        </td>
                    </tr>
                    <tr>
                        <td>City </td>
                        <td>:</td>
                        <td><?= $header->ship_to_city ?></td>
                    </tr>
                    <tr>
                        <td>Trucker </td>
                        <td>:</td>
                        <td><?= $header->trucker_name ?></td>
                    </tr>
                    <tr>
                        <td>Truck Type </td>
                        <td>:</td>
                        <td><?= $header->truck_type ?></td>
                    </tr>
                    <tr>
                        <td>Driver </td>
                        <td>:</td>
                        <td><?= $header->driver_name ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mb-4 mt-3">
            <div class="col">
                <table class="table table-bordered" style="font-size: 14px; text-align:left !important;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Ctn No.</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        $total_req = 0;
                        foreach ($packing_detail->result() as $detail) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $detail->ctn ?></td>
                                <td><?= $detail->item_code ?></td>
                                <td><?= $detail->item_name ?></td>
                                <td><?= $detail->qty ?></td>
                            </tr>
                        <?php
                            $total_req += $detail->qty;
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Total</th>
                            <th><?= $total_req ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="print-button">
            <button class="btn btn-primary" onclick="printAndClose()">Print</button>
        </div>
    </div>

    <script>
        // Generate QR Code for Putaway and Receive Numbers
        new QRious({
            element: document.getElementById("qrPutawayNumber"),
            value: "<?php echo $picking_number; ?>",
            size: 60
        });
        new QRious({
            element: document.getElementById("qrReceiveNumber"),
            value: "<?php echo $shipment_number; ?>",
            size: 60
        });

        // Generate QR Codes for each item
        <?php $no = 1;
        foreach ($data as $item): $no++; ?>
            new QRious({
                element: document.getElementById("qrLocation<?php echo $no; ?>"),
                value: "<?php echo $item['location']; ?>",
                size: 60
            });
            new QRious({
                element: document.getElementById("qrItemCode<?php echo $no; ?>"),
                value: "<?php echo $item['item_code']; ?>",
                size: 60
            });
            new QRious({
                element: document.getElementById("qrLpnNumber<?php echo $no; ?>"),
                value: "<?php echo $item['lpn_number']; ?>",
                size: 60
            });
        <?php endforeach; ?>

        function printAndClose() {
            window.print();
        }

        // Close the tab after printing
        window.addEventListener("afterprint", () => {
            window.close();
        });
    </script>
</body>

</html>