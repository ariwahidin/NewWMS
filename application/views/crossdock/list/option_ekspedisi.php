<option value="">--Choose Ekspedisi--</option>
<?php
foreach ($ekspedisi->result() as $eks) {
?>
    <option value="<?= $eks->id ?>"><?= $eks->name ?>
    <?php
}
    ?>