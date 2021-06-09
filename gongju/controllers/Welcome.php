<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        /*$this->load->model('select_model');
        $list=$this->select_model->test([]);*/
        $this->load->model('select_model');
        $list=$this->select_model->getProvinces([]);
		$this->load->view('index',['provinces_list'=>$list]);
	}
    public function getSchoolByType(){
        $school_type = $this->input->post( 'type' );
        $this->load->model('select_model');
        $list=$this->select_model->test(['type'=>$school_type]);
        p($list);
    }
    public function getProvincesName()
    {
        $this->load->model('select_model');
        $list=$this->select_model->getProvinces([]);
        p($list);
    }
    public function getSchool(){
        //分数
        $score = $this->input->get( 'score' );
        //分数
        $wen_li = $this->input->get( 'wen_li' );
        //获得奖项代码
        $awards_code = $this->input->get( 'awards_code' );
        $awards_code=!empty($awards_code)?explode('|',$awards_code):'';
        //填报的学校
        $college_place = $this->input->get( 'college_place' );
        $college_place=!empty($college_place)?explode('|',$college_place):'';

        $this->load->model('select_model');
        $baodi_major=$this->select_model->getAll($college_place,$score,$awards_code,$wen_li);
        exit(json_encode(['data'=>$baodi_major],JSON_UNESCAPED_UNICODE));

        //报考地区得到地区学校

        if(empty($college_place)){
            p("<script>2222</script>");
        }
        $college_place_school=$this->select_model->getSchoolByArea(['college_place'=>$college_place]);
        v($college_place_school);

        /**
         * 保底：
         * 1.得奖为充分的
         * 2.或分数比学生分数低两分的
         */

        $school_by_awards=[];
        if(!empty($awards_code)){
            $school_by_awards=$this->select_model->getSchoolByAwards(['awards_code'=>$awards_code]);
            v($school_by_awards);
        }

        foreach($school_by_awards as $k=>$v)
        {
            if($v['sign']==1){

            }
        }



        /*$list=$this->select_model->test(['type'=>1]);
        p($list);*/
    }

}
