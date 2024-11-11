<link href="<?= base_url() ?>myassets/css/select2.min.css" rel="stylesheet" />
<style>
    .swal2-container {
        z-index: 9999;
    }
</style>

<style>
    .table-hover tbody tr:hover {
        background-color: yellowgreen !important;
        /* Warna hijau btn-success */
        /* color: #fff; */
        /* Warna teks putih agar kontras */
    }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>

<div class="row">
    <div class="col col-md-12">
        <div class="card">
            <div class="card-header d-flex">
                <h5>Putaway List</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover table-nowrap table-sm table-striped table-hover" id="tablePutaway">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Putaway Number</th>
                            <th>Receiving Number</th>
                            <th>Receiving Date</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>No Truck</th>
                            <th>Receive Qty</th>
                            <th>Putaway Qty</th>
                            <th>Putaway Status</th>
                            <th>Created by</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($receive->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <a href="<?= base_url('putaway/desktop?edit=true&put_no=' . $data->putaway_number) ?>" class="btn btn-sm btn-primary" title=" <?= $data->putaway_status == 'N' ? 'Edit' : 'View' ?>"><i class="<?= $data->putaway_status == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <a href="<?= base_url('putaway/printPutawaySheet?put_no=' . $data->putaway_number . '&rcv_no=' . $data->receive_number . '&type=print') ?>" class="btn btn-sm btn-info" target="_blank" rel="noopener noreferrer" title="Print Putaway Sheet"> <i class="ri-printer-fill"></i></a>

                                    <?php if ($data->putaway_status == 'N') { ?>
                                        <button type="button" class="btn btn-sm btn-success d-inline" data-put-number="<?= $data->putaway_number ?>" id="confirmPutaway" title="Confirm Putaway"> <i class="ri-check-double-fill"></i></button>
                                        <a href="<?= base_url('putaway/desktop?partial=true&edit=true&put_no=' . $data->putaway_number) ?>" class="btn btn-sm btn-warning d-none" title="Partial Putaway"><i class="ri-check-fill"></i></a>
                                    <?php } ?>
                                </td>
                                <td> <a href="<?= base_url('putaway/desktop?edit=true&put_no=' . $data->putaway_number) ?>"><?= $data->putaway_number ?></a></td>
                                <td><?= $data->receive_number ?></td>
                                <td><?= $data->receive_date ?></td>
                                <td><?= $data->po_number ?></td>
                                <td><?= $data->supplier_name ?></td>
                                <td><?= $data->truck_no ?></td>
                                <td><?= $data->total_qty ?></td>
                                <td><?= $data->qty_putaway ?></td>
                                <td><?= $data->putaway_status == 'N' ? 'Pending' : 'Complete'; ?></td>
                                <td><?= $data->created_by ?></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>myassets/js/select2.min.js"></script>

<script>
    $(document).ready(function() {


        $('#tablePutaway').DataTable({
            scrollX: true,
            fixedColumns: {
                leftColumns: 4
            },
            columnDefs: [{
                orderable: false,
                targets: 1
            }]
        });

        $('.btnComplete').on('click', function() {
            let rcv_number = $(this).data('rcv-number');

            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to proceed, you cannot undo this action?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location = `<?= site_url('receiving/complete/') ?>${rcv_number}`;
                    $.post('<?= site_url('putaway/create') ?>', {
                        ib_no: rcv_number
                    }, function(response) {
                        if (response) {
                            window.location = `<?= site_url('putaway/desktop?edit=true&ib=') ?>${rcv_number}`;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: response.message
                            });
                        }
                    }, 'JSON');
                }
            })
        });

        $('#confirmPutaway').on('click', function() {
            let putaway_number = $(this).data('put-number');
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
                    // If the user confirms, call the proccess() function
                    $.ajax({
                        url: '<?= site_url('putaway/completePutaway') ?>',
                        type: 'POST',
                        data: {
                            putaway_number: putaway_number
                        },
                        dataType: 'json',
                        success: function(response) {
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
                                    window.location = `<?= site_url('putaway/putawayList') ?>`;
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
    })
</script>