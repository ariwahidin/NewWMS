<link href="<?= base_url() ?>myassets/css/select2.min.css" rel="stylesheet" />
<style>
    .swal2-container {
        z-index: 9999;
    }
</style>

<div class="row">
    <div class="col col-md-12">
        <div class="card">
            <div class="card-header bg-primary d-flex">
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
                            <th>Total Qty</th>
                            <th>Status</th>
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
                                <td><?= $data->receive_number ?></td>
                                <td><?= $data->receive_date ?></td>
                                <td><?= $data->total_qty ?></td>
                                <td><?= $data->is_complete ?? ''; ?></td>
                                <td>
                                    <!-- <a href="<?= base_url() ?>order/spkShow?spk=<?= $data->receive_number ?>" class="btn btn-sm btn-secondary btnPrint" data-id="<?= $data->id ?>">Print</a> -->
                                    <!-- <a href="<?= base_url() ?>order/planningOrder?edit=true&spk=<?= $data->receive_number ?>" class="btn btn-sm btn-primary btnView" data-id="<?= $data->id ?>">View</a> -->
                                    <a href="<?= base_url() ?>receiving/putaway?id=<?= $data->id ?>" class="btn btn-sm btn-secondary btnPutaway" data-id="<?= $data->id ?>">Putaway</a>
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
    $(document).ready(function(){


    })
</script>