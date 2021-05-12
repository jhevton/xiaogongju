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
    function test(){
        //$this->db->select( '*' )->from( 'auth_group' )->join( 'auth_group_access', 'auth_group_access.uid=auth_group.id' )->where( 'uid', $admin_id)->where( 'status', 1 )->get()->result_array();
        return $this->db->select( )->from( 'school' )->get()->result_array();
    }
}