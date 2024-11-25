<!-- <div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <span class="me-3" id="spConnect"></span>
                        <a href="javascript: void(0);">Dashboards </a>
                    </li>
                </ol>
            </div>
        </div>
    </div> -->
<!-- </div> -->

<div class="row">

    <div class="col-md-12">
        <button onclick="window.location.reload();" class="btn btn-primary btn-sm mb-3"><i class="mdi mdi-refresh"></i> Refresh</button>
    </div>

    <div class="col-md-12">
        <div class="card overflow-hidden shadow-none">
            <div class="card-body bg-primary-subtle">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success-subtle text-primary rounded-circle fs-16">
                                <i class="ri-inbox-archive-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-16">INBOUND</h6>
                    </div>
                </div>
            </div>
            <div class="card-body bg-primary-subtle border-top border-danger border-opacity-25 border-top-dashed">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Create Receving
                                    <?php
                                    $sql = "select COUNT(*) as count_cr_rcv 
                                                from receive_header WHERE is_complete = 'N'";
                                    $query = $this->db->query($sql);
                                    $row = $query->row();
                                    ?>
                                    <span class="badge bg-primary text-uppercase"><?= $row->count_cr_rcv; ?></span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">


                                    <?php
                                    $sql = "select receive_number as rcv_nbr 
                                            from receive_header WHERE is_complete = 'N'";
                                    $query = $this->db->query($sql);
                                    foreach ($query->result() as $row) {
                                    ?>
                                        <div class="badge fw-medium bg-secondary-subtle text-secondary"><?= $row->rcv_nbr; ?></div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Putaway Pending
                                    <?php
                                    $sql = "select COUNT(*) as count_cr_rcv 
                                                from putaway_header WHERE is_complete = 'N'";
                                    $query = $this->db->query($sql);
                                    $row = $query->row();
                                    ?>
                                    <span class="badge bg-warning text-uppercase"><?= $row->count_cr_rcv; ?></span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">


                                    <?php
                                    $sql = "select receive_number as rcv_nbr 
                                            from putaway_header WHERE is_complete = 'N'";
                                    $query = $this->db->query($sql);
                                    foreach ($query->result() as $row) {
                                    ?>
                                        <div class="badge fw-medium bg-secondary-subtle text-secondary"><?= $row->rcv_nbr; ?></div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Complete Putaway
                                    <?php
                                    $sql = "select COUNT(*) as count_cr_rcv 
                                                from receive_header a
                                                INNER JOIN putaway_header b on a.receive_number = b.receive_number
                                                WHERE b.is_complete = 'N'";
                                    $query = $this->db->query($sql);
                                    $row = $query->row();
                                    ?>
                                    <span class="badge bg-success text-uppercase"><?= $row->count_cr_rcv; ?></span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">


                                    <?php
                                    $sql = "select a.receive_number as rcv_nbr 
                                            from receive_header a
                                            INNER JOIN putaway_header b on a.receive_number = b.receive_number
                                            WHERE b.is_complete = 'Y'";
                                    $query = $this->db->query($sql);
                                    foreach ($query->result() as $row) {
                                    ?>
                                        <div class="badge fw-medium bg-secondary-subtle text-secondary"><?= $row->rcv_nbr; ?></div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card overflow-hidden shadow-none">
            <div class="card-body bg-success-subtle">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-14">
                                <i class="ri-inbox-unarchive-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fs-16">OUTBOUND</h6>
                    </div>
                </div>
            </div>
            <div class="card-body bg-success-subtle border-top border-danger border-opacity-25 border-top-dashed">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Create Shipment
                                    <span class="badge bg-primary text-uppercase">20</span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">UI/UX</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Figma</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">HTML</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">CSS</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Javascript</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">C#</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Nodejs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Picking Pending
                                    <span class="badge bg-primary text-uppercase">20</span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">UI/UX</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Figma</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">HTML</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">CSS</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Javascript</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">C#</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Nodejs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    Packing Pending
                                    <span class="badge bg-success text-uppercase">20</span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 fs-16">
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">UI/UX</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Figma</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">HTML</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">CSS</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Javascript</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">C#</div>
                                    <div class="badge fw-medium bg-secondary-subtle text-secondary">Nodejs</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


<script src="<?= base_url('jar/html/default/') ?>assets/libs/echarts/echarts.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tableShipments').DataTable();
    });
</script>