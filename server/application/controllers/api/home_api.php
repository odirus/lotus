<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Home_api extends REST_Controller {
  
    public function __construct() {
        parent::__construct();
        session_start();
    }

    /**
     * 获取商品目录
     */
    public function products_class_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->products_class()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get product class success',
                    'data'   => $res
                )
            );
        }
        
    }


    /**
     * 热门商品推荐
     */
    public function popular_products_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_product()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular products success',
                    'data'   => $res
                )
            );
        }
    }

    /**
     * 热门店铺推荐
     */
    public function popular_shop_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_shop()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular product success',
                    'data'   => $res
                )
            );
        }
    }

    /**
     * 根据分类获取商品的分页数
     */
    public function products_page_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $this->load->library('home_lib');
        $res = $this->home_lib->products_page($class_a, $class_b);
        if ($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products page success',
                    'data'   => array(
                        'pages' => $res['data']
                    )
                )
            );
        } else {
            //默认获取失败时返回的消息
            $msg = 'Get products page failed';
            //获取上一层返回消息，并且格式化为字符串形式
            if (count($res['msg'] > 0)) {
                $msg = implode("; ", $res['msg']);
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


    /**
     * 根据分类获取商品信息
     */
    public function products_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $page    = (int) ($this->input->get('page', TRUE));
        $this->load->library('home_lib');
        $res = $this->home_lib->product($class_a, $class_b, $page);
        if ($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products success',
                    'data'   => $res['data']
                )
            );
        } else {
            $msg = 'Get products failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
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