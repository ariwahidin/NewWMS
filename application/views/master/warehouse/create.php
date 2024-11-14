<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white">Create Warehouse</h5>
            </div>
            <div class="card-body">


                <form action="<?= site_url('warehouse/store') ?>" method="post">
                    <div class="form-group mb-3">
                        <label for="item_code">Warehouse Code</label>
                        <input type="text" class="form-control" id="code" name="code" placeholder="Warehouse Code">                        
                    </div>                    
                    <div class="form-group mb-3">
                        <label for="item_name">Description</label>
                        <input type="text" class="form-control" id="desc" name="desc" placeholder="Description">
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1 mt-2 mb-2 float-end w-xs">Submit</button>
                </form>


            
            </div>
        </div>
    </div>
</div>