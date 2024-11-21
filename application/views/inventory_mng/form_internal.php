<div class="row">
    <div class="col-md-4 col-sm-12 mb-0">
        <button id="backButton" type="button" class="btn btn-primary btn-sm mt-0 mb-2"><i class="ri-arrow-left-line"></i> Back</button>
        <div class="card">
            <!-- <div class="card-header">
                <h5 class="fs-15 fw-semibold mb-0">
                    <button type="button" class="btn btn-sm btn-circle btn-outline-secondary" id="viewItem">
                        <i class="ri ri-survey-line"></i>
                    </button>
                </h5>
            </div> -->
            <div class="card-body table-responsive text-center justify-content-center">
                <form id="inventoryForm" action="<?= base_url('InventoryMng/transferLoc') ?>" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">LOCATION </label></td>
                            <td>
                                : <input type="text" name="location" class="form-control-sm" maxlength="8">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">LPN </label></td>
                            <td>
                                : <input type="text" name="lpn" class="form-control-sm">
                            </td>
                        </tr>
                        <tr>
                            <td><label for="firstNameinput" class="form-label">ITEM </label></td>
                            <td>
                                : <input type="text" name="item_code" class="form-control-sm">
                            </td>
                        </tr>
                    </table>

                    <div class="modal-footer mt-3 mb-0 pb-0 border-0 gap-2 d-flex d-inline justify-content-center">
                        <button onclick="document.getElementById('putawayForm').reset()" type="button" style="min-width: 100px;" class="btn btn-warning">CANCEL</button>
                        <button type="submit" style="min-width: 100px;" class="btn btn-primary">OK</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        document.getElementById('backButton').addEventListener('click', function() {
            window.history.back();
        });
    });
</script>