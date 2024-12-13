<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_app extends CI_Model
{
    public function getData($table, $where)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        return $this->db->get($table);
    }

    public function countMemo($search)
    {
        $nip = $this->session->userdata('nip');
        if (!$search) {
            $sql = "SELECT * FROM memo WHERE nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%'";
            return $this->db->query($sql)->num_rows();
        } else {
            $sql = "SELECT * FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND memo.judul LIKE '%$search%'";
            return $this->db->query($sql)->num_rows();
        }
    }

    public function memo_get($limit, $start, $search)
    {
        $nip = $this->session->userdata('nip');
        if (!$search) {
            $sql = "select a.id,a.nomor_memo,a.nip_kpd,a.judul,a.tanggal,a.read,a.nip_dari,a.persetujuan,b.nama FROM memo a 
                    LEFT JOIN users b ON a.nip_dari = b.nip
                    WHERE nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%' ORDER BY tanggal DESC limit " . $start . ", " . $limit;
        } else {
            $sql = "select a.id,a.nomor_memo,a.nip_kpd,a.judul,a.tanggal,a.read,a.nip_dari,a.persetujuan,b.nama FROM memo a 
                    LEFT JOIN users b ON a.nip_dari = b.nip
                    WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND a.judul like '%$search%' ORDER BY tanggal DESC limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function memo_get_detail($id)
    {
        $nip = $this->session->userdata('nip');
        $sql = "select memo.read FROM memo WHERE Id =$id";
        $query = $this->db->query($sql);
        $result = $query->row();
        $kalimat = $result->read;
        if (preg_match("/$nip/i", $kalimat)) {
        } else {
            $kalimat1 = $kalimat . ' ' . $nip;
            $data_update1    = array(
                'read'    => $kalimat1
            );
            $this->db->where('Id', $id);
            $this->db->update('memo', $data_update1);
        }
        $sql = "
			SELECT a.*,b.nama_jabatan,b.nama,b.supervisi,c.kode_nama,b.level_jabatan 
			FROM memo a
			LEFT JOIN users b ON a.nip_dari = b.nip
			LEFT JOIN bagian c ON b.bagian = c.kode
			WHERE (a.id = '$id' AND (a.nip_dari LIKE '%$nip%' OR a.nip_kpd LIKE '%$nip%' OR a.nip_cc LIKE '%$nip%'))
		";
        //$query = $this->db->query($sql);
        //return $query->result();
        $query = $this->db->query($sql);
        return $query->row();
    }

    function sendto($level_jabatan, $bagian)
    {
        if ($level_jabatan == 2) {
            $sql = "SELECT * FROM users WHERE (status=1) AND ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= '$level_jabatan')) ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 3) {
            $sql = "SELECT * FROM users WHERE (status=1) AND ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 2)) ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 4) {
            $sql = "SELECT * FROM users WHERE (status=1) AND ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 2)) ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 5 and $bagian <> 11) {
            $sql = "SELECT * FROM users WHERE (status=1) AND level_jabatan >= 2 ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 5 and $bagian == 11) {
            $sql = "SELECT * FROM users WHERE (status=1) AND (level_jabatan >= 2 OR bagian = 4) ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 6) {
            $sql = "SELECT * FROM users WHERE (status=1) AND level_jabatan >= 2 ORDER BY level_jabatan DESC";
        } elseif ($level_jabatan == 1) {
            $sql = "SELECT * FROM users WHERE (status=1) AND bagian = '$bagian' ORDER BY level_jabatan DESC";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function count_memo_send($search)
    {
        $nip = $this->session->userdata('nip');
        if (!$search) {
            $sql = "select Id FROM memo WHERE nip_dari LIKE '%$nip%'";
        } else {
            $sql = "select Id FROM memo WHERE nip_dari LIKE '%$nip%' AND memo.judul LIKE '%$search%'";
        }
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function memo_get_send($limit, $start, $search)
    {
        $nip = $this->session->userdata('nip');
        if (!$search) {
            $sql = "select a.id,a.nomor_memo,a.nip_kpd,a.judul,a.tanggal,a.read,a.nip_dari,a.persetujuan,b.nama FROM memo a 
                    LEFT JOIN users b ON a.nip_dari = b.nip
                    WHERE nip_dari LIKE '%$nip%' ORDER BY tanggal DESC limit " . $start . ", " . $limit;
        } else {
            $sql = "select a.id,a.nomor_memo,a.nip_kpd,a.judul,a.tanggal,a.read,a.nip_dari,a.persetujuan,b.nama FROM memo a 
                    LEFT JOIN users b ON a.nip_dari = b.nip
                    WHERE nip_dari LIKE '%$nip%' AND a.judul like '%$search%' ORDER BY tanggal DESC limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function inbox($nip)
    {
        $sql = "SELECT COUNT(Id) as jumlah_memo FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function task($nip)
    {
        $sql = "SELECT COUNT(id) as jumlah_task FROM task WHERE (`member` LIKE '%$nip%' or `pic` like '%$nip%') and activity='1'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    function user_get_detail($nip)
    {
        $sql = "SELECT * from users where nip='$nip' ";
        $query = $this->db->query($sql);
        return $query->row();
    }
    function user_count($nip)
    {
        $sql = "SELECT id FROM users";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    function user_get($limit, $start, $nip)
    {
        $nip = '';
        $sql = "SELECT * FROM users ORDER BY id DESC limit " . $start . ", " . $limit;
        $query = $this->db->query($sql);
        return $query->result();
    }
}
