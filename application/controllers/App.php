<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
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

    public function inbox()
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
            $this->load->view('memo/v_inbox', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function memo_view($id)
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $data['memo'] = $this->M_app->memo_get_detail($id);
            if (empty($data['memo'])) {
                $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
                redirect('app/inbox');
            } else {
                $this->load->view('Layouts/v_header', $data);
                $this->load->view('memo/v_memo', $data);
                $this->load->view('Layouts/v_footer');
            }
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function memo_pdf($id)
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            //script disini
            $data['memo'] = $this->M_app->memo_get_detail($id);

            // include APPPATH . 'libraries/dompdf/autoload.inc.php';

            $this->load->view('memo/v_memo_pdf', $data);
            // $this->load->view('cetak_form_cuti', $data);
            // $dompdf = new Dompdf\Dompdf();
            // $dompdf->loadHtml($this->load->view('memo_pdf', $data, true));
            // // (Optional) Setup the paper size and orientation
            // $dompdf->setPaper('a4', 'potrait');

            // // Render the HTML as PDF
            // $dompdf->render();

            // // Output the generated PDF to Browser
            // $dompdf->stream('memo_view.pdf', array("Attachment" => 0));
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function create_memo()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $data['sendto'] = $this->M_app->sendto($this->session->userdata('level_jabatan'), $this->session->userdata('bagian'));
            $this->load->view('Layouts/v_header', $data);
            $this->load->view('memo/v_create', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function outbox()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
            // Pagination
            $config['base_url'] = base_url('app/outbox');
            $config['total_rows'] = $this->M_app->count_memo_send($search);
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
            $config['first_link'] = '<<';
            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '>>';
            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_tag_close'] = '</li>';
            $config['prev_link'] = '<';
            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '>';
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
            $data['inbox'] = $this->M_app->memo_get_send($config['per_page'], $page, $search);
            $data['pagination'] = $this->pagination->create_links();

            $this->load->view('Layouts/v_header', $data);
            $this->load->view('memo/v_outbox', $data);
            $this->load->view('Layouts/v_footer');
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function create_memo_approve()
    {
        $a = $this->session->userdata('level');
        $nip = $this->session->userdata('nip');
        if (strpos($a, '401') !== false) {
            $memo_id = $this->uri->segment(3);
            $data['sendto'] = $this->M_app->sendto($this->session->userdata('level_jabatan'), $this->session->userdata('bagian'));
            // $sql = "select * FROM memo WHERE Id =$memo_id AND (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%')"; //sebelum penambahan nomor memo
            $sql = "SELECT a.*,b.nama_jabatan,b.nama,b.supervisi,c.kode_nama,b.level_jabatan 
                        FROM memo a
                        LEFT JOIN users b ON a.nip_dari = b.nip
                        LEFT JOIN bagian c ON b.bagian = c.kode
                        WHERE (a.id = '$memo_id' AND (a.nip_dari LIKE '%$nip%' OR a.nip_kpd LIKE '%$nip%' OR a.nip_cc LIKE '%$nip%'))";
            $result = $this->db->query($sql);
            $count = $result->num_rows;
            if ($count == 0) {
                $data['memo'] = $result->row();
                $this->load->view('Layouts/v_header', $data);
                $this->load->view('memo/v_create', $data);
                $this->load->view('Layouts/v_footer');
            }
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
        }
    }

    public function simpan_memo()
    {
        $a = $this->session->userdata('level');
        if (strpos($a, '401') !== false) {
            $judul = $this->input->post('subject_memo');
            $isi_memo = $this->input->post('isi_memo');
            if (!empty($this->input->post('attch_exist'))) {
                $attach_name = $this->input->post('attch_exist');
                $attach = $this->input->post('attch_exist_nm');
            } else {
                $attach_name = "";
                $attach = "";
            }

            $this->form_validation->set_rules('tujuan_memo[]', 'Tujuan memo', 'required');
            $this->form_validation->set_rules('subject_memo', 'subject memo', 'required|trim');
            $this->form_validation->set_rules('isi_memo', 'Isi memo', 'required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
            if ($this->form_validation->run() == FALSE) {
                $response = [
                    'success' => false,
                    'msg' => array_values($this->form_validation->error_array())[0]
                ];
            } else {
                date_default_timezone_set('Asia/Jakarta');
                $nip_kpd = '';
                $nip_cc = '';
                $i = 0;
                foreach ($this->input->post('tujuan_memo[]') as $value) {
                    $nip_kpd .= $value . ';';
                    $get_user[] = $this->db->get_where('users', ['nip' => $value])->result_array();
                    $phone[] = $get_user[$i][0]['phone'];
                    $i++;
                }

                if (!empty($this->input->post('cc_memo[]'))) {
                    $ii = 0;
                    foreach ($this->input->post('cc_memo[]') as $value1) {
                        $nip_cc .= $value1 . ';';
                        $get_user_cc[] = $this->db->get_where('users', ['nip' => $value1])->result_array();
                        $phone_cc[] = $get_user_cc[$ii][0]['phone'];
                        $ii++;
                    }
                }

                // simpan memo
                if ($this->session->userdata('level_jabatan') >= 2) {
                    $bagian = $this->session->userdata('kode_nama');
                    $sql = "SELECT MAX(nomor_memo) FROM memo WHERE bagian = '$bagian' AND YEAR(tanggal) = year(curdate());";
                    $res1 = $this->db->query($sql);

                    if ($res1->num_rows() > 0) {
                        $res2 = $res1->result_array();
                        $no_memo = $res2[0]['MAX(nomor_memo)'] + 1;
                    } else {
                        $no_memo = 1;
                    }
                } else {
                    $no_memo = '';
                }


                $data_update1     = array(
                    'nomor_memo'    => $no_memo,
                    'nip_kpd'        => $nip_kpd,
                    'nip_cc'        => $nip_cc,
                    'judul'            => $judul,
                    'isi_memo'        => $isi_memo,
                    'nip_dari'        => $this->session->userdata('nip'),
                    'tanggal'        => date('Y-m-d H:i:s'),
                    'read'            => 0,
                    'persetujuan'    => 0,
                    'bagian'        => $this->session->userdata('kode_nama'),
                    'attach'        => $attach,
                    'attach_name'    => $attach_name
                );


                $this->db->insert('memo', $data_update1);
                $last_id = $this->db->insert_id();
                $xx = $nip_kpd . $last_id;
                // $this->session->set_userdata('msg_memo', $xx);

                //simpan upload
                // Count total files
                $countfiles = count(array_filter($_FILES['file']['name']));

                // Looping all files
                for ($i = 0; $i < $countfiles; $i++) {
                    $filename_ = $_FILES['file']['name'][$i];
                    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                    $s1 = substr(str_shuffle($permitted_chars), 0, 10);
                    $array = explode('.', $_FILES['file']['name'][$i]);
                    $extension = end($array);
                    $filename = $s1 . '.' . $extension;

                    $sql = "UPDATE memo SET attach = CONCAT_WS('$filename',attach, ';') WHERE Id=$last_id";
                    $query = $this->db->query($sql);
                    $sql1 = "UPDATE memo SET attach_name = CONCAT_WS('$filename_',attach_name, ';') WHERE Id=$last_id";
                    $query = $this->db->query($sql1);

                    // Upload file
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], '../moc.mlejitoffice.id/upload/att_memo/' . $filename);
                }

                //Send notif wa
                $nama_session = $this->session->userdata('nama');
                $subject_memo = $this->input->post('subject_memo');
                $msg = "There's a new Memo\nMOC From : *$nama_session*\nSubject :  *$subject_memo*";

                if (!empty($this->input->post('cc_memo[]'))) {
                    $phone_user = array_merge($phone, $phone_cc);
                } else {
                    $phone_user = $phone;
                }

                $send_wa = implode(',', $phone_user);
                $this->api_whatsapp->wa_notif($msg, $send_wa);

                $response = [
                    'success' => true,
                    'msg' => 'Create & send success to ID ' . $xx
                ];
            }
            echo json_encode($response);
        } else {
            $this->session->set_flashdata('forbidden', 'Not Allowed!');
            redirect('home');
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
        if ($this->session->userdata('isLogin') == FALSE) {
            redirect('home');
        } else {
            //inbox notif
            $nip = $this->session->userdata('nip');
            $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
            $sql2 = "SELECT * FROM asset_ruang";
            $sql3 = "SELECT * FROM asset_lokasi";
            $query = $this->db->query($sql);
            $query2 = $this->db->query($sql2);
            $query3 = $this->db->query($sql3);
            $res2 = $query->result_array();
            $asset_ruang = $query2->result();
            $asset_lokasi = $query3->result();
            $result = $res2[0]['COUNT(Id)'];
            $data['count_inbox'] = $result;
            $data['asset_ruang'] = $asset_ruang;
            $data['asset_lokasi'] = $asset_lokasi;

            // Tello
            $sql4 = "SELECT COUNT(Id) FROM task WHERE (`member` LIKE '%$nip%' or `pic` like '%$nip%') and activity='1'";
            $query4 = $this->db->query($sql4);
            $res4 = $query4->result_array();
            $result4 = $res4[0]['COUNT(Id)'];
            $data['count_inbox2'] = $result4;

            $this->load->model(
                'M_absen',
                'user'
            );
            $data['cek_user'] = $this->user->cek_user();
            $data['lokasi_absensi'] = $this->user->get_location();

            $data['data_user'] = $this->user->get_user();
            $this->load->view('absen_wfh_view', $data);
        }
    }
    public function fetch_user()
    {
        $this->load->model('M_absen', 'user');
        $users = $this->user->get_user(); // Fetch all users from the database

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
                $data['users'] = $users;
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
        if ($this->session->userdata('isLogin') == FALSE) {
            redirect('home');
        } else {
            $a = $this->session->userdata('level');
            if (strpos($a, '401') !== false) {
                $data['user'] = $this->m_app->user_get_detail($this->sessuin->userdata(''));
                if (empty($data['user'])) {
                    echo "<script>alert('Unauthorize Privilage!');window.history.back();</script>";
                } else {
                    //inbox notif
                    $nip = $this->session->userdata('nip');
                    $sql = "SELECT COUNT(Id) FROM memo WHERE (nip_kpd LIKE '%$nip%' OR nip_cc LIKE '%$nip%') AND (`read` NOT LIKE '%$nip%');";
                    $query = $this->db->query($sql);
                    $res2 = $query->result_array();
                    $result = $res2[0]['COUNT(Id)'];
                    $data['count_inbox'] = $result;

                    $sql3 = "SELECT COUNT(id) FROM task WHERE (`member` LIKE '%$nip%' or `pic` like '%$nip%') and activity='1'";
                    $query3 = $this->db->query($sql3);
                    $res3 = $query3->result_array();
                    $result3 = $res3[0]['COUNT(id)'];
                    $data['count_inbox2'] = $result3;

                    $this->load->view('user_view_photo', $data);
                }
            }
        }
    }
    public function add_photo()
    {
        $this->load->model('M_absen', 'user');
        $id_edit = $this->input->post('id');
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
            'id',
            $id_edit
        );
        $this->db->update('users', $edit_data);
        $this->session->set_flashdata('message', "Student: $username added successfully!");
        echo "Student: $username added successfully!";



        redirect('app/user');
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
