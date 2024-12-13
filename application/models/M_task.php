<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_task extends CI_Model
{
  public function task_count($search)
  {
    $nip = $this->session->userdata('nip');
    if (!$search) {
      $sql = "SELECT id FROM task where member like '%$nip%' or pic like '%$nip%'";
      $query = $this->db->query($sql);
    } else {
      $sql = "SELECT id FROM task where (member like '%$nip%' or pic like '%$nip%') AND task.name like '%$search'";
      $query = $this->db->query($sql);
    }
    return $query->num_rows();
  }

  public function task_get($limit, $start, $search)
  {
    $nip = $this->session->userdata('nip');
    if (!$search) {
      $sql = "SELECT * from task where member like '%$nip%' or pic like '%$nip%' ORDER BY activity asc , date_created desc limit " . $start . ", " . $limit;
      $query = $this->db->query($sql);
    } else {
      $sql = "SELECT * from task where (member like '%$nip%' or pic like '%$nip%') AND task.name like '%$search%' ORDER BY activity asc , date_created desc limit " . $start . ", " . $limit;
      $query = $this->db->query($sql);
    }
    return $query->result();
  }

  public function sendto($level_jabatan, $bagian)
  {
    if ($level_jabatan == 2) {
      $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 3) {
      $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) AND level like '%601%' ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 4) {
      $sql = "SELECT * FROM users WHERE ((level_jabatan <= '$level_jabatan' AND bagian = '$bagian') OR (level_jabatan >= 1)) ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 5 and $bagian <> 11) {
      $sql = "SELECT * FROM users WHERE level_jabatan >= 1 ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 5 and $bagian == 11) {
      $sql = "SELECT * FROM users WHERE (level_jabatan >= 1 OR bagian = 4) ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 6) {
      $sql = "SELECT * FROM users WHERE level_jabatan >= 1 ORDER BY level_jabatan DESC";
    } elseif ($level_jabatan == 1) {
      $sql = "SELECT * FROM users WHERE bagian = '$bagian' ORDER BY level_jabatan DESC";
    }
    $query = $this->db->query($sql);
    return $query->result();
  }
}
