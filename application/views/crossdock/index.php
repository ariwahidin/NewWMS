<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($_GET['edit']) && $order->created_by != $_SESSION['user_data']['username']) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert bg-danger border-danger text-white mb-3 mt-0" role="alert">
                <!-- alert untuk user yang bukan pembuat tugas ini jadi tidak boleh edit -->
                <strong>You are not the creator of this task, so you are not allowed to edit it, </strong> the creator is : <b><?= $order->created_by ?></b>

            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <a href="javascript:history.back()" class="btn btn-primary btn-sm mb-3"><i class="mdi mdi-keyboard-backspace"></i> Back</a>

        <?php if (isset($_GET['edit']) && $order->created_by == $_SESSION['user_data']['username']) { ?>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="generateSPK" disabled><i class="mdi mdi-content-save"></i> Save</button>
        <?php } ?>

        <?php if (!isset($_GET['edit'])) { ?>
            <button type="button" class="btn btn-primary btn-sm mb-3" id="generateSPK" disabled> <i class="mdi mdi-content-save"></i> Save</button>
        <?php } ?>
        <div class="card mb-3">
            <div class="card-header bg-primary d-none">
                <h5 class="card-title mb-0 text-white">Header Information</h5>
            </div>
            <div class="card-body headerInfo">
                <form id="formHeader">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td>Receive Number</td>
                                    <td>:</td>
                                    <td>
                                        <input type="hidden" id="prosesAction" value="<?= (isset($order)) && $order->receive_number ? 'edit' : 'add'; ?>">
                                        <input style="max-width: 100px" type="text" class="form-control-sm" id="spkNumber" placeholder="" value="<?= $order->receive_number ?? 'Auto Generated' ?>" readonly>
                                        <input style="max-width: 80px" type="hidden" class="form-control-sm d-inline" id="status" placeholder="" value="<?= $order->is_complete ?? '' ?>" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DO/ASN</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm required-input" id="loadNumber" placeholder="" value="<?= $order->ship_reff ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>PO Number</td>
                                    <td>:</td>
                                    <td>
                                        <?php $po_number = $order->po_number ?? ''; ?>
                                        <input type="text" class="form-control-sm required-input" id="poNumber" placeholder="" value="<?= $po_number ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Invoice No.</td>
                                    <td>:</td>
                                    <td>
                                        <?php $invoiceNumber = $order->invoice_number ?? ''; ?>
                                        <input type="text" class="form-control-sm required-input" id="invoiceNumber" placeholder="" value="<?= $invoiceNumber ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>SJ No.</td>
                                    <td>:</td>
                                    <td>
                                        <?php $SJNumber = $order->sj_number ?? ''; ?>
                                        <input type="text" class="form-control-sm required-input" id="SJNumber" placeholder="" value="<?= $SJNumber ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Receive Date</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $orderDate = $order->order_date ?? date('Y-m-d');
                                        $orderTime = isset($order) ? ($order->receive_time ? date('H:i', strtotime($order->receive_time)) : '') : '';
                                        ?>
                                        <input type="date" class="form-control-sm" id="orderDate" value="<?= $orderDate ?>" required>
                                        <input type="time" class="form-control-sm" id="orderTime" value="<?= $orderTime ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Unloading Date</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $spkDate = $order->unloading_date ?? date('Y-m-d');
                                        ?>
                                        <input type="date" class="form-control-sm" id="spkDate" value="<?= $spkDate ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Receive Status</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $shipmode = array(
                                            'Received',
                                            'Partially Received',
                                            'Not Received'
                                        );
                                        ?>
                                        <select class="form-control-sm required-input" id="receiveStatus">
                                            <?php
                                            foreach ($shipmode as $s) {
                                            ?>
                                                <option value="<?= $s ?>" <?= isset($order) ? (($order->receiving_status == $s) ? 'selected' : '') : ''  ?>><?= $s ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck Arrival</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $truckArrivalDate = $order->truck_arival_date ?? date("Y-m-d");
                                        $truckArrivalTime = isset($order->truck_arival_time) ? date('H:i', strtotime($order->truck_arival_time)) : '';
                                        ?>
                                        <input type="date" class="form-control-sm" id="truckArivalDate" value="<?= $truckArrivalDate ?>" placeholder="" required>
                                        <input type="time" class="form-control-sm" id="truckArivalTime" value="<?= $truckArrivalTime ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Start/Finish Unloading</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $startLoading = isset($order->start_loading) ? date('H:i', strtotime($order->start_loading)) : '';
                                        $finishLoading = isset($order->finish_loading) ? date('H:i', strtotime($order->finish_loading)) : '';
                                        ?>
                                        <input type="time" class="form-control-sm" id="startLoading" placeholder="" value="<?= $startLoading ?>" required>
                                        <input type="time" class="form-control-sm" id="finishLoading" placeholder="" value="<?= $finishLoading ?>" required>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td>Supplier</td>
                                    <td>:</td>
                                    <td style="white-space: nowrap;">

                                        <?php
                                        $supplier_id = $order->supplier_id ?? '';
                                        $supplier_code = $order->supplier_code ?? '';
                                        $supplier_name = $order->supplier_name ?? '';
                                        ?>

                                        <input type="hidden" id="supplierID" value="<?= $supplier_id ?>">
                                        <input style="max-width: 120px;" type="text" class="form-control-sm required-input" id="supplierCode" value="<?= $supplier_code ?>" placeholder="" readonly>
                                        <input style="width: 220px;" type="text" class="form-control-sm required-input" id="supplierName" value="<?= $supplier_name ?>" placeholder="" readonly>
                                        <button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalSupplier">search</button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Transporter</td>
                                    <td>:</td>
                                    <td style="white-space: nowrap;">

                                        <?php
                                        $transporter_id = $order->transporter_id ?? '';
                                        $transporter_code = $order->ekspedisi_code ?? '';
                                        $transporter_name = $order->ekspedisi_name ?? '';
                                        ?>

                                        <input type="hidden" id="transporterID" value="<?= $transporter_id ?>">
                                        <input style="max-width: 120px;" type="text" class="form-control-sm" id="transporter" value="<?= $transporter_code ?>" placeholder="" readonly>
                                        <input style="width: 220px;" type="text" class="form-control-sm" id="transporter_name" value="<?= $transporter_name ?>" placeholder="" readonly>
                                        <button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">search</button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck Type</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $truckType = $order->truck_type ?? '';
                                        ?>

                                        <select class="form-control-sm" id="truckType">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            foreach ($truck->result() as $data) {
                                            ?>
                                                <option value="<?= $data->truck_name ?>" <?= ($data->truck_name == $truckType) ? 'selected' : ''; ?>> <?= $data->truck_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck No</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="truckNo" value="<?= $order->truck_no ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Driver Name</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="driverName" value="<?= $order->driver_name ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Driver Phone</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="driverPhone" value="<?= $order->driver_phone ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Container No.</td>
                                    <td>:</td>
                                    <td>
                                        <?php $containerNo = $order->container_no ?? ''; ?>
                                        <input type="number" class="form-control-sm" id="containerNo" value="<?= $containerNo ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Remarks</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="remarks" placeholder="" value="<?= $order->remarks ?? '' ?>" required>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card headerInfo">
            <div class="card-header bg-default">
                <strong>Total Selected Items: <span id="selectedOrdersCount">0</span></strong>
                <button type="button" class="btn-sm bg-warning float-end" data-bs-toggle="modal" data-bs-target="#modalAvailableOrder">List Item</button>
            </div>
            <div class="card-body table-responsive">
                <table style="white-space: nowrap; font-size: smaller;" class="table table-bordered table-sm table-striped" id="cartTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th class="d-none">LPN</th>
                            <th>Qty (Pcs)</th>
                            <th>Receive Location</th>
                            <th>Expiry Date</th>
                            <th>QA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ekspedisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Eks ID</th>
                            <th>Eks Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($ekspedisi->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->code ?></td>
                                <td><?= $data->name ?></td>
                                <td><button class="btn btn-primary btn-sm btnEkpedisi" data-id="<?= $data->id ?>" data-code="<?= $data->code ?>" data-name="<?= $data->name ?>">select</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModalSupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Supplier Code</th>
                            <th>Supplier Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($supplier->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->code ?></td>
                                <td><?= $data->name ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm btnSupplier"
                                        data-id="<?= $data->id ?>"
                                        data-code="<?= $data->code ?>"
                                        data-name="<?= $data->name ?>">select</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Order Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($type->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->code ?></td>
                                <td><?= $data->name ?></td>
                                <td><button class="btn btn-primary btn-sm btnType" data-id="<?= $data->id ?>" data-code="<?= $data->code ?>" data-name="<?= $data->name ?>">select</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAvailableOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning pb-4">
                <h5 style="color: white;" class="modal-title" id="exampleModalLabel">List Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">

                <input type="text" id="searchOrders" class="form-control-sm" placeholder="Search Order">
                <!-- <button class="btn-small float-end" id="btnRefresh">Refresh</button>y -->
                <br>
                <br>
                <div style="max-height: 360px;">
                    <strong>Total : <span id="totalDo">0</span> <span id="resultCount"></span></strong>
                    <table style="white-space: nowrap; font-size: smaller;" class="table table-sm table-bordered table-striped" id="tableShipments">
                        <thead class="bg-warning">
                            <tr style="white-space: nowrap;">
                                <th style="text-align: center;">Select</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jar/html/default/') ?>assets/libs/echarts/echarts.min.js"></script>

<script>
    $(document).ready(function() {

        let uom = [];
        let existingShipments = [];
        let selectedOrders = [];

        getOrder();

        function makeFieldsReadonly() {
            let status = $('#status').val();
            if (status == 'Y') {
                // document.querySelectorAll('input, select, button').forEach(element => {
                //     element.readOnly = true; // Untuk input
                //     element.disabled = true; // Untuk select
                // });

                // document.querySelectorAll('button').forEach(element => {
                //     element.style.display = 'none';
                // });

                document.querySelectorAll('.headerInfo').forEach(container => {
                    // Set input dan select di dalam .headerInfo menjadi readonly atau disabled
                    container.querySelectorAll('input, select').forEach(element => {
                        element.readOnly = true; // Untuk input
                        element.disabled = true; // Untuk select
                    });

                    // Sembunyikan semua button di dalam .headerInfo
                    container.querySelectorAll('button').forEach(button => {
                        button.style.display = 'none';
                    });
                });
            }
        }

        function getOrder() {


            let dataToPost = {};

            let prosesAction = $('#prosesAction').val();

            if (prosesAction == 'edit') {
                dataToPost.ib_no = $('#spkNumber').val();
            }

            $.ajax({
                url: '<?= site_url('receiving/getItems') ?>', // URL ke function di controller
                type: 'POST',
                data: dataToPost,
                dataType: 'json',
                success: function(data) {
                    let tableBody = $('#tableShipments tbody');

                    // Iterasi setiap data yang diterima dari server
                    let totalData = parseInt($('#totalDo').text());
                    $.each(data.shipments, function(index, order) {



                        // Cek apakah shipment_id sudah ada dalam tabel
                        if (!existingShipments.includes(order.shipment_id)) {
                            // Jika belum ada, tambahkan baris baru
                            let row = `<tr>
                                            <td style="align-content:center; text-align:center;">
                                                <input type="checkbox" class="order-checkbox" value="${order.item_code}" data-order='${JSON.stringify(order)}'/>
                                            </td>
                                            <td>${order.item_code}</td>
                                            <td>${order.item_name}</td>
                                        </tr>`;

                            tableBody.append(row); // Tambahkan baris ke tabel
                            existingShipments.push(order.item_code); // Tambahkan ID ke daftar existingShipments
                            totalData += 1; // Update jumlah data
                            $('#totalDo').text(totalData);
                        }
                        stopLoading();
                    });

                    if (prosesAction == 'edit') {
                        selectedOrders = data.shipment_current;

                        $.each(selectedOrders, function(index, order) {
                            order.item_code = order.item_code;
                        });

                        updateCartTable();

                        $.each(selectedOrders, function(index, order) {
                            $('#tableShipments input[value="' + order.item_code + '"]').prop('checked', true);
                        })

                        makeFieldsReadonly();
                    }
                }
            });
        }

        function getUom() {
            $.ajax({
                url: '<?= site_url('receiving/getUom') ?>',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    uom = response.data;
                    console.log(uom);
                    getOrder();
                }
            });
        }


        $('#searchOrders').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            let count = 0; // Variabel untuk menghitung baris yang ditemukan

            $('#tableShipments tbody tr').filter(function() {
                let match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
                if (match) count++; // Tambahkan 1 ke hitungan jika ditemukan
            });

            // Tampilkan hasilnya
            $('#resultCount').text('( ' + count + ' rows found )');

            if (value == '') {
                $('#resultCount').text('');
            }
        });

        $('.btnEkpedisi').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#transporterID').val(id);
            $('#transporter').val(code);
            $('#transporter_name').val(name);
            $('#exampleModal').modal('hide');
        })

        $('.btnSupplier').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#supplierID').val(id);
            $('#supplierCode').val(code);
            $('#supplierName').val(name);
            $('#exampleModalSupplier').modal('hide');
        })

        $('.btnType').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#orderTypeID').val(id);
            $('#orderType').val(code);
            $('#orderTypeName').val(name);
            $('#modalType').modal('hide');
        })




        // Function untuk mengupdate tampilan tabel cart
        function updateCartTable(item_code_selected = null, uncheck = false) {
            let cartBody = $('#cartTable tbody');
            let prosesAction = $('#prosesAction').val();

            $('#selectedOrdersCount').text(selectedOrders.length); // Update jumlah order

            if (uncheck) {
                $('#cartTable tbody tr').each(function() {
                    let shipmentIdInput = $(this).find('.in-item');
                    let shipmentId = shipmentIdInput.val();

                    if (shipmentId == item_code_selected) {
                        $(this).remove();
                    }
                });
                return;
            }

            $('#generateSPK').prop('disabled', false); // Aktifkan tombol Generate SPK jika ada order di cart
            $.each(selectedOrders, function(index, order) {

                let row = generateRow(index, order);

                if (item_code_selected != null) {

                    let isExist = false;

                    $('#cartTable tbody tr').each(function() {
                        let shipmentIdInput = $(this).find('.in-item');
                        let shipmentId = shipmentIdInput.val();
                        if (shipmentId == order.item_code) {
                            isExist = true;
                        }
                    });

                    if (!isExist) {
                        cartBody.append(row);
                        return false;
                    }

                } else {
                    cartBody.append(row);
                }

            });


        }

        function generateRow(index, order = {}) {

            const today = new Date();
            const nextYear = new Date(today.setFullYear(today.getFullYear() + 1));
            const formattedDate = nextYear.toISOString().split('T')[0];

            let expiry = formattedDate;

            return `
            <tr>
                <td>${index + 1}</td>
                <td>${order.item_code ?? ''}</td>
                <td>${order.item_name ?? ''}</td>
                <td class="d-none" ><input style="max-width: 80px;" type="text" class="form-control-sm in-lpn" value="${order.lpn_number ?? 'auto'}" readonly></td>
                <td>
                    <input type="hidden" class="form-control-sm in-id" value="${order.id ?? ''}">
                    <input type="hidden" class="form-control-sm in-item" value="${order.item_code ?? ''}">
                    <input type="hidden" class="form-control-sm in-item-name" value="${order.item_name ?? ''}">
                    <input style="max-width: 80px;" type="number" class="form-control-sm in-qty" value="${order.qty ?? '1'}">
                </td>
                <td><input type="text" class="form-control-sm in-rcv-loc" value="${order.receive_location ?? 'RECVDOCK'}" readonly></td>
                <td><input type="date" class="form-control-sm in-expiry" value="${order.expiry_date ?? expiry}"></td>
                <td><input style="max-width: 80px;" type="text" class="form-control-sm in-qa" value="A" readonly></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-order" data-id="${order.item_code ?? ''}"> <i class="ri-close-fill"></i> </button>
                    <button type="button" class="btn btn-primary btn-sm add-line d-none" data-id="${order.item_code ?? ''}"> <i class="ri-add-fill"></i> </button>
                </td>
            </tr>
        `;
        }


        $('#tableShipments').on('change', '.order-checkbox', function() {
            let order = JSON.parse($(this).attr('data-order'));
            if ($(this).is(':checked')) {
                // Tambahkan ke array selectedOrders
                selectedOrders.push(order);
                updateCartTable(order.item_code);
            } else {
                // Hapus dari array selectedOrders jika uncheck
                selectedOrders = selectedOrders.filter(o => o.item_code !== order.item_code);
                updateCartTable(order.item_code, true);
            }
            updateRowNumbers();
            console.log(selectedOrders);
        });

        // Event untuk menghapus order dari cart
        $('#cartTable').on('click', '.remove-order', function() {
            $(this).closest('tr').remove();
            let item_code = $(this).attr('data-id');
            $(`input[value="${item_code}"]`).prop('checked', false);
            selectedOrders = selectedOrders.filter(o => o.item_code !== item_code);
            updateRowNumbers();
        });


        // Function to update row numbers
        function updateRowNumbers() {
            $('#cartTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1); // Update the index in the first column
            });
        }

        // Saat tombol Generate SPK diklik
        $('#generateSPK').on('click', function() {

            let isFormValid = true;
            let emptyFields = [];

            // Get all required input fields
            document.querySelectorAll(".required-input").forEach(function(input) {
                if (input.value.trim() === "") {
                    isFormValid = false;
                    emptyFields.push(input.placeholder || "Select option");
                }
            });

            if (!isFormValid) {
                // Show SweetAlert if any field is empty
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    // text: 'Please fill in all fields: ' + emptyFields.join(", "),
                    text: 'Please fill in all fields',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'Do you want to proceed with the form submission?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirms, call the proccess() function
                        proccess();
                    } else {
                        // If the user cancels, show a message or handle accordingly
                        Swal.fire({
                            icon: 'info',
                            title: 'Cancelled',
                            text: 'The process has been cancelled.',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }
        });

        // $('#btnPutaway').on('click', function() {
        //     let putaway = true;
        //     proccess(putaway);
        // });


        function proccess(putaway = false) {
            if (selectedOrders.length > 0) {
                // startLoading();
                let orderIds = selectedOrders.map(order => order.shipment_id);

                const dataObj = {};

                // Mencari semua input dan select di dalam form
                $('#formHeader').find('input, select').each(function() {
                    const inputId = $(this).attr('id'); // Ambil ID
                    const inputValue = $(this).val(); // Ambil nilai

                    // Simpan dalam objek
                    dataObj[inputId] = inputValue;
                });

                // console.log(dataObj); // Untuk memeriksa objek hasil

                let suratJalanHeader = {
                    nomor: $('#suratJalanNumber').val(),
                    tanggal: $('#tanggalSuratJalan').val(),
                    pengirim: $('#namaPengirim').val(),
                    penerima: $('#namaPenerima').val()
                };

                let itemsDetail = collectCartItems();
                let prosesAction = $('#prosesAction').val();

                let url = '<?= site_url('receiving/createProccess') ?>';
                if (prosesAction == 'edit') {
                    url = '<?= site_url('receiving/editProccess') ?>';
                }

                $.ajax({
                    url: url, // URL ke function untuk create SPK
                    type: 'POST',
                    data: {
                        order_ids: orderIds,
                        header: dataObj,
                        items: itemsDetail
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {

                            stopLoading();


                            if (putaway) {
                                let ib_no = $('#spkNumber').val();
                                $.post('<?= site_url('putaway/create') ?>', {
                                    ib_no
                                }, function(response) {
                                    if (response.success == true) {
                                        window.location.href = 'receivingList';
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Failed',
                                            text: response.message
                                        });
                                    }
                                }, 'JSON');
                                return;
                            }



                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Data has been saved successfully!',
                                showCancelButton: true,
                                confirmButtonText: 'Print SPK',
                                cancelButtonText: 'Ok',
                                customClass: {
                                    confirmButton: 'btn btn-success me-2 d-none',
                                    cancelButton: 'btn btn-secondary'
                                },
                                buttonsStyling: false,
                                allowOutsideClick: false
                            }).then((result) => {
                                selectedOrders = []; // Kosongkan cart
                                updateCartTable(); // Refresh tampilan cart
                                $('#tableShipments input[type="checkbox"]').prop('checked', false);

                                if (result.isConfirmed) {
                                    $('body').empty();
                                    // printSpk(response.spk_number);
                                } else if (result.dismiss === Swal.DismissReason.cancel) {

                                    if (prosesAction == 'edit') {
                                        location.reload();
                                        return;
                                    }

                                    $('body').empty();
                                    window.location.href = 'receivingList';
                                }
                            });
                        } else {
                            stopLoading();
                            let textWarning = 'Failed to save data';
                            Swal.fire({
                                icon: 'warning',
                                title: 'Failed to save data',
                                text: response.message || textWarning,
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select at least one item.',
                    confirmButtonText: 'OK'
                })
            }
        }




        // Mengumpulkan detail item dari tabel cart saat ingin mengirim
        function collectCartItems() {
            let cartItems = [];

            // Iterasi setiap baris di dalam tabel cart
            $('#cartTable tbody tr').each(function() {
                let item_code = $(this).find('.in-item').val();
                let item_name = $(this).find('.in-item-name').val();
                let lpn = $(this).find('.in-lpn').val();
                let quantity = $(this).find('.in-qty').val();
                let location = $(this).find('.in-rcv-loc').val();
                let put_location = $(this).find('.in-put-loc').val();
                let status = $(this).find('.in-status').val();
                let expiry = $(this).find('.in-expiry').val();
                let qa = $(this).find('.in-qa').val();

                // Buat objek untuk setiap item
                if (item_code && quantity) { // Pastikan kedua input tidak kosong
                    cartItems.push({
                        item_code: item_code,
                        item_name: item_name,
                        lpn_number: lpn,
                        quantity: parseInt(quantity), // Pastikan quantity adalah integer
                        rcv_loc: location,
                        put_loc: put_location,
                        status: status,
                        expiry: expiry,
                        qa: qa
                    });
                }
            });

            return cartItems; // Kembalikan array yang berisi objek item
        }
    });
</script>