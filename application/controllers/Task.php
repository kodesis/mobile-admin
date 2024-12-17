<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Api_Whatsapp');
    $this->load->model('M_task');
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

  public function task()
  {
    $a = $this->session->userdata('level');
    if (strpos($a, '601') !== false) {
      $search = htmlspecialchars($this->input->get('search') ?? '', ENT_QUOTES, 'UTF-8');
      // Pagination
      $config['base_url'] = base_url('task/task');
      $config['total_rows'] = $this->M_task->task_count($search);
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
      $data['task'] = $this->M_task->task_get($config['per_page'], $page, $search);
      $data['pagination'] = $this->pagination->create_links();

      $this->load->view('Layouts/v_header', $data);
      $this->load->view('task/v_task', $data);
      $this->load->view('Layouts/v_footer');
    } else {
      $this->session->set_flashdata('forbidden', 'Not Allowed!');
      redirect('home');
    }
  }

  public function task_view($id)
  {
    $a = $this->session->userdata('level');
    $data['task'] = $this->db->get_where('task', ['id' => $id])->row();
    if (strpos($a, '601') !== false) {
      if ($data['task']) {
        $cek_detail = $this->db->get_where('task_detail', ['id_task' => $id])->num_rows();
        if ($cek_detail) {
          $this->db->where('b.id_task', $id);
          $this->db->from('users as a');
          $this->db->join('task_detail as b', 'a.nip = b.responsible');
          $data['task_detail'] = $this->db->get()->result();

          $this->db->select('*,c.activity as status_task,b.activity,b.comment as comment,b.date_created');
          $this->db->where('b.id_detail', $this->uri->segment(4));
          $this->db->from('users as a');
          $this->db->join('task_detail as b', 'a.nip=b.responsible');
          $this->db->join('task as c', 'b.id_task=c.id');
          $data['task_comment'] = $this->db->get()->row_array();

          $this->db->where('b.id_task_detail', $this->uri->segment(4));
          $this->db->from('users as a');
          $this->db->join('task_detail_comment as b', 'a.nip=b.member');
          $this->db->order_by('date_created', 'DESC');
          $data['task_comment_member'] = $this->db->get()->result();

          // Update read card
          if ($this->uri->segment(4)) {
            $nip = $this->session->userdata('nip');
            $id_detail = $this->uri->segment(4);
            $sqlx = "SELECT task_detail.read FROM task_detail WHERE id_detail ='$id_detail'";
            $queryxx = $this->db->query($sqlx);
            $resultx = $queryxx->row();
            $kalimat = $resultx->read;
            if (preg_match("/$nip/i", $kalimat)) {
            } else {
              $kalimat1 = $kalimat . ' ' . $nip;
              $data_update11  = array(
                'read'  => $kalimat1
              );
              $this->db->where('id_detail', $id_detail);
              $this->db->update('task_detail', $data_update11);
            }
          }

          // Update read task
          if ($this->uri->segment(3)) {
            $nip = $this->session->userdata('nip');
            $id_task = $this->uri->segment(3);
            $sql = "SELECT task.read FROM task WHERE id ='$id_task'";
            $result = $this->db->query($sql)->row();
            $kalimat = $result->read;
            if (preg_match("/$nip/i", $kalimat)) {
            } else {
              $kalimat1 = $kalimat . ' ' . $nip;
              $update  = array(
                'read'  => $kalimat1
              );
              $this->db->where('id', $id_task);
              $this->db->update('task', $update);
            }
          }

          $this->load->view('Layouts/v_header', $data);
          $this->load->view('task/v_detail', $data);
          $this->load->view('Layouts/v_footer');
        } else {
          if ($data['task']->pic !== $this->session->userdata('nip')) {
            $this->session->set_flashdata('forbidden', "PIC has'nt created a task card yet!");
            redirect('task/task');
          } else {
            redirect('task/detail_task/' . $id);
          }
        }
      } else {
        $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
        redirect('task/task');
      }
    } else {
      $this->session->set_flashdata('forbidden', 'Not Allowed!');
      redirect('home');
    }
  }

  public function create_task()
  {
    $a = $this->session->userdata('level');
    $data['task_edit'] = $this->db->get_where('task', ['id' => $this->uri->segment(3)])->row_array();

    if (strpos($a, '601') !== false || $data['task_edit']['pic'] == $this->session->userdata('nip')) {
      $data['sendto'] = $this->M_task->sendto($this->session->userdata('level_jabatan'), $this->session->userdata('bagian'));

      $this->load->view('Layouts/v_header');
      $this->load->view('task/v_create', $data);
      $this->load->view('Layouts/v_footer');
    } else {
      $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
      redirect('task/task');
    }
  }

  public function save_task()
  {
    $a = $this->session->userdata('level');
    if (strpos($a, '601') !== false) {
      $project_name = htmlspecialchars($this->input->post('project_name'), ENT_QUOTES, 'UTF-8');
      $activity = $this->input->post('activity');
      $member_name = $this->input->post('member_task[]');
      $comment = htmlspecialchars($this->input->post('comment'), ENT_QUOTES, 'UTF-8');

      $this->form_validation->set_rules('project_name', 'Project or task name', 'required|trim');
      $this->form_validation->set_rules('member_task[]', 'Member name', 'required');
      $this->form_validation->set_rules('activity', 'Activity', 'required|in_list[1,2,3]');
      $this->form_validation->set_error_delimiters('<span class="error text-danger">', '</span>');

      if ($this->form_validation->run() == FALSE) {
        $this->create_task();
      } else {
        date_default_timezone_set('Asia/Jakarta');

        // Simpan Task / Project
        $member_task = '';
        $i = 0;
        if (!empty($member_name)) {
          foreach ($member_name as $value) {
            $member_task .= $value . ';';
            $get_member[] = $this->db->get_where('users', ['nip' => $value])->row_array();
            $phone_member[] = $get_member[$i]['phone'];
            $i++;
          }
        }
        $insert = [
          'name' => $project_name,
          'member' => $member_task,
          'activity' => $activity,
          'comment' => $comment,
          'pic' => $this->session->userdata('nip')
        ];
        $this->db->insert('task', $insert);
        $last_id = $this->db->insert_id();

        // Notif Whatsapp
        $nama_session = $this->session->userdata('nama');
        $msg = "There's a new task\nTask Name : *$project_name*\n\nCreated By : *$nama_session*";
        $send_wa = implode(',', $phone_member);
        $this->api_whatsapp->wa_notif($msg, $send_wa);

        // Alert berhasil insert
        $this->session->set_flashdata('success', 'Task successfully created!');
        redirect('task/detail_task/' . $last_id);
      }
    } else {
      $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
      redirect('task/task');
    }
  }

  public function detail_task($id)
  {
    $a = $this->session->userdata('level');
    if (strpos($a, '601') !== false) {
      $get_task = $this->db->get_where('task', ['id' => $id])->row_array();
      if ($get_task['pic'] !== $this->session->userdata('nip')) {
        $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
        redirect('task/task');
      }

      $data['task'] = $get_task['member'];
      $nip_task = explode(';', $get_task['member']);
      $this->db->where_in('nip', $nip_task);
      $data['ss'] = $this->db->get('users')->result();

      $this->load->view('Layouts/v_header');
      $this->load->view('task/v_create_card', $data);
      $this->load->view('Layouts/v_footer');
    } else {
      $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
      redirect('task/task');
    }
  }

  public function save_detail_task($id)
  {
    $nip = $this->session->userdata('nip');
    $user = $this->db->get_where('users', ['nip' => $nip])->row();

    $id_task = $this->input->post('id_task');
    $id_card = $this->input->post('id_card');
    $card_name = htmlspecialchars($this->input->post('card_name'), ENT_QUOTES, 'UTF-8');
    $responsible = $this->input->post('responsible');
    $description = htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'UTF-8');
    $start = $this->input->post('start_date');
    $end = $this->input->post('end_date');
    $activity = $this->input->post('activity');

    $this->form_validation->set_rules('card_name', 'card name', 'required|trim');
    $this->form_validation->set_rules('responsible', 'responsible', 'required');
    $this->form_validation->set_rules('start_date', 'start date', 'required');
    $this->form_validation->set_rules('end_date', 'due date', 'required');
    $this->form_validation->set_rules('activity', 'activity', 'required|in_list[1,2,3]');
    $this->form_validation->set_error_delimiters('<span class="error text-danger">', '</span>');

    $target_file = '../moc.mlejitoffice.id/upload/task_comment/';

    if ($this->form_validation->run() == FALSE) {
      $this->detail_task($this->uri->segment(3));
    } else {
      if ($_FILES['attachment']['name'][0] != "") {
        $nama_file = array();
        for ($xx = 0; $xx < count($_FILES['attachment']['name']); $xx++) {
          $newfilename = str_replace(' ', '', time() . '_' . $_FILES['attachment']['name'][$xx]);
          move_uploaded_file($_FILES['attachment']['tmp_name'][$xx], $target_file . $newfilename);
          $nama_file[] = str_replace(' ', '', time() . '_' . $_FILES['attachment']['name'][$xx]);
        }
      } else {
        $nama_file = null;
      }

      $file_i = implode(';', $nama_file);

      $insert = [
        'id_task' => $id,
        'task_name' => $card_name,
        'responsible' => $responsible,
        'description' => $description,
        'start_date' => $start,
        'due_date' => $end,
        'activity' => $activity,
        'attachment' => $file_i,
      ];

      $this->db->insert('task_detail', $insert);

      $task = $this->db->get_where('task', ['id' => $id])->row();
      $nip_member = rtrim($task->member, ';');
      $arr_member = explode(';', $nip_member);

      foreach ($arr_member as $value) {
        $user = $this->db->get_where('users', ['nip' => $value])->row();
        $msg = "There's a new card\nTask Name:*$task->name*\nCard Name : *$card_name*\n\nCreated By :  *$user->nama*";
        $this->api_whatsapp->wa_notif($msg, $user->phone);
      }

      $this->session->set_flashdata('success', 'Card successfully created!');
      redirect('task/task_view/' . $id);
    }
  }

  public function update_detail_task()
  {
    $nip = $this->session->userdata('nip');
    $user = $this->db->get_where('users', ['nip' => $nip])->row();

    $id_task = $this->input->post('id_task');
    $id_card = $this->input->post('id_card');
    $card_name = htmlspecialchars($this->input->post('card_name'), ENT_QUOTES, 'UTF-8');
    $responsible = $this->input->post('responsible');
    $description = htmlspecialchars($this->input->post('description'), ENT_QUOTES, 'UTF-8');
    $start = $this->input->post('start_date');
    $end = $this->input->post('end_date');
    $activity = $this->input->post('activity');

    $this->form_validation->set_rules('card_name', 'card name', 'required|trim');
    $this->form_validation->set_rules('responsible', 'responsible', 'required');
    $this->form_validation->set_rules('start_date', 'start date', 'required');
    $this->form_validation->set_rules('end_date', 'due date', 'required');
    $this->form_validation->set_rules('activity', 'activity', 'required|in_list[1,2,3]');
    $this->form_validation->set_error_delimiters('<span class="error text-danger">', '</span>');

    $target_file = '../moc.mlejitoffice.id/upload/task_comment/';

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('forbidden', array_values($this->form_validation->error_array())[0]);
      redirect('task/card_edit/' . $id_task . '/' . $id_card);
    } else {
      if ($_FILES['attachment']['name'][0] != "") {
        $nama_file = array();
        for ($xx = 0; $xx < count($_FILES['attachment']['name']); $xx++) {
          $newfilename = str_replace(' ', '', time() . '_' . $_FILES['attachment']['name'][$xx]);
          move_uploaded_file($_FILES['attachment']['tmp_name'][$xx], $target_file . $newfilename);
          $nama_file[] = str_replace(' ', '', time() . '_' . $_FILES['attachment']['name'][$xx]);
        }
      } else {
        $nama_file = null;
      }

      $file_i = implode(';', $nama_file);

      $update = [
        'id_task' => $id_task,
        'task_name' => $card_name,
        'responsible' => $responsible,
        'description' => $description,
        'start_date' => $start,
        'due_date' => $end,
        'activity' => $activity,
        'attachment' => $file_i,
      ];
      $this->db->where('id_detail', $id_card);
      $this->db->update('task_detail', $update);

      // update task 
      $this->db->where('id', $id_task);
      $this->db->update('task', ['read' => 0]);


      $this->session->set_flashdata('success', 'Card successfully updated!');
      redirect('task/task_view/' . $id_task);
    }
  }

  public function activity_comment()
  {
    if (isset($_FILES['file'])) {
      // Initialize the arrays
      $arr_att = [];
      $arr_name = [];
      $files = $_FILES;
      $cpt = count($_FILES['file']['name']);

      for ($i = 0; $i < $cpt; $i++) {

        $name = time() . $files['file']['name'][$i];
        $name_xx = $files['file']['name'][$i];

        $parts = explode(".", $name_xx); // Split the string into parts
        $ext = end($parts); // Get the last part of the array
        $att_name = time() . '.' . $ext;

        $_FILES['file']['name'] = $name;
        $_FILES['file']['type'] = $files['file']['type'][$i];
        $_FILES['file']['tmp_name'] = $files['file']['tmp_name'][$i];
        $_FILES['file']['error'] = $files['file']['error'][$i];
        $_FILES['file']['size'] = $files['file']['size'][$i];
        $this->load->library('upload');
        $this->upload->initialize($this->set_upload_options('../moc.mlejitoffice.id/upload/task_comment'));
        if (!($this->upload->do_upload('file')) || $files['file']['error'][$i] != 0) {
          // print_r($this->upload->display_errors());
        } else {
          $arr_att[] = $att_name;
          $arr_name[] = $name;
        }
      }
      // var_dump(array($arr_att));
      // var_dump($this->upload->data()['file_size']);
      if ($this->upload->data()['file_size'] < 10000) {
        $id_detail = $this->input->post('id_detail');
        $get_task_detail = $this->db->query("SELECT * FROM task as a left join task_detail as b on(a.id=b.id_task) where b.id_detail='$id_detail'")->row_array();
        $phone_x = explode(';', $get_task_detail['member']);
        foreach ($phone_x as $k) { //member card kirim ke wa
          $get_user = $this->db->get_where('users', ['nip' => $k])->row_array();
          $task_name = $get_task_detail['task_name'];
          // $nama_member = $get_user["nama"];
          $comment = $this->input->post("comment");
          $nama_session = $this->session->userdata('nama');
          $msg = "There's a new comment\nCard Name : *$task_name*\nComment : *$comment*\n\nComment from :  *$nama_session*";
          $this->api_whatsapp->wa_notif($msg, $get_user['phone']);
        }

        $data = [
          "id_task_detail" => $this->input->post('id_detail'),
          "comment_member" => $this->input->post('comment'),
          "attachment" => implode(';', $arr_att),
          "attachment_name" => implode(';', str_replace(' ', '_', $arr_name)),
          "member" => $this->session->userdata('nip')

        ];

        $this->db->insert('task_detail_comment', $data);

        // update task detail
        $this->db->set('read', '0');
        $this->db->where('id_detail', $id_detail);
        $this->db->update('task_detail');

        //Update Task
        $task_detail = $this->db->get_where('task_detail', ['id_detail' => $id_detail])->row();
        $task = $this->db->get_where('task', ['id' => $task_detail->id_task])->row();

        $this->db->set('read', '0');
        $this->db->where('id', $task->id);
        $this->db->update('task');
        redirect('task/task_view/' . $this->input->post('id_task') . '/' . $this->input->post('id_detail'));
      } else {
        echo "<script>alert('File Tidak boleh lebih dari 10Mb !');window.history.back();</script>";
      }
    } else {
      redirect('task/task_view/' . $this->input->post('id_task') . '/' . $this->input->post('id_detail'));
    }
  }

  public function set_upload_options($file_path)
  {
    // upload an image options
    $config = array();
    $config['upload_path'] = $file_path;
    $config['allowed_types'] = '*';
    $config['max_size'] = 10000;
    // $config ['encrypt_name'] = true;
    return $config;
  }

  public function update_task($id)
  {
    $a = $this->session->userdata('level');
    if (strpos($a, '601') !== false) {
      $project_name = htmlspecialchars($this->input->post('project_name'), ENT_QUOTES, 'UTF-8');
      $activity = $this->input->post('activity');
      $member_name = $this->input->post('member_task[]');
      $comment = htmlspecialchars($this->input->post('comment'), ENT_QUOTES, 'UTF-8');

      $this->form_validation->set_rules('project_name', 'Project or task name', 'required|trim');
      $this->form_validation->set_rules('member_task[]', 'Member name', 'required');
      $this->form_validation->set_rules('activity', 'Activity', 'required|in_list[1,2,3]');
      $this->form_validation->set_error_delimiters('<span class="error text-danger">', '</span>');

      if ($this->form_validation->run() == FALSE) {
        $this->create_task($id);
      } else {
        date_default_timezone_set('Asia/Jakarta');

        // Simpan Task / Project
        $member_task = '';
        $i = 0;
        if (!empty($member_name)) {
          foreach ($member_name as $value) {
            $member_task .= $value . ';';
            $get_member[] = $this->db->get_where('users', ['nip' => $value])->row_array();
            $phone_member[] = $get_member[$i]['phone'];
            $i++;
          }
        }
        $update = [
          'name' => $project_name,
          'member' => $member_task,
          'activity' => $activity,
          'comment' => $comment,
          'pic' => $this->session->userdata('nip')
        ];
        $this->db->where('id', $id);
        $this->db->update('task', $update);
        $last_id = $this->db->insert_id();

        // Alert berhasil insert
        $this->session->set_flashdata('success', 'Task successfully updated!');
        redirect('task/task');
      }
    } else {
      $this->session->set_flashdata('forbidden', 'Unauthorize Privilage!');
      redirect('task/task');
    }
  }

  public function card_edit()
  {
    $task_id = $this->uri->segment(3);
    $card_id = $this->uri->segment(4);
    $get_task = $this->db->get_where('task', ['id' => $task_id])->row_array();
    $data['task'] = $get_task['member'];
    $nip_task = explode(';', $get_task['member']);
    $this->db->where_in('nip', $nip_task);
    $data['ss'] = $this->db->get('users')->result();
    $data['row_edit'] = $this->db->get_where('task_detail', ['id_detail' => $card_id])->row_array();

    $this->load->view('Layouts/v_header');
    $this->load->view('task/v_create_card', $data);
    $this->load->view('Layouts/v_footer');
  }

  public function close_task()
  {
    $id = $this->uri->segment(3);
    // update task detail
    $this->db->where('id_task', $id);
    $this->db->set('activity', '3');
    $this->db->update('task_detail');

    // update task master
    $this->db->where('id', $id);
    $this->db->set('activity', '3');
    $this->db->update('task');
    $this->session->set_flashdata('success', 'Task successfully closed!');
    redirect('task/task_view/' . $id);
  }
}
