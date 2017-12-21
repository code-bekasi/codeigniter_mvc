<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function email_helper($to,$subject,$message,$email='test@email.com')
{
	$ci =& get_instance();
	$get_email = $ci->db->where('user', $email)->get('email_setting')->row();
	// $e = $ci->db->get_where('email_setting',array('id'=>1))->row_array();
	$config = array(
	    'protocol' 	=> $get_email->protocol,
	    'smtp_host' => $get_email->host,
	    'smtp_port' => $get_email->port,
	    'smtp_user' => $get_email->user,
	    'smtp_pass' => $get_email->pass,
	    'mailtype' 	=> $get_email->mailtype,
	    'charset' 	=> 'utf-8',
	    'newline' 	=> "\r\n",
	    'wordwrap' 	=> TRUE
	  );
	$ci->load->library('email');
	$ci->email->initialize($config);
	
	$ci->email->from($get_email->mailfrom, $get_email->fromnamer);
	$ci->email->to($to);
	// $ci->email->cc($get_email->adminemail);
	$ci->email->subject($subject);
	$ci->email->message($message);
	$ci->email->send();
}

function session_admin()
{
  $ci =& get_instance();

  $id 			= $ci->session->userdata('user_id');
  $get_row_db 	= $ci->db->where('id', $id)->get('users')->row(); // get row to check deleted status

  if ($ci->session->userdata('level') != 1 && $ci->session->userdata('level') != 3) {
  		//OR $get_row_db->deleted != 0
      $ci->session->set_flashdata('notif', 'Maaf anda tidak bisa mengakses halaman ini');
    redirect('admin','refresh');
  }
}

function generate_qr($qrcode)
{
	$ci =& get_instance();
	$ci->load->library('ciqrcode');

	header("Content-Type: image/png");
	$params['data'] = $qrcode;
	$name = $qrcode.uniqid().'.png';
	
	$params['level'] = 'H';
	$params['size'] = 10;
	$params['savename'] = FCPATH.'assets/images/qrcode/'.$name; //tempat simpan qr qode
	$ci->ciqrcode->generate($params);
	
	return $name;
}


/* End of file system_helper.php */
/* Location: ./application/helpers/system_helper.php */