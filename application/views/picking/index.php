<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($_GET['edit']) && $order->created_by != $_SESSION['user_data']['username']) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert bg-danger border-danger text-white mb-3 mt-0" role="alert">
                <strong>You are not the creator of this task, so you are not allowed to edit it, </strong> the creator is : <b><?= $order->created_by ?></b>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row pt-0">
    <div class="col-md-12 mt-0">
        <a href="javascript:history.back()" class="btn btn-primary btn-sm mb-3"><i class="mdi mdi-keyboard-backspace"></i> Back</a>
        <a href="<?= base_url('picking/printPickingSheet?pick_no=' . $order->picking_number . '&ship_no=' . $order->shipment_number . '&type=print') ?>" class="btn btn-sm btn-info mb-3" target="_blank" rel="noopener noreferrer" title="Print Picking Sheet"> <i class="ri-printer-fill"></i></a>
    </div>


    <div class="col-md-12 mb-3">
        <div class="accordion custom-accordionwithicon" id="accordionWithicon0">
            <div class="accordion-item">
                <h2 class="accordion-header" id="accordionwithiconExample0">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse0" aria-expanded="true" aria-controls="accor_iconExamplecollapse0">
                        Shipment Information
                    </button>
                </h2>
                <div id="accor_iconExamplecollapse0" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample0" data-bs-parent="#accordionWithicon0">
                    <div class="accordion-body headerInfo">
                        <form id="formHeader">
                            <div class="row">
                                <div class="col-md-6">
                                    <table>
                                        <tr>
                                            <td>Picking Number</td>
                                            <td>:</td>
                                            <td>
                                                <input style="max-width: 100px" type="text" class="form-control-sm" id="pickingNumber" placeholder="" value="<?= $order->picking_number ?? 'Auto Generated' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Shipment Number</td>
                                            <td>:</td>
                                            <td>
                                                <input type="hidden" id="prosesAction" value="<?= (isset($order)) && $order->shipment_number ? 'edit' : 'add'; ?>">
                                                <input style="max-width: 100px" type="text" class="form-control-sm" id="shipmentNumber" placeholder="" value="<?= $order->shipment_number ?? 'Auto Generated' ?>" readonly>
                                                <input style="max-width: 80px" type="hidden" class="form-control-sm d-inline" id="status" placeholder="" value="<?= $order->is_complete ?? '' ?>" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Shipment Date</td>
                                            <td>:</td>
                                            <td>
                                                <?php
                                                $orderDate = $order->shipment_date ?? date('Y-m-d');
                                                $orderTime = isset($order) ? ($order->shipment_date ? date('H:i', strtotime($order->shipment_date)) : '') : '';
                                                ?>
                                                <input type="date" class="form-control-sm" id="shipmentDate" value="<?= $orderDate ?>" required>
                                                <input type="time" class="form-control-sm" id="shipmentTime" value="<?= $orderTime ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Start/Finish Loading</td>
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

                                        <tr>
                                            <td>Ship Request Date</td>
                                            <td>:</td>
                                            <td>
                                                <?php
                                                $shipRequestDate = $order->ship_request_date ?? date('Y-m-d');
                                                ?>
                                                <input type="date" class="form-control-sm" id="shipRequestDate" value="<?= $shipRequestDate ?>" placeholder="" required>
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
                                            <td>Doc No.</td>
                                            <td>:</td>
                                            <td>
                                                <input type="text" class="form-control-sm required-input" id="shipReff" placeholder="" value="<?= $order->ship_reff ?? '' ?>" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Print DO Date</td>
                                            <td>:</td>
                                            <td>
                                                <?php
                                                $printDODate = $order->print_do_date ?? date('Y-m-d');
                                                $printDOTime = isset($order->print_do_time) ? date('H:i', strtotime($order->print_do_time)) : '';
                                                ?>
                                                <input type="date" class="form-control-sm" id="printDODate" value="<?= $printDODate ?>" placeholder="" required>
                                                <input type="time" class="form-control-sm" id="printDOTime" value="<?= $printDOTime ?>" placeholder="" required>
                                            </td>
                                        </tr>


                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table>
                                        <tr>
                                            <td>Customer</td>
                                            <td>:</td>
                                            <td style="white-space: nowrap;">

                                                <?php
                                                $customer_id = $order->customer_id ?? '';
                                                $ship_to = $order->ship_to ?? '';
                                                $customer_name = $order->customer_name ?? '';
                                                ?>

                                                <input type="hidden" id="customerId" value="<?= $customer_id ?>">
                                                <input style="max-width: 120px;" type="text" class="form-control-sm required-input" id="shipTo" value="<?= $ship_to ?>" placeholder="" readonly>
                                                <input style="width: 220px;" type="text" class="form-control-sm required-input" id="customerName" value="<?= $customer_name ?>" placeholder="" readonly>
                                                <button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCustomer">search</button>

                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Customer Address</td>
                                            <td>:</td>
                                            <td>
                                                <?php $customer_address = $order->ship_to_address1 ?? ''; ?>
                                                <textarea class="form-control-sm required-input" rows="3" style="width: 100%;" readonly name="" id="customerAddress"><?= $customer_address ?></textarea>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Customer City</td>
                                            <td>:</td>
                                            <td>
                                                <?php $customer_city = $order->ship_to_city ?? ''; ?>
                                                <input type="text" class="form-control-sm required-input" id="customerCity" placeholder="" value="<?= $customer_city ?>" required>
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
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <div class="accordion custom-accordionwithicon" id="accordionWithicon">
            <div class="accordion-item">
                <h2 class="accordion-header" id="accordionwithiconExample1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse1" aria-expanded="true" aria-controls="accor_iconExamplecollapse1">
                        Shipment Items Request
                    </button>
                </h2>
                <div id="accor_iconExamplecollapse1" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample1" data-bs-parent="#accordionWithicon">
                    <div class="accordion-body">
                        <table style="white-space: nowrap; font-size: smaller;" class="table table-bordered table-sm table-striped" id="">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>Qty UoM</th>
                                    <th>UoM</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $total_qty = 0;
                                foreach ($shipment_detail->result() as $data) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data->item_code ?></td>
                                        <td><?= $data->item_name ?></td>
                                        <td><?= $data->qty_in ?></td>
                                        <td><?= $data->qty_uom ?></td>
                                        <td><?= $data->uom ?></td>
                                        <td><?= $data->qty ?></td>
                                    </tr>
                                <?php
                                    $total_qty += $data->qty;
                                } ?>
                            </tbody>
                            <tfooter>
                                <tr>
                                    <td colspan="6">Total :</td>
                                    <td><strong><?= $total_qty ?></strong></td>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="accordion custom-accordionwithicon" id="accordionWithicon2">
            <div class="accordion-item">
                <h2 class="accordion-header" id="accordionwithiconExample2">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accor_iconExamplecollapse2" aria-expanded="true" aria-controls="accor_iconExamplecollapse2">
                        Shipment Items Picked
                    </button>
                </h2>
                <div id="accor_iconExamplecollapse2" class="accordion-collapse collapse show" aria-labelledby="accordionwithiconExample2" data-bs-parent="#accordionWithicon2">
                    <div class="accordion-body">
                        <table style="white-space: nowrap; font-size: smaller;" class="table table-bordered table-sm table-striped" id="cartTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>GRN</th>
                                    <th>Location</th>
                                    <th>Pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">Total : </td>
                                    <td><strong id="totalPicked"></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
