<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Apply_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('apply_model', 'apply_m');
    }

    /**
     * 申请店铺
     *
     * @param string shopkeeper_name   店主姓名
     * @param string shopkeeper_tel    店主联系方式
     * @param string shop_name         店铺名称
     * @param string shop_address      店铺地址
     *
     */
    public function do_apply_post() {
        $apply_info = array(
            'shopkeeper_name' => $this->input->post('shopkeeper_name', TRUE),
            'shopkeeper_tel'  => $this->input->post('shopkeeper_tel', TRUE),
            'shop_name'       => $this->input->post('shop_name', TRUE),
            'shop_address'    => $this->input->post('shop_address', TRUE),
        );
        
        $res = $this->apply_m->base_query(array('shopkeeper_tel' => $apply_info['shopkeeper_tel']), 'id');
        $res_array = $res->result_array();
        if (count($res_array) > 0) {
            $this->response('该联系电话已经被注册' ,400);
        }

        $this->load->library('apply_lib');
        if($this->apply_lib->do_apply($apply_info)) {
            $this->response("ok", 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Apply success',
                    'data'   => NULL
                )
            );
            */
        } else {
            $this->response("fail", 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Apply failed',
                    'data'   => NULL
                )
            );
            */
        }
    }

    public function apply_result_search_get() {
        $shopkeeper_tel = $this->input->get('shopkeeper_tel');

        if (empty($shopkeeper_tel)) {
            $this->response('TEL is illegal', 400);
        }
        
        $result_object = $this->apply_m->base_query(array('shopkeeper_tel' => $shopkeeper_tel), 'status, decision, register_code, code_available, failed_message');
        $result_array = $result_object->result_array();
        if (count($result_array) > 0) {
            //查找到该号码已经被注册，正式版时如果返回多条信息，则抛出异常
            $info = $result_array[0];
            if ($info['status'] == 'verifing') {
                $this->response('店铺申请正在被审核', 200);
            } elseif ($info['status'] == 'verified' && $info['decision'] == 'passed') {
                $this->response('店铺申请已经通过，注册码已经发送至您的手机', 200);
            } elseif ($info['status'] == 'verified' && $info['decision'] == 'failed') {
                $this->response('店铺申请提交失败：' . $info['failed_message'], 200);
            } elseif ($info['status'] == 'verified' && $info['decision'] == 'passed' && $info['code_available'] == 'n') {
                $this->response('店铺申请成功，注册码已经被使用', 200);
            } else {
                //抛出异常
                $this->response('数据错误，请联系管理员', 200);
            }
        } else {
            $this->response('联系电话未被注册', 200);
        }

        echo '<pre>';
        var_dump($info);die;
    }

    /**
     * 审核中的店铺
     *
     * @param void
     */
    public function apply_verifying_get() {
        //@Todo check privilege
        $this->load->library('apply_lib');
        $this->response($this->apply_lib->apply_verifying(), 200);
        /**
        $this->response(
            array(
                'result' => 'ok',
                'msg'    => 'Get verifying data success',
                'data'   => $this->apply_lib->apply_verifying()
            )
        );
        */
    }

    /**
     * 待审核店铺店铺申请详细信息
     *
     * @param     apply_id      申请编号
     */
    public function apply_verifying_detail_get() {
        //@todo check privilege
        $apply_id = (int) ($this->input->get('apply_id', TRUE));
        $this->load->library('apply_lib');
        $res = $this->apply_lib->apply_verifying_detail($apply_id);
        if($res['res']) {
            $this->response($res['data'], 200);
            /**
            $this->response(
                array(
                    'resutl' => 'ok',
                    'msg'    => 'Get verifying detail data success',
                    'data'   => $res['data']
                )
            );
            */
        } else {
            $msg = 'Get verifying detail data failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'resutl' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
        
    }

    /**
     * 店铺审核通过
     *
     * @param    apply_id      店铺编号
     */
    public function apply_verifying_pass_post() {
        //@todo Check privilege
        $this->load->library('apply_lib');
        $apply_id = (int) ($this->input->post('apply_id', TRUE));
        $res = $this->apply_lib->apply_verifying_pass($apply_id);
        if($res['res']) {
            $this->response(array('register_code' => $res['data']), 200);
            /**
            $this->response(
                set rulerarray(
                    'result' => 'ok',
                    'msg'    => 'Verifying pass success',
                    'data'   => array(
                        'register_code' => $res['data']
                    )
                )
            );
            */
        } else {
            $msg = 'Verifying passed failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
    }

    /**
     * 店铺审核未通过
     *
     * @param     apply_id     店铺编号
     * @param     message      店铺审核未通过原因
     */
    public function apply_verifying_failed_post() {
        //@todo Check privilege
        $this->load->library('apply_lib');
        $apply_id = (int) ($this->input->post('apply_id', TRUE));
        $message  = $this->input->post('message', TRUE);
        $res = $this->apply_lib->apply_verifying_failed($apply_id, $message);
        if($res['res']) {
            $this->response("ok", 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Verifying success',
                    'data'   => NULL
                )
            );
            */
        } else {
            $msg = 'Verifying failed';
            if (count($res['msg'] > 0)) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
    }
    
    /**
     * 已经被审核店铺信息（通过和未通过）
     * 
     * @param     void
     */
    public function apply_verified_get() {
        //@Check privilege
        $this->load->library('apply_lib');
        $this->response($this->apply_lib->apply_verified(), 200);
        /**
        $this->response(
            array(
                'result' => 'ok',
                'msg'    => 'Get verified data success',
                'data'   => $this->apply_lib->apply_verified()
            )
        );
        */
    }

}

?>
