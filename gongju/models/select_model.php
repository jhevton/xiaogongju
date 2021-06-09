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
    function getAll(array $college_place,$score,$awards_code=[],$wen_li='')
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
            $awards_major=$this->getMajorIdByAwards(['awards_code'=>$awards_code,'condition'=>1]);
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

        return $major_list;
    }
}