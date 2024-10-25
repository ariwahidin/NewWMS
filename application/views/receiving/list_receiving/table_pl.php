<table id="tablePL" class="table table-sm table-nowrap table-striped table-bordered compact table-hover" style="width:100%:">
    <thead>
        <tr>
            <th>#</th>
            <th>PL No</th>
            <th>Activity Date</th>
            <th>Created Date</th>
            <th>Status</th>
            <th>SJ No</th>
            <th>SJ Time</th>
            <th>PL Print Time</th>
            <th>PL Date Amano</th>
            <th>PL Time Amano</th>
            <th>Qty</th>
            <th>Dealer Code</th>
            <th>Dealer / Depo</th>
            <th>Ekspedisi</th>
            <th>Dock</th>
            <th>No Truck</th>
            <th>Dest</th>
            <th>Pintu Loading</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($picking_list->result() as $data) {
        ?>
            <tr class="btnEdit" data-id="<?= $data->id ?>" style=" cursor: pointer;">
                <td><?= $no++ ?></td>
                <td><?= $data->pl_no ?></td>
                <td><?= $data->activity_date ?></td>
                <td><?= date('Y-m-d H:i:s', strtotime($data->created_at)) ?></td>
                <td><?= $data->status ?></td>
                <td><?= $data->sj_no ?></td>
                <td><?= $data->sj_time ?></td>
                <td><?= $data->pl_print_time ?></td>
                <td><?= $data->adm_pl_date ?></td>
                <td><?= $data->adm_pl_time ?></td>
                <td><?= $data->tot_qty ?></td>
                <td><?= $data->dealer_code ?></td>
                <td><?= $data->dealer_det ?></td>
                <td><?= $data->ekspedisi_name ?></td>
                <td><?= $data->dock ?></td>
                <td><?= $data->no_truck ?></td>
                <td><?= $data->dest ?></td>
                <td><?= $data->pintu_loading ?></td>
                <td><?= $data->remarks ?></td>
                <td>
                    <button class="btn btn-sm btn-info btnEdit" data-id="<?= $data->id ?>">Edit</button>
                    <button class="btn btn-sm btn-danger btnDelete" data-id="<?= $data->id ?>">Delete</button>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>