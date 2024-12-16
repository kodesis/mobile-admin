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
                $jam_keluar_plus_two = (new DateTime($data_user->jam_keluar))->modify('+2 hours')->format('H:i:s');
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

            $data['result1'] = $result1;
            $data['result2'] = $result2;

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
                    $this->db->where('TIME(waktu) <=', $jam_masuk_plus_two); // Check for records under jam_masuk_plus_two
                    $users = $this->db->get()->result_array();

                    $data['users'] = $users;
                    $data['tipe'] = $tipe;
                } else if ($tipe == 'pulang') {
                    $this->db->select('*'); // Fetch only these columns
                    $this->db->from('tblattendance'); // Table name
                    $this->db->where('username', $this->session->userdata('username'));
                    $this->db->where('DATE(date)', date('Y-m-d')); // Today's date
                    $this->db->where('TIME(waktu) >=', $jam_keluar_plus_two); // Check for records under jam_keluar_plus_two
                    $users = $this->db->get()->result_array();
                    // return $query->result_array(); // Return the result as an array

                    $data['users'] = $users;
                    $data['tipe'] = $tipe;
                } else {
                    $data['users'] = $users;
                    $data['tipe'] = $tipe;
                }
                $tableHTML = $this->load->view('userTable', $data, TRUE);
                echo json_encode([
                    'status' => 'success',
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
        for ($i = 1; $i <= 5; $i++) {
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

        $attendanceData = json_decode(file_get_contents("php://input"), true);

        if (!$attendanceData) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No attendance data received.'
            ]);
            return;
        }

        $response = $this->user->insertAttendance($attendanceData);

        echo json_encode($response);
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
}
