<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" id="viewShipment">
                        <i class="ri ri-survey-line"></i>
                    </button>
                    <span id="spanProgress">0</span>%
                    <button id="btnConfirm" class="btn btn-success float-end d-inline"> <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM</button>
                </h5>
            </div>
            <div class="card-body table-responsive text-end" id="divFormPacking">
                <form id="pickingForm" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Shipment Number : </label></td>
                            <td>
                                <input style="max-width: 160px;" type="text" name="shipment_number" class="form-control-sm" value="<?= $_GET['ob'] ?? '' ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Item Code : </label></td>
                            <td>
                                <input type="text" style="max-width: 128px;" id="item_code" name="item_code" class="form-control-sm">
                                <button class="btn btn-danger btn-sm" id="btnClear">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Location : </label></td>
                            <td>
                                <input type="text" style="max-width: 128px;" name="location" class="form-control-sm" required>
                                <button class="btn btn-danger btn-sm" id="btnClearLocation">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button onclick="document.getElementById('pickingForm').reset()" type="button" style="min-width: 100px;" class="btn btn-warning">CANCEL</button>
                        <button type="button" style="min-width: 100px;" class="btn btn-info" id="btnSearch">SEARCH</button>
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary d-none">PACK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Picked Items
                    <button class="btn btn-warning btn-sm float-end" id="btnRefreshPick"> <i class="ri ri-restart-line"></i> Refresh</button>
                </h5>
            </div>
            <div class="card-body table-responsive">
                <form action="formChange">
                    <input type="hidden" name="old_item" value="">
                    <input type="hidden" name="new_item" value="">
                </form>
                <table class="table table-sm" id="tablePickedItems">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ItemCode</th>
                            <th>GRN</th>
                            <th>Location</th>
                            <th>Pcs</th>
                            <th>Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
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
                <h5 class="modal-title" id="exampleModalLabel">Picked Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped table-nowrap" id="tableShipment">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>Qty Req</th>
                            <th>Qty Req (Pcs)</th>
                            <th>Qty Pick (Pcs)</th>
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


