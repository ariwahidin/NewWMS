<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button id="btnTransporter" class="btn btn-sm btn-circle btn-outline-primary" title="Transporter">
                        <i class="ri ri-truck-line"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" title="List Carton" id="viewCarton">
                        <i class="ri ri-survey-line"></i>
                    </button>
                    <button id="btnConfirm" class="btn btn-success btn-sm float-end d-inline" title="Confirm to close this order">
                        <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM
                    </button>
                    <span id="spanProgress">0</span>%
                </h5>
            </div>
            <div class="card-body table-responsive text-end" id="divFormPacking">
                <form id="loadingForm" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Shipment No. : </label></td>
                            <td>
                                <input style="max-width: 160px;" type="text" name="shipment_number" class="form-control-sm" value="<?= $_GET['ob'] ?? '' ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Carton No : </label></td>
                            <td>
                                <input type="text" style="max-width: 128px;" id="carton_no" name="carton_no" class="form-control-sm" required>
                                <button class="btn btn-danger btn-sm" id="btnClear">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Qty Carton : </label></td>
                            <td>
                                <input type="nuber" style="max-width: 160px;" name="qty_carton" class="form-control-sm" required>
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
                        <button onclick="document.getElementById('loadingForm').reset()" type="button" style="min-width: 100px;" class="btn btn-warning btn-sm">CANCEL</button>
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
                <table class="table table-sm" id="tableContainer">
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
                                <select name="transporter" style="max-width: 160px;" class="form-control-sm" id="transporter">
                                    <option value="">Select Transporter</option>
                                    <?php
                                    foreach ($transporter as $key => $value) {
                                    ?>
                                        <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">No Truck : </label></td>
                            <td>
                                <input type="hidden" name="shipment_id" style="max-width: 160px;" class="form-control-sm">
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

<div class="modal fade" id="modalCarton" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Carton List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped" id="tableCarton">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Shipment No.</th>
                            <th>Carton No.</th>
                            <th>Qty</th>
                            <th>Qty In</th>
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
            shipmentHeader();
        });


        const shipmentHeader = () => {
            let shipment_no = $("input[name='shipment_number']").val();
            if (shipment_no == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select shipment no.',
                })
                return false;
            }

            $.ajax({
                url: "<?= base_url('ShippingLoading/getShipment') ?>",
                type: 'POST',
                data: {
                    shipment_number: shipment_no
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success == true) {
                        let shipment = response.data;
                        $("input[name='shipment_id']").val(shipment.id);
                        $("select[name='transporter']").val(shipment.transporter_id);
                        $("input[name='no_truck']").val(shipment.truck_no);
                        $("input[name='driver_name']").val(shipment.driver_name);
                        $("input[name='driver_tlp']").val(shipment.driver_phone);
                    }
                }
            }).done(function() {
                $('#modalTransporter').modal('show');
            })
        }


        $("#formTransporter").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('ShippingLoading/saveTransporter') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        successSound.play();
                        Swal.fire({
                            timer: 1000,
                            title: 'Success',
                            icon: 'success',
                            text: response.message,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        })
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

        $('#loadingForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('ShippingLoading/saveLoading') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        successSound.play();
                        $("input[name='carton_no']").val('');
                        $("input[name='qty_carton']").val('');
                        $("input[name='carton_no']").focus();
                        getContainerDetail();
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

        // $('#item_code').on('keyup', function() {
        //     $("input[name='qty_in']").val('');
        //     $("input[name='qty_uom']").val('');
        //     $("input[name='uom']").val('');
        // })

        $('#btnClear').on('click', function() {
            $("input[name='carton_no']").val('');
            $("input[name='qty_carton']").val('');
            $("input[name='carton_no']").focus();
        })

        // $('#btnSearch').click(function() {
        //     $("input[name='qty_in']").val('');
        //     $("input[name='qty_uom']").val('');
        //     $("input[name='uom']").val('');
        //     $.ajax({
        //         url: '<?= base_url('packingScan/searchShipment') ?>',
        //         type: 'POST',
        //         data: $('#packingForm').serialize(),
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.success) {
        //                 let item = response.data[0];
        //                 $("input[name='qty_in']").val(item.qty_in);
        //                 $("input[name='qty_uom']").val(item.qty_uom);
        //                 $("input[name='uom']").val(item.uom);
        //             } else {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Failed',
        //                     text: response.message
        //                 })
        //             }
        //             getPackingDetail();
        //         }
        //     });
        // });

        $('#btnRefresh').on('click', function() {
            getContainerDetail();
        });

        getContainerDetail();
        function getContainerDetail() {

            let tbody = $('#tableContainer tbody');

            $.ajax({
                url: '<?= base_url('ShippingLoading/getContainerDetail') ?>',
                type: 'POST',
                data: $('#loadingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            let button = '';
                            if (item.shipping_load_id == null) {
                                button = `<button type="button" class="btn btn-danger btn-sm btnRemove" data-id="${item.id}">
                                                <i class="ri ri-delete-bin-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm d-none btnEdit" data-item='${JSON.stringify(item)}'>
                                                <i class="ri ri-edit-2-fill"></i>
                                            </button> 
                                        `;
                            } else {
                                button = `<span class="badge bg-success">${item.shipping_load_number}</span>`;
                            }
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.container_no}</td>
                                            <td>${item.shipment_number}</td>
                                            <td>${item.carton_no}</td>
                                            <td>${item.qty_carton}</td>
                                            <td>
                                                ${button}
                                            </td>
                                        </tr>
                                    `);
                        }

                        $('#spanProgress').text(response.progress);

                        if (response.progress == 100) {
                            $('#btnConfirm').addClass('d-block');
                            $('#btnConfirm').removeClass('d-none');
                        }else{
                            $('#btnConfirm').removeClass('d-block');
                            $('#btnConfirm').addClass('d-none');
                        }
                    } else {

                    }
                }
            });
        }

        $('#tableContainer').on('click', '.btnRemove', function() {
            let id = $(this).data('id');
            removeContainerDetail(id);
        });

        // $('#tableContainer').on('click', '.btnEdit', function() {
        //     let item = $(this).data('item');
        //     $("input[name='edit_id']").val(item.id);
        //     $("input[name='edit_item_code']").val(item.item_code);
        //     $("input[name='edit_qty_in']").val(item.qty_in);
        //     $("input[name='edit_qty_uom']").val(item.qty_uom);
        //     $("input[name='edit_uom']").val(item.uom);
        //     $("input[name='edit_ctn_no']").val(item.carton);
        //     $('#modalEdit').modal('show');
        // })

        // $('#formEdit').on('submit', function(e) {
        //     e.preventDefault();
        //     $.ajax({
        //         url: "<?= base_url('packingScan/editPacking') ?>",
        //         type: "POST",
        //         data: $('#formEdit').serialize(),
        //         dataType: "JSON",
        //         success: function(response) {
        //             if (response.success == true) {
        //                 $('#modalEdit').modal('hide');
        //                 getPackingDetail();
        //             }
        //         }
        //     });
        // })

        $('#tableCarton').on('click', '.btnSelect', function() {
            let carton = $(this).data('carton');
            let carton_in = carton.qty_carton - carton.qty_carton_in;
            if (carton_in < 1) {
                Swal.fire({
                    'icon': 'error',
                    'title': 'Not Allowed',
                    'text': 'Maximum Quantity',
                });
                return;
            }
            $("input[name='carton_no']").val(carton.carton);
            $("input[name='qty_carton']").val(carton_in);
            $('#modalCarton').modal('hide');
        });

        function removeContainerDetail(id) {
            $.ajax({
                url: '<?= base_url('ShippingLoading/removeContainerDetail') ?>',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        getContainerDetail();
                    } else {}
                }
            });
        }

        $('#viewCarton').on('click', function() {
            let tbody = $('#tableCarton tbody');


            let shipment_number = $('#shipment_number').val();

            if (shipment_number == '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'Please input shipment number',
                });
                return;
            }

            $.ajax({
                url: '<?= base_url('ShippingLoading/getCartonList') ?>',
                type: 'POST',
                data: $('#loadingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let cartons = response.data;
                        tbody.empty();
                        for (let i = 0; i < cartons.length; i++) {
                            let carton = cartons[i];
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${carton.shipment_number}</td>
                                            <td>${carton.carton}</td>
                                            <td>${carton.qty_carton}</td>
                                            <td>${carton.qty_carton_in}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary btnSelect ${carton.qty_carton_in == carton.qty_carton ? 'disabled' : ''}" data-carton='${JSON.stringify(carton)}'><i class="ri ri-check-line"></i></button>
                                            </td>
                                        </tr>
                                        `);
                        }
                        $('#modalCarton').modal('show');
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
                    text: 'Do you want to confirm this container?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmLoading(shipment_number);
                    }
                })
            }
        })

        function confirmLoading(shipment_number) {
            $.ajax({
                url: '<?= base_url('ShippingLoading/confirmLoading') ?>',
                type: 'POST',
                data: {
                    shipment_number: shipment_number
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        successSound.play();
                        Swal.fire({
                            timer: 1000,
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timerProgressBar: true,
                        }).then((result) => {
                            getContainerDetail();
                        })
                    } else {
                        failedSound.play();
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