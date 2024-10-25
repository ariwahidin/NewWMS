<link href="<?= base_url() ?>myassets/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@latest/dist/css/select2.min.css" rel="stylesheet" />
<script src="<?= base_url() ?>myassets/js/jquery-3.7.0.js"></script>
<script src="<?= base_url() ?>myassets/js/jquery.dataTables.min.js"></script>

<style>
    .select2-container {
        z-index: 1600;
        /* Atur z-index sesuai kebutuhan */
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: blueviolet;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Outbound Task</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Outbound Task</a>
                        <span id="spConnect"></span>
                    </li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
            <div class="flex-grow-1">
                <h4 class="card-title mb-0 flex-grow-1"><strong id="clock"></strong></h4>
            </div>
            <div class="mt-3 mt-lg-0">
                <form action="javascript:void(0);">
                    <div class="row">
                        <div class="col-sm-auto">
                            <div class="input-group">
                                <input type="text" id="inSearchDest" class="form-control" placeholder="Destination">
                                <input type="text" id="inSearch" class="form-control" placeholder="Picking list">
                                <button class="btn btn-primary btn-sm border-primary text-white" id="btnSearch">
                                    <i class="ri-search-2-line"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <?php if ($_SESSION['user_data']['role'] != 4) { ?>
                <div class="mt-3 ms-3 mt-lg-0">
                    <button class="btn btn-primary" id="btnCreate">Create new task</button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div id="content"></div>


<div class="modal fade" id="createTask" tabindex="-1" aria-labelledby="createTaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="createTaskLabel">Create Activity Outbound</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="createTaskBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalCreateTask">
                <div id="task-error-msg" class="alert alert-danger py-2"></div>
                <form autocomplete="off" action="#" id="creatask">
                    <input type="hidden" id="proses" name="proses" class="form-control">
                    <input type="hidden" id="id_task" name="id_task" class="form-control">


                    <div class="row">
                        <div class="col-6 col-lg-3" id="divPLNo">

                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="priority-field" class="form-label">Ekspedisi</label><br>
                            <select class="form-control-sm" name="ekspedisi" id="ekspedisi" style="width: 150px;" disabled>
                                <option value="">Choose ekspedisi</option>
                                <?php foreach ($ekspedisi->result() as $eks) { ?>
                                    <!-- <option value="<?= $eks->id ?>"><?= $eks->name ?></option> -->
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="" class="form-label">Destination</label>
                            <input type="text" id="dest" name="dest" class="form-control" placeholder="" value="" required readonly>
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="" class="form-label">Dealer Code</label>
                            <input type="text" id="dealer_code" name="dealer_code" class="form-control" placeholder="" value="" readonly>
                        </div>
                        <div class="col-6 col-lg-3">
                            <label for="" class="form-label">Dealer / Depo</label>
                            <input type="text" id="dealer_det" name="dealer_det" class="form-control" placeholder="" value="" readonly>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label for="priority-field" class="form-label">Total Qty</label>
                            <input type="number" id="qty" name="qty" class="form-control" placeholder="" value="" readonly>
                        </div>

                        <div class="col-6 col-lg-3">
                            <label for="priority-field" class="form-label">No truck</label>
                            <input type="text" id="no_truck" name="no_truck" class="form-control" placeholder="" value="" readonly>
                        </div>


                        <div class="col-6 col-lg-3">
                            <label for="priority-field" class="form-label">Pintu Loading</label>
                            <input type="text" id="pintu_loading" name="pintu_loading" class="form-control" value="">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12 col-lg-6">
                            <label for="priority-field" class="form-label">Picker</label>
                            <select style="background-color: blue !important;" class="js-example-basic-multiple form-control-sm" name="picker_id[]" multiple="multiple" id="picker_id" required>
                                <option value="">Choose picker</option>
                                <?php foreach ($checker->result() as $check) { ?>
                                    <option value="<?= $check->id ?>"><?= $check->fullname ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="priority-field" class="form-label">Remarks</label>
                            <input type="text" id="remarks" name="remarks" class="form-control" value="">
                        </div>
                    </div>

                    <br>

                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-ghost-success" data-bs-dismiss="modal"><i class="ri-close-fill align-bottom"></i> Close</button>
                        <button type="submit" class="btn btn-primary" id="btnTask">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@latest/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        var socket;
        getAllRowTask();
        initWebSocket();

        $('.js-example-basic-multiple').select2();

        $('#btnCreate').on('click', function() {



            $.get('getPickingListAdm', {}, function(response) {

                if (response.success == true) {

                    let divPL = $('#divPLNo');
                    divPL.empty();
                    divPL.html(`<label for="" class="form-label">PL No </label>
                                    <select name="no_pl" id="no_pl" class="form-control" style="width: 200px; height: 50px;" required>
                                </select>`);

                    let selPL = $('#no_pl');
                    selPL.empty();

                    let opt = `<option value="">Choose PL No </option>`;
                    response.picking_list.forEach(function(elem) {
                        opt += `<option value="${elem.pl_id}">${elem.pl_no}</option>`;
                    });
                    selPL.html(opt);

                    // console.log(opt);
                    // .select2();
                    $('#no_pl').select2({
                        // tags: true,
                        dropdownParent: $("#createTask")
                    });

                    // Membersihkan opsi-opsi yang sudah terpilih sebelumnya
                    $('#picker_id').find('option:selected').prop('selected', false).trigger('change');

                    $('#btnTask').text('Create');
                    $('#createTaskLabel').text('Create new');
                    $('#proses').val('new_task');
                    $('#createTask').modal('show');

                    // $('#createTask').on('shown.bs.modal', function() {
                    //     // $('.js-example-basic-single').select2('open');
                    //     $('.js-example-basic-single').focus();

                    // });
                }
            }, 'json');

        });

        $('#divPLNo').on('change', '#no_pl', function() {
            let id = $(this).val();
            if (id != '') {
                $.post('getPickingListAdmById', {
                    id: id
                }, function(response) {
                    let data = response.picking_list;
                    $('#no_truck').val(data.no_truck);
                    $('#dest').val(data.dest);
                    $('#dealer_code').val(data.dealer_code);
                    $('#dealer_det').val(data.dealer_det);
                    $('#ekspedisi').val(data.expedisi);
                    $('#pintu_loading').val(data.pintu_loading);
                    $('#remarks').val(data.remarks);
                    $('#qty').val(data.tot_qty);
                }, 'json');
            }
        })

        function initWebSocket() {
            socket = new WebSocket(urlWebsocket);

            socket.onopen = function() {
                $('#spConnect').html(`<i class="ri-swap-box-fill"></i>`);
                // console.log('WebSocket connection opened');
                socket.send('ping');
            };

            socket.onmessage = function(event) {
                // console.log('Received message: ' + event.data);
                getAllRowTask();
            };

            socket.onclose = function(event) {
                $('#spConnect').html(`<i class="ri-alert-fill"></i>`);
                setTimeout(initWebSocket, 5000); // Retry after 5 seconds
            };

            socket.onerror = function(error) {
                console.error('WebSocket error: ' + error);
            };
        }

        $("#creatask").on("keypress", "input", function(event) {
            if (event.which === 13) {
                event.preventDefault(); // Prevent default behavior of Enter key
                var inputs = $(this).closest("form").find(":input");
                var index = inputs.index(this);

                // Find next empty input field
                for (var i = index + 1; i < inputs.length; i++) {
                    if ($(inputs[i]).is(":text") && $(inputs[i]).val() === '') {
                        $(inputs[i]).focus();
                        break;
                    }
                }
            }
        });

        $('#creatask').on('submit', function(e) {
            e.preventDefault();
            let form = new FormData(this);
            let proses = $('#proses').val();

            if (proses === 'new_task') {
                $.ajax({
                    url: 'createTask',
                    type: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == true) {
                            getAllRowTask();
                            $('#createTask').modal('hide');
                            socket.send('ping');
                        }
                    }
                });
            } else {
                $.ajax({
                    url: 'editTask',
                    type: 'POST',
                    data: form,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == true) {
                            getAllRowTask();
                            $('#createTask').modal('hide');
                            socket.send('ping');
                        }
                    }
                });
            }

        })

        $('#content').on('click', '.btnEdit', async function() {
            startLoading();
            let id = $(this).data('id');
            let pl_id = $(this).data('pl-id');
            let result = await $.post('getTaskById', {
                id: id,
                pl_id: pl_id
            }, function(response) {
                let task = response.data;
                // console.log(task);
                // return false;

                $('#proses').val('edit_task');
                $('#id_task').val(id);

                let divPL = $('#divPLNo');
                divPL.empty();
                divPL.html(`<label for="" class="form-label">PL No </label>
                                    <select name="no_pl" id="no_pl" class="form-control" style="width: 200px; height: 50px;" required>
                                </select>`);

                let selPL = $('#no_pl');
                selPL.empty();

                let opt = `<option value="">Choose PL No </option>`;
                opt += `<option value="${task.pl_id}" selected>${task.no_pl}</option>`;
                selPL.html(opt);
                $('#no_pl').select2({
                    // tag : [],
                    dropdownParent: $("#createTask")
                });

                // Membersihkan opsi-opsi yang sudah terpilih sebelumnya
                $('#picker_id').find('option:selected').prop('selected', false);
                // Meloop melalui setiap nilai yang dipilih
                $.each(response.picker, function(index, value) {
                    // Menandai opsi dengan nilai yang sesuai sebagai dipilih
                    // console.log(value.user_id);
                    $('#picker_id option[value="' + value.user_id + '"]').prop('selected', true).trigger('change');
                });


                $('#dest').val(task.dest);
                $('#dealer_code').val(task.dealer_code);
                $('#dealer_det').val(task.dealer_det);
                $('#qty').val(task.qty);
                $('#no_truck').val(task.no_truck);
                $('#ekspedisi').val(task.expedisi);
                $('#pintu_loading').val(task.pintu_loading);
                $('#remarks').val(task.remarks);

                $('#btnTask').text('Update');
                $('#createTaskLabel').text('Edit');
                $('#proses').val('edit_task');

                $('#createTask').modal('show');
                stopLoading();
            }, 'json');
        })

        $('#content').on('click', '.btnDelete', function() {
            let id = $(this).data('id');
            let pl_id = $(this).data('pl-id');
            Swal.fire({
                icon: "question",
                title: "Do you want to delete this activity?",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    startLoading();
                    $.post('deleteOut', {
                        id: id,
                        pl_id: pl_id
                    }, function(response) {
                        stopLoading();
                        if (response.success == true) {
                            getAllRowTask();
                            socket.send('ping');
                        }
                    }, 'json');
                }
            });
        });

        $('#content').on('click', '.btnActivity', function() {

            Swal.fire({
                icon: "question",
                title: "Are u sure to " + $(this).data('proses') + " ?",
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    startLoading();
                    let dataToPost = {
                        id: $(this).data('id'),
                        pl_id: $(this).data('pl-id'),
                        activity: $(this).data('activity'),
                        proses: $(this).data('proses')
                    };
                    $.post('prosesActivity', dataToPost, function(response) {
                        if (response.success == true) {
                            getAllRowTask();
                            stopLoading();
                            socket.send('ping');
                        } else {
                            stopLoading();
                            Swal.fire({
                                icon: 'error',
                                title: response.message,
                            })
                        }
                    }, 'json');
                }
            });
        })

        $('#btnSearch').on('click', getAllRowTask);

        function getAllRowTask() {
            let search = $('#inSearch').val();
            let searchDest = $('#inSearchDest').val();
            $.post('getAllRowTask', {
                search,
                searchDest
            }, function(response) {
                let content = $('#content');
                content.empty();
                content.html(response);
            });
        }

        function formatTime(timeString) {
            var timeComponents = timeString.split(':');

            if (timeComponents.length !== 3) {
                return 'Invalid time format';
            }

            var hours = parseInt(timeComponents[0]);
            var minutes = parseInt(timeComponents[1]);
            var seconds = parseInt(timeComponents[2]);

            if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) {
                return 'Invalid time format';
            }

            hours = (hours < 10) ? '0' + hours : hours;
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            seconds = (seconds < 10) ? '0' + seconds : seconds;

            var formattedTime = hours + ':' + minutes + ':' + seconds;

            return formattedTime;
        }

        getOptionEkspedisi();

        function getOptionEkspedisi() {
            $.post('getOptionEkspedisi', {}, function(response) {
                let selOptEkspedisi = $('#ekspedisi');
                selOptEkspedisi.empty();
                selOptEkspedisi.html(response.option_ekspedisi);
            }, 'json');
        }
    });
</script>