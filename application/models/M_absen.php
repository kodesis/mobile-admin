<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_absen extends CI_Model
{
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
                        $endOfDay->modify('+2 hours');

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
                            $tipe = 'Keluar';
                        } elseif ($currentTime->format('H:i:s') >= $endOfDay->format('H:i:s')) {
                            // After jam_keluar, it is 'Pulang'
                            $tipe = 'Pulang';
                        }

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
        // $this->db->where('userImage !=', NULL);
        $this->db->where('username', $this->session->userdata('username'));
        $query = $this->db->get();

        // return $query->result_array(); // Return the result as an array
        return $query->row(); // Return the result as an array
    }
}
