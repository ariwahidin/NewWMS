<option value="">--Choose No Truck--</option>
<?php
foreach ($ekspedisi->result() as $eks) {
?>
    <option value="<?= $eks->no_truck ?>" data-id="<?= $eks->id ?>"><?= $eks->no_truck ?></option>
<?php
}
?>