<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;

        }

        table {
            margin: auto;
        }
    </style>
</head>


<body class="nav-md" onload="window.print()">
    <div class="right_col" role="main">
        <div class="clearfix"></div>

        <div class="x_panel card">
            <img src="<?= base_url('assets/images/kodesis_kotak.png') ?>" alt="" width="10%">
            <br><br>
            <div align="center">
                <font style="font-size:17px;">
                    E-MEMO INTERN<br>
                    No. <?php
                        $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
                        $bln = $array_bln[date('n', strtotime($memo->tanggal))];

                        echo sprintf("%03d", $memo->nomor_memo) . '/E-MEMO/' . $memo->kode_nama . '/' . $bln . '/' . date('Y', strtotime($memo->tanggal));
                        ?>
                    <hr />
                </font>
            </div>
            <font style="font-size:14px;">
                <table class="center">
                    <tr>
                        <td><strong>Dari</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>:&nbsp;&nbsp;</td>
                        <td><?php echo $memo->nama . " (" . $memo->nama_jabatan . ")"; ?></td>
                    </tr>
                    <tr>
                        <td valign="top"><strong>Kepada</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top">:&nbsp;&nbsp;</td>
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
                                echo "<br>";
                                $no++;
                            endforeach;
                            ?></td>
                    </tr>
                    <tr>
                        <td valign="top"><strong>Tembusan</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td valign="top">:&nbsp;&nbsp;</td>
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
                                    echo "<br>";
                                    $no++;
                                endforeach;
                            } else {
                                echo "--";
                            };
                            ?></td>
                    </tr>
                    <tr>
                        <td><strong>Perihal</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>:&nbsp;&nbsp;</td>
                        <td><?php echo $memo->judul; ?></td>
                    </tr>
                </table>
                <hr>
                <div>
                    <div style="page-break-after: avoid;"></div>
                    <div style="page-break-before: avoid; width:95%; margin: auto;" class="content-pdf">
                        <?php echo $memo->isi_memo ?>
                    </div>
                </div>
                <div style="margin-top: 25px;">
                    <span>Jakarta, <?php
                                    $date = $memo->tanggal;
                                    echo $newDate = date("d F Y", strtotime($date));
                                    ?></span>
                </div>
                <?php if (!empty($memo->attach)) { ?>
                    <div>
                        <?php
                        $attach_ = '';
                        $no = '1';
                        $attch1 = explode(";", $memo->attach);
                        $attch2 = explode(";", $memo->attach_name);

                        foreach (array_combine($attch1, $attch2) as $attch1 => $attch2) {
                            if (!empty($attch1)) {
                                $attach_ .= "<a href='" . base_url() . "upload/att_memo/" . $attch1 . "' target='_blank'>" . $no . '. ' . $attch2 . "</a></br>\n";
                                $no++;
                            }
                        }
                        echo $attach_;

                        ?>
                    </div>
                <?php } ?>
            </font>
        </div>
    </div>
</body>

</html>