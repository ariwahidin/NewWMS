<link href="<?= base_url() ?>myassets/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="<?= base_url() ?>myassets/js/jquery-3.7.0.js"></script>
<script src="<?= base_url() ?>myassets/js/jquery.dataTables.min.js"></script>
<style>
    table tr th:first-child {
        max-width: 10px !important;
    }
</style>



<div class="row">
    <div class="col col-md-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Master work schedule</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Master work schedule</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col col-md-10">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" id="btnAdd">Add new work schedule</button>
            </div>
            <div class="card-body">
                <table id="user-table" class="table table-sm table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Position</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($work_schedule->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->fullname ?></td>
                                <td><?= $data->date ?></td>
                                <td><?= date('H:i', strtotime($data->start_time)) ?></td>
                                <td><?= date('H:i', strtotime($data->end_time)) ?></td>
                                <td><?= $data->position_name ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm btnEdit" 
                                    data-id="<?= $data->id ?>" 
                                    data-user-id="<?= $data->user_id ?>"
                                    data-position-id="<?= $data->position_id ?>"
                                    data-date="<?=$data->date?>"
                                    data-start-time="<?= date('H:i', strtotime($data->start_time)) ?>"
                                    data-end-time="<?= date('H:i', strtotime($data->end_time)) ?>"
                                    >Edit</button>
                                    <button class=" btn btn-danger btn-sm btnDelete" data-id="<?= $data->id ?>">Delete</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Grids in modals -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerForm"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="form-group col">
                                <label for="name" class="form-label">Employee name</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">Choosee employee</option>
                                    <?php
                                    foreach ($users->result() as $data) {
                                    ?>
                                        <option value="<?= $data->id ?>"><?= $data->fullname ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="eks_id" name="eks_id" val="" readonly>
                                <input type="hidden" id="form_proses" name="form_proses" val="" readonly>
                            </div>
                            <div class="form-group col">
                                <label for="name" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" placeholder="" required>
                            </div>
                            <div>
                                <label for="name" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="start_time" value="08:00" name="start_time" placeholder="" required>
                            </div>
                            <div>
                                <label for="name" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="end_time" value="17:00" name="end_time" placeholder="" required>
                            </div>
                            <div>
                                <label for="name" class="form-label">Position</label>
                                <select name="position_id" id="position_id" class="form-control">
                                    <option value="">Choosee Position</option>
                                    <?php
                                    foreach ($position->result() as $data) {
                                    ?>
                                        <option value="<?= $data->id ?>"><?= $data->name ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#scheduleForm').on('submit', function(e) {
            e.preventDefault();
            let formUser = new FormData(this);
            let form_proses = $('#form_proses').val();

            if (form_proses === 'add_new') {
                $.ajax({
                    url: 'createSchedule',
                    type: 'POST',
                    data: formUser,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success == true) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = 'index';
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: response.message
                            });
                        }
                    },
                    dataType: 'json'
                });
            } else {
                $.ajax({
                    url: 'editSchedule',
                    type: 'POST',
                    data: formUser,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success == true) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.href = 'index';
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: response.message
                            });
                        }
                    },
                    dataType: 'json'
                });
            }
        });

        $('#user-table').DataTable();

        $('#btnAdd').on('click', function() {
            $('#headerForm').text('Add new work schedule');
            $('#form_proses').val('add_new');
            $('#modalForm').modal('show');
        })

        $('.btnEdit').on('click', function() {
            $('#headerForm').text('Edit work schedule');
            $('#form_proses').val('edit');
            $('#eks_id').val($(this).data('id'));
            $('#date').val($(this).data('date'));
            $('#user_id').val($(this).data('user-id'));
            $('#start_time').val($(this).data('start-time'));
            $('#end_time').val($(this).data('end-time'));
            $('#position_id').val($(this).data('position-id'));
            $('#modalForm').modal('show');
        })

        $('.btnDelete').on('click', function() {
            let id = $(this).data('id');
            $.post('deleteSchedule', {
                id: id
            }, function(response) {
                if (response.success == true) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = 'index';
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.message
                    });
                }
            }, 'json');
        })
    });
</script>