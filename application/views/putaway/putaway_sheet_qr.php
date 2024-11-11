<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putaway Sheet with QR Code</title>
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
        <h3 class="text-center ">Putaway Sheet</h3>

        <?php
        // Contoh data header
        $putaway_number = $rcv->putaway_number;
        $receive_number = $rcv->receive_number;

        $data = $rcv_detail;
        ?>

        <div class="row mb-4 mt-3">
            <div class="col text-center">
                <strong>Putaway Number:</strong> <?php echo $putaway_number; ?><br>
                <canvas id="qrPutawayNumber" class="qrcode"></canvas>
            </div>
            <div class="col text-center">
                <strong>Receive Number:</strong> <?php echo $receive_number; ?><br>
                <canvas id="qrReceiveNumber" class="qrcode"></canvas>
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 25%;">Item Code</th>
                    <th style="width: 25%;">LPN Number</th>
                    <th style="width: 10%;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach ($data as $item): $no++; ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td>
                            <canvas id="qrItemCode<?php echo $no; ?>" class="qrcode"></canvas>
                            <p><?php echo $item['item_code']; ?></p>
                        </td>
                        <td>
                            <canvas id="qrLpnNumber<?php echo $no; ?>" class="qrcode"></canvas>
                            <p><?php echo $item['lpn_number']; ?></p>
                        </td>
                        <td><?php echo $item['qty']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="print-button">
            <button class="btn btn-primary" onclick="printAndClose()">Print</button>
        </div>
    </div>

    <script>
        // Generate QR Code for Putaway and Receive Numbers
        new QRious({
            element: document.getElementById("qrPutawayNumber"),
            value: "<?php echo $putaway_number; ?>",
            size: 60
        });
        new QRious({
            element: document.getElementById("qrReceiveNumber"),
            value: "<?php echo $receive_number; ?>",
            size: 60
        });

        // Generate QR Codes for each item
        <?php $no=1; foreach ($data as $item): $no++; ?>
            new QRious({
                element: document.getElementById("qrLocation<?php echo $no; ?>"),
                value: "<?php echo $item['receive_location']; ?>",
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