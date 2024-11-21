<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body table-responsive text-center justify-content-center">
                <form id="inventoryForm" action="<?= base_url('InventoryMng/chooseeInv') ?>" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Inventory Menu : </label></td>
                            <td>
                                <select name="inventory" class="form-control" id="inventory">
                                    <option value="">---- Select Inv Mng ----</option>
                                    <option value="1">Internal Transfer Location</option>
                                </select>
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

    });
</script>