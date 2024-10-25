<!-- <link href="https://cdn.jsdelivr.net/npm/select2@latest/dist/css/select2.min.css" rel="stylesheet" /> -->
<link href="<?= base_url() ?>myassets/css/select2.min.css" rel="stylesheet" />
<style>
    .swal2-container {
        z-index: 9999;
    }
</style>
<div class="row">
    <div class="col col-md-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">List Order</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">List Order</a></li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col col-md-12">
        <div class="card">
            <div class="card-header bg-primary d-flex">
                <!-- <span style="white-space: nowrap; color: whitesmoke; padding-top: 10px;">Order Table  &nbsp; </span> -->
                <!-- <input type="date" class="form-control-sm" style="width: 200px; margin-right: 10px;" id="sStartDate" placeholder="Start Date">
                <input type="date" class="form-control-sm" style="width: 200px; margin-right: 10px;" id="sEndDate" placeholder="End Date">
                <button class="btn btn-outline-success" id="sButton"><i class="ri-filter-fill"></i></button>&nbsp;&nbsp;
                <button class="btn btn-info" id="btnAdd">Add new</button>&nbsp;&nbsp; -->
            </div>
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <input type="text" id="search" class="form-control-sm" placeholder="Search by Customer Name">
                </div>
                <table class="table table-bordered table-sm table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>SPK Number</th>
                            <th>SPK Date</th>
                            <th>Order Date</th>
                            <th>Truck</th>
                            <th>Total Drop</th>
                            <th>Origin Qty</th>
                            <th>Load Qty</th>
                            <th>Total DO</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="order_data">
                        <?php
                        $no = 1;
                        foreach ($order->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->spk_number ?></td>
                                <td><?= $data->spk_date ?></td>
                                <td><?= $data->order_date ?></td>
                                <td><?= $data->truck_name ?></td>
                                <td><?= $data->total_drop ?></td>
                                <td><?= $data->qty_ori ?></td>
                                <td><?= $data->qty_loading ?></td>
                                <td><?= $data->total_dn ?></td>
                                <td><?= $data->status ?? '' ?></td>
                                <td>
                                    <a href="<?= base_url() ?>order/spkShow?spk=<?= $data->spk_number ?>" class="btn btn-sm btn-secondary btnPrint" data-id="<?= $data->id ?>">Print</a>
                                    <a href="<?= base_url() ?>order/planningOrder?edit=true&spk=<?= $data->spk_number ?>" class="btn btn-sm btn-primary btnView" data-id="<?= $data->id ?>">View</a>
                                    <a class="btn btn-sm btn-secondary btnChange" data-id="<?= $data->id ?>">Change</a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div id="pagination_link"></div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalFormSJ" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddSJ">Add SJ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <span>Total PL : <span id="spanTotPL">0</span></span>
                        <div id="mdCardNoPL" style="max-height:300px; overflow-y: auto;">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <form action="" id="formInputSJ">
                            <div class="form-group">
                                <label for="">SJ No : </label>
                                <input type="text" value="" class="form-control" name="inSJ" required autocomplete="off">
                            </div>
                            <div class="form-group mt-1">
                                <label for="">SJ Time : </label>
                                <input type="time" value="" class="form-control" name="inSJTime" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 float-end">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Grids in modals -->
<!-- <div class="modal fade" id="modalForm" aria-labelledby="exampleModalgridLabel" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerForm"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="plForm">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">PL No : </label>
                                <input type="text" class="form-control" id="pl_no" name="pl_no" placeholder="" required autocomplete="off">
                            </div>
                            <input type="hidden" id="form_proses" name="form_proses" val="" readonly>
                            <input type="hidden" id="pl_id" name="pl_id" val="" readonly>
                        </div>


                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">PL Printed Time: </label>
                                <input type="time" class="form-control" id="pl_print_time" name="pl_print_time" placeholder="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">PL Date Amano : </label>
                                <input type="date" class="form-control" id="rec_pl_date" name="rec_pl_date" placeholder="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">PL Time Amano : </label>
                                <input type="time" class="form-control" id="rec_pl_time" name="rec_pl_time" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Dealer Code : </label>
                                <select name="dealer_code" id="dealer_code" required>
                                    <option value="">Choose dealer code</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Dealer / Depo : </label>
                                <input type="text" class="form-control" id="dealer_det" name="dealer_det" placeholder="" readonly required>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Pintu Loading : </label>
                                <input type="number" class="form-control" id="pintu_loading" name="pintu_loading" placeholder="">
                            </div>
                        </div>

                    </div>



                    <div class="row mt-2">

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Destination : </label>
                                <select name="dest" id="dest" class="form-control" required>
                                    <option value="">Choose destination</option>
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Dest. Type : </label>
                                <input type="text" class="form-control" id="dock" name="dock" placeholder="" readonly>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Total Qty : </label>
                                <input type="number" class="form-control" id="tot_qty" name="tot_qty" placeholder="">
                            </div>
                        </div>

                    </div>


                    <div class="row mt-2">

                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">No Truck : </label>
                                <select class="form-control" name="no_truck" id="no_truck" required>
                                    <option value="">Choose No Truck</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">Expedisi : </label>
                                <select class="form-control" name="expedisi" id="expedisi" required>
                                    <option value="">Choose ekspedisi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">SJ No : </label>
                                <input type="text" class="form-control" id="sj_no" name="sj_no" placeholder="">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="name" class="form-label">SJ Time : </label>
                                <input type="time" class="form-control" id="sj_time" name="sj_time" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="name" class="form-label">Activity Date : </label>
                                <input type="date" class="form-control" name="activity_date" id="activity_date" required>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label for="name" class="form-label">Remarks : </label>
                                <input type="text" class="form-control" name="remarks" id="remarks" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12 mt-3">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->
<script src="<?= base_url() ?>myassets/js/select2.min.js"></script>

<script>
    $(document).ready(function() {


        let limit = 5;
        let start = 0;
        let search_value = '';


        // getOrderList();
        // function getOrderList() {

        //     var today = new Date().toISOString().split('T')[0];
        //     if ($('#sStartDate').val() == '') {
        //         $('#sStartDate').val(today)
        //     }
        //     if ($('#sEndDate').val() == '') {
        //         $('#sEndDate').val(today)
        //     }

        //     let sDate = $('#sStartDate').val();
        //     let eDate = $('#sEndDate').val();

        //     // let divTable = $('#divSchedule');
        //     // divTable.empty();
        //     $.ajax({
        //         url: "getListOrder",
        //         type: "POST",
        //         data: {
        //             startDate: sDate,
        //             endDate: eDate
        //         },
        //         dataType: 'JSON',
        //         success: function(response) {
        //             // if (response.success == true) {
        //             //     $('#cardPL').empty();
        //             //     $('#cardPL').html(response.table);
        //             //     $('#tablePL').dataTable();
        //             // }
        //         }
        //     });
        // }
    })
</script>

<!-- <script>
    $(document).ready(function() {

        var socket;
        initWebSocket();
        function initWebSocket() {
            socket = new WebSocket(urlWebsocket);

            socket.onopen = function() {
                $('#spConnect').html(`<i class="ri-swap-box-fill"></i>`);
                // console.log('WebSocket connection opened');
                socket.send('ping');
            };

            socket.onmessage = function(event) {
                // console.log('Received message: ' + event.data);
                // getAllRowTask();
            };

            socket.onclose = function(event) {
                $('#spConnect').html(`<i class="ri-alert-fill"></i>`);
                setTimeout(initWebSocket, 5000); // Retry after 5 seconds
            };

            socket.onerror = function(error) {
                console.error('WebSocket error: ' + error);
            };
        }

        $('#dest').select2({
            // tags: true,
            dropdownParent: $("#modalForm")
        });


        $('#dest').on('change', function() {
            let kode = $(this).val();
            let name = $(this).children('option:selected').data('name');
            $('#dock').val(name);
        })

        $('#dealer_code').select2({
            // tags: true,
            dropdownParent: $("#modalForm")
        });

        $('#dealer_code').on('change', function() {
            let kode = $(this).val();
            let name = $(this).children('option:selected').data('name');
            $('#dealer_det').val(name);
        })

        $('#no_truck').select2({
            // tags: true,
            dropdownParent: $("#modalForm")
        });

        $('#no_truck').on('change', function() {
            let kode = $(this).val();
            let id = $(this).children('option:selected').data('id');
            // console.log(id);
            $('#expedisi').val(id);
        })

        getTablePickingList();

        $('#sButton').on('click', function() {
            getTablePickingList();
        })

        let isSubmitting = false;
        $('#plForm').on('submit', function(e) {
            e.preventDefault();
            if (isSubmitting) {
                return;
            }
            isSubmitting = true;
            startLoading();
            let formUser = new FormData(this);
            let form_proses = $('#form_proses').val();

            if (form_proses === 'add_new') {
                $.ajax({
                    url: 'createPickingList',
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
                                timer: 1000
                            }).then(function() {
                                getTablePickingList();
                            }).then(function() {
                                isSubmitting = false;
                                $('#modalForm').modal('hide');
                                stopLoading();
                                socket.send('ping');
                            })
                        } else {
                            isSubmitting = false;
                            stopLoading();
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
                    url: 'editPickingList',
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
                                timer: 1000
                            }).then(function() {
                                isSubmitting = false;
                                getTablePickingList();
                                $('#modalForm').modal('hide');
                                socket.send('ping');
                                stopLoading();
                            })
                        } else {
                            isSubmitting = false;
                            stopLoading();
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

        $('#formInputSJ').on('submit', function(e) {
            e.preventDefault();

            let idSelected = [];

            $('.in_sj_id').each(function(index, item) {
                if ($(this).prop('checked')) {
                    console.log($(this).val());
                    idSelected.push($(this).val());
                }
            });

            if (idSelected.length > 0) {
                startLoading();
                let formData = new FormData(this);
                formData.append('id', idSelected);

                $.ajax({
                    url: 'addSJ',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success == true) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1000
                            }).then(function() {
                                getTablePickingList();
                            }).then(function() {
                                $('#modalFormSJ').modal('hide');
                                stopLoading();
                            })
                        } else {
                            stopLoading();
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

        })

        function getTablePickingList() {

            var today = new Date().toISOString().split('T')[0];
            if ($('#sStartDate').val() == '') {
                $('#sStartDate').val(today)
            }
            if ($('#sEndDate').val() == '') {
                $('#sEndDate').val(today)
            }

            let sDate = $('#sStartDate').val();
            let eDate = $('#sEndDate').val();

            let divTable = $('#divSchedule');
            divTable.empty();
            $.ajax({
                url: "getTablePickingList",
                type: "POST",
                data: {
                    startDate: sDate,
                    endDate: eDate
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success == true) {
                        $('#cardPL').empty();
                        $('#cardPL').html(response.table);
                        $('#tablePL').dataTable();
                    }
                }
            });

            // $.post('', {}, function(response) {

            // }, 'json');
        }

        $('#btnRefresh').on('click', async function() {
            startLoading();
            await getTablePickingList();
            stopLoading();
        })


        $('#btnAdd').click(function() {
            loadModalAdd();
        });


        $('#btnAddSJ').on('click', function() {

            let start_date = $('#sStartDate').val();
            let end_date = $('#sEndDate').val();
            $.post('getPLWithNoSJ', {
                start_date,
                end_date
            }, function(response) {
                $('#mdCardNoPL').empty();
                $('#spanTotPL').text(response.data.length);
                $('#mdCardNoPL').html(response.content);
                $('#modalFormSJ').modal('show');
            }, 'json');

        })

        function loadModalAdd() {
            moment.tz.setDefault('Asia/Jakarta');
            let today = moment().format('YYYY-MM-DD');
            let currentTime = moment().format('HH:mm');
            $('#sj_time').val('');
            $('#rec_pl_date').val(today);
            $('#activity_date').val(today);
            $('#rec_pl_time').val(currentTime);
            $('#pl_print_time').val(currentTime);


            $('#remarks').val('');
            $('#pl_id').val('');
            $('#pl_no').val('');
            $('#dest').val('').change();
            $('#tot_qty').val('');
            $('#pintu_loading').val('');
            $('#dealer_code').val('').change();
            $('#dealer_det').val('');
            $('#dock').val('');
            $('#expedisi').val('');
            $('#no_truck').val('').change();
            $('#sj_no').val('');


            $('#headerForm').text('Add new PL');
            $('#form_proses').val('add_new');
            $('#modalForm').modal('show');

            $('#modalForm').on('shown.bs.modal', function() {
                $('#pl_no').focus();
            });

        }

        $('#cardPL').on('click', '.btnEdit', function() {
            let pl_id = $(this).data('id');

            $.post('getPickingListAdmById', {
                id: pl_id
            }, function(response) {
                let data = response.picking_list;
                $('#form_proses').val('edit');
                $('#pl_id').val(data.id);
                $('#pl_no').val(data.pl_no);
                $('#dest').val(data.dest).change();
                $('#tot_qty').val(data.tot_qty);
                $('#pintu_loading').val(data.pintu_loading);
                $('#dealer_code').val(data.dealer_code).change();
                $('#dealer_det').val(data.dealer_det);
                $('#dock').val(data.dock);
                $('#pl_print_time').val(data.pl_print_time);
                $('#rec_pl_date').val(data.adm_pl_date);
                $('#rec_pl_time').val(data.adm_pl_time);
                $('#expedisi').val(data.expedisi);
                $('#no_truck').val(data.no_truck).change();
                $('#sj_no').val(data.sj_no);
                $('#sj_time').val(data.sj_time);
                $('#activity_date').val(data.activity_date);
                $('#remarks').val(data.remarks);
                $('#headerForm').text('Edit PL');
                $('#form_proses').val('edit');
                $('#modalForm').modal('show');
            }, 'json');

        })


        $('#cardPL').on('click', '.btnDelete', function() {
            let id = $(this).data('id');


            Swal.fire({
                icon: "question",
                title: "Do you want to delete this data?",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete!",
                denyButtonText: `Don't save`
            }).then((result) => {
                if (result.isConfirmed) {
                    startLoading();
                    $.post('cekStatusPickingList', {
                        id: id
                    }, function(response) {
                        stopLoading();
                        if (response.success == true) {
                            if (response.data.status == 'unprocessed') {
                                $.post('deletePickingList', {
                                    id: id
                                }, function(response) {
                                    if (response.success == true) {
                                        getTablePickingList();
                                    }
                                }, 'json');
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Not allowed',
                                    text: 'Status picking list : ' + response.data.status
                                });
                            }
                        }
                    }, 'json');
                }
            });




            // $.post('deleteEkspedisi', {
            //     id: id
            // }, function(response) {
            //     if (response.success == true) {
            //         Swal.fire({
            //             position: "top-end",
            //             icon: "success",
            //             title: response.message,
            //             showConfirmButton: false,
            //             timer: 1500
            //         }).then(function() {
            //             window.location.href = 'index';
            //         })
            //     } else {
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Failed',
            //             text: response.message
            //         });
            //     }
            // }, 'json');
        })

        getOptionEkspedisi();

        function getOptionEkspedisi() {
            $.post('getOptionEkspedisi', {}, function(response) {
                let selOptNoTruck = $('#no_truck');
                let selOptEkspedisi = $('#expedisi');
                selOptNoTruck.empty();
                selOptNoTruck.html(response.option_no_truck);
                selOptEkspedisi.empty();
                selOptEkspedisi.html(response.option_ekspedisi);
            }, 'json');
        }

        getOptionDealerAndDest();

        function getOptionDealerAndDest() {
            $.post('getOptionDealerAndDest', {}, function(response) {
                let selOptDealer = $('#dealer_code');
                let selOptDest = $('#dest');
                selOptDealer.empty();
                selOptDealer.html(response.option_dealer);
                selOptDest.empty();
                selOptDest.html(response.option_dest);
            }, 'json');
        }
    });
</script> -->