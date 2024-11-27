<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button id="btnTransporter" class="btn btn-sm btn-circle btn-outline-primary" title="Transporter">
                        <i class="ri ri-truck-line"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" title="List Carton" id="viewPicking">
                        <i class="ri ri-survey-line"></i>
                    </button>
                    <button id="btnConfirm" class="btn btn-success btn-sm float-end d-inline" title="Confirm to close this order">
                        <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM
                    </button>
                    <span id="spanProgress">0</span>%
                </h5>
            </div>
            <div class="card-body table-responsive text-end" id="divFormPacking">
                <form id="packingForm" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Shipment No. : </label></td>
                            <td>
                                <input style="max-width: 160px;" type="text" name="shipment_number" class="form-control-sm" value="">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Carton No : </label></td>
                            <td>
                                <input type="text" style="max-width: 128px;" id="carton_no" name="carton_no" class="form-control-sm">
                                <button class="btn btn-danger btn-sm" id="btnClear">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Qty Carton: </label></td>
                            <td>
                                <input type="text" style="max-width: 160px;" name="qty_carton" class="form-control-sm" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Container No : </label></td>
                            <td>
                                <input type="text" style="max-width: 160px;" name="container_no" value="001" class="form-control-sm" required>
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button onclick="document.getElementById('packingForm').reset()" type="button" style="min-width: 100px;" class="btn btn-warning btn-sm">CANCEL</button>
                        <button type="button" style="min-width: 100px;" class="btn btn-info btn-sm" id="btnSearch">SEARCH</button>
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary btn-sm">IN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Container
                    <button class="btn btn-warning btn-sm float-end" id="btnRefresh"> <i class="ri ri-restart-line"></i> Refresh</button>
                </h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm" id="tablePacking">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Container No.</th>
                            <th>Shipment No.</th>
                            <th>Carton No.</th>
                            <th>Qty Carton</th>
                            <th>Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTransporter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Transporter</h5>
            </div>
            <div class="modal-body">
                <form id="formTransporter">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Transporter : </label></td>
                            <td>
                                <input type="text" name="transporter" style="max-width: 160px;" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">No Truck : </label></td>
                            <td>
                                <input type="text" name="no_truck" style="max-width: 160px;" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Driver Name : </label></td>
                            <td>
                                <input type="text" name="driver_name" style="max-width: 160px;" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Driver Telp : </label></td>
                            <td>
                                <input type="text" name="driver_tlp" style="max-width: 160px;" class="form-control-sm">
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <form id="formEdit">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Item Code : </label></td>
                            <td>
                                <input type="text" name="edit_item_code" style="max-width: 160px;" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Qty : </label></td>
                            <td>
                                <input type="text" style="max-width: 78px;" name="edit_qty_in" class="form-control-sm" required>
                                <input type="hidden" name="edit_id" class="form-control-sm" required>
                                <input type="hidden" name="edit_qty_uom" class="form-control-sm" required>
                                <input type="text" style="max-width: 78px;" name="edit_uom" class="form-control-sm" readonly placeholder="UoM" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Carton No. : </label></td>
                            <td>
                                <input style="max-width: 160px;" type="text" name="edit_ctn_no" class="form-control-sm" value="" required>
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPick" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Carton List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped" id="tablePick">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Shipment No.</th>
                            <th>Carton No.</th>
                            <th>Qty</th>
                            <th>Action</th>
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

