<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary">
                <a class="btn btn-success" href="<?= site_url('supplier/create') ?>">Add New Supplier</a>
            </div>
            <div class="card-body">
                <table id="itemTable" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Address</th>
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
                "url": "<?= site_url('supplier/fetch_data') ?>",
                "type": "POST"
            },
            "columns": [
                {
                    "data": null, // Tambahkan kolom untuk nomor urut
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Menghitung nomor urut global
                    }
                },
                {
                    "data": "code"
                },
                {
                    "data": "name"
                },
                {
                    "data": "address"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<a href="<?= site_url('supplier/edit/') ?>${row.id}">Edit</a> | 
                                    <a href="<?= site_url('supplier/delete/') ?>${row.id}" onclick="return confirm('Are you sure?')">Delete</a>`;
                    }
                }
            ]
        });
    });
</script>