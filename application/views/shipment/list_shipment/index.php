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
            <div class="card-header d-flex">
                <a href="<?= base_url('shipment/index') ?>" class="btn btn-primary btn-sm">Create New</a>
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <input type="text" id="search" class="form-control-sm" placeholder="Search">
                </div>
                <table class="table table-bordered table-sm table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Shipment Number</th>
                            <th>Created Date</th>
                            <th>Customer Name</th>
                            <th>City</th>
                            <th>Trucker</th>
                            <th>Total Item</th>
                            <th>Total Qty Request</th>
                            <th>Total Qty Picked</th>
                            <th>Shipment Status</th>
                            <th>Created by</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($shipment->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->shipment_number ?></td>
                                <td><?= $data->created_at ?></td>
                                <td><?= $data->customer_name ?></td>
                                <td><?= $data->city ?></td>
                                <td><?= $data->trucker_name ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty_req ?></td>
                                <td><?= $data->qty_pick ?></td>
                                <td><?= $data->is_complete == 'N' ? 'Open' : 'Complete'; ?></td>
                                <td><?= $data->created_by ?></td>
                                <td>
                                    <a href="<?= base_url('shipment/index?edit=true&ob=' . $data->shipment_number) ?>" class="btn btn-sm btn-primary" title="<?= $data->is_complete == 'N' ? 'Edit' : 'View' ?>"><i class=" <?= $data->is_complete == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <?php if ($data->is_complete == 'N') { ?>
                                        <button class="btn btn-sm btn-success btnComplete fs-12" data-ob-number="<?= $data->shipment_number ?>" title="Click to complete the picking process"><i class="ri-check-fill"></i></button>
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