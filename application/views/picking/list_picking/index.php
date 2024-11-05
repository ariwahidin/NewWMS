<link href="<?= base_url() ?>myassets/css/select2.min.css" rel="stylesheet" />
<style>
    .swal2-container {
        z-index: 9999;
    }
</style>

<style>
    .table-hover tbody tr:hover {
        background-color: #28a745;
        /* Warna hijau btn-success */
        color: #fff;
        /* Warna teks putih agar kontras */
    }
</style>

<div class="row">
    <div class="col col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 clas="card-title">Picking List</h5>
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <input type="text" id="search" class="form-control-sm" placeholder="Search">
                </div>
                <table class="table table-bordered table-hover table-sm table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Picking Number</th>
                            <th>Shipment Number</th>
                            <th>Created Date</th>
                            <th>Customer</th>
                            <th>Total Items</th>
                            <th>Req Qty</th>
                            <th>Picked Qty</th>
                            <th>Picking Status</th>
                            <th>Created by</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($picking->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td> <a href="<?= base_url('picking/index?edit=true&pick_no=' . $data->picking_number) ?>"><?= $data->picking_number ?></a></td>
                                <td><?= $data->shipment_number ?></td>
                                <td><?= $data->created_at ?></td>
                                <td><?= $data->customer_name ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty_req ?></td>
                                <td><?= $data->qty_pick ?></td>
                                <td><?= $data->picking_status == 'N' ? 'Pending' : 'Complete'; ?></td>
                                <td><?= $data->created_by ?></td>
                                <td>
                                    <a href="<?= base_url('picking/index?edit=true&pick_no=' . $data->picking_number) ?>" class="btn btn-sm btn-primary" title=" <?= $data->picking_status == 'N' ? 'Edit' : 'View' ?>"><i class="<?= $data->picking_status == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <a href="<?= base_url('picking/printPickingSheet?pick_no=' . $data->picking_number . '&ship_no=' . $data->shipment_number . '&type=print') ?>" class="btn btn-sm btn-info" target="_blank" rel="noopener noreferrer" title="Print Picking Sheet"> <i class="ri-printer-fill"></i></a>

                                    <?php if ($data->picking_status == 'N') { ?>
                                        <button type="button" class="btn btn-sm btn-primary d-inline confirmPicking" data-pick-number="<?= $data->picking_number ?>" title="Complete Picking"> <i class="ri-check-fill"></i></button>
                                    <?php } ?>
                                </td>
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
        $('.confirmPicking').on('click', function() {
            let pick_number = $(this).data('pick-number');

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
                    $.post('<?= site_url('picking/completePicking') ?>', {
                        pick_no: pick_number
                    }, function(response) {
                        if (response.success) {
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
                                location.reload();
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