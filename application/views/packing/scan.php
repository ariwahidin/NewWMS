<div class="row">
    <div class="col-md-6 col-sm-12">

        <div class="card">

            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-evenly">
                    <p class="text-muted mb-0">
                        Req Qty : <span id="spanReqQty">0</span>
                    </p>
                    <p class="text-muted mb-0">
                        Qty Packed : <span id="spanCurQty">0</span>
                    </p>
                </div>
            </div>
            <div class="progress animated-progress rounded-bottom rounded-0" style="height: 6px;" id="progressBar">
            </div>
            <div class="card-body table-responsive">

                <form id="packingForm">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Shipment No : </label></td>
                            <td><input style="max-width: 160px;" type="text" id="shipmentNo" name="shipmentNo" class="form-control-sm" value="<?= $_GET['ship'] ?? '' ?>" required <?= isset($_GET['pack']) ? 'readonly' : '' ?>></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" id="viewShipment"><i class="ri-eye-line"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" id="deleteShipment"><i class=" ri-delete-bin-7-line"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="firstNameinput" class="form-label">Item Code : </label></td>
                            <td><input style="max-width: 160px;" name="itemCode" id="itemCode" type="text" class="form-control-sm" required></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger" id="deleteItemCode"><i class=" ri-delete-bin-7-line"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="firstNameinput" class="form-label">Ctn No : </label></td>
                            <td><input style="max-width: 160px;" id="cartonNo" name="cartonNo" type="text" class="form-control-sm" required></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td><label for="firstNameinput" class="form-label">Qty : </label></td>
                            <td><input style="max-width: 160px;" name="qty" id="qty" type="text" class="form-control-sm" required></td>
                            <td></td>
                        </tr>
                    </table>
                    <button type="submit" style="width: -webkit-fill-available" class="btn btn-info float-end btn-block mt-2">Submit</button>
                </form>
            </div>
            <card-footer>
            </card-footer>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Packed Item
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm table-striped table-nowrap" id="tablePacked">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Ctn</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailShipment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Shipment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped" id="tableShipment">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>Qty</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailPutaway" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Putaway</h5>
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
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div class="customizer-setting d-sm-block">
    <div class="btn-info rounded-pill shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
        <i class="mdi mdi-spin mdi-cog-outline fs-22"></i>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#packingForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>packing/store",
                data: $('#packingForm').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {
                        getItemPackingDetail();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        });

        $('#packingForm #itemCode').on('keyup', function() {
            let is_search = true;
            let shipment_number = $('#shipmentNo').val();
            let item_code = $('#itemCode').val();

            if (is_search && item_code.trim().length > 1 && shipment_number.trim().length > 0) {
                $.post("<?php echo base_url() ?>packing/getItemToPacking", {
                    shipment_number,
                    item_code
                }, function(response) {
                    stopLoading();
                    if (response.success == true) {
                        // $('#itemCode').val(response.data.item_code);
                        $('#qty').val(response.data.total_qty);
                        // $('#location').focus();
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }, "json");
            }
        })

        $('#shipmentNo').on('change', function() {
            getItemPackingDetail();
        });

        getItemPackingDetail();

        function getItemPackingDetail() {
            let shipment_number = $('#shipmentNo').val();
            let table = $('#tablePacked tbody');
            table.empty();
            if (shipment_number.trim().length > 0) {
                $.post("<?= base_url('packing/getItemPackingDetail') ?>", {
                    shipment_number
                }, function(response) {
                    if (response.success) {
                        let items = response.data;
                        if (items.length > 0) {

                            items.forEach(function(item, index) {
                                let row = `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.ctn}</td>            
                                        <td>${item.item_code}</td>            
                                        <td>${item.qty}</td>
                                        <td class="text-center">
                                            <buttonn class="btn btn-danger btn-sm btnDelete" data-item='${JSON.stringify(item)}'><i class=" ri-delete-bin-7-line"></i></buttonn>
                                        </td>            
                                    </tr>`;
                                table.append(row);
                            });
                        }
                        loadProgress(response.qty);
                    }
                }, 'json');
            }
        }

        $('#tablePacked tbody').on('click', '.btnDelete', function() {
            let item = $(this).data('item');
            $.post("<?= base_url('packing/deleteItem') ?>", {
                item
            }, function(response) {
                if (response.success) {
                    getItemPackingDetail();
                } else {
                    Swal.fire('error', 'error', response.message ?? 'Failed');
                }
            }, 'json');
        })

        $('#deleteShipment').click(function() {
            $('#shipmentNo').val('');
            $('#itemCode').val('');
            $('#qty').val('');
            $('#cartonNo').val('');
        });

        $('#deleteItemCode').click(function() {
            $('#itemCode').val('');
            $('#cartonNo').val('');
        });

        function loadProgress(qty) {
            let reqQty = qty.req_qty ?? 0;
            let curQty = qty.cur_qty ?? 0;


            let curPercent = parseInt((curQty / reqQty) * 100);
            let reqPercent = parseInt(100 - curPercent);

            console.log(reqPercent);
            console.log(curPercent);

            $('#spanReqQty').text(reqQty);
            $('#spanCurQty').text(curQty);

            let progress = $('#progressBar');
            progress.empty();
            let bar = `
                <div class="progress-bar bg-primary rounded-0" role="progressbar" style="width: ${reqPercent}%" aria-valuenow="${reqPercent}" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-warning rounded-0" role="progressbar" style="width: ${curPercent}%" aria-valuenow="${curPercent}" aria-valuemin="0" aria-valuemax="100"></div>
                `;
            progress.append(bar);
        }

        $('#viewShipment').on('click', function() {
            let shipment_number = $('#shipmentNo').val();
            let tbody = $('#tableShipment tbody');
            tbody.empty();
            if (shipment_number.trim().length > 0) {
                $.post("<?= base_url('packing/getItemShipment') ?>", {
                    shipment_number
                }, function(response) {
                    if (response.success) {
                        let items = response.data;
                        if (items.length > 0) {
                            items.forEach(function(item, index) {
                                let row = `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.item_code}</td>           
                                        <td>${item.qty}</td>
                                        <td class="text-center">
                                            <buttonn class="btn btn-primary btn-sm btnSelect" data-item='${JSON.stringify(item)}'><i class="ri-check-fill"></i></buttonn>
                                        </td>            
                                    </tr>`;
                                tbody.append(row);
                            });
                        }
                    }
                }, 'json');
            }
            $('#detailShipment').modal('show');
        })

        $('#tableShipment').on('click', '.btnSelect', function() {
            let item = $(this).data('item');
            let item_code = item.item_code;
            let qty = item.qty;
            $('#itemCode').val(item_code);
            $('#qty').val(qty);
            $('#detailShipment').modal('hide');
            $('#cartonNo').focus();
        })
    });
</script>