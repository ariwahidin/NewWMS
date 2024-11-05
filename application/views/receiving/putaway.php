<div class="row">
    <div class="col-md-4 col-sm-12">

        <div class="card">

            <div class="card-header">
                <!-- <h5 class="fs-15 fw-semibold">IB Number</h5> -->
                <!-- <p class="text-muted">Total Qty : 120</p> -->
                <div class="d-flex flex-wrap justify-content-evenly">
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2"></i>Exp Qty
                    </p>
                    <p class="text-muted mb-0">
                        <i class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2"></i>Qty Scanned
                    </p>
                    <!-- <p class="text-muted mb-0"><i class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2"></i>To Do</p> -->
                </div>
            </div>
            <div class="progress animated-progress rounded-bottom rounded-0" style="height: 6px;">
                <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                <!-- <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div> -->
            </div>
            <div class="card-body table-responsive">

                <form id="putawayForm">

                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Receive No : </label></td>
                            <td><input style="max-width: 160px;" id="receiveNumber" name="receiveNumber" type="text" class="form-control-sm"></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" id="viewReceive"><i class="ri-eye-line"></i></button>
                                <button type="button" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Putaway No : </label></td>
                            <td><input style="max-width: 160px;" type="text" id="putawayNumber" name="putawayNumber" class="form-control-sm" required></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" id="viewPutaway"><i class="ri-eye-line"></i></button>
                                <button type="button" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">LPN No : </label></td>
                            <td><input style="max-width: 160px;" name="lpnNumber" type="text" id="lpnNumber" class="form-control-sm" required></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" id="viewLPN"><i class="ri-eye-line"></i></button>
                                <button type="button" class="btn btn-sm btn-danger" id="deleteLPN"><i class=" ri-delete-bin-7-line"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Item Code : </label></td>
                            <td><input style="max-width: 160px;" name="itemCode" id="itemCode" type="text" class="form-control-sm" required></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Qty : </label></td>
                            <td><input style="max-width: 160px;" name="qty" id="qty" type="text" class="form-control-sm" required></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Location : </label></td>
                            <td><input style="max-width: 160px;" name="location" id="location" type="text" class="form-control-sm" required></td>
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
</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <button class="btn btn-primary btn-block">Complete Putaway</button>
    </div>
</div>

<div class="modal fade" id="detailReceive" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Receive Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>LPN</th>
                            <th>Qty</th>
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
        $('#viewPutaway').click(function() {
            $('#detailPutaway').modal('show');
        });

        $('#viewReceive').click(function() {

            let receiveNumber = $('#receiveNumber').val().trim();

            if (receiveNumber == '') {
                // Swall
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select receive number!',
                })
                return false;
            } else {
                startLoading();
                // ajax
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() ?>putawayScan/getPutawayHeader?rcv=" + receiveNumber,
                    dataType: "json",
                    success: function(response) {
                        stopLoading();
                        if (response.success == true) {
                            let detail = response.detail;
                            var html = '';
                            var i;
                            for (i = 0; i < detail.length; i++) {
                                html += '<tr>' +
                                    '<td>' + (i + 1) + '</td>' +
                                    '<td>' + detail[i].item_code + '</td>' +
                                    '<td>' + detail[i].lpn_number + '</td>' +
                                    '<td>' + detail[i].qty + '</td>' +
                                    '</tr>';
                            }
                            $('#detailReceive tbody').html(html);
                            $('#detailReceive').modal('show');
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

        });

        $('#viewLPN').click(function() {

            let receiveNumber = $('#receiveNumber').val().trim();
            let lpnNumber = $('#lpnNumber').val().trim();
            if (receiveNumber == '' || lpnNumber == '') {
                // Swall
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please select receive number and LPN number!',
                })
                return false;
            } else {
                startLoading();
                // ajax
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url() ?>putawayScan/getReceiveDetailByLpn?rcv=" + receiveNumber + "&lpn=" + lpnNumber,
                    dataType: "json",
                    success: function(response) {
                        stopLoading();
                        if (response.success == true) {
                            let detail = response.detail;
                            var html = '';
                            var i;
                            for (i = 0; i < detail.length; i++) {
                                html += '<tr>' +
                                    '<td>' + (i + 1) + '</td>' +
                                    '<td>' + detail[i].item_code + '</td>' +
                                    '<td>' + detail[i].lpn_number + '</td>' +
                                    '<td>' + detail[i].qty + '</td>' +
                                    '</tr>';
                            }
                            $('#detailReceive tbody').html(html);
                            $('#detailReceive').modal('show');
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

        });

        $('#putawayForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>putawayScan/putawayProccess",
                data: $('#putawayForm').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {
                        // Swall
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        })
                        $('#detailPutaway').modal('hide');
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

        function getItemToPutaway(lpnNumber, receiveNumber, putawayNumber) {
            startLoading();
            $.post("<?php echo base_url() ?>putawayScan/getItemToPutaway", {
                lpnNumber: lpnNumber,
                receiveNumber: receiveNumber,
                putawayNumber: putawayNumber
            }, function(response) {
                stopLoading();
                if (response.success == true) {
                    $('#itemCode').val(response.data.item_code);
                    $('#qty').val(response.data.qty);
                    $('#location').focus();
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

        $('#lpnNumber, #receiveNumber, #putawayNumber').on('keyup', function() {
            let lpnNumber = $('#lpnNumber').val();
            let receiveNumber = $('#receiveNumber').val();
            let putawayNumber = $('#putawayNumber').val();

            let is_request = false;
            if (lpnNumber.trim() != '' && receiveNumber.trim() != '' && putawayNumber.trim() != '') {
                setTimeout(function() {
                    if (!is_request) {
                        getItemToPutaway(lpnNumber, receiveNumber, putawayNumber);
                    }
                    is_request = true;
                }, 1000);
            }

            // $('#putawayForm').submit();

            // ajax
            // $.ajax({
            //     type: "GET",
            //     url: "<?php echo base_url() ?>putawayScan/getItemNameByCode?itemCode=" + itemCode,
            //     dataType: "json",
            //     success: function(response) {
            //         if (response.success == true) {
            //             $('#itemName').val(response.data.item_name);
            //         } else {
            //             // Swall
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: response.message,
            //             })
            //         }
            //     }
            // });
        });

        $('#deleteLPN').click(function() {
            $('#lpnNumber').val('');
            $('#itemCode').val('');
            $('#qty').val('');
            $('#location').val('');
            $('#lpnNumber').focus();
        });

        $('#receiveNumber').click(function() {
            $('#receiveNumber').val('');
            $('#putawayNumber').val('');
            $('#receiveNumber').focus();
        });

        $('#putawayNumber').click(function() {
            $('#putawayNumber').val('');
            $('#putawayNumber').focus();
        });

    });
</script>