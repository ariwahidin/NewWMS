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
            <div class="card-header">
                <!-- <h5 clas="card-title">Packing List</h5> -->
                <a href="<?= base_url('packing/createNew') ?>" class="btn btn-primary btn-sm">Create New</a>
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                </div>
                <table class="table table-bordered table-hover table-sm table-striped table-hover table-nowrap" id="tablePacking">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Action</th>
                            <th>Packing Number</th>
                            <th>Shipment Number</th>
                            <th>Packing Date</th>
                            <th>Total Items</th>
                            <th>Total Ctn</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($packing->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <a href="<?= base_url('packing/createNew?pack=' . $data->packing_number . '&ship=' . $data->shipment_number) ?>" class="btn btn-primary btn-sm" title="Edit"><i class="ri-edit-line"></i></a>
                                    <a href="<?= base_url('packing/printPackingSheet?pack=' . $data->packing_number . '&ship=' . $data->shipment_number) ?>" class="btn btn-info btn-sm" title="Print Packing Sheet" target="_blank"><i class="ri-printer-line"></i></a>
                                </td>
                                <td><?= $data->packing_number ?></td>
                                <td><?= $data->shipment_number ?></td>
                                <td><?= date('Y-m-d', strtotime($data->created_at)) ?></td>
                                <td><?= $data->total_qty ?></td>
                                <td><?= $data->total_ctn ?></td>
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

        $('#tablePacking').DataTable({
            // scrollX: true,
            // fixedColumns: {
            //     leftColumns: 4 // Mengunci 3 kolom pertama
            // }
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