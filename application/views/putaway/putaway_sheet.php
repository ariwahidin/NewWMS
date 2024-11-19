<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Putaway Sheet with Barcode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
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

        .barcode {
            width: 120px;
            height: 40px;
            display: block;
            margin: 0 auto;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
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
        <h3 class="text-center">Putaway Sheet</h3>

        <?php
        // Contoh data header
        $putaway_number = $rcv->putaway_number;
        $receive_number = $rcv->receive_number;

        $data = $rcv_detail;
        ?>

        <div class="row mb-4 mt-3">
            <div class="col text-center">
                <strong>Putaway Number:</strong> <?php echo $putaway_number; ?><br>
                <canvas id="barcodePutawayNumber" class="barcode"></canvas>
            </div>
            <div class="col text-center">
                <strong>Receive Number:</strong> <?php echo $receive_number; ?><br>
                <canvas id="barcodeReceiveNumber" class="barcode"></canvas>
            </div>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 25%;">Item Code</th>
                    <th style="width: 10%;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($data as $item): ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td>
                            <canvas id="barcodeItemCode<?php echo $no; ?>" class="barcode"></canvas>
                            <p><?php echo $item['item_code']; ?></p>
                        </td>
                        <td><?php echo $item['qty']; ?></td>
                    </tr>
                <?php $no++;
                endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Generate barcodes for header fields
            JsBarcode("#barcodePutawayNumber", '<?php echo $putaway_number; ?>', {
                format: "CODE128",
                displayValue: false
            });
            JsBarcode("#barcodeReceiveNumber", '<?php echo $receive_number; ?>', {
                format: "CODE128",
                displayValue: false
            });

            // Generate barcodes for each item in the table
            <?php $no = 1;
            foreach ($data as $item): ?>
                JsBarcode("#barcodeItemCode<?php echo $no; ?>", '<?php echo $item['item_code']; ?>', {
                    format: "CODE128",
                    displayValue: false
                });
                <?php $no++; ?>
            <?php endforeach; ?>
        });
    </script>
</body>

</html>