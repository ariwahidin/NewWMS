<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <!-- <h5 class="card-title mb-0 text-white">List Item</h5> -->
                <a class="btn btn-success" href="<?= site_url('item/create') ?>">Add New Item</a>
            </div>
            <div class="card-body">
                <table id="itemTable" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#itemTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= site_url('item/fetch_data') ?>",
                "type": "POST"
            },
            "columns": [{
                    "data": null, // Tambahkan kolom untuk nomor urut
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Menghitung nomor urut global
                    }
                },
                {
                    "data": "item_code"
                },
                {
                    "data": "item_name"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<a href="<?= site_url('item/edit/') ?>${row.id}">Edit</a> | 
                                    <a href="<?= site_url('item/delete/') ?>${row.id}" onclick="return confirm('Are you sure?')">Delete</a>`;
                    }
                }
            ]
        });
    });
</script>