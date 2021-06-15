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
    public function getBaodiSchool(){
        //分数
        $score = $this->input->get( 'score' );
        //文理
        $wen_li = $this->input->get( 'wen_li' );
        //获得奖项代码
        $awards_code = $this->input->get( 'awards_code' );
        $awards_code=!empty($awards_code)?explode('|',$awards_code):'';
        //填报的学校
        $college_place = $this->input->get( 'college_place' );
        $college_place=!empty($college_place)?explode('|',$college_place):'';

        $this->load->model('select_model');
        $baodi_major=$this->select_model->getBaodiSchool($college_place,$score,$awards_code,$wen_li);
        exit(json_encode($baodi_major,JSON_UNESCAPED_UNICODE));
    }
    public function getTuijianSchool(){
        //分数
        $score = $this->input->get( 'score' );
        //文理
        $wen_li = $this->input->get( 'wen_li' );
        //获得奖项代码
        $awards_code = $this->input->get( 'awards_code' );
        $awards_code=!empty($awards_code)?explode('|',$awards_code):'';
        //填报的学校
        $college_place = $this->input->get( 'college_place' );
        $college_place=!empty($college_place)?explode('|',$college_place):'';

        $this->load->model('select_model');
        $tuijian_major=$this->select_model->getTuijianSchool($college_place,$score,$awards_code,$wen_li);
        exit(json_encode($tuijian_major,JSON_UNESCAPED_UNICODE));
    }
    public function getChongciSchool(){
        //分数
        $score = $this->input->get( 'score' );
        //文理
        $wen_li = $this->input->get( 'wen_li' );
        //获得奖项代码
        $awards_code = $this->input->get( 'awards_code' );
        $awards_code=!empty($awards_code)?explode('|',$awards_code):'';
        //填报的学校
        $college_place = $this->input->get( 'college_place' );
        $college_place=!empty($college_place)?explode('|',$college_place):'';

        $this->load->model('select_model');
        $tuijian_major=$this->select_model->getChongciSchool($college_place,$score,$awards_code,$wen_li);
        //exit(json_encode(['data'=>$baodi_major],JSON_UNESCAPED_UNICODE));
        //exit(json_encode(['data'=>$tuijian_major],JSON_UNESCAPED_UNICODE));
        exit(json_encode($tuijian_major,JSON_UNESCAPED_UNICODE));

    }

}
