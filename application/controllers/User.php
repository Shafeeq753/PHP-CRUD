<?php
class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username =  $this->input->post('username');
            //    $email =  $this->input->post('email');
            $mobile =  $this->input->post('mobile');
            $address =  $this->input->post('address');

            $data = array(
                'username' => $username,
                //   'email' => $email,
                'mobile' => $mobile,
                'address' => $address
            );

            $status = $this->user_model->insertUser($data);
            if ($status == true) {
                $this->session->set_flashdata('success', 'successfully Added');
                redirect(base_url('user/add'));
            } else {
                $this->session->set_flashdata('error', 'Error');
                $this->load->view('user/add_user');
            }
        } else {
            $this->load->view('user/add_user');
        }
    }
    function index()
    {
        $data['users'] = $this->user_model->getUsers();
        $this->load->view('user/index', $data);
    }

    function edit($id)
    {
        $data['user'] = $this->user_model->getUser($id);
        $data['id'] = $id;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username =  $this->input->post('username');
            $mail =  $this->input->post('mail');
            $mobile =  $this->input->post('mobile');
            $address =  $this->input->post('address');

            $data = array(
                'username' => $username,
                'mail' => $mail,
                'mobile' => $mobile,
                'address' => $address
            );

            $status = $this->user_model->updateUser($id, $data);
            if ($status == true) {
                $this->session->set_flashdata('success', 'successfully Added');
                redirect(base_url('user/edit/' . $id));
            } else {
                $this->session->set_flashdata('error', 'Error');
                $this->load->view('user/edit_user');
            }
        }

        $this->load->view('user/edit_user', $data);
    }

    function delete($id)
    {
        $status = $this->user_model->deleteUser($id);
        if ($status == true) {
            $this->session->set_flashdata('deleted', 'successfully Deleted');
            redirect(base_url('user/index/'));
        } else {
            $this->session->set_flashdata('error', 'Error');
            redirect(base_url('user/index/'));
        }
    }
    public function login()
    {
        $this->load->helper('url');
        $this->load->view('login_form');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function login_user()
{
    $this->load->library('session');

    if ($this->session->has_userdata('id')) {
        redirect(base_url('user/index'));

    }

    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $this->load->database();
    $this->load->model('user_model');
    $user = $this->user_model->getUserr($username);

    if ($user) {
        if ($user->password == $password) {
            echo "Login Successfully";
            $this->session->set_userdata('id', $user->id);
        } else {
            echo "Incorrect Password";
        }
    } else {
        echo "No account exists with this username";
    }
}



    public function home()
    {
        $this->load->helper('url');
        $this->load->view('home');
    }

    public function logout()
    {
        $this->load->library('session');
        $this->session->unset_userdata('id');
        redirect('user/login');
    }
}
