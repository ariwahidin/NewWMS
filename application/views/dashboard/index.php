<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <span class="me-3" id="spConnect"></span>
                        <a href="javascript: void(0);">Dashboards </a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-success mb-3" role="tablist">
            <li class="nav-item waves-effect waves-light" role="presentation">
                <a class="nav-link active btnDash" data-bs-toggle="tab" href="#" data-tab="DashboardDaily" role="tab" aria-selected="false" tabindex="-1">Daily</a>
            </li>
            <li class="nav-item waves-effect waves-light" role="presentation">
                <a class="nav-link btnDash" data-bs-toggle="tab" href="#" role="tab" data-tab="DashboardMonthly" aria-selected="false" tabindex="-1">Monthly</a>
            </li>
        </ul>
    </div>
</div> -->

<div class="row">
    <div class="col-md-12">
        <div  class="card d-none">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white">Shipments last 7 days</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm" id="tableShipments">
                    <thead class="bg-secondary">
                        <tr style="white-space: nowrap;">
                            <th>No</th>
                            <th>WMS</th>
                            <th>Shipment ID</th>
                            <th>Ship To</th>
                            <th>Customer Name</th>
                            <th>City</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            foreach($shipments as $data){
                        ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['WMS_CODE'] ?></td>
                            <td><?= $data['SHIPMENT_ID'] ?></td>
                            <td><?= $data['SHIP_TO_ADDRESS1'] ?></td>
                            <td><?= $data['SHIP_TO_NAME'] ?></td>
                            <td><?= $data['SHIP_TO_CITY'] ?></td>
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

<script src="<?= base_url('jar/html/default/') ?>assets/libs/echarts/echarts.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tableShipments').DataTable();
    });
</script>