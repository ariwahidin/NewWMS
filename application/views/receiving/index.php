<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header bg-primary">
                <h5 class="card-title mb-0 text-white">Header Info</h5>
            </div>
            <div class="card-body">
                <form id="formHeader">
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td>Receive Number</td>
                                    <td>:</td>
                                    <td>
                                        <input type="hidden" id="prosesAction" value="<?= (isset($order)) && $order->spk_number ? 'edit' : 'add'; ?>">
                                        <input type="text" class="form-control-sm" id="spkNumber" placeholder="" value="<?= $order->spk_number ?? 'Auto Generated' ?>" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Load Number</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="loadNumber" placeholder="" value="<?= $order->load_number ?? '' ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Order Date</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $orderDate = $order->order_date ?? date('Y-m-d');
                                        $orderTime = isset($order) ? ($order->order_time ? date('H:i', strtotime($order->order_time)) : '') : '';
                                        ?>
                                        <input type="date" class="form-control-sm" id="orderDate" value="<?= $orderDate ?>" required>
                                        <input type="time" class="form-control-sm" id="orderTime" value="<?= $orderTime ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>SPK Date</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $spkDate = $order->spk_date ?? date('Y-m-d');
                                        ?>
                                        <input type="date" class="form-control-sm" id="spkDate" value="<?= $spkDate ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ship Mode</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $shipmode = array(
                                            'Truck',
                                            'Air',
                                            'Sea',
                                            'Train',
                                            'Mail/Courier',
                                            'Own Collection'
                                        );
                                        ?>
                                        <select class="form-control-sm" id="shipMode">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            foreach ($shipmode as $s) {
                                            ?>
                                                <option value="<?= $s ?>" <?= isset($order) ? (($order->ship_mode == $s) ? 'selected' : '') : ''  ?>><?= $s ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Order Type</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $orderTypeID = $order->order_type_id ?? '';
                                        $orderType = $order->order_type_code ?? '';
                                        $orderTypeName = $order->order_type_name ?? '';
                                        ?>

                                        <input type="hidden" class="form-control-sm" id="orderTypeID" value="<?= $orderTypeID ?>">
                                        <input style="width: 100px;" type="text" class="form-control-sm" id="orderType" placeholder="" value="<?= $orderType ?>" required readonly>
                                        <input style="width: 100px;" type="text" class="form-control-sm" id="orderTypeName" placeholder="" value="<?= $orderTypeName ?>" required readonly>
                                        <button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#modalType">search</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck Arival Date</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $truckArrivalDate = $order->truck_arival_date ?? '';
                                        $truckArrivalTime = isset($order->truck_arival_time) ? date('H:i', strtotime($order->truck_arival_time)) : '';
                                        ?>
                                        <input type="date" class="form-control-sm" id="truckArivalDate" value="<?= $truckArrivalDate ?>" placeholder="" required>
                                        <input type="time" class="form-control-sm" id="truckArivalTime" value="<?= $truckArrivalTime ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Start/Finish Loading Time</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $startLoading = isset($order->start_loading) ? date('H:i', strtotime($order->start_loading)) : '';
                                        $finishLoading = isset($order->finish_loading) ? date('H:i', strtotime($order->finish_loading)) : '';
                                        ?>
                                        <input type="time" class="form-control-sm" id="startLoading" placeholder="" value="<?= $startLoading ?>" required>
                                        <input type="time" class="form-control-sm" id="finishLoading" placeholder="" value="<?= $finishLoading ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dispath Proccess</td>
                                    <td>:</td>
                                    <td>

                                        <?php
                                        $dispath_proscess = array(
                                            'On Scheduling',
                                            'Waiting Truck',
                                            'Finish Loading'
                                        )
                                        ?>

                                        <select id="dispathProccess" class="form-control-sm">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            foreach ($dispath_proscess as $val) {
                                            ?>
                                                <option value="<?= $val ?>" <?= (isset($order) && $val == $order->dispath_proccess) ? 'selected' : '' ?>> <?= $val ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                        <?php
                                        // $dispath = $order->dispath_proccess ?? '';
                                        ?>
                                        <!-- <input type="text" class="form-control-sm" id="dispathProccess" value="<?= $dispath ?>" placeholder="" required> -->
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table>
                                <tr>
                                    <td>Load Status</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $loadStatus = array(
                                            'NORMAL',
                                            'TOP PRIORITAS'
                                        );
                                        ?>
                                        <select class="form-control-sm" id="loadStatus">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            $ldsts = $order->load_status ?? '';
                                            foreach ($loadStatus as $val) {
                                            ?>
                                                <option value="<?= $val ?>" <?= ($ldsts == $val) ? 'selected' : '' ?>> <?= $val ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Transporter</td>
                                    <td>:</td>
                                    <td style="white-space: nowrap;">

                                        <?php
                                        $transporter_id = $order->transporter_id ?? '';
                                        $transporter_code = $order->ekspedisi_code ?? '';
                                        $transporter_name = $order->ekspedisi_name ?? '';
                                        ?>

                                        <input type="hidden" id="transporterID" value="<?= $transporter_id ?>">
                                        <input style="max-width: 120px;" type="text" class="form-control-sm" id="transporter" value="<?= $transporter_code ?>" placeholder="" readonly>
                                        <input style="width: 220px;" type="text" class="form-control-sm" id="transporter_name" value="<?= $transporter_name ?>" placeholder="" readonly>
                                        <button type="button" class="btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">search</button>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck Type</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $truckType = $order->truck_type ?? '';
                                        ?>

                                        <select class="form-control-sm" id="truckType">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            foreach ($truck->result() as $data) {
                                            ?>
                                                <option value="<?= $data->truck_name ?>" <?= ($data->truck_name == $truckType) ? 'selected' : ''; ?>> <?= $data->truck_name ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Truck No</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="truckNo" value="<?= $order->truck_no ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Driver Name</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="driverName" value="<?= $order->driver_name ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Driver Phone</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="driverPhone" value="<?= $order->driver_phone ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total CBM</td>
                                    <td>:</td>
                                    <td>
                                        <input type="number" class="form-control-sm" id="totalCBM" value="<?= $order->total_cbm ?? '' ?>" placeholder="" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Charge By</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $charge_by = array(
                                            'TRIP',
                                            'KG',
                                            'KM'
                                        );
                                        ?>
                                        <select class="form-control-sm" id="chargeBy">
                                            <option value="">-- Choose --</option>
                                            <?php
                                            foreach ($charge_by as $val) {
                                            ?>
                                                <option value="<?= $val ?>" <?= (isset($order) && $val == $order->charge_by) ? 'selected' : '' ?>> <?= $val ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Remarks</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control-sm" id="remarks" placeholder="" value="<?= $order->remarks ?? '' ?>" required>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-default">
                <strong>Total Selected Items: <span id="selectedOrdersCount">0</span></strong>
                <button type="button" class="btn-sm bg-warning float-end" data-bs-toggle="modal" data-bs-target="#modalAvailableOrder">List Item</button>
            </div>
            <div class="card-body table-responsive">
                <table style="white-space: nowrap; font-size: smaller;" class="table table-bordered table-sm table-striped" id="cartTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Qty (Pcs)</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" id="generateSPK" disabled>Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ekspedisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Eks ID</th>
                            <th>Eks Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($ekspedisi->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->code ?></td>
                                <td><?= $data->name ?></td>
                                <td><button class="btn btn-primary btn-sm btnEkpedisi" data-id="<?= $data->id ?>" data-code="<?= $data->code ?>" data-name="<?= $data->name ?>">select</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Order Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table style="font-size: smaller;" class="table table-bordered table-sm table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($type->result() as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data->code ?></td>
                                <td><?= $data->name ?></td>
                                <td><button class="btn btn-primary btn-sm btnType" data-id="<?= $data->id ?>" data-code="<?= $data->code ?>" data-name="<?= $data->name ?>">select</button></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAvailableOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning pb-4">
                <h5 style="color: white;" class="modal-title" id="exampleModalLabel">List Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body table-responsive">

                <input type="text" id="searchOrders" class="form-control-sm" placeholder="Search Order">
                <!-- <button class="btn-small float-end" id="btnRefresh">Refresh</button>y -->
                <br>
                <br>
                <div style="max-height: 360px;">
                    <strong>Total : <span id="totalDo">0</span> <span id="resultCount"></span></strong>
                    <table style="white-space: nowrap; font-size: smaller;" class="table table-sm table-bordered table-striped" id="tableShipments">
                        <thead class="bg-warning">
                            <tr style="white-space: nowrap;">
                                <th style="text-align: center;">Select</th>
                                <th>WMS</th>
                                <th>Shipment ID</th>
                                <th>Ship To</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('jar/html/default/') ?>assets/libs/echarts/echarts.min.js"></script>

<script>
    $(document).ready(function() {

        let existingShipments = [];
        let selectedOrders = [];

        getOrder();

        function getOrder() {


            let dataToPost = {};

            let prosesAction = $('#prosesAction').val();

            if (prosesAction == 'edit') {
                dataToPost.spk_number = $('#spkNumber').val();
            }

            $.ajax({
                url: '<?= site_url('order/getOrder') ?>', // URL ke function di controller
                type: 'POST',
                data: dataToPost,
                dataType: 'json',
                success: function(data) {
                    let tableBody = $('#tableShipments tbody');

                    // Iterasi setiap data yang diterima dari server
                    let totalData = parseInt($('#totalDo').text());
                    $.each(data.shipments, function(index, order) {
                        // Cek apakah shipment_id sudah ada dalam tabel
                        if (!existingShipments.includes(order.shipment_id)) {
                            // Jika belum ada, tambahkan baris baru
                            let row = `<tr>
                                            <td style="align-content:center; text-align:center;">
                                                <input type="checkbox" class="order-checkbox" value="${order.shipment_id}" data-order='${JSON.stringify(order)}'/>
                                            </td>
                                            <td>${order.warehouse}</td>
                                            <td>${order.shipment_id}</td>
                                            <td>
                                                ${order.ship_to_city}
                                            </td>
                                        </tr>`;

                            tableBody.append(row); // Tambahkan baris ke tabel
                            existingShipments.push(order.shipment_id); // Tambahkan ID ke daftar existingShipments
                            totalData += 1; // Update jumlah data
                            $('#totalDo').text(totalData);
                        }
                        stopLoading();
                    });

                    if (prosesAction == 'edit') {
                        selectedOrders = data.shipment_current;
                        updateCartTable();

                        $.each(selectedOrders, function(index, order) {
                            $('#tableShipments input[value="' + order.shipment_id + '"]').prop('checked', true);
                        })
                    }
                }
            });
        }

        // Event untuk menambahkan order ke cart
        $('#tableShipments').on('change', '.order-checkbox', function() {
            let order = JSON.parse($(this).attr('data-order'));

            if ($(this).is(':checked')) {
                // Tambahkan ke array selectedOrders
                selectedOrders.push(order);
                updateCartTable(order.shipment_id);
            } else {
                // Hapus dari array selectedOrders jika uncheck
                selectedOrders = selectedOrders.filter(o => o.shipment_id !== order.shipment_id);
                updateCartTable(order.shipment_id, true);
            }

            // Update tabel cart



        });

        $('#searchOrders').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            let count = 0; // Variabel untuk menghitung baris yang ditemukan

            $('#tableShipments tbody tr').filter(function() {
                let match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
                if (match) count++; // Tambahkan 1 ke hitungan jika ditemukan
            });

            // Tampilkan hasilnya
            $('#resultCount').text('( ' + count + ' rows found )');

            if (value == '') {
                $('#resultCount').text('');
            }
        });

        $('.btnEkpedisi').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#transporterID').val(id);
            $('#transporter').val(code);
            $('#transporter_name').val(name);
            $('#exampleModal').modal('hide');
        })

        $('.btnType').on('click', function() {
            let id = $(this).data('id');
            let code = $(this).data('code');
            let name = $(this).data('name');
            $('#orderTypeID').val(id);
            $('#orderType').val(code);
            $('#orderTypeName').val(name);
            $('#modalType').modal('hide');
        })

        // $('#btnRefresh').on('click', function() {
        //     startLoading();
        //     $.get('<?= base_url() ?>/order/sync_orders', function() {
        //         getOrder();
        //     });
        // })


        // Function untuk mengupdate tampilan tabel cart
        function updateCartTable(shipment_id_selected = null, uncheck = false) {
            let cartBody = $('#cartTable tbody');

            // cartBody.empty();


            if (selectedOrders.length > 0) {

                $('#selectedOrdersCount').text(selectedOrders.length); // Update jumlah order
                
                
                if (uncheck) {
                    $('#cartTable tbody tr').each(function() {
                        let shipmentIdInput = $(this).find('.in-item');
                        let shipmentId = shipmentIdInput.val();

                        console.log(shipmentId);
                        if (shipmentId == shipment_id_selected) {
                            $(this).remove();
                        }
                    });
                    return;
                }




                $('#generateSPK').prop('disabled', false); // Aktifkan tombol Generate SPK jika ada order di cart
                $.each(selectedOrders, function(index, order) {
                    let row = `<tr>
                                        <td>${index + 1}</td>
                                        <td>${order.shipment_id}</td>
                                        <td>${order.ship_to_name}</td>
                                        <td>
                                            <input type="hidden" class="form-control-sm in-item" value="${order.shipment_id}">
                                            <input type="number" class="form-control-sm in-qty" value="1">
                                        </td>
                                        <td><input type="text" class="form-control-sm in-rcv-loc" value="stagging"></td>
                                        <td><input type="text" class="form-control-sm in-status" value="approved" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-order" data-id="${order.shipment_id}">Remove</button></td>
                                    </tr>`;

                    if (shipment_id_selected != null) {

                        let isExist = false;

                        $('#cartTable tbody tr').each(function() {
                            let shipmentIdInput = $(this).find('.in-item');
                            let shipmentId = shipmentIdInput.val();
                            if (shipmentId == order.shipment_id) {
                                isExist = true;
                            }
                        });

                        if (!isExist) {
                            cartBody.append(row);
                            return false;
                        }

                    } else {
                        cartBody.append(row);
                    }



                });


            } else {
                $('#generateSPK').prop('disabled', true); // Nonaktifkan tombol jika cart kosong
                $('#selectedOrdersCount').text(0); // Reset jumlah order jika tidak ada yang dipilih
            }
        }

        // Saat tombol Generate SPK diklik
        $('#generateSPK').on('click', function() {
            if (selectedOrders.length > 0) {
                let orderIds = selectedOrders.map(order => order.shipment_id);

                const dataObj = {};

                // Mencari semua input dan select di dalam form
                $('#formHeader').find('input, select').each(function() {
                    const inputId = $(this).attr('id'); // Ambil ID
                    const inputValue = $(this).val(); // Ambil nilai

                    // Simpan dalam objek
                    dataObj[inputId] = inputValue;
                });

                // console.log(dataObj); // Untuk memeriksa objek hasil

                let suratJalanHeader = {
                    nomor: $('#suratJalanNumber').val(),
                    tanggal: $('#tanggalSuratJalan').val(),
                    pengirim: $('#namaPengirim').val(),
                    penerima: $('#namaPenerima').val()
                };

                let itemsDetail = collectCartItems();




                let prosesAction = $('#prosesAction').val();

                let url = '<?= site_url('receiving/createProccess') ?>';
                // if (prosesAction == 'edit') {
                //     url = '<?= site_url('order/editSpk') ?>';
                // }

                $.ajax({
                    url: url, // URL ke function untuk create SPK
                    type: 'POST',
                    data: {
                        order_ids: orderIds,
                        header: dataObj,
                        items: itemsDetail
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // alert('SPK berhasil dibuat!');
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Data has been saved successfully!',
                                showCancelButton: true,
                                confirmButtonText: 'Print SPK',
                                cancelButtonText: 'Ok',
                                customClass: {
                                    confirmButton: 'btn btn-success me-2',
                                    cancelButton: 'btn btn-secondary'
                                },
                                buttonsStyling: false,
                                allowOutsideClick: false
                            }).then((result) => {
                                selectedOrders = []; // Kosongkan cart
                                updateCartTable(); // Refresh tampilan cart
                                $('#tableShipments input[type="checkbox"]').prop('checked', false);

                                if (result.isConfirmed) {
                                    $('body').empty();
                                    // printSpk(response.spk_number);
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    $('body').empty();
                                    window.location.href = 'receivingList';
                                }
                            });
                        } else {
                            alert('Gagal membuat SPK.');
                        }
                    }
                });
            }
        });

        // function printSpk(spkNumber) {
        //     const printUrl = "<?= site_url('order/spkShow?spk=') ?>" + spkNumber; // Sesuaikan URL endpoint untuk print SPK
        //     window.open(printUrl, '_blank'); // Membuka halaman cetak di tab baru
        //     window.location.href = 'listOrder';
        // }

        // Event untuk menghapus order dari cart
        $('#cartTable').on('click', '.remove-order', function() {
            let orderId = $(this).attr('data-id');
            
            
            // selectedOrders = selectedOrders.filter(o => o.shipment_id !== orderId);
            $(`input[value="${orderId}"]`).prop('checked', false);
            
            updateCartTable(orderId, true);
            // Update tabel cart
            // updateCartTable();

        });

        // Mengumpulkan detail item dari tabel cart saat ingin mengirim
        function collectCartItems() {
            let cartItems = [];

            // Iterasi setiap baris di dalam tabel cart
            $('#cartTable tbody tr').each(function() {
                let shipmentId = $(this).find('.in-item').val(); 
                let quantity = $(this).find('.in-qty').val(); 
                let location = $(this).find('.in-rcv-loc').val(); 
                let status = $(this).find('.in-status').val(); 

                // Buat objek untuk setiap item
                if (shipmentId && quantity) { // Pastikan kedua input tidak kosong
                    cartItems.push({
                        item_code : shipmentId,
                        quantity: parseInt(quantity), // Pastikan quantity adalah integer
                        rcv_loc : location,
                        status : status
                    });
                }
            });

            return cartItems; // Kembalikan array yang berisi objek item
        }
    });
</script>