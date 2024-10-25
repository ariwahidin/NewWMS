<table id="table_pl_no_sj" class="fs-11 table table-sm table-nowrap table-striped table-bordered compact table-hover" style="width:100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>PL No</th>
            <th>Activity Date</th>
            <th>Dest</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($pl->result() as $data) {
        ?>
            <tr style="cursor:pointer">
                <td><?= $no++ ?></td>
                <td><?= $data->pl_no ?></td>
                <td><?= $data->activity_date ?></td>
                <td><?= $data->dest ?></td>
                <td class="text-center">
                    <input type="checkbox" value="<?= $data->id ?>" class="in_sj_id" style="cursor:pointer">
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>