<div class="modal fade" id="exampleModalCustomer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Ship To</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($customer->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->ship_to ?></td>
                                <td><?= $data->customer_name ?></td>
                                <td>
                                    <?= $data->ship_to_address1 ?>
                                    <br>
                                    <?= $data->ship_to_address2 ?>
                                    <br>
                                    <?= $data->ship_to_address3 ?>
                                    <br>
                                    <?= $data->ship_to_city ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm btncustomer"
                                        data-id="<?= $data->id ?>"
                                        data-ship-to="<?= $data->ship_to ?>"
                                        data-customer-name="<?= $data->customer_name ?>"
                                        data-address1="<?= $data->ship_to_address1 ?>"
                                        data-city="<?= $data->ship_to_city ?>">select</button>
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
                <h5 style="color: white;" class="modal-title" id="exampleModalLabel">List Items Available</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">

                <input type="text" id="searchOrders" class="form-control-sm" placeholder="Search Order">
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
                                <th>Available</th>
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

        let existingShipments = [];
        let selectedOrders = [];

        getOrder();

        function makeFieldsReadonly() {
            let status = $('#status').val();
            if (status == 'Y') {
                document.querySelectorAll('.headerInfo').forEach(container => {
                    container.querySelectorAll('input, select').forEach(element => {
                        element.readOnly = true;
                        element.disabled = true;
                    });
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
                dataToPost.ob_no = $('#shipmentNumber').val();
                dataToPost.pick_no = $('#pickingNumber').val();
            }

            $.ajax({
                url: '<?= base_url('picking/getItems') ?>',
                type: 'POST',
                data: dataToPost,
                dataType: 'json',
                success: function(data) {
                    let tableBody = $('#tableShipments tbody');
                    let totalData = parseInt($('#totalDo').text());
                    $.each(data.shipments, function(index, order) {
                        if (!existingShipments.includes(order.shipment_id)) {
                            let row = `<tr>
                                            <td style="align-content:center; text-align:center;">
                                                <input type="checkbox" class="order-checkbox" value="${order.item_code}" data-order='${JSON.stringify(order)}'/>
                                            </td>
                                            <td>${order.item_code}</td>
                                            <td>${order.item_name}</td>
                                            <td>${order.available}</td>
                                        </tr>`;

                            tableBody.append(row);
                            existingShipments.push(order.item_code);
                            totalData += 1;
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

        $('#tableShipments').on('change', '.order-checkbox', function() {
            let order = JSON.parse($(this).attr('data-order'));

            if ($(this).is(':checked')) {
                selectedOrders.push(order);
                updateCartTable(order.item_code);
            } else {
                selectedOrders = selectedOrders.filter(o => o.item_code !== order.item_code);
                updateCartTable(order.item_code, true);
            }

        });

        $('#searchOrders').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            let count = 0;

            $('#tableShipments tbody tr').filter(function() {
                let match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
                if (match) count++;
            });


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

        $('.btncustomer').on('click', function() {
            let id = $(this).data('id');
            let ship_to = $(this).data('ship-to');
            let customer_name = $(this).data('customer-name');
            let customer_address = $(this).data('address1');
            let customer_city = $(this).data('city');
            $('#customerId').val(id);
            $('#shipTo').val(ship_to);
            $('#customerName').val(customer_name);
            $('#customerAddress').val(customer_address);
            $('#customerCity').val(customer_city);
            $('#exampleModalCustomer').modal('hide');
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

        function updateCartTable(item_code_selected = null, uncheck = false) {
            let cartBody = $('#cartTable tbody');
            let prosesAction = $('#prosesAction').val();

            if (selectedOrders.length > 0) {

                $('#selectedOrdersCount').text(selectedOrders.length);

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

                if (prosesAction == 'edit') {}

                $('#generateSPK').prop('disabled', false);
                let totalPicked = 0;
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

                    totalPicked += order.qty

                });

                $('#totalPicked').text(totalPicked);


            } else {
                $('#generateSPK').prop('disabled', true);
                $('#selectedOrdersCount').text(0);
            }
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
                <td>
                    ${order.grn_number ?? ''}
                </td>
                <td>
                    ${order.location ?? ''}
                </td>
                <td>
                    <input type="hidden" class="form-control-sm in-id" value="${order.id ?? ''}">
                    <input type="hidden" class="form-control-sm in-item" value="${order.item_code ?? ''}">
                    <input type="hidden" class="form-control-sm in-item-name" value="${order.item_name ?? ''}">
                    ${order.qty ?? '1'}
                </td>
            </tr>
        `;
        }


        $('#cartTable').on('click', '.add-line', function() {
            const $currentRow = $(this).closest('tr');
            const currentIndex = $('#cartTable tbody tr').length;

            const orderData = {
                item_code: $currentRow.find('.in-item').val(),
                item_name: $currentRow.find('td').eq(2).text(),
                lpn_number: $currentRow.find('.in-lpn').val(),
                qty: $currentRow.find('.in-qty').val(),
                receive_location: $currentRow.find('.in-rcv-loc').val(),
                plan_put_location: $currentRow.find('.in-put-loc').val(),
                status: $currentRow.find('.in-status').val()
            };

            const newRow = generateRow(currentIndex, orderData);
            $currentRow.after(newRow);

            updateRowNumbers();
        });


        $('#cartTable').on('click', '.remove-order', function() {

            $(this).closest('tr').remove();
            updateRowNumbers();

            let item_code = $(this).attr('data-id');
            $(`input[value="${item_code}"]`).prop('checked', false);

        });


        function updateRowNumbers() {
            $('#cartTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#generateSPK').on('click', function() {

            let isFormValid = true;
            let emptyFields = [];

            document.querySelectorAll(".required-input").forEach(function(input) {
                if (input.value.trim() === "") {
                    isFormValid = false;
                    emptyFields.push(input.placeholder || "Select option");
                }
            });

            if (!isFormValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
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
                        proccess();
                    } else {
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

        function proccess(putaway = false) {
            if (selectedOrders.length > 0) {
                let orderIds = selectedOrders.map(order => order.shipment_id);
                const dataObj = {};

                $('#formHeader').find('input, select').each(function() {
                    const inputId = $(this).attr('id');
                    const inputValue = $(this).val();
                    dataObj[inputId] = inputValue;
                });

                let suratJalanHeader = {
                    nomor: $('#suratJalanNumber').val(),
                    tanggal: $('#tanggalSuratJalan').val(),
                    pengirim: $('#namaPengirim').val(),
                    penerima: $('#namaPenerima').val()
                };

                let itemsDetail = collectCartItems();
                let prosesAction = $('#prosesAction').val();

                let url = '<?= site_url('shipment/createProccess') ?>';
                if (prosesAction == 'edit') {
                    url = '<?= site_url('shipment/editProccess') ?>';
                }

                $.ajax({
                    url: url,
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
                                selectedOrders = [];
                                updateCartTable();
                                $('#tableShipments input[type="checkbox"]').prop('checked', false);

                                if (result.isConfirmed) {
                                    $('body').empty();
                                } else if (result.dismiss === Swal.DismissReason.cancel) {

                                    if (prosesAction == 'edit') {
                                        location.reload();
                                        return;
                                    }

                                    $('body').empty();
                                    window.location.href = 'list';
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
            }
        }


        function collectCartItems() {
            let cartItems = [];

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


                if (item_code && quantity) {
                    cartItems.push({
                        item_code: item_code,
                        item_name: item_name,
                        lpn_number: lpn,
                        quantity: parseInt(quantity),
                        rcv_loc: location,
                        put_loc: put_location,
                        status: status,
                        expiry: expiry,
                        qa: qa
                    });
                }
            });

            return cartItems;
        }
    });
</script>