<option value="">--Choose Destination--</option>
<?php
foreach ($dest->result() as $data) {
?>
    <option value="<?= $data->code ?>" data-name="<?= $data->name ?>"><?= $data->code ?></option>
<?php
}
?>