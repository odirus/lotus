<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class View_model extends Base_model {

    private $_CI;
    private $_table = 'view';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function add_view($class, $id) {
        switch ($class) {
        case 'shop':
            $sql = "update view set count = count + 1 WHERE sn = $id AND class = 0";
            break;
        case 'product':
            $sql = "update view set count = count + 1 WHERE sn = $id AND class = 1";
            break;
        default:
            $sql = "update view set count = count + 1 WHERE sn = 0 AND class = 0";
        }
        if ($this->_CI->db->query($sql)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 统计最热门排行
     *
     * @param $class 分类              可选值 shop, product
     * @param $row   需要被排名的数量
     */
    public function count_view_rank($class, $row) {
        $this->_CI->load->model('product_model', 'product_m');
        $sql = "SELECT sn FROM view WHERE class = '$class' ORDER BY count DESC LIMIT 0,$row";
        $res_array = $this->_CI->db->query($sql)->result_array();
        $rank_array = array();
        foreach ($res_array as $key => $value) {
            $rank_array[$key]['id'] = $value['sn'];
        }
        return $rank_array;
    }

}