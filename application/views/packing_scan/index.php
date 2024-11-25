<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" id="viewPicking">
                        <i class="ri ri-survey-line"></i>
                    </button>
                    <span id="spanProgress">0</span>%
                    <button id="btnConfirm" class="btn btn-success float-end d-inline"> <i class="mdi mdi-checkbox-marked-circle-outline"></i> CONFIRM</button>
                </h5>
            </div>
            <div class="card-body table-responsive text-end" id="divFormPacking">
                <form id="packingForm" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="" class="form-label">Shipment Number : </label></td>
                            <td>
                                <input type="text" name="shipment_number" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Item Code : </label></td>
                            <td>
                                <input type="text" style="max-width: 128px;" name="item_code" class="form-control-sm">
                                <button class="btn btn-danger btn-sm" id="btnClear">
                                    <i class="ri ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Qty : </label></td>
                            <td>
                                <input type="text" style="max-width: 78px;" name="qty_in" class="form-control-sm" required>
                                <input type="hidden" name="qty_uom" class="form-control-sm">
                                <input type="text" style="max-width: 78px;" name="uom" class="form-control-sm" readonly placeholder="UoM" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="" class="form-label">Carton No. : </label></td>
                            <td>
                                <input type="text" name="ctn_no" class="form-control-sm" value="001">
                            </td>
                        </tr>
                    </table>
                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button onclick="document.getElementById('packingForm').reset()" type="button" style="min-width: 100px;" class="btn btn-warning">CANCEL</button>
                        <button type="button" style="min-width: 100px;" class="btn btn-info" id="btnSearch">SEARCH</button>
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">PACK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-sm" id="tablePacking">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ItemCode</th>
                            <th>Qty</th>
                            <th>UoM</th>
                            <th>Pcs</th>
                            <th>Carton</th>
                            <th>Remove</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPick" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Picked Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped" id="tablePick">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Item Code</th>
                            <th>Qty Pick (Pcs)</th>
                            <th>Qty Pack (Pcs)</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#packingForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= base_url('packingScan/savePacking') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        getPackingDetail();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message
                        })
                    }
                }
            });
        });

        $('#btnClear').on('click', function() {
            $("input[name='qty_in']").val('');
            $("input[name='qty_uom']").val('');
            $("input[name='uom']").val('');
        })

        $('#btnSearch').click(function() {
            $("input[name='qty_in']").val('');
            $("input[name='qty_uom']").val('');
            $("input[name='uom']").val('');
            $.ajax({
                url: '<?= base_url('packingScan/searchShipment') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let item = response.data[0];
                        $("input[name='qty_in']").val(item.qty_in);
                        $("input[name='qty_uom']").val(item.qty_uom);
                        $("input[name='uom']").val(item.uom);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: response.message
                        })
                    }
                    getPackingDetail();
                }
            });
        });

        function getPackingDetail() {

            let tbody = $('#tablePacking tbody');

            $.ajax({
                url: '<?= base_url('packingScan/getPackingDetail') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.qty_in}</td>
                                            <td>${item.uom}</td>
                                            <td>${item.qty}</td>
                                            <td>${item.carton}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btnRemove" data-id="${item.id}">
                                                    <i class="ri ri-delete-bin-line"></i>
                                                </button> 
                                            </td>
                                        </tr>
                                    `);
                        }

                        $('#spanProgress').text(response.progress);
                    } else {

                    }
                }
            });
        }

        $('#tablePacking').on('click', '.btnRemove', function() {
            let id = $(this).data('id');
            removePackingDetail(id);
        });

        function removePackingDetail(id) {
            $.ajax({
                url: '<?= base_url('packingScan/removePackingDetail') ?>',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        getPackingDetail();
                    } else {}
                }
            });
        }

        $('#viewPicking').on('click', function() {
            let tbody = $('#tablePick tbody');
            $.ajax({
                url: '<?= base_url('packingScan/getItemsPicking') ?>',
                type: 'POST',
                data: $('#packingForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let items = response.data;
                        tbody.empty();
                        for (let i = 0; i < items.length; i++) {
                            let item = items[i];
                            tbody.append(`
                                        <tr>
                                            <td>${i + 1}</td>
                                            <td>${item.item_code}</td>
                                            <td>${item.qty_pick}</td>
                                            <td>${item.qty_pack}</td>
                                            </tr>
                                            `);
                        }
                        $('#modalPick').modal('show');
                    } else {}
                }

            })
        })
    });
</script>