<div class="modal fade" id="itemInventory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: scroll ; max-height: 600px;" id="modalBodyInventory">

            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const successSound = new Audio('<?= base_url('myassets/sound/success.mp3') ?>');
        const failedSound = new Audio('<?= base_url('myassets/sound/failed.mp3') ?>');

        // $('#pickingForm').submit(function(e) {
        //     e.preventDefault();
        //     $.ajax({
        //         url: "<?= base_url('packingScan/savePacking') ?>",
        //         type: 'POST',
        //         data: $(this).serialize(),
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.success) {
        //                 successSound.play();
        //                 getPackingDetail();
        //             } else {
        //                 failedSound.play();
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Failed',
        //                     text: response.message
        //                 })
        //             }
        //         }
        //     });
        // });

        // $('#item_code').on('keyup', function() {
        //     $("input[name='qty_in']").val('');
        //     $("input[name='qty_uom']").val('');
        //     $("input[name='uom']").val('');
        // })

        // $('#btnClear').on('click', function() {
        //     $("input[name='item_code']").val('');
        //     $("input[name='qty_in']").val('');
        //     $("input[name='qty_uom']").val('');
        //     $("input[name='uom']").val('');
        // })

        $('#btnSearch').click(function() {
            let shipment_number = $("input[name='shipment_number']").val();
            let item_code = $("input[name='item_code']").val();
            let location = $("input[name='location']").val();

            if (shipment_number == '' || item_code == '' || location == '') {
                failedSound.play();
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Shipment Number, Item Code, and Location must be filled'
                })
            } else {
                $.ajax({
                    url: "<?= base_url('pickingScan/searchInventory') ?>",
                })
            }
        });

        $('#btnRefresh').on('click', function() {
            getPickedDetail();
        });

        // getPackingDetail();

        getPickedDetail();

        function getPickedDetail() {

            let tbody = $('#tablePickedItems tbody');

            $.ajax({
                url: '<?= base_url('pickingScan/getPickedDetail') ?>',
                type: 'POST',
                data: $('#pickingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            let button = `<button type="button" class="btn btn-primary btn-sm btnChange" title="Change" data-item='${JSON.stringify(item)}'>
                                                <i class="ri ri-refresh-line"></i>
                                            </button>`;
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.grn_number}</td>
                                            <td>${item.location}</td>
                                            <td>${item.qty}</td>
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

        // $('#tablePickedItems').on('click', '.btnRemove', function() {
        //     let id = $(this).data('id');
        //     removePackingDetail(id);
        // });

        $('#tablePickedItems').on('click', '.btnChange', function() {
            let item = $(this).data('item');
            $("input[name='old_item']").val(JSON.stringify(item));
            let modalBody = $('#modalBodyInventory');
            modalBody.empty();
            $.ajax({
                url: '<?= base_url('pickingScan/changePickedDetail') ?>',
                type: 'POST',
                data: {
                    item: item,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let inventory = response.data;
                        console.log(inventory);
                        inventory.forEach(element => {
                            let card = `
                                <div class="card bg-default bg-soft-light btnCard" data-item='${JSON.stringify(element)}' data-action="change">
                                    <div class="card-body" style="background-color: greenyellow; cursor: pointer;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-15 mb-0">${element.item_code}</h5>
                                                <p class="text-muted mb-0 d-none">BALL LINER PEN,STRIPE BODY,MEDIUM,RED</p>
                                                <p class="text-muted mb-0"><span>${element.whs_code}</span> | <span>${element.location}</span> | <span>${element.location}</span> | <span>${element.grn_number}</span></p>
                                                <p class="text-muted mb-0"><span>Available</span> : <span>${element.available}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            modalBody.append(card);
                        })
                    }
                }
            });


            // $("input[name='edit_id']").val(item.id);
            // $("input[name='edit_item_code']").val(item.item_code);
            // $("input[name='edit_qty_in']").val(item.qty_in);
            // $("input[name='edit_qty_uom']").val(item.qty_uom);
            // $("input[name='edit_uom']").val(item.uom);
            // $("input[name='edit_ctn_no']").val(item.carton);
            $('#itemInventory').modal('show');
        })

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

        // $('#tableShipment').on('click', '.btnSelect', function() {
        //     let item = $(this).data('item');
        //     let qty_in = item.qty_pick - item.qty_pack;
        //     if (qty_in < 1) {
        //         Swal.fire({
        //             'icon': 'error',
        //             'title': 'Not Allowed',
        //             'text': 'Maximum Quantity',
        //         });
        //         return;
        //     }
        //     $("input[name='item_code']").val(item.item_code);
        //     $("input[name='qty_in']").val(qty_in);
        //     $("input[name='qty_uom']").val(item.qty_uom);
        //     $("input[name='uom']").val(item.uom);
        //     $('#modalPick').modal('hide');
        // });

        // function removePackingDetail(id) {
        //     $.ajax({
        //         url: '<?= base_url('packingScan/removePackingDetail') ?>',
        //         type: 'POST',
        //         data: {
        //             id: id
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.success) {
        //                 getPackingDetail();
        //             } else {}
        //         }
        //     });
        // }

        $('#viewShipment').on('click', function() {
            let tbody = $('#tableShipment tbody');
            $.ajax({
                url: '<?= base_url('pickingScan/getItemsToPicking') ?>',
                type: 'POST',
                data: $('#pickingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];

                            let button = '<button class="btn btn-sm btnSelect" data-item="' + JSON.stringify(item) + '"><i class="ri ri-check-line"></i></button> ';
                            if (item.qty_pick == item.qty_req) {
                                button = 'full picked';
                            }
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.qty_in} ${item.uom}</td>
                                            <td>${item.qty_req}</td>
                                            <td>${item.qty_pick}</td>
                                            <td class="text-center">
                                                ${button}
                                            </td>
                                            </tr>
                                            `);
                        }
                        $('#modalPick').modal('show');
                    } else {}
                }

            })
        })

        $("#modalBodyInventory").on('click', '.btnCard', function() {
            let item = $(this).data('item');
            let action = $(this).data('action');
            if (action == 'change') {
                changePicking(item);
            }
        })

        let isSubmit = false;

        function changePicking(item) {
            let new_item = item;
            let old_item = JSON.parse($("input[name='old_item']").val());
            if (!isSubmit) {
                isSubmit = true;
                $.ajax({
                    url: '<?= base_url('pickingScan/updatePickingDetail') ?>',
                    type: 'POST',
                    data: {
                        new_item: new_item,
                        old_item: old_item
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            successSound.play();
                            $('#itemInventory').modal('hide');
                            getPickedDetail();

                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error',
                            })
                        }
                    }
                }).done(function() {
                    setTimeout(function() {
                        isSubmit = false;
                    }, 2000);
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'To many click!',
                })
            }
        }


        // $('#btnConfirm').on('click', function() {
        //     let shipment_number = $("input[name='shipment_number']").val();
        //     if (shipment_number == '') {
        //         Swal.fire({
        //             title: 'Error',
        //             text: 'Please input shipment number',
        //             icon: 'error',
        //             confirmButtonText: 'OK'
        //         })
        //     } else {
        //         Swal.fire({
        //             title: 'Are you sure?',
        //             text: 'Do you want to confirm this packing?',
        //             icon: 'question',
        //             showCancelButton: true,
        //             confirmButtonText: 'Yes, proceed',
        //             cancelButtonText: 'Cancel'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 confirmPacking(shipment_number);
        //             }
        //         })
        //     }
        // })

        // function confirmPacking(shipment_number) {
        //     $.ajax({
        //         url: '<?= base_url('packing/confirmPacking') ?>',
        //         type: 'POST',
        //         data: {
        //             shipment_number: shipment_number
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.success) {
        //                 Swal.fire({
        //                     timer: 1000,
        //                     title: 'Success',
        //                     text: response.message,
        //                     icon: 'success',
        //                     showConfirmButton: false,
        //                     timerProgressBar: true,
        //                 }).then((result) => {
        //                     getPackingDetail();
        //                 })
        //             } else {
        //                 Swal.fire({
        //                     title: 'Error',
        //                     text: response.message,
        //                     icon: 'error',
        //                     confirmButtonText: 'OK'
        //                 })
        //             }
        //         }
        //     });
        // }
    });
</script>