<style>
    table {
        border: 0 !important;
        border-collapse: collapse;
    }

    table.center {
        margin-left: auto;
        margin-right: auto;
    }
</style>
<div id="page">
    <?php include APPPATH . '/views/v_nav.php' ?>
    <div class="page-content">
        <div class="content mt-0 mb-3">
            <div class="text-center mt-4">
                <font style="font-size:17px;">
                    E-MEMO INTERN</br>
                    No.
                    <?php
                    $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
                    $bln = $array_bln[date('n', strtotime($memo->tanggal))];

                    echo sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/' . $memo->kode_nama . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                    ?>
                    <hr />
                </font>
            </div>
            <table class="center">
                <tr>
                    <td style="width:30%"><strong>From</td>
                    <td> : </td>
                    <td><?php echo $memo->nama . " (" . $memo->nama_jabatan . ")"; ?></td>
                </tr>
                <tr>
                    <td valign="top"><strong>To</td>
                    <td valign="top"> : </td>
                    <td>
                        <?php
                        $no = 0;
                        $string = substr($memo->nip_kpd, 0, -1);
                        $arr_kpd = explode(";", $string);
                        foreach ($arr_kpd as $data) :
                            $sql = "SELECT nama,nama_jabatan FROM users WHERE nip='$data';";
                            $query = $this->db->query($sql);
                            $result = $query->row();
                            echo $result->nama . " (" . $result->nama_jabatan . ")";
                            echo "</br>";
                            $no++;
                        endforeach;
                        ?></td>
                </tr>
                <tr>
                    <td valign="top"><strong>CC</td>
                    <td valign="top"> : </td>
                    <td>
                        <?php
                        $no = 0;
                        if (!empty($memo->nip_cc)) {
                            $string = substr($memo->nip_cc, 0, -1);
                            $arr_kpd = explode(";", $string);
                            foreach ($arr_kpd as $data) :
                                $sql = "SELECT nama,nama_jabatan FROM users WHERE nip='$data';";
                                $query = $this->db->query($sql);
                                $result = $query->row();
                                echo $result->nama . " (" . $result->nama_jabatan . ")";
                                echo "</br>";
                                $no++;
                            endforeach;
                        } else {
                            echo "--";
                        };
                        ?></td>
                </tr>
                <tr>
                    <td style="width:30%"><strong>Subject</td>
                    <td> : </td>
                    <td><?php echo $memo->judul; ?></td>
                </tr>
            </table>
            <hr />
            </br>

            <table>
                <tr>
                    <td style="word-wrap: break-word; text-align:justify;" width="100%"><?php echo $memo->isi_memo; ?></td>
                </tr>
            </table></br></br>

            <table>
                <tr>
                    <td width="100%">Jakarta,
                        <?php
                        $date = $memo->tanggal;
                        echo $newDate = date("d F Y", strtotime($date));
                        ?>
                    </td>
                </tr>
                <?php if (($this->session->userdata('level_jabatan') >= 1) and ($memo->nip_dari <> $this->session->userdata('nip'))) { ?>
                    <tr width="100%">
                        <td style="text-align:right">
                            <button type="button" class="btn btn-warning" onclick="history.back()"><i class="fa-solid fa-circle-chevron-left"></i></button>
                        </td>
                        <td style="text-align:right">
                            <form action="<?php echo base_url() . "app/create_memo_approve/" . $memo->Id . "/x"; ?>" target="">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-reply"></i></button>
                            </form>
                        </td>
                        <td style="text-align:right">
                            <form action="<?php echo base_url() . "app/create_memo_approve/" . $memo->Id; ?>" target="">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-reply-all" aria-hidden="true"></i></button>
                            </form>
                        </td>
                        <td style="text-align:right"><a href="<?= base_url('app/memo_pdf/' . $memo->Id) ?>" class="btn btn-success" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a></td>
                    </tr>
                <?php } else { ?>

                <?php } ?>

            </table>
            <br>
            <table>
                <tr>
                    <td>Attachment : </td>
                </tr>
                <?php if (!empty($memo->attach)) { ?>
                    <tr>
                        <td>
                            <?php
                            $attach_ = '';
                            $no = '1';
                            $i = 0;
                            $attch1 = explode(";", $memo->attach);
                            $attch2 = explode(";", $memo->attach_name);

                            foreach (array_combine($attch1, $attch2) as $attch1 => $attch2) {
                                if (!empty($attch1)) {
                                    $array = explode('.', $attch1);
                                    $extension = end($array);
                                    
                                    if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
                                        $attach_ .= "<a href='#' data-bs-toggle='modal' data-bs-target='#exampleModal$no'>" . $no . '. ' . $attch2 . "</a></br>\n";
                                    } else {
                                        $attach_ .= "<a href='https://moc.mlejitoffice.id/upload/att_memo/" . $attch1 . "' target='_blank' onclick='PageReload()'>" . $no . '. ' . $attch2 . "</a></br>\n";
                                    }    
                                } 
                                ?>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal<?= $no ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Attachment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="https://moc.mlejitoffice.id/upload/att_memo/<?= $attch1 ?>" alt="attachment" width="100%">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                $no++;
                            }
                            echo $attach_;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>