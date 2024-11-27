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
            <!-- <div class="card-header">
                <h5 clas="card-title">Picking List</h5>
            </div> -->
            <div class="card-body table-responsive">
                <!-- <div class="mb-3">
                    <input type="text" id="search" class="form-control-sm" placeholder="Search">
                </div> -->
                <table class="table table-bordered table-hover table-sm table-striped table-hover table-nowrap" id="tablePicking">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Ship No.</th>
                            <th>Created</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Req Qty</th>
                            <th>Pick Qty</th>
                            <th>Status</th>
                            <th>Created by</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($picking->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <a href="<?= base_url('picking/index?edit=true&pick_no=' . $data->picking_number) ?>" class="btn btn-sm btn-primary" title=" <?= $data->picking_status == 'N' ? 'Edit' : 'View' ?>"><i class="<?= $data->picking_status == 'N' ? 'ri-edit-2-fill' : 'ri-eye-fill' ?>"></i></a>
                                    <a href="<?= base_url('picking/printPickingSheet?pick_no=' . $data->picking_number . '&ship_no=' . $data->shipment_number . '&type=print') ?>" class="btn btn-sm btn-info" target="_blank" rel="noopener noreferrer" title="Print Picking Sheet"> <i class="ri-printer-fill"></i></a>
                                    <a href="<?= base_url('picking/printLabel?pick_no=' . $data->picking_number . '&ship_no=' . $data->shipment_number . '&type=print') ?>" class="btn btn-sm btn-outline-info" target="_blank" rel="noopener noreferrer" title="Print Label"> <i class="ri-printer-fill"></i></a>
                                    <a href="<?= base_url('packingScan/index?ob=' . $data->shipment_number) ?>" class="btn btn-sm btn-warning" title="Packing"> <i class="ri ri-inbox-archive-line"></i></a>
                                    <a href="<?= base_url('ShippingLoading/index?ob=' . $data->shipment_number) ?>" class="btn btn-sm btn-outline-primary" title="Shipping Loading"> <i class="ri  ri-truck-line"></i></a>

                                    <?php if ($data->picking_status == 'N') { ?>
                                        <!-- <button type="button" class="btn btn-sm btn-primary d-inline confirmPicking" data-pick-number="<?= $data->picking_number ?>"  title="Complete Picking"> <i class="ri-check-fill"></i></button> -->
                                    <?php } ?>
                                </td>
                                <td><?= $data->shipment_number ?></td>
                                <td><?= date('Y-m-d', strtotime($data->created_at)) ?></td>
                                <td><?= $data->customer_name ?></td>
                                <td><?= $data->total_item ?></td>
                                <td><?= $data->total_qty_req ?></td>
                                <td><?= $data->qty_pick ?></td>
                                <td><?= $data->picking_status == 'N' ? 'Pending' : 'Complete'; ?></td>
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

        $('#tablePicking').DataTable({
            // scrollX: true,
            columnDefs: [
                { orderable: false, targets: 1 }
            ],
            fixedColumns: {
                leftColumns: 3 // Mengunci 3 kolom pertama
            }
        });

        // $('.confirmPicking').on('click', function() {
        //     let pick_number = $(this).data('pick-number');

        //     Swal.fire({
        //         icon: 'question',
        //         title: 'Are you sure?',
        //         text: 'Do you want to proceed, you cannot undo this action?',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, proceed',
        //         cancelButtonText: 'Cancel'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.post('<?= site_url('picking/completePicking') ?>', {
        //                 pick_no: pick_number
        //             }, function(response) {
        //                 if (response.success) {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'Success',
        //                         timer: 1500,
        //                         showConfirmButton: false,
        //                         timerProgressBar: true,
        //                         didOpen: () => {
        //                             Swal.showLoading()
        //                         },
        //                         text: response.message
        //                     }).then(() => {
        //                         location.reload();
        //                     })
        //                 } else {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Failed',
        //                         text: response.message
        //                     });
        //                 }
        //             }, 'JSON');
        //         }
        //     })
        // });
    })
</script>