<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="card">
            <div class="card-body table-responsive">
                <form id="putawayForm" action="<?= base_url('putawayScan/receive') ?>" method="post">
                    <table class="table-nowrap table-sm fs-11 mb-0">
                        <tr>
                            <td><label for="firstNameinput" class="form-label">Receive No : </label></td>
                            <td><input style="max-width: 160px;" id="receiveNumber" name="receiveNumber" type="text" class="form-control-sm" required></td>
                            <td>
                                <button onclick="document.getElementById('putawayForm').reset()" type="button" class="btn btn-sm btn-danger"><i class=" ri-delete-bin-7-line"></i></button>
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