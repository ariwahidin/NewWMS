<div class="row">
    <div class="col-md-4 col-sm-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" data-rcv="<?= $_POST['receiveNumber'] ?>" id="viewItem"><i class="ri ri-survey-line"></i></button>
                    <span><?= $_POST['receiveNumber'] ?></span> / <span><?= $putaway->putaway_number ?></span>
                </h5>
                <button id="btnConfirm" class="btn btn-success float-end d-inline" data-put-no="<?= $putaway->putaway_number ?>"> <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM</button>
                <span class="text-muted">Progress : <span id="percentProgress">0</span>%</span>
                <!-- <div class="d-flex flex-wrap justify-content-evenly">
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2"></i>Exp Qty
                    </p>
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2"></i>Qty Scanned
                    </p>
                    <p class="text-muted mb-0"><i class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2"></i>To Do</p>
                </div> -->
            </div>
            <!-- <div class="progress animated-progress rounded-bottom rounded-0" style="height: 6px;">
                <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
            </div> -->
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
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">OK</button>
                    </div>

                </form>
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

<!-- <div class="row">
    <div class="col-md-6 col-sm-12">
        <button class="btn btn-primary btn-block">Complete Putaway</button>
    </div>
</div> -->

<div class="modal fade" id="itemDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Item</h5>
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
<!-- <div class="modal fade" id="detailPutaway" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div> -->



<script>
    $(document).ready(function() {
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
                                    window.history.back();
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: response.message
                                });
                            }
                        }
                    })
                } else {
                    // If the user cancels, show a message or handle accordingly
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelled',
                        text: 'The process has been cancelled.',
                        confirmButtonText: 'OK'
                    });
                }
            })

        }



        // $('#viewReceive').click(function() {

        //     let receiveNumber = $('#receiveNumber').val().trim();

        //     if (receiveNumber == '') {
        //         // Swall
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: 'Please select receive number!',
        //         })
        //         return false;
        //     } else {
        //         startLoading();
        //         // ajax
        //         $.ajax({
        //             type: "GET",
        //             url: "<?php echo base_url() ?>putawayScan/getPutawayHeader?rcv=" + receiveNumber,
        //             dataType: "json",
        //             success: function(response) {
        //                 stopLoading();
        //                 if (response.success == true) {
        //                     let detail = response.detail;
        //                     var html = '';
        //                     var i;
        //                     for (i = 0; i < detail.length; i++) {
        //                         html += '<tr>' +
        //                             '<td>' + (i + 1) + '</td>' +
        //                             '<td>' + detail[i].item_code + '</td>' +
        //                             '<td>' + detail[i].lpn_number + '</td>' +
        //                             '<td>' + detail[i].qty + '</td>' +
        //                             '</tr>';
        //                     }
        //                     $('#detailReceive tbody').html(html);
        //                     $('#detailReceive').modal('show');
        //                 } else {
        //                     // Swall
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Oops...',
        //                         text: response.message,
        //                     })
        //                 }
        //             }
        //         });
        //     }

        // });

        // $('#viewLPN').click(function() {

        //     let receiveNumber = $('#receiveNumber').val().trim();
        //     let lpnNumber = $('#lpnNumber').val().trim();
        //     if (receiveNumber == '' || lpnNumber == '') {
        //         // Swall
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: 'Please select receive number and LPN number!',
        //         })
        //         return false;
        //     } else {
        //         startLoading();
        //         // ajax
        //         $.ajax({
        //             type: "GET",
        //             url: "<?php echo base_url() ?>putawayScan/getReceiveDetailByLpn?rcv=" + receiveNumber + "&lpn=" + lpnNumber,
        //             dataType: "json",
        //             success: function(response) {
        //                 stopLoading();
        //                 if (response.success == true) {
        //                     let detail = response.detail;
        //                     var html = '';
        //                     var i;
        //                     for (i = 0; i < detail.length; i++) {
        //                         html += '<tr>' +
        //                             '<td>' + (i + 1) + '</td>' +
        //                             '<td>' + detail[i].item_code + '</td>' +
        //                             '<td>' + detail[i].lpn_number + '</td>' +
        //                             '<td>' + detail[i].qty + '</td>' +
        //                             '</tr>';
        //                     }
        //                     $('#detailReceive tbody').html(html);
        //                     $('#detailReceive').modal('show');
        //                 } else {
        //                     // Swall
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Oops...',
        //                         text: response.message,
        //                     })
        //                 }
        //             }
        //         });
        //     }

        // });

        // $('#putawayForm').submit(function(e) {
        //     e.preventDefault();
        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url() ?>putawayScan/putawayProccess",
        //         data: $('#putawayForm').serialize(),
        //         dataType: "json",
        //         success: function(response) {
        //             if (response.success == true) {
        //                 // Swall
        //                 Swal.fire({
        //                     icon: 'success',
        //                     title: 'Success',
        //                     text: response.message,
        //                 })
        //                 $('#detailPutaway').modal('hide');
        //             } else {
        //                 // Swall
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Oops...',
        //                     text: response.message,
        //                 })
        //             }
        //         }
        //     });
        // });

        // function getItemToPutaway(lpnNumber, receiveNumber, putawayNumber) {
        //     startLoading();
        //     $.post("<?php echo base_url() ?>putawayScan/getItemToPutaway", {
        //         lpnNumber: lpnNumber,
        //         receiveNumber: receiveNumber,
        //         putawayNumber: putawayNumber
        //     }, function(response) {
        //         stopLoading();
        //         if (response.success == true) {
        //             $('#itemCode').val(response.data.item_code);
        //             $('#qty').val(response.data.qty);
        //             $('#location').focus();
        //         } else {
        //             // Swall
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Oops...',
        //                 text: response.message,
        //             })
        //         }
        //     }, "json");
        // }

        // $('#lpnNumber, #receiveNumber, #putawayNumber').on('keyup', function() {
        //     let lpnNumber = $('#lpnNumber').val();
        //     let receiveNumber = $('#receiveNumber').val();
        //     let putawayNumber = $('#putawayNumber').val();

        //     let is_request = false;
        //     if (lpnNumber.trim() != '' && receiveNumber.trim() != '' && putawayNumber.trim() != '') {
        //         setTimeout(function() {
        //             if (!is_request) {
        //                 getItemToPutaway(lpnNumber, receiveNumber, putawayNumber);
        //             }
        //             is_request = true;
        //         }, 1000);
        //     }
        // });

        // $('#deleteLPN').click(function() {
        //     $('#lpnNumber').val('');
        //     $('#itemCode').val('');
        //     $('#qty').val('');
        //     $('#location').val('');
        //     $('#lpnNumber').focus();
        // });

        // $('#receiveNumber').click(function() {
        //     $('#receiveNumber').val('');
        //     $('#putawayNumber').val('');
        //     $('#receiveNumber').focus();
        // });

        // $('#putawayNumber').click(function() {
        //     $('#putawayNumber').val('');
        //     $('#putawayNumber').focus();
        // });

    });
</script>