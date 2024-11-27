<div class="row">
    <div class="col-md-6 col-sm-12">
        <button id="btnConfirm" class="btn btn-success d-inline mb-3" data-put-no="<?= $putaway->putaway_number ?>"> <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM</button>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-12 col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" data-rcv="<?= $_POST['receiveNumber'] ?>" id="viewItem"><i class="ri ri-survey-line"></i></button>
                    <span><?= $_POST['receiveNumber'] ?></span> / <span><?= $putaway->putaway_number ?></span>
                </h5>

                <span class="text-muted">Progress : <span id="percentProgress">0</span>%</span>
                <div class="card-body table-responsive text-center">

                    <form id="putawayForm">

                        <table class="table-nowrap table-sm fs-11 mb-0">
                            <tr>
                                <td><label for="firstNameinput" class="form-label">Item Code </label></td>
                                <td>
                                    <input name="receiveNumber" id="receiveNumber" type="hidden" value="<?php echo $_POST['receiveNumber']; ?>" class="form-control-sm">
                                    <input name="receive_detail_id" id="receive_detail_id" type="hidden" value="" class="form-control-sm">
                                    <input name="putaway_number" id="putaway_number" type="hidden" value="" class="form-control-sm">
                                    <input name="putaway_id" id="putaway_id" type="hidden" value="" class="form-control-sm">
                                    <input name="grn_id" id="grn_id" type="hidden" value="" class="form-control-sm">
                                    <input name="grn_number" id="grn_number" type="hidden" value="" class="form-control-sm">
                                    <input name="receive_id" id="receive_id" type="hidden" value="" class="form-control-sm">
                                    : <input style="max-width: 160px;" name="itemCode" id="itemCode" type="text" class="form-control-sm" autocomplete="off">
                                </td>
                                <td>
                                    <button type="button" onclick="document.getElementById('itemCode').value=''" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="firstNameinput" class="form-label">Qty </label></td>
                                <td>
                                    : <input style="max-width: 80px;" name="qty_in" id="qty_in" type="number" class="form-control-sm" autocomplete="off">
                                    <input style="max-width: 80px;" name="qty_uom" id="qty_uom" type="hidden" class="form-control-sm">
                                    <input style="max-width: 75px;" name="uom" id="uom" type="text" class="form-control-sm" readonly>
                                </td>
                                <td>
                                    <button type="button" onclick="document.getElementById('qty_in').value=''" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="firstNameinput" class="form-label">Location : </label></td>
                                <td>
                                    <input style="max-width: 160px;" name="rcv_loc" id="rcv_loc" type="hidden" required>
                                    : <input style="max-width: 160px;" name="put_loc" id="put_loc" type="text" class="form-control-sm" minlength="8" maxlength="8" required autocomplete="off">
                                </td>
                                <td>
                                    <button onclick="document.getElementById('put_loc').value=''" type="button" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
                                </td>
                            </tr>
                        </table>

                        <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                            <button onclick="window.history.back()" type="button" style="min-width: 100px;" class="btn btn-warning">CANCEL</button>
                            <button type="button" style="min-width: 100px;" class="btn btn-info" id="searchBtn">SEARCH</button>
                            <button type="submit" style="min-width: 100px;" class="btn btn-primary">PUT</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">Items Scanned</h5>
            </div>
            <div class="card-body table-responsive">
                <table style="font-size: 11px;" class="table table-sm table-bordered table-striped" id="tablePutaway">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>Qty</th>
                            <th>UoM</th>
                            <th>Location</th>
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

<div class="modal fade" id="itemDetailModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Item To Putaway</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped" id="tableDetail">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>Req Qty</th>
                            <th>Scan Qty </th>
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
        $('#viewItem').click(function() {
            let receiveNumber = $(this).data('rcv');
            startLoading();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/getItemReceiveToScan",
                data: {
                    receiveNumber
                },
                dataType: "json",
                success: function(response) {
                    stopLoading();
                    if (response.success == true) {
                        let items = response.data;
                        let table = $('#tableDetail tbody');
                        table.empty();

                        items.forEach((item, index) => {
                            let row = `<tr>
                                <td>${index + 1}</td>
                                <td>${item.item_code}</td>
                                <td>${item.req_qty} PCS</td>
                                <td>${item.qty_scan} PCS</td>
                            </tr>`;
                            table.append(row);
                        })

                        $('#itemDetailModal').modal('show');
                    }
                }
            })
        });

        $('#searchBtn').click(function() {
            searchItems();
        });

        function searchItems() {
            startLoading();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/getItems",
                data: $('#putawayForm').serialize(),
                dataType: "json",
                success: function(response) {
                    stopLoading();
                    if (response.success == true) {
                        let item = response.data[0];
                        $('#receive_id').val(item.receive_id);
                        $('#receive_detail_id').val(item.receive_detail_id);
                        $('#putaway_id').val(item.putaway_id);
                        $('#putaway_number').val(item.putaway_number);
                        $('#qty_in').val(item.qty_in);
                        $('#grn_id').val(item.grn_id);
                        $('#grn_number').val(item.grn_number);
                        $('#qty_uom').val(item.qty_uom);
                        $('#uom').val(item.uom);
                        $('#rcv_loc').val(item.receive_location);
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        }


        $('#putawayForm').submit(function(e) {
            e.preventDefault();
            startLoading();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/proccessPutaway",
                data: $('#putawayForm').serialize(),
                dataType: "json",
                success: function(response) {
                    stopLoading();
                    if (response.success == true) {
                        successSound.play();
                        getItemScan();
                    } else {
                        failedSound.play();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        });

        getItemScan();

        function getItemScan() {
            let receiveNumber = $('#receiveNumber').val();
            let tablePutaway = $('#tablePutaway tbody');
            tablePutaway.html('');
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/getItemPutaway",
                data: {
                    receiveNumber: receiveNumber
                },
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {
                        items = response.items;
                        $('#percentProgress').text(response.percent);
                        if (response.percent == 100) {
                            $('#btnConfirm').attr('disabled', false);
                            $('#btnConfirm').removeClass('d-none');
                        } else {
                            $('#btnConfirm').attr('disabled', true);
                            $('#btnConfirm').addClass('d-none');
                        }
                        items.forEach((item, index) => {
                            let row = `<tr>
                                        <td>${index + 1}</td>
                                        <td>${item.item_code}</td>
                                        <td>${item.qty_in}</td>
                                        <td>${item.uom}</td>
                                        <td>${item.to_location}</td>
                                        <td><button type="button" class="btn btn-sm btn-danger btn-delete" data-items='${JSON.stringify(item)}'><i class=" ri-delete-bin-7-line"></i></button></td>
                                    </tr>`;
                            tablePutaway.append(row);
                        })
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        }

        $('#tablePutaway').on('click', '.btn-delete', function() {
            let items = $(this).data('items');
            startLoading();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/deleteItemPutaway",
                data: {
                    items: items
                },
                dataType: "json",
                success: function(response) {
                    stopLoading();
                    if (response.success == true) {
                        getItemScan();
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        });

        $('#btnConfirm').click(function() {
            let putaway_number = $(this).data('put-no');
            completePutaway(putaway_number);
        });


        function completePutaway(putaway_number) {
            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to complete this putaway : ' + putaway_number + '?',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    startLoading();
                    $.ajax({
                        url: '<?= site_url('putaway/completePutaway') ?>',
                        type: 'POST',
                        data: {
                            putaway_number: putaway_number
                        },
                        dataType: 'json',
                        success: function(response) {
                            stopLoading();
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    allowEnterKey: false,
                                    stopKeydownPropagation: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                }).then((result) => {
                                    successSound.play();
                                    window.history.back();
                                })
                            } else {
                                failedSound.play();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: response.message
                                });
                            }
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelled',
                        text: 'The process has been cancelled.',
                        confirmButtonText: 'OK'
                    });
                }
            })

        }

    });
</script>