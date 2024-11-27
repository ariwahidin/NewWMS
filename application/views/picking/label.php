<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Multiple Labels</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        /* General Reset */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Page Layout */
        .page {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
        }

        /* Label Design */
        .label {
            width: calc(50% - 10px);
            /* Dua label per baris */
            border: 1px solid #000;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            page-break-inside: avoid;
            /* Hindari pemotongan label */
        }

        .label h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .label p {
            font-size: 14px;
            margin: 5px 0;
        }

        .label img {
            display: block;
            margin: 10px auto 0;
        }

        /* Item List Styling */
        .item-list {
            list-style-type: square;
            padding-left: 20px;
            margin: 10px 0;
        }

        .item-list li {
            font-size: 14px;
            margin: 3px 0;
        }

        .barcode {
            width: 140px;
            height: 70px;
            /* posisi bacode di pojok kanan atas agak di tengah */
            float: right;
            margin-top: 10px;

        }

        /* Print Optimization */
        @media print {
            body {
                background: none;
            }

            .page {
                padding: 0;
                margin: 0;
                margin-top: 20px;
                margin-left: 20px;
            }

            .label {
                width: calc(50% - 10px);
                margin-bottom: 10px;
                page-break-inside: avoid;
                /* Pastikan satu label utuh */
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <?php
        $box = 1;
        $total_boxes = $label->num_rows();
        foreach ($label->result() as $row) {
            $ctn = $row->carton;
        ?>
            <div class="label">
                <h2>
                    Order #: <?php echo $row->shipment_number; ?>
                    <canvas id="barcode<?php echo $ctn; ?>" class="barcode"></canvas>

                </h2>
                <p><strong>From:</strong> Yusen Logistics<br>Jl. Cakung Cilincing<br>Jakarta Utara 12345</p>

                <!-- customer -->
                <p><strong>To:</strong> <?= $row->customer ?><br><?= $row->customer_name ?><br><?= $row->ship_to_address1 ?></p>
                <p><strong>Box:</strong> <?= $box ?> of <?= $total_boxes ?></p>
                <p><strong>Items:</strong></p>
                <ul class="item-list">

                    <?php
                    $no = 1;
                    foreach (getItemPacked($row->shipment_number, $ctn)->result() as $item) {
                    ?>
                        <li><?php echo $item->item_code; ?> (<?= $item->qty_in . " " . $item->uom ?>)</li>
                    <?php
                        $no++;
                    }
                    ?>
                </ul>
            </div>
        <?php
            $box++;
        }
        ?>
    </div>
    <script>
        <?php
        foreach ($label->result() as $row2) {
            $ctn2 = $row2->carton;
        ?>
            JsBarcode("#barcode" + '<?php echo $ctn2 ?>', '<?php echo $ctn2 ?>', {
                format: "CODE128",
                displayValue: true
            });
        <?php
        }
        ?>
    </script>
</body>

</html>