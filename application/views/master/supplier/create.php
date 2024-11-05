<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white">Create Item</h5>
            </div>
            <div class="card-body">


                
                <form action="<?= site_url('supplier/store') ?>" method="post">
                    <div class="form-group mb-3">
                        <label for="item_code">Code</label>
                        <input type="text" class="form-control" id="supplier_code" name="supplier_code" placeholder="Supplier Code">                        
                    </div>                    
                    <div class="form-group mb-3">
                        <label for="item_name">Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Supplier Name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="item_name">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1 mt-2 mb-2 float-end w-xs">Submit</button>
                </form>


            
            </div>
        </div>
    </div>
</div>