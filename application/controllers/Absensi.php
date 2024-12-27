<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Api_Whatsapp');
        $this->load->model('m_app');
        if ($this->session->userdata('isLogin') == FALSE) {
            $this->session->set_flashdata(
                'msg',
                '<div class="alert rounded-s bg-red-dark" role="alert">
                    Your session has been expired! Please login!
                    <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">&times;</button>
                </div>'

            );
            redirect('auth');
        }
    }
    public function user()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
            // Pagination
            $config['base_url'] = base_url('app/inbox');
            $config['total_rows'] = $this->M_app->countMemo($search);
            $config['per_page'] = 10;
            $config['uri_segment'] = 3;
            $config['num_links'] = 1;
            $config['enable_query_strings'] = TRUE;
            $config['page_query_string'] = TRUE;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
            $config['query_string_segment'] = 'page';

            // Bootstrap style pagination
            $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = '<i class="fa-solid fa-angles-left"></i>';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa-solid fa-angles-right"></i>';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa-solid fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa-solid fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';
            $config['attributes'] = array('class' => 'page-link rounded-xs bg-dark-dark color-white shadow-l border-0');

            // Initialize paginaton
            $this->pagination->initialize($config);
            $page = ($this->input->get('page')) ? (($this->input->get('page') - 1) * $config['per_page']) : 0;
            $data['inbox'] = $this->M_app->memo_get($config['per_page'], $page, $search);
            $data['pagination'] = $this->pagination->create_links();

            $this->load->view('Layouts/v_header', $data);
            $this->load->view('absensi/v_user_list', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }
    public function absen_wfa()
    {
        $this->load->model('M_absen', 'user');
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
            $data['user'] = $this->m_app->user_get_detail($this->session->userdata('nip'));
            $data['cek_user'] = $this->user->cek_user();
            $data['lokasi_absensi'] = $this->user->get_location();
            $data['data_user'] = $this->user->get_user();
            $data['data_users'] = $this->user->data_user();

            // Access properties using '->' because $cek_user is an object
            $data_user = $this->user->data_user();

            // Ensure $cek_user is not null and contains jam_masuk and jam_keluar
            if ($data_user && isset($data_user->jam_masuk) && isset($data_user->jam_keluar)) {
                $jam_masuk_plus_two = (new DateTime($data_user->jam_masuk))->modify('+2 hours')->format('H:i:s');
                $jam_keluar_plus_two = (new DateTime($data_user->jam_keluar))->modify('+0 hours')->format('H:i:s');
            } else {
                echo 'Error: Missing "jam_masuk" or "jam_keluar" data.';
                return;
            }

            $this->db->select('*');
            $this->db->from('tblattendance');
            $this->db->where('username', $this->session->userdata('username')); // Filter by username
            $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
            $this->db->where('TIME(waktu) <=', $jam_masuk_plus_two); // Check for records under jam_masuk_plus_two
            $query = $this->db->get(); // Execute the query
            $result1 = $query->result_array(); // Fetch results

            $this->db->select('*');
            $this->db->from('tblattendance');
            $this->db->where('username', $this->session->userdata('username')); // Filter by username
            $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
            $this->db->where('TIME(waktu) >=', $jam_keluar_plus_two); // Check for records under jam_keluar_plus_two
            $query = $this->db->get(); // Execute the query
            $result2 = $query->result_array(); // Fetch results

            $this->db->select('*');
            $this->db->from('tblattendance');
            $this->db->where('username', $this->session->userdata('username')); // Filter by username
            $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
            $this->db->where('TIME(waktu) >=', $jam_masuk_plus_two); // Check for records after jam_masuk_plus_two
            $this->db->where('TIME(waktu) <=', $jam_keluar_plus_two); // Check for records before jam_keluar_plus_two
            $query = $this->db->get(); // Execute the query
            $result3 = $query->result_array(); // Fetch results

            $this->db->select('*');
            $this->db->from('users');
            $this->db->where('username', $this->session->userdata('username')); // Filter by username
            $query = $this->db->get(); // Execute the query
            $lokasi_presensi_user = $query->row(); // Fetch results

            $data['result1'] = $result1;
            $data['result2'] = $result2;
            $data['result3'] = $result3;
            $data['lokasi_presensi_user'] = $lokasi_presensi_user;

            $this->load->view('Layouts/v_header', $data);
            $this->load->view('absensi/v_absen_wfa', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function fetch_user($tipe = null)
    {
        $this->load->model('M_absen', 'user');
        $data_user = $this->user->data_user();
        $users = $this->user->get_user(); // Fetch all users from the database
        $data['tipe'] = $tipe;
        if ($data_user && isset($data_user->jam_masuk) && isset($data_user->jam_keluar)) {
            $jam_masuk_plus_two = (new DateTime($data_user->jam_masuk))->modify('+2 hours')->format('H:i:s');
            $jam_keluar_plus_two = (new DateTime($data_user->jam_keluar))->modify('+2 hours')->format('H:i:s');
        } else {
            echo 'Error: Missing "jam_masuk" or "jam_keluar" data.';
            return;
        }

        if ($users) {
            // If using result_array(), users will be an array, even if there's only one user
            $hasPicture = false;

            // Iterate over users (even if it's just one user) to check if 'userImage' is not null
            foreach ($users as $user) {
                if (!empty($user['userImage'])) {
                    $hasPicture = true; // If 'userImage' is not empty, set flag to true
                    break; // No need to continue looping if we find a picture
                }
            }

            if (!$hasPicture) {
                echo json_encode([
                    'status' => 'No Picture'
                ]);
            } else {
                // Load the user table view and capture its output
                if ($tipe == 'masuk') {
                    $this->db->select('*'); // Fetch only these columns
                    $this->db->from('tblattendance'); // Table name
                    $this->db->where('username', $this->session->userdata('username'));
                    $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
                    $this->db->where('tipe', 'Masuk'); // Check for records under jam_keluar_plus_two
                    $users = $this->db->get()->result_array();

                    $data['users'] = $users;
                } else if ($tipe == 'pulang') {
                    $this->db->select('*'); // Fetch only these columns
                    $this->db->from('tblattendance'); // Table name
                    $this->db->where('username', $this->session->userdata('username'));
                    $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
                    $this->db->where('tipe', 'Pulang'); // Check for records under jam_keluar_plus_two
                    $users = $this->db->get()->result_array();
                    // return $query->result_array(); // Return the result as an array

                    $data['users'] = $users;
                } else if ($tipe == 'absensi') {
                    $this->db->select('*');
                    $this->db->from('tblattendance');
                    $this->db->where('username', $this->session->userdata('username')); // Filter by username
                    $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
                    $this->db->where_in('tipe', ['Masuk', 'Telat']);
                    $users = $this->db->get()->result_array();
                    $data['users'] = $users;
                } else {
                    $data['users'] = $users;
                }
                $tableHTML = $this->load->view('userTable', $data, TRUE);
                echo json_encode([
                    'status' => 'success',
                    'tipe' => $tipe,
                    'data' => $users,
                    'html' => $tableHTML
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No records found'
            ]);
        }
    }
    public function user_photo()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
            $data['user'] = $this->m_app->user_get_detail($this->session->userdata('nip'));


            $this->load->view('Layouts/v_header', $data);
            $this->load->view('absensi/v_user_photo', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }
    public function add_photo()
    {
        $this->load->model('M_absen', 'user');
        $nip = $this->input->post('nip');
        $username = $this->input->post('username');

        $imageFileNames = [];
        $folderPath = FCPATH . "resources/labels/{$username}/";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Process images
        for ($i = 1; $i <= 10; $i++) {
            $capturedImage = $this->input->post("capturedImage{$i}");
            if ($capturedImage) {
                $base64Data = explode(',', $capturedImage)[1];
                $imageData = base64_decode($base64Data);
                $labelName = "{$i}.png";
                file_put_contents("{$folderPath}{$labelName}", $imageData);
                $imageFileNames[] = $labelName;
            }
        }

        $imagesJson = json_encode($imageFileNames);

        // Check for duplicate registration number

        // Save the student
        $edit_data = [
            'userImage' => $imagesJson,
        ];
        $this->db->where(
            'nip',
            $nip
        );
        $this->db->update('users', $edit_data);
        $this->session->set_flashdata('message', "Student: $username added successfully!");
        echo "Student: $username added successfully!";



        redirect('absensi/user_photo');
    }
    public function recordAttendance()
    {
        $this->load->model('M_absen', 'user');

        // Only allow POST requests
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Method Not Allowed', 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['capturedImage'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data or missing image.']);
            return;
        }

        $folderPath = FCPATH . "upload/attendance/";

        // Ensure the directory exists
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Process and save the image
        $base64Data = explode(',', $data['capturedImage'])[1];
        $imageData = base64_decode($base64Data);
        $filename = 'Attendance_' . uniqid() . '.png';

        if (file_put_contents($folderPath . $filename, $imageData)) {
            // Save attendance data to the database
            $attendance = [
                'username' => $data['username'],
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'attendanceStatus' => $data['attendanceStatus'],
                'lokasiAttendance' => $data['lokasiAttendance'],
                'tanggalAttendance' => $data['tanggalAttendance'],
                'image' => $filename
            ];

            // Call the method to insert attendance
            $response = $this->user->insertAttendance($attendance);

            // Return the response to the client
            // echo json_encode($response);


            echo json_encode(['status' => 'success', 'message' => 'Attendance recorded successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save image.']);
        }


        // echo json_encode($response);
    }
    public function delete_user_images()
    {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['username']) || empty($input['username'])) {
            echo json_encode(['status' => 'error', 'message' => 'Username is required.']);
            return;
        }

        $username = $input['username'];

        // Fetch user data
        $user = $this->db->get_where('users', ['username' => $username])->row();

        if (!$user || empty($user->userImage)) {
            echo json_encode(['status' => 'error', 'message' => 'No images found for this user.']);
            return;
        }

        $images = json_decode($user->userImage, true); // Decode JSON array
        $path = FCPATH . 'resources/labels/' . $username . '/';

        // Delete all images in the directory
        foreach ($images as $image) {
            $file = $path . $image;
            if (is_file($file)) {
                unlink($file); // Delete each image
            }
        }

        // Clear userImage field by setting it to NULL
        $this->db->where('username', $username);
        $this->db->set('userImage', 'NULL', false);
        $this->db->update('users');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'All images deleted and userImage set to NULL successfully.']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update userImage field to NULL.']);
            return;
        }

        echo json_encode(['status' => 'success', 'message' => 'All images deleted and userImage set to NULL successfully.']);
    }
    // public function tes()
    // {
    //     $a = $this->session->userdata('level');
    //     if (strpos($a, '401') !== false) {
    //         $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
    //         $data['user'] = $this->m_app->user_get_detail($this->session->userdata('nip'));


    //         $this->load->view('Layouts/v_header', $data);
    //         $this->load->view('userTable', $data);
    //         $this->load->view('Layouts/v_footer');
    //     } else {
    //         $this->session->set_flashdata('forbidden', 'Not Allowed!');
    //         redirect('home');
    //     }
    // }
    public function absen_list()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
            $data['user'] = $this->m_app->user_get_detail($this->session->userdata('nip'));

            $this->db->select('*'); // Fetch only these columns
            $this->db->from('tblattendance'); // Table name
            $this->db->where('attendanceStatus', 'Pending');
            $data['notif'] = $this->db->get()->num_rows();

            $this->load->view('Layouts/v_header', $data);
            $this->load->view('absensi/v_absensi_list', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function ajax_list()
    {
        $this->load->model('M_absen', 'user');

        $list = $this->user->get_datatables();
        $data = array();
        $crs = "";
        $no = $_POST['start'];
        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        foreach ($list as $cat) {
            $date = new DateTime($cat->date);

            $no++;
            $row = array();
            // $row[] = $no;
            $maxLength = 10; // Define the max length
            if (strlen($cat->nama) > $maxLength) {
                $truncated = substr($cat->nama, 0, strrpos(substr($cat->nama, 0, $maxLength), ' ')) . '';
            } else {
                $truncated = $cat->nama;
            }
            $row[] = $truncated;

            $monthIndex = (int) $date->format('n') - 1; // Get the month index (0-based)
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->attendanceStatus;

            $row[] = $cat->nip;
            $row[] = $cat->nama;
            $row[] = $cat->attendanceStatus;
            $row[] = $cat->lokasiAttendance;
            $row[] = $cat->tipe;
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->waktu;
            $row[] = "<img width='200px' src='" . base_url('upload/attendance/' . $cat->image) . "'>";
            // $row[] = $cat->halaman_page;



            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all(),
            "recordsFiltered" => $this->user->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function ajax_list2()
    {
        $this->load->model('M_absen', 'user');

        $list = $this->user->get_datatables2();
        $data = array();
        $crs = "";
        $no = $_POST['start'];
        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];
        foreach ($list as $cat) {
            $date = new DateTime($cat->date);

            $no++;
            $row = array();
            // $row[] = $no;
            $maxLength = 10; // Define the max length
            if (strlen($cat->nama) > $maxLength) {
                $truncated = substr($cat->nama, 0, strrpos(substr($cat->nama, 0, $maxLength), ' ')) . '';
            } else {
                $truncated = $cat->nama;
            }
            $row[] = $truncated;

            $monthIndex = (int) $date->format('n') - 1; // Get the month index (0-based)
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->attendanceStatus;

            $row[] = $cat->nip;
            $row[] = $cat->nama;
            $row[] = $cat->attendanceStatus;
            $row[] = $cat->lokasiAttendance;
            $row[] = $cat->tipe;
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->waktu;
            $row[] = "<img width='200px' src='" . base_url('upload/attendance/' . $cat->image) . "'>";
            // $row[] = $cat->halaman_page;



            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all2(),
            "recordsFiltered" => $this->user->count_filtered2(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function ajax_list3()
    {
        $this->load->model('M_absen', 'user');

        $list = $this->user->get_datatables3();
        $data = array();
        $crs = "";
        $no = $_POST['start'];

        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        foreach ($list as $cat) {
            $date = new DateTime($cat->date);

            $no++;
            $row = array();
            // $row[] = $no;
            $maxLength = 10; // Define the max length
            if (strlen($cat->nama) > $maxLength) {
                $truncated = substr($cat->nama, 0, strrpos(substr($cat->nama, 0, $maxLength), ' ')) . '';
            } else {
                $truncated = $cat->nama;
            }
            $row[] = $truncated;

            $monthIndex = (int) $date->format('n') - 1; // Get the month index (0-based)
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->attendanceStatus;

            $row[] = $cat->nip;
            $row[] = $cat->nama;
            $row[] = $cat->attendanceStatus;
            $row[] = $cat->lokasiAttendance;
            $row[] = $cat->tipe;
            $row[] = $date->format('d') . ' ' . $months[$monthIndex] . ' ' . $date->format('Y');
            $row[] = $cat->waktu;
            if (!isset($cat->image)) {
                $row[] = "<img width='200px' src='" . base_url('upload/attendance/' . $cat->image) . "'>";
            } else {
                $row[] = 'No Image';
            }
            // $row[] = $cat->halaman_page;

            if ($cat->attendanceStatus == 'Pending') {
                $row[] = '<center> <div class="list-icons d-inline-flex">
                <button title="Update User" onclick="onApprove(' . $cat->id . ')" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
  <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
</svg></button>
                                                <button title="Delete User" onclick="onNotApprove(' . $cat->id . ')" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
</svg></button>
            </div>
    </center>';
            } else {
                $row[] = 'Approved';
            }


            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all3(),
            "recordsFiltered" => $this->user->count_filtered3(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function approval($tipe, $id)
    {
        $this->load->model('M_absen', 'user');

        if ($tipe == "Approved") {
            $status = 'Present';
        } else {
            $status = 'Absent';
        }
        $date = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $this->user->update(
            array(
                'attendanceStatus'      => $status,
            ),
            array('id' => $id)
        );
        echo json_encode(array("status" => TRUE));
    }
}
