<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_absen extends CI_Model
{

    var $table = 'tblattendance';
    var $column_order = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable orderable
    var $column_search = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable searchable 
    var $order = array('date' => 'desc', 'waktu' => 'desc'); // default order 

    function _get_datatables_query()
    {

        $this->db->select('tblattendance.*');
        $this->db->from('tblattendance');
        $this->db->where('username', $this->session->userdata('username'));
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            // $this->db->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all()
    {

        $this->_get_datatables_query();
        $query = $this->db->get();

        return $this->db->count_all_results();
    }


    var $table2 = 'tblattendance';
    var $column_order2 = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable orderable
    var $column_search2 = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable searchable 
    var $order2 = array('date' => 'desc', 'waktu' => 'desc'); // default order 

    function _get_datatables_query2()
    {

        $this->db->select('tblattendance.*,users.bagian');
        $this->db->from('tblattendance');
        $this->db->where('bagian', $this->session->userdata('bagian'));
        $this->db->join('users', 'users.username = tblattendance.username');
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            // $this->db->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    function get_datatables2()
    {
        $this->_get_datatables_query2();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered2()
    {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all2()
    {

        $this->_get_datatables_query2();
        $query = $this->db->get();

        return $this->db->count_all_results();
    }


    var $table3 = 'tblattendance';
    var $column_order3 = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable orderable
    var $column_search3 = array('tblattendance.nama', 'tblattendance.date', 'attendanceStatus', 'tblattendance.nip', 'tblattendance.nama', 'attendanceStatus', 'lokasiAttendance', 'tipe', 'date', 'waktu'); //set column field database for datatable searchable 
    var $order3 = array('date' => 'desc', 'waktu' => 'desc'); // default order 

    function _get_datatables_query3()
    {

        $this->db->select('tblattendance.*,users.bagian');
        $this->db->from('tblattendance');
        $this->db->where('attendanceStatus', 'Pending');
        $this->db->where('bagian', $this->session->userdata('bagian'));
        $this->db->join('users', 'users.username = tblattendance.username');
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            // $this->db->order_by(key($order), $order[key($order)]);
            foreach ($order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    function get_datatables3()
    {
        $this->_get_datatables_query3();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered3()
    {
        $this->_get_datatables_query3();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all3()
    {

        $this->_get_datatables_query3();
        $query = $this->db->get();

        return $this->db->count_all_results();
    }

    public function get_user()
    {
        $this->db->select('*'); // Fetch only these columns
        $this->db->from('users'); // Table name
        // $this->db->where('userImage !=', NULL);
        $this->db->where('username', $this->session->userdata('username'));
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->result_array(); // Return the result as an array
    }
    public function check_registration_exists($username)
    {
        $this->db->where('username', $username);
        return $this->db->count_all_results('users') > 0;
    }
    public function insertAttendance($attendanceData)
    {
        $response = ['status' => 'error', 'message' => 'No data provided'];


        if (!empty($attendanceData)) {
            try {
                foreach ($attendanceData as $data) {
                    // Fetch the user's jam_masuk and jam_keluar values
                    $this->db->select('jam_masuk, jam_keluar');
                    $this->db->from('users');
                    $this->db->where('username', $data['username']);
                    $jam = $this->db->get()->row();

                    // Ensure we have both jam_masuk and jam_keluar
                    if ($jam) {
                        // Get current time and time ranges
                        $currentTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

                        // Parse jam_masuk and jam_keluar as DateTime objects
                        $startOfDay = new DateTime($jam->jam_masuk); // Assuming format is H:i:s
                        $endOfDay = new DateTime($jam->jam_keluar);
                        $startOfDay->modify('+2 hours');

                        // Debug outputs
                        // echo "Current Time: " . $currentTime->format('H:i:s') . "<br>";
                        // echo "Start of Day: " . $startOfDay->format('H:i:s') . "<br>";
                        // echo "End of Day: " . $endOfDay->format('H:i:s') . "<br>";

                        // Check the time and set 'tipe' based on current time
                        if ($currentTime->format('H:i:s') < $startOfDay->format('H:i:s')) {
                            // Before jam_masuk, it is 'Masuk'
                            $tipe = 'Masuk';
                        } elseif ($currentTime->format('H:i:s') >= $startOfDay->format('H:i:s') && $currentTime->format('H:i:s') < $endOfDay->format('H:i:s')) {
                            // Between jam_masuk and jam_keluar, it is 'Keluar'
                            $tipe = 'Telat/Keluar';
                        } elseif ($currentTime->format('H:i:s') >= $endOfDay->format('H:i:s')) {
                            // After jam_keluar, it is 'Pulang'
                            $tipe = 'Pulang';
                        }

                        $this->db->select('*');
                        $this->db->from('tblattendance');
                        $this->db->where('username', $data['username']);
                        $this->db->where('tipe', $tipe);
                        $this->db->where('date', date("Y-m-d"));
                        $cek_absen = $this->db->get()->row();
                        if (empty($cek_absen)) {
                            // Insert the attendance record
                            $this->db->insert('tblattendance', [
                                'username' => $data['username'],
                                'nip' => $data['nip'],
                                'nama' => $data['nama'],
                                'attendanceStatus' => $data['attendanceStatus'],
                                'lokasiAttendance' => $data['lokasiAttendance'],
                                'date' => date("Y-m-d"),
                                'tipe' => $tipe
                            ]);
                        }
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = "User not found for username: " . $data['username'];
                        return $response;
                    }
                }

                $response['status'] = 'success';
                $response['message'] = "Attendance recorded successfully for all entries.";
            } catch (Exception $e) {
                $response['status'] = 'error';
                $response['message'] = "Error inserting attendance data: " . $e->getMessage();
            }
        }

        return $response;
    }
    public function get_location()
    {
        $this->db->select('*'); // Fetch all columns
        $this->db->from('lokasi_presensi'); // Table name
        $query = $this->db->get();

        return $query->result_array(); // Return the result as an array
    }
    public function cek_user()
    {
        $this->db->select('*'); // Fetch all columns
        $this->db->from('tblattendance'); // Table name
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('DATE(date)', date('Y-m-d')); // Add condition for today's date
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->result(); // Return the result as an array
    }
    public function data_user()
    {
        $this->db->select('*'); // Fetch only these columns
        $this->db->from('users'); // Table name
        $this->db->where('username', $this->session->userdata('username'));
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }
    public function update($data, $where)
    {
        $this->db->update($this->table, $data, $where);
    }
}
