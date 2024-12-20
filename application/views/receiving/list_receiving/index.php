<link href="<?= base_url() ?>myassets/css/select2.min.css" rel="stylesheet" />
<style>
    .swal2-container {
        z-index: 9999;
    }
</style>

<style>
    .table-hover tbody tr:hover {
        background-color: yellowgreen;
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
            <div class="card-header bg-default d-flex">
                <a href="<?= base_url('receiving/create') ?>" class="btn btn-success btn-sm">Create New</a>
                <div class="form-check form-switch form-switch-right form-switch-md ms-3 mt-1">
                    <label for="default-rangeslider" class="form-label text-muted">Include Confirm Receive</label>
                    <?php
                    $checked = '';
                    if (isset($_GET['includeConfirm']) && $_GET['includeConfirm'] == 'true') {
                        $checked = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" <?= $checked ?> id="includeConfirm">
                </div>
            </div>
            <div class="card-body table-responsive">
                <table style="font-size: 12px;" class="table table-bordered table-nowrap table-sm table-striped table-hover fs-sm" id="receiveTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Rcv No.</th>
                            <th>Rcv Date</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Trucker</th>
                            <th>No Truck</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Status</th>
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
                                    <a href="<?= base_url('receiving/edit?edit=true&ib=' . $data->receive_number) ?>" class="btn btn-sm btn-primary" title="<?= $data->is_complete == 'N' ? 'Edit' : 'View' ?>"><i class=" <?= $data->is_complete == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <a href="<?= base_url('receiving/edit?copy=true&edit=false&ib=' . $data->receive_number) ?>" class="btn btn-sm btn-warning" title="Copy Receive"><i class="ri-file-copy-2-fill"></i></a>
                                    <?php if ($data->is_complete == 'N') { ?>
                                        <button class="btn btn-sm btn-success btnComplete fs-12" data-rcv-number="<?= $data->receive_number ?>" title="Click to complete the receiving process and proceed to putaway"><i class="ri-check-fill"></i></button>
                                    <?php } ?>
                                </td>
                                <td> <a href="<?= base_url('receiving/index?edit=true&ib=' . $data->receive_number) ?>"><?= $data->receive_number ?></a></td>
                                <td><?= $data->receive_date ?></td>
                                <td><?= $data->po_number ?></td>
                                <td><?= $data->supplier_name ?></td>
                                <td><?= $data->ekspedisi_name ?></td>
                                <td><?= $data->truck_no ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty ?></td>
                                <td><?= $data->is_complete == 'N' ? 'Open' : 'Confirmed'; ?></td>
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

        $('#receiveTable').DataTable({});

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
                    $.post('<?= site_url('putaway/create') ?>', {
                        ib_no: rcv_number
                    }, function(response) {
                        if (response.success == true) {
                            let put_no = response.putaway_number;
                            window.location = `<?= base_url('putaway/putawayList') ?>`;
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


        $('#includeConfirm').on('change', function() {
            if ($(this).is(':checked')) {
                window.location = `<?= base_url('receiving/receivingList?includeConfirm=true') ?>`;
            } else {
                window.location = `<?= base_url('receiving/receivingList?includeConfirm=false') ?>`;
            }
        })
    })
</script>