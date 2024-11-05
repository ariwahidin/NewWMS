<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white">Create Item</h5>
            </div>
            <div class="card-body">


                
                <form action="<?= site_url('item/store') ?>" method="post">
                    <div class="form-group mb-3">
                        <label for="item_code">Item Code</label>
                        <input type="text" class="form-control" id="item_code" name="item_code" placeholder="Item Code">                        
                    </div>                    
                    <div class="form-group mb-3">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name">
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1 mt-2 mb-2 float-end w-xs">Submit</button>
                </form>


            
            </div>
        </div>
    </div>
</div>