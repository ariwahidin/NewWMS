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
            <div class="card-header bg-primary d-flex">
                <a href="<?= base_url('receiving/index') ?>" class="btn btn-success btn-sm">Create New</a>
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <input type="text" id="search" class="form-control-sm" placeholder="Search">
                </div>
                <table class="table table-bordered table-sm table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Receiving Number</th>
                            <th>Receiving Date</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Trucker</th>
                            <th>No Truck</th>
                            <th>Total Item</th>
                            <th>Total Qty</th>
                            <th>Receiving Status</th>
                            <th>Created by</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($receive->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td> <a href="<?= base_url('receiving/index?edit=true&ib=' . $data->receive_number) ?>"><?= $data->receive_number ?></a></td>
                                <td><?= $data->receive_date ?></td>
                                <td><?= $data->po_number ?></td>
                                <td><?= $data->supplier_name ?></td>
                                <td><?= $data->ekspedisi_name ?></td>
                                <td><?= $data->truck_no ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty ?></td>
                                <td><?= $data->is_complete == 'N' ? 'Open' : 'Complete'; ?></td>
                                <td><?= $data->created_by ?></td>
                                <td>
                                    <a href="<?= base_url('receiving/index?edit=true&ib=' . $data->receive_number) ?>" class="btn btn-sm btn-primary" title="<?= $data->is_complete == 'N' ? 'Edit' : 'View' ?>"><i class=" <?= $data->is_complete == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <?php if ($data->is_complete == 'N') { ?>
                                        <!-- if hover describe the fungc -->
                                        <button class="btn btn-sm btn-success btnComplete fs-12" data-rcv-number="<?= $data->receive_number ?>" title="Click to complete the receiving process and proceed to putaway"><i class="ri-check-fill"></i></button>
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
                            window.location = `<?= site_url('putaway/desktop?edit=true&put_no=') ?>${put_no}`;
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