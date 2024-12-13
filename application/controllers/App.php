<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Api_Whatsapp');
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
}