<script>
    $(document).ready(function() {
        const successSound = new Audio('<?= base_url('myassets/sound/success.mp3') ?>');
        const failedSound = new Audio('<?= base_url('myassets/sound/failed.mp3') ?>');


        $('#btnTransporter').on('click', function() {
            $('#modalTransporter').modal('show');
        });

        $('#packingForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('packingScan/savePacking') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        successSound.play();
                        getPackingDetail();
                    } else {
                        failedSound.play();
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message
                        })
                    }
                }
            });
        });

        $('#item_code').on('keyup', function() {
            $("input[name='qty_in']").val('');
            $("input[name='qty_uom']").val('');
            $("input[name='uom']").val('');
        })

        $('#btnClear').on('click', function() {
            $("input[name='item_code']").val('');
            $("input[name='qty_in']").val('');
            $("input[name='qty_uom']").val('');
            $("input[name='uom']").val('');
        })

        $('#btnSearch').click(function() {
            $("input[name='qty_in']").val('');
            $("input[name='qty_uom']").val('');
            $("input[name='uom']").val('');
            $.ajax({
                url: '<?= base_url('packingScan/searchShipment') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let item = response.data[0];
                        $("input[name='qty_in']").val(item.qty_in);
                        $("input[name='qty_uom']").val(item.qty_uom);
                        $("input[name='uom']").val(item.uom);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message
                        })
                    }
                    getPackingDetail();
                }
            });
        });

        $('#btnRefresh').on('click', function() {
            getPackingDetail();
        });

        // getPackingDetail();

        function getPackingDetail() {

            let tbody = $('#tablePacking tbody');

            $.ajax({
                url: '<?= base_url('packingScan/getPackingDetail') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            let button = '';
                            if (item.is_sealed == 'N') {
                                button = `<button type="button" class="btn btn-danger btn-sm btnRemove" data-id="${item.id}">
                                                <i class="ri ri-delete-bin-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm btnEdit" data-item='${JSON.stringify(item)}'>
                                                <i class="ri ri-edit-2-fill"></i>
                                            </button> 
                                        `;
                            } else {
                                button = `<span class="badge bg-success">${item.packing_number}</span>`;
                            }
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.qty_in}</td>
                                            <td>${item.uom}</td>
                                            <td>${item.qty}</td>
                                            <td>${item.carton}</td>
                                            <td>
                                                ${button}
                                            </td>
                                        </tr>
                                    `);
                        }

                        $('#spanProgress').text(response.progress);
                    } else {

                    }
                }
            });
        }

        $('#tablePacking').on('click', '.btnRemove', function() {
            let id = $(this).data('id');
            removePackingDetail(id);
        });

        $('#tablePacking').on('click', '.btnEdit', function() {
            let item = $(this).data('item');
            $("input[name='edit_id']").val(item.id);
            $("input[name='edit_item_code']").val(item.item_code);
            $("input[name='edit_qty_in']").val(item.qty_in);
            $("input[name='edit_qty_uom']").val(item.qty_uom);
            $("input[name='edit_uom']").val(item.uom);
            $("input[name='edit_ctn_no']").val(item.carton);
            $('#modalEdit').modal('show');
        })

        $('#formEdit').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('packingScan/editPacking') ?>",
                type: "POST",
                data: $('#formEdit').serialize(),
                dataType: "JSON",
                success: function(response) {
                    if (response.success == true) {
                        $('#modalEdit').modal('hide');
                        getPackingDetail();
                    }
                }
            });
        })

        $('#tablePick').on('click', '.btnSelect', function() {
            let item = $(this).data('item');
            let qty_in = item.qty_pick - item.qty_pack;
            if (qty_in < 1) {
                Swal.fire({
                    'icon': 'error',
                    'title': 'Not Allowed',
                    'text': 'Maximum Quantity',
                });
                return;
            }
            $("input[name='item_code']").val(item.item_code);
            $("input[name='qty_in']").val(qty_in);
            $("input[name='qty_uom']").val(item.qty_uom);
            $("input[name='uom']").val(item.uom);
            $('#modalPick').modal('hide');
        });

        function removePackingDetail(id) {
            $.ajax({
                url: '<?= base_url('packingScan/removePackingDetail') ?>',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        getPackingDetail();
                    } else {}
                }
            });
        }

        $('#viewPicking').on('click', function() {
            let tbody = $('#tablePick tbody');
            $.ajax({
                url: '<?= base_url('packingScan/getItemsPicking') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.qty_in} ${item.uom}</td>
                                            <td>${item.qty_pick}</td>
                                            <td>${item.qty_pack}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary btnSelect" data-item='${JSON.stringify(item)}'><i class="ri ri-check-line"></i></button>
                                            </td>
                                            </tr>
                                            `);
                        }
                        $('#modalPick').modal('show');
                    } else {}
                }

            })
        })

        $('#btnConfirm').on('click', function() {
            let shipment_number = $("input[name='shipment_number']").val();
            if (shipment_number == '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Please input shipment number',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to confirm this packing?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmPacking(shipment_number);
                    }
                })
            }
        })

        function confirmPacking(shipment_number) {
            $.ajax({
                url: '<?= base_url('packing/confirmPacking') ?>',
                type: 'POST',
                data: {
                    shipment_number: shipment_number
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            timer: 1000,
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timerProgressBar: true,
                        }).then((result) => {
                            getPackingDetail();
                        })
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            });
        }
    });
</script>