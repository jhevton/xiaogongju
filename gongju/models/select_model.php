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
    function getSchoolByAreaAndWenli(array $params){
        extract($params);
        //$sql="select school_id,school_name,school_level,(select area_name from area where area.id=school.province_id ) province_name from school where province_id in (".join(',',$college_place).")";
        $where_str="";
        $where_str.="province_id in (".join(',',$college_place).") and specialty_name<>''";
        if(!empty($wen_li)){
            $where_str.=" and irt_university_specialty.type=".$wen_li;
        }
        /**
         * SELECT
        school_id,
        school_name,
        school_level,
        specialty_name,
        is_constraints,
        (
        SELECT
        area_name
        FROM
        area
        WHERE
        area.id = school.province_id
        ) province_name
        FROM
        school
        LEFT JOIN irt_university_specialty ON school.school_id = irt_university_specialty.irt_school_id
        WHERE
        province_id IN (110000)
        AND irt_university_specialty.type = 2
         */
        $sql="SELECT
                    irt_university_specialty.id,
                    school_id,
                    school_name,
                    irt_school.score,
                    school_level,
                    specialty_name,
                    (
                        SELECT
                            area_name
                        FROM
                            area
                        WHERE
                            area.id = school.province_id
                    ) province_name,
                    is_constraints
                FROM
                    school
                    LEFT JOIN irt_school on irt_school.id=school.school_id
                LEFT JOIN irt_university_specialty ON school.school_id = irt_university_specialty.irt_school_id
                WHERE
                    $where_str";
        $school=$this->db->query($sql)->result_array();
        return $school;
    }
    function getMajorIdByAwards(array $params)
    {
        extract($params);
        $where_arr=[];
        $where_arr[]="ddic_code in (".join(',',$awards_code).")";
        if(isset($condition)){
            $where_arr[]="sign=".$condition;
        }
        $sql="select * from irt_university_specialty_constraints where ".join(' and ',$where_arr);
        $school=$this->db->query($sql)->result_array();
        return $school;
    }
    function getSchoolLessScore(array $params)
    {
        extract($params);
        $sql="select * from irt_school where score<$score";
        $school=$this->db->query($sql)->result_array();
        return $school;
    }
    function getBaodiSchool(array $college_place,$score,$awards_code=[],$wen_li='')
    {
        $school_major_list=$this->getSchoolByAreaAndWenli(['college_place'=>$college_place,'wen_li'=>$wen_li]);

        /**
         * 保底：
         * 1.得奖为充分的
         * 2.或分数比学生分数低两分的学校
         */
        $score_school=[];
        $baodi_score=$score-2;
        if($baodi_score>0){
            foreach($school_major_list as $k=>$v)
            {
                if($v['score']<=$baodi_score){
                    $score_school[$v['id']]=$v;
                }
            }
        }
        //p($school_major_list);
        $awards_school=[];
        if(!empty($awards_code)){
            //获奖,条件充分(0充分,1必要)
            /*正式数据*///$awards_major=$this->getMajorIdByAwards(['awards_code'=>$awards_code,'condition'=>0]);
            /*测试数据*/$awards_major=$this->getMajorIdByAwards(['awards_code'=>$awards_code,'condition'=>1]);
            //p($awards_major);
            foreach($school_major_list as $k=>$v){
                foreach($awards_major as $p=>$q){
                    if($v['id']==$q['university_specialty_id'])
                    {
                        $awards_school[$v['id']]=$v;
                    }
                }
            }
        }
        $major_list=array_merge($score_school,$awards_school);

        return $this->formatReturnData($major_list);
    }
    function getTuijianSchool(array $college_place,$score,$awards_code=[],$wen_li='')
    {
        $school_major_list=$this->getSchoolByAreaAndWenli(['college_place'=>$college_place,'wen_li'=>$wen_li]);
        //p($school_major_list);
        /**
         * 推荐：
         * 1.满足比学生得分低一分的但比学生分数高一分的这个区间内的学校并且没有充分必要条件限制的专业
         * 2.满足比学生得分低一分的但比学生分数高一分的这个区间内的学校并且必要条件限制的专业
         */
        $tuijian_min_score=$score-1;
        $tuijian_max_score=$score+1;
        $score_school=[];
        if($tuijian_min_score>0){
            foreach($school_major_list as $k=>$v)
            {
                if($v['score']<=$tuijian_max_score&&$v['score']>=$tuijian_min_score&&$v['is_constraints']==0){
                    $score_school[$v['id']]=$v;
                }
            }
        }
        $awards_school=[];
        if(!empty($awards_code)){
            $awards_major=$this->getMajorIdByAwards(['awards_code'=>$awards_code,'condition'=>1]);
            //p($awards_major);
            foreach($school_major_list as $k=>$v){
                /*正式数据数据*///if($v['score']<=$tuijian_max_score&&$v['score']>=$tuijian_min_score&&$v['is_constraints']==1){
                /*测试数据*/if($v['score']<=22&&$v['score']>=8){
                    foreach($awards_major as $p=>$q){
                        if($v['id']==$q['university_specialty_id'])
                        {
                            $awards_school[$v['id']]=$v;
                        }
                    }
                }

            }
        }
        $major_list=array_merge($score_school,$awards_school);
        return $this->formatReturnData($major_list);
    }
    function getChongciSchool(array $college_place,$score,$awards_code=[],$wen_li='')
    {
        $school_major_list=$this->getSchoolByAreaAndWenli(['college_place'=>$college_place,'wen_li'=>$wen_li]);
        //p($school_major_list);
        /**
         * 推荐：
         * 1.满足比学生得分高两分到三分学校并且没有充分必要条件限制的专业
         * 2.满足比学生得分高两分到三分的学校并且必要条件限制的专业
         */
        $chongci_min_score=$score+2;
        $chongci_max_score=$score+3;
        $score_school=[];
        foreach($school_major_list as $k=>$v)
        {
            if($v['score']<=$chongci_max_score&&$v['score']>=$chongci_min_score&&$v['is_constraints']==0){
                $score_school[$v['id']]=$v;
            }
        }
        $awards_school=[];
        if(!empty($awards_code)){
            $awards_major=$this->getMajorIdByAwards(['awards_code'=>$awards_code,'condition'=>1]);
            foreach($school_major_list as $k=>$v){
                /*正式数据数据*///if($v['score']<=$chongci_max_score&&$v['score']>=$chongci_min_score&&$v['is_constraints']==1){
                /*测试数据*/if($v['score']<=22&&$v['score']>=8){
                    foreach($awards_major as $p=>$q){
                        if($v['id']==$q['university_specialty_id'])
                        {
                            $awards_school[$v['id']]=$v;
                        }
                    }
                }

            }
        }
        $major_list=array_merge($score_school,$awards_school);
        //p($major_list);
        return $this->formatReturnData($major_list);
    }
    function formatReturnData(array $params)
    {
        //v($params);
        $tmp_key_arr = [];
        $options=$params;
        foreach ($options as $k => &$v)
        {
            $tmp_key = "";
            $tmp_val="";
            foreach ($v as $p => &$q)
            {
                if ($p == "school_id") $tmp_key = $q;
                if(!isset($tmp_key_arr[ $tmp_key ][ 'major_list' ])){
                    $tmp_key_arr[ $tmp_key ][ 'major_list' ]="";
                    //continue;
                }
                if($p=='specialty_name'){
                    $tmp_arr=explode(',',$tmp_key_arr[ $tmp_key ][ 'major_list' ]);

                    if(isset($tmp_arr[0])){
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name0' ] =$tmp_arr[0];
                    }else{
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name0' ]='';
                    }

                    if(isset($tmp_arr[1])){
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name1' ] =$tmp_arr[1];
                    }else{
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name1' ]='';
                    }
                    if(isset($tmp_arr[2])){
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name2' ] =$tmp_arr[2];
                    }else{
                        $tmp_key_arr[ $tmp_key ][ 'specialty_name2' ]='';
                    }
                    $tmp_key_arr[ $tmp_key ][ 'major_list' ] =$tmp_key_arr[ $tmp_key ][ 'major_list' ].$q.",";
                    $tmp_key_arr[ $tmp_key ][ 'specialty_name0' ] =$q;
                }
                    $tmp_key_arr[ $tmp_key ][ $p ] = $q;


            }


        }
        array_shift($tmp_key_arr);
        //p($tmp_key_arr);
        return $tmp_key_arr;
    }
    /*function formatReturnData(array $params)
    {
        $tmp_major_list=[];
        foreach($params as $k=>&$v){
            if(empty($tmp_major_list)){
                $v['major_list']=$v['specialty_name'];
                $tmp_major_list[]=$v;
                continue;
            }
            foreach($tmp_major_list as $p=>&$q)
            {
                if($v['school_id']==$q['school_id']){
                    $q['major_list'].=$v['specialty_name'].",";
                }
            }
        }
        return $tmp_major_list;
    }*/
}