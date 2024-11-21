<div class="row">
    <div class="col-md-4 col-sm-12">
        <button id="backButton" type="button" class="btn btn-primary btn-sm mt-0 mb-2"><i class="ri-arrow-left-line"></i> Back</button>
        <div class="card">
            <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" id="viewItem">
                        <i class="ri ri-survey-line"></i>
                    </button>
                </h5>
            </div>
            <div class="card-body table-responsive text-center justify-content-center">

                <form id="transferLocItemFormSearch">
                    <input type="hidden" name="s_location" value="<?= $_POST['location'] ?? '' ?>">
                    <input type="hidden" name="s_item_code" value="<?= $_POST['item_code'] ?? '' ?>">
                    <input type="hidden" name="s_lpn" value="<?= $_POST['lpn'] ?? '' ?>">
                </form>

                <form id="transferLocItemForm">
                    <?php
                    $item1 = $items[0] ?? '';
                    ?>
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">WHS</label></td>
                            <td>
                                <input type="hidden" name="inventory_id" class="form-control-sm" value="<?= $item1->id ?? '' ?>" readonly>
                                <input type="hidden" name="whs_code" class="form-control-sm" value="<?= $item1->whs_code ?? '' ?>" readonly>
                                : <input type="text" name="whs_name" class="form-control-sm" value="<?= $item1->whs_code ?? '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">FROM LOC. </label></td>
                            <td>
                                : <input type="text" name="location" class="form-control-sm" value="<?= $item1->location ?? '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">LPN </label></td>
                            <td>
                                : <input type="text" name="lpn" class="form-control-sm" value="<?= $item1->lpn_number ?? '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">ITEM CODE</label></td>
                            <td>
                                : <input type="text" name="item_code" class="form-control-sm" value="<?= $item1->item_code ?? '' ?>" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">QTY (PCS)</label></td>
                            <td>
                                : <input type="number" name="qty_in" class="form-control-sm" value="<?= $item1->available ?? '' ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">TO LOC. </label></td>
                            <td>
                                : <input type="text" name="to_location" minlength="8" maxlength="8" class="form-control-sm" required>
                            </td>
                        </tr>
                    </table>

                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button onclick="window.location.history.back();" type="button" style="min-width: 100px;" class="btn btn-warning">CANCEL</button>
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">OK</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="itemDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: scroll ; max-height: 600px;" id="modalBodyItem">
                <?php
                foreach ($items as $item) {
                ?>
                    <div class="card bg-default bg-soft-light btnCard" data-item='<?= json_encode($item) ?>'>
                        <div class="card-body" style="background-color: greenyellow; cursor: pointer;">
                            <div class="d-flex align-items-center mb-1">
                                <div class="flex-grow-1">
                                    <h5 class="fs-15 mb-0"><?= $item->item_code ?></h5>
                                    <p class="text-muted mb-0"><?= $item->item_name ?></p>
                                    <p class="text-muted mb-0"><span><?= $item->whs_code ?></span> | <span><?= $item->location ?></span> | <span><?= $item->lpn_number ?></span> | <span><?= $item->grn_number ?></span></p>
                                    <p class="text-muted mb-0"><span>Available</span> : <span><?= $item->available ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        document.getElementById('backButton').addEventListener('click', function() {
            window.history.back();
        });

        $('#viewItem').click(function() {
            $('#itemDetailModal').modal('show');
        });

        $('#modalBodyItem').on('click', '.btnCard', function() {
            let item = $(this).data('item');
            $('input[name="inventory_id"]').val(item.id);
            $('input[name="whs_code"]').val(item.whs_code);
            $('input[name="whs_name"]').val(item.whs_code);
            $('input[name="location"]').val(item.location);
            $('input[name="lpn"]').val(item.lpn_number);
            $('input[name="item_code"]').val(item.item_code);
            $('input[name="item_name"]').val(item.item_name);
            $('input[name="qty_in"]').val(item.available);
            $('#itemDetailModal').modal('hide');
        });

        $('#transferLocItemForm').on('submit', function(e) {
            e.preventDefault();
            startLoading();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>InventoryMng/proccessTransfer",
                data: $('#transferLocItemForm').serialize(),
                dataType: "json",
                success: function(response) {
                    stopLoading();
                    if (response.success == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1000,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            stopKeydownPropagation: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        }).then((result) => {
                            getItemToTransfer();
                        })
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        })

        getItemToTransfer();

        function getItemToTransfer() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url() ?>InventoryMng/getItemToTransfer",
                data: $('#transferLocItemFormSearch').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success == true) {
                        $('#modalBodyItem').html('');

                        let items = response.items;

                        items.forEach(element => {
                            let card = ` <div class="card bg-default bg-soft-light btnCard" data-item='${JSON.stringify(element)}'>
                                            <div class="card-body" style="background-color: greenyellow; cursor: pointer;">
                                                <div class="d-flex align-items-center mb-1">
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-15 mb-0">${element.item_code}</h5>
                                                        <p class="text-muted mb-0">${element.item_name}</p>
                                                        <p class="text-muted mb-0"><span>${element.whs_code}</span> | <span>${element.location}</span> | <span>${element.lpn_number}</span> | <span>${element.grn_number}</span></p>
                                                        <p class="text-muted mb-0"><span>Available</span> : <span>${element.available}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            $('#modalBodyItem').append(card);
                        });
                    } else {
                        // Swall
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                }
            });
        }
    });
</script>