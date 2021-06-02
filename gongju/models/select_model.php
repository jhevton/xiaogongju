<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/27
 * Time: 17:12
 */

class Select_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    /**
     * @param array $params=['type'=>学校类型]
     * @return mixed
     */
    function test(array $params){
        //$this->db->select( '*' )->from( 'auth_group' )->join( 'auth_group_access', 'auth_group_access.uid=auth_group.id' )->where( 'uid', $admin_id)->where( 'status', 1 )->get()->result_array();
        extract($params);

        return $this->db->select( )->from( 'school' )->get()->result_array();
    }
    function getProvinces(array $params)
    {
       return $this->db->select( 'id,area_name')->from( 'area' )->where('parent_id',0)->get()->result_array();
    }
}