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
            <div class="card-header d-flex">
                <a href="<?= base_url('shipment/index') ?>" class="btn btn-primary btn-sm">Create New</a>
                <div class="form-check form-switch form-switch-right form-switch-md ms-3 mt-1">
                    <label for="default-rangeslider" class="form-label text-muted">Include Confirm Shipment</label>
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
                <table class="table table-bordered table-nowrap table-sm table-striped table-hover" id="tableShipment">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Shipment Number</th>
                            <th>Created Date</th>
                            <th>Customer Name</th>
                            <th>City</th>
                            <th>Trucker</th>
                            <th>Item</th>
                            <th>Qty Request</th>
                            <th>Qty Picked</th>
                            <th>Shipment Status</th>
                            <th>Created by</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($shipment->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <a href="<?= base_url('shipment/index?edit=true&ob=' . $data->shipment_number) ?>" class="btn btn-sm btn-primary" title="<?= $data->is_complete == 'N' ? 'Edit' : 'View' ?>"><i class=" <?= $data->is_complete == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <a href="<?= base_url('shipment/index?edit=true&ob=' . $data->shipment_number . "&copy=true") ?>" class="btn btn-sm btn-warning" title="copy shipment"><i class="ri ri-file-copy-2-line"></i></a>
                                    <?php if ($data->is_complete == 'N') { ?>
                                        <button class="btn btn-sm btn-success btnComplete fs-12" data-ob-number="<?= $data->shipment_number ?>" title="Confirm and start picking"><i class="ri-check-fill"></i></button>
                                    <?php } ?>
                                </td>
                                <td><?= $data->shipment_number ?></td>
                                <td><?= $data->created_at == null ? '' : date('Y-m-d', strtotime($data->created_at)) ?></td>
                                <td><?= $data->customer_name ?></td>
                                <td><?= $data->city ?></td>
                                <td><?= $data->trucker_name ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty_req ?></td>
                                <td><?= $data->qty_pick ?></td>
                                <td><?= $data->is_complete == 'N' ? 'Open' : 'Complete'; ?></td>
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

        $('#tableShipment').DataTable({
            scrollX: true,
            fixedColumns: {
                leftColumns: 4
            }
        })

        $('#includeConfirm').on('change', function() {
            if ($(this).is(':checked')) {
                window.location = `<?= base_url('shipment/list?includeConfirm=true') ?>`;
            } else {
                window.location = `<?= base_url('shipment/list?includeConfirm=false') ?>`;
            }
        })

        $('.btnComplete').on('click', function() {
            let ob_number = $(this).data('ob-number');
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
                    $.post('<?= site_url('picking/create') ?>', {
                        ob_no: ob_number
                    }, function(response) {
                        if (response.success == true) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                timer: 1500,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading()
                                },
                                text: response.message
                            }).then(() => {
                                window.location = `<?= base_url('picking/list') ?>`;
                            })
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
    })
</script>