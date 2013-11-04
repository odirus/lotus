<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Auth_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function is_login() {
        $this->load->library('auth_lib');
        $this->auth_lib->user_is_login();
        if (!isset($_SESSION['object_user_id'])) {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'User did not login',
                    'data'   => NULL
                )
            );
        }
    }
        

    /**
     * 登录系统
     *
     * @param string   username
     * @param string   password
     * @param string   remember
     * 
     * @return restful
     */
    public function do_login_post() {
        //@toto remember ? on : off
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        $remember = $this->input->post('remember', TRUE);//默认一周时间过期
        $this->load->library('auth_lib');
        $res = $this->auth_lib->do_login($username, $password, $remember);
        if($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Login success',
                    'data' => NULL
                )
            );
        } else {
            $msg = 'Username or password wrong';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => $msg,
                    'data' => NULL
                ) 
            );
        }
        
    }

    /**
     * 卖家是否已经登录
     *
     * @param void
     */
    public function user_is_login_get() {
        $this->load->library('auth_lib');
        if ($this->auth_lib->user_is_login()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'User has login',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'User has not login',
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * 当前登录用户信息
     *
     * @param void
     */
    public function user_info_get() {
        $this->is_login();
        $this->load->library('auth_lib');
        if ($user_info = $this->auth_lib->user_info()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get user info success',
                    'data'   => $user_info
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Get user info failed',
                    'data'   => NULL
                )
            );
        }
    }
    
    /**
     * 登出系统
     *
     * @param void
     */
    public function do_logout_post() {
        $this->is_login();
        $this->load->library('auth_lib');
        if($this->auth_lib->do_logout()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Logout success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Logout failed',
                    'data' => NULL
                ) 
            );
        }
    }

    /**
     * 注册码是否可用，是否存在，是否被使用，格式是否正确
     *
     * @param string   register_code
     * 
     * @return restful
     */
    public function register_code_is_available_post() {
        $register_code = $this->input->post('register_code', TRUE);

        $this->load->library('auth_lib');
        $res = $this->auth_lib->verify_register_code($register_code);
        if($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Register code is available',
                    'data' => NULL
                )
            );
        } else {
            $msg = 'Register code is not available';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => $msg,
                    'data' => NULL
                )
            );
        }
    }

    /**
     * 验证用户名是否可用，是否已经被注册，格式是否正确
     *
     * @param string   username
     * 
     * @return restful
     */
    public function username_is_available_post() {
        $username = $this->input->post('username', TRUE);
        $this->load->library('auth_lib');
        $res = $this->auth_lib->verify_username($username);
        if($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Username is available',
                    'data' => NULL
                )
            );
        } else {
            $msg = 'Username is not available';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => $msg,
                    'data' => NULL
                )
            );
        }
    }

    /**
     * 注册新用户接口
     *
     * @param string   username
     * @param string   password
     * @param string   role
     * @param string   regisger_code
     * 
     * @return restful
     */
    public function do_register_post() {
        $username      = $this->input->post('username', TRUE);
        $password      = $this->input->post('password', TRUE);
        $role          = $this->input->post('user_role', TRUE);
        $register_code = $this->input->post('register_code', TRUE);
 
        $this->load->library('auth_lib');
        $res = $this->auth_lib->do_register(array(
            'username'      => $username,
            'password'      => $password,
            'role'          => $role,
            'register_code' => $register_code
        ));
        if($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Register new user success',
                    'data' => NULL
                )
            );
        } else {
            $msg = 'Register new user failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => $msg,
                    'data' => NULL
                )
            );
        }
    }

    /**
     * 修改用户密码
     *
     * @param string   old_password
     * @param string   new_password
     *
     * @return restful
     */
    public function change_password_post() {
        //验证用户是否登录
        $this->is_login();
        $old_password = $this->input->post('old_password', TRUE);
        $new_password = $this->input->post('new_password', TRUE);
        $this->load->library('auth_lib');
        $res = $this->auth_lib->change_password($old_password, $new_password);
        if ($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Change password success',
                    'data'   => NULL
                )
            );
        } else {
            $msg = 'Change password failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
        }
    }
}
