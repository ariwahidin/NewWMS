<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

<style>
    /* Custom warna tombol Excel */
    .dt-button.buttons-excel {
        background-color: #28a745;
        /* Warna hijau */
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
    }

    .dt-button.buttons-excel:hover {
        background-color: #218838;
        /* Warna hijau lebih gelap */
    }
</style>

<div class="row">
    <div class="col-md-12">
        <!-- <h5 class="mb-3">Header Justify Tabs</h5> -->
        <div class="card">
            <div class="card-header align-items-xl-center d-xl-flex">
                <!-- <p class="text-muted flex-grow-1 mb-xl-0">Use <code>card-header-pills</code> class to create header justify tab.</p> -->
                <div class="flex-shrink-0">
                    <ul class="nav nav-pills card-header-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#byItem" role="tab" aria-selected="false" tabindex="-1">
                                By Item
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#byLocation" role="tab" aria-selected="false" tabindex="-1">
                                By Location
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#byDetail" role="tab" aria-selected="true">
                                Detail
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active show" id="byItem" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                    // var_dump($by_item->result());
                                ?>
                                <table class="table table-nowrap table-bordered table-sm table-striped table-hover" id="byItemTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Item Code</th>
                                            <th>On Hand</th>
                                            <th>Allocated</th>
                                            <th>Available</th>
                                            <th>In Transit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($by_item->result() as $data) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data->item_code ?></td>
                                                <td><?= $data->on_hand ?></td>
                                                <td><?= $data->allocated ?></td>
                                                <td><?= $data->available ?></td>
                                                <td><?= $data->in_transit ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="byLocation" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-8">
                                <table class="table table-nowrap table-bordered table-sm table-striped table-hover" id="byLocationTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Location</th>
                                            <th>Item Code</th>
                                            <th>On Hand</th>
                                            <th>Allocated</th>
                                            <th>Available</th>
                                            <th>In Transit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($by_location->result() as $data) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data->location ?></td>
                                                <td><?= $data->item_code ?></td>
                                                <td><?= $data->on_hand ?></td>
                                                <td><?= $data->allocated ?></td>
                                                <td><?= $data->available ?></td>
                                                <td><?= $data->in_transit ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="byDetail" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-10">
                                <table class="table table-nowrap table-bordered table-sm table-striped table-hover" id="byDetailTable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Location</th>
                                            <th>Item Code</th>
                                            <th>Receive Date</th>
                                            <th>Expiry Date</th>
                                            <th>QA</th>
                                            <th>On Hand</th>
                                            <th>Allocated</th>
                                            <th>Available</th>
                                            <th>In Transit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($by_detail->result() as $data) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $data->location ?></td>
                                                <td><?= $data->item_code ?></td>
                                                <td><?= $data->receive_date ?></td>
                                                <td><?= $data->expiry_date ?></td>
                                                <td><?= $data->qa ?></td>
                                                <td><?= $data->on_hand ?></td>
                                                <td><?= $data->allocated ?></td>
                                                <td><?= $data->available ?></td>
                                                <td><?= $data->in_transit ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
</div>

<!-- jQuery, DataTables, and Buttons JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#byItemTable').DataTable({
            paging: false, // Disable pagination
            info: false, // Optional: Hide table information
            dom: 'Bfrtip', // Show buttons (export options)
            buttons: [{
                extend: 'excelHtml5',
                text: 'Excel', // Custom text on button
                className: 'btn btn-success', // Custom class (if needed)
                filename: 'Inventory By Item ' + '<?= date('Y-m-d') ?>', // Custom filename
                title: 'Inventory By Item', // Custom title in Excel sheet
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5] // Export specific columns (index-based)
                }
            }]
        });

        $('#byLocationTable').DataTable({
            paging: false, // Disable pagination
            info: false, // Optional: Hide table information
            // searching: false // Optional: Disable search box if not needed
            dom: 'Bfrtip', // Show buttons (export options)
            buttons: [{
                extend: 'excelHtml5',
                text: 'Excel', // Custom text on button
                className: 'btn btn-success', // Custom class (if needed)
                filename: 'Inventory By Location ' + '<?= date('Y-m-d') ?>', // Custom filename
                title: 'Inventory By Location', // Custom title in Excel sheet
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // Export specific columns (index-based)
                }
            }]
        });

        $('#byDetailTable').DataTable({
            paging: false, // Disable pagination
            info: false, // Optional: Hide table information
            // searching: false // Optional: Disable search box if not needed
            dom: 'Bfrtip', // Show buttons (export options)
            buttons: [{
                extend: 'excelHtml5',
                text: 'Excel', // Custom text on button
                className: 'btn btn-success', // Custom class (if needed)
                filename: 'Inventory By Detail ' + '<?= date('Y-m-d') ?>', // Custom filename
                title: 'Inventory By Detail', // Custom title in Excel sheet
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] // Export specific columns (index-based)
                }
            }]
        });
    });
</script>