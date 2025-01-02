<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<style>
    #btn-create {
        position: fixed;
        bottom: 12%;
        right: 10px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: #4A89DC;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 4px;
    }

    #btn-create:hover {
        background-color: #555;
    }

    .truncate {
        white-space: nowrap;
        /* Prevent text from wrapping */
        overflow: hidden;
        /* Hide overflowing content */
        text-overflow: ellipsis;
        /* Add '...' when text overflows */
        max-width: 150px;
        /* Adjust width to your preference */
    }

    .badge {
        /* padding: 6px 9px !important; */
        font-size: 10px !important;
        font-weight: 500 !important;
        margin-right: 2px;
        transform: translateY(-2px);
        border: 0px !important;
        border-radius: 8px;
        z-index: 2;
        /* position: absolute; */
        right: 15px;
        /* margin-top: 16px !important; */
    }
</style>
<div id="page">
    <?php include APPPATH . '/views/v_nav.php' ?>
    <div class="page-content">
        <div class="content mt-0 mb-3">
            <h3 class="text-center my-3">Absensi LIST</h3>
            <!-- <div class="search-box shadow-xl border-0 bg-theme rounded-sm bottom-0">
                <form action="" method="get">
                    <i class="fa fa-search"></i>
                    <input type="text" class="border-0" placeholder="Fill in the subject you want to search." id="search" name="search" value="<?= strtolower($this->input->get('search') ?? '') ?>">
                </form>
            </div> -->
        </div>
        <div class="card card-style bg-theme pb-0">
            <div class="content" id="tab-group-2">
                <div class="tab-controls tabs-small tabs-rounded" data-highlight="bg-blue-dark">
                    <a href="#" data-active data-bs-toggle="collapse" data-bs-target="#tab-5" onclick="refreshTable1()">User</a>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-6" onclick="refreshTable2()">Tim</a>
                    <?php
                    if ($this->session->userdata('level_jabatan') >= 3) {
                    ?>
                        <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-7" onclick="refreshTable3()">Approval
                            <?php
                            if (!empty($notif)) {
                            ?>
                                <span class="badge gradient-red color-white"><?= $notif ?></span>
                            <?php
                            }
                            ?></a>
                    <?php
                    }
                    ?>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-8" style="color: #198754;"><i class="fa-solid fa-file-excel"></i> Export</a>

                    <!-- <a href="#" data-bs-toggle="collapse" data-bs-target="#tab-7">Tab 7</a> -->
                </div>
                <div class="clearfix mb-3"></div>
                <div data-bs-parent="#tab-group-2" class="collapse show" id="tab-5">
                    <div class="content" style="cursor: pointer;  margin: 0;">
                        <table id="table1" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Nama</th>
                                    <th style="text-align:left;">Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:left;">2</td>
                                    <td>Tiger</td>
                                    <td>Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011-04-25</td>
                                    <td>$320,800</td>
                                    <td>5421</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div data-bs-parent="#tab-group-2" class="collapse" id="tab-6">
                    <div class="content" style="cursor: pointer;  margin: 0;">
                        <table id="table2" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Nama</th>
                                    <th style="text-align:left;">Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:left;">2</td>
                                    <td>Tiger</td>
                                    <td>Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011-04-25</td>
                                    <td>$320,800</td>
                                    <td>5421</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div data-bs-parent="#tab-group-2" class="collapse" id="tab-7">
                    <div class="content" style="cursor: pointer;  margin: 0;">
                        <table id="table3" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Nama</th>
                                    <th style="text-align:left;">Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:left;">2</td>
                                    <td>Tiger</td>
                                    <td>Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011-04-25</td>
                                    <td>$320,800</td>
                                    <td>5421</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Nip</th>
                                    <th>Full Name</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div data-bs-parent="#tab-group-2" class="collapse" id="tab-8">
                    <div class="content" style="cursor: pointer;  margin: 0;">
                        <form class="form" id="form_export" action="<?= base_url('absensi/process_export') ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="" class="label">Tanggal Absensi</label>
                                    <input type="text" class="form-control month-picker" name="tanggal" id="tanggal_export_absensi">
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="label">Data</label>
                                    <select class="form-control" name="data_absensi" id="data_absensi">
                                        <option value="All" selected>All</option>
                                        <option value="User">User</option>
                                        <option value="Team">Team</option>
                                        <!-- <option value="Team">Team</option> -->
                                    </select>

                                </div>
                            </div>
                            <!-- <button class="btn btn-success rounded">Export</button> -->
                        </form>
                        <button class="btn btn-success rounded" onclick="proccess_export()">Export</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination -->
        <!-- <div class="content">
            <div class="row">
                <div class="col-12 font-15">
                    <nav>
                        <?= $pagination ?>
                    </nav>
                </div>
            </div>
        </div> -->

        <!-- Button Create -->
        <!-- <a href="<?= base_url('app/create_memo') ?>" class="btn" id="btn-create"><i class="fa-solid fa-plus"></i></a> -->
    </div>
</div>
<script>
    function onApprove(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah anda yakin ingin Approve Absensi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Approve Absensi',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {
                url = "<?php echo site_url('absensi/approval/Approved/') ?>" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function() {
                        swal.fire("Saving data...");

                    },
                    success: function(data) {
                        /* if(!data.status)alert("ho"); */
                        if (!data.status) swal.fire('Gagal menyimpan data', 'error ');
                        else {
                            // document.getElementById('PakaianAdat').reset();

                            (JSON.stringify(data));
                            swal.fire({
                                customClass: 'slow-animation',
                                icon: 'success',
                                showConfirmButton: false,
                                title: 'Berhasil Approve',
                                timer: 1500

                            });
                            $('#table1').DataTable().ajax.reload();
                            $('#table2').DataTable().ajax.reload();
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire('Operation Failed!', errorThrown, 'error');
                    },
                    complete: function() {
                        console.log('Editing job done');

                    }

                });
            }
        })
    }

    function onNotApprove(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Apakah anda yakin ingin Tidak Approve Absensi?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tidak Approve Absensi',
            cancelButtonText: 'Tidak',
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {
                url = "<?php echo site_url('absensi/approval/NotApproved/') ?>" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function() {
                        swal.fire("Saving data...");

                    },
                    success: function(data) {
                        /* if(!data.status)alert("ho"); */
                        if (!data.status) swal.fire('Gagal menyimpan data', 'error ');
                        else {
                            // document.getElementById('PakaianAdat').reset();

                            (JSON.stringify(data));
                            swal.fire({
                                customClass: 'slow-animation',
                                icon: 'success',
                                showConfirmButton: false,
                                title: 'Berhasil Tidak Approve',
                                timer: 1500

                            });
                            $('#table1').DataTable().ajax.reload();
                            $('#table2').DataTable().ajax.reload();

                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal.fire('Operation Failed!', errorThrown, 'error');
                    },
                    complete: function() {
                        console.log('Editing job done');

                    }

                });
            }
        })
    }

    function refreshTable1() {

        $('#table1').DataTable().ajax.reload();
    }

    function refreshTable2() {

        $('#table2').DataTable().ajax.reload();
    }

    function refreshTable3() {

        $('#table3').DataTable().ajax.reload();
    }

    function proccess_export() {
        const ttlTanggalValue = $('#tanggal_export_absensi').val();
        const ttlDataValue = $('#data_absensi').val();

        if (!ttlTanggalValue) {
            // alert('Input is empty. Please fill it out.');
            swal.fire({
                customClass: 'slow-animation',
                icon: 'error',
                showConfirmButton: false,
                title: 'Kolom Tanggal Export Tidak Boleh Kosong',
                timer: 1500
            });
        } else if (!ttlDataValue) {
            // alert('Input is empty. Please fill it out.');
            swal.fire({
                customClass: 'slow-animation',
                icon: 'error',
                showConfirmButton: false,
                title: 'Kolom Data Tidak Boleh Kosong',
                timer: 1500
            });
        } else {
            // var url;
            // var formData;
            // url = "<?php echo site_url('absensi/process_export') ?>";
            // var formData = new FormData($("#form_export")[0]);
            // $.ajax({
            //     url: url,
            //     type: "POST",
            //     data: formData,
            //     contentType: false,
            //     processData: false,
            //     xhrFields: {
            //         responseType: 'blob' // Expect a binary response (Excel file)
            //     },
            //     beforeSend: function() {
            //         swal.fire({
            //             icon: 'info',
            //             timer: 3000,
            //             showConfirmButton: false,
            //             title: 'Loading...'

            //         });
            //     },
            //     success: function(blob, status, xhr) {
            //         const contentDisposition = xhr.getResponseHeader('Content-Disposition');
            //         const matches = contentDisposition.match(/filename="(.+)"/);
            //         const fileName = matches ? matches[1] : 'export.xlsx';

            //         const url = window.URL.createObjectURL(blob);
            //         const a = document.createElement('a');
            //         a.href = url;
            //         a.download = fileName;
            //         document.body.appendChild(a);
            //         a.click();
            //         a.remove();
            //         window.URL.revokeObjectURL(url);

            //         swal.fire({
            //             customClass: 'slow-animation',
            //             icon: 'success',
            //             showConfirmButton: false,
            //             title: 'Berhasil Melakuakn Export',
            //             timer: 1500
            //         });
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         swal.fire('Operation Failed!', errorThrown, 'error');
            //     },
            //     complete: function() {
            //         console.log('Editing job done');
            //     }
            // });
            $('#form_export').submit();
            setTimeout(function() {
                location.reload();
            }, 5000); // Delay of 10 seconds (10000 milliseconds)        }

        }
    }
</script>