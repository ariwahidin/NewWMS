<option value="">--Choose Dealer Code--</option>
<?php
foreach ($dealer->result() as $data) {
?>
    <option value="<?= $data->code ?>" data-name="<?= $data->name ?>"><?= $data->code ?></option>
<?php
}
?>