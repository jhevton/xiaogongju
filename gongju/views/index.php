<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>小工具</title>
    <link href="<?=base_url('res/web/js/bootstrap-3.3.5/css/bootstrap.css')?>" rel="stylesheet">
    <link href="<?=base_url('res/web/css/tip-darkgray/tip-darkgray.css');?>" rel="stylesheet" >
    <link href="<?=base_url('res/web/css/jquery.dataTables.min.css');?>" rel="stylesheet" >
    <script src="<?=base_url('res/web/js/jquery-1.11.1.min.js')?>"></script>
    <script src="<?=base_url('res/web/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('res/web/js/json2.js')?>"></script>
    <script src="<?=base_url('res/web/js/jquery.poshytip.js')?>"></script>
    <script src="<?=base_url('res/web/js/jquery.dataTables.min.js')?>"></script>

</head>
<style>
    body {
        font-family: "Microsoft Yahei";
    }

    .stu-place label, .stu-place {
        margin: 0px 10px 10px 10px;
    }

    .bottom-line {
        border-top: 1px solid #ccc;
        padding-top: 25px;
    }

    .look-more {
        color: #0044cc;
        cursor: pointer;
    }
</style>
<script>
    $( function() {
        //$( ".content" ).addClass( 'hide' ).eq( 0 ).removeClass( 'hide' );
        $( '#college-place-title' ).poshytip( {
            className       : 'tip-darkgray',
            bgImageFrameSize: 9,
            content         : '报考地必填',
            alignTo         : 'target',
            alignX          : 'inner-left',
            alignY          : 'bottom',
            offsetX         : 100,
            offsetY         : 5
        } );
        $( '#stu-place-title' ).poshytip( {
            className       : 'tip-darkgray',
            bgImageFrameSize: 9,
            content         : '生源地必填',
            alignTo         : 'target',
            alignX          : 'inner-left',
            alignY          : 'bottom',
            offsetX         : 100,
            offsetY         : 5
        } );
        $( '#wenli-title' ).poshytip( {
            className       : 'tip-darkgray',
            bgImageFrameSize: 9,
            content         : '此生源地对应年级的文理必填',
            alignTo         : 'target',
            alignX          : 'inner-left',
            alignY          : 'top',
            offsetX         : 100,
            offsetY         : 5
        } );
        $( ".next-btn" ).each( function() {
            $( this ).click( function() {
                if( $( this ).attr( 'id' ) == 'first-btn' ) {
                    var stu_place = $( "input[name=stu_place]:checked" ).val();
                    if( typeof (stu_place) == "undefined" ) {
                        $( '#stu-place-title' ).poshytip( 'show' );
                        $( '#stu-place-title' ).poshytip( 'hideDelayed', 2000 );
                        return false;
                    }
                    //文理判断
                    if($(".wenli" ).hasClass('hide')){
                        wen_li_tag=0;
                    }else{
                        var wenli=$("input[name=wen_li]:checked" ).val();
                        if(typeof (wenli)=="undefined"){
                            $( '#wenli-title' ).poshytip( 'show' );
                            $( '#wenli-title' ).poshytip( 'hideDelayed', 2000 );
                        }
                    }

                }
                if( $( this ).attr( 'id' ) == 'college-place-btn' ) {
                    var college_place = $( "input[name=college_place]:checked" ).val();
                    if( typeof (college_place) == "undefined" ) {
                        $( '#college-place-title' ).poshytip( 'show' );
                        $( '#college-place-title' ).poshytip( 'hideDelayed', 2000 );
                        return false;
                    }



                    //ajax
                    var awards_code_str="";
                    for(var i in awards_code)
                    {
                        if(awards_code[i]){
                            awards_code_str+=awards_code[i]+"|";
                        }
                    }
                    $.get(
                        '<?=base_url("welcome/getSchool")?>',
                        { 'score': score, 'awards_code':awards_code_str.substring(0,awards_code_str.length-1),'college_place':college_place_str,'wen_li':wen_li_tag},
                        function( data ) {
                            /*$('#baodi-table-test').DataTable( {
                                data:data
                            } );*/
                            $('#baodi-table-test' ).text(data);
                            //跳转

                            //location.href='/mobile/account/index.html';
                        },
                        'json'
                    );

                }
                //$( this ).parents( '.content' ).addClass( 'hide' ).next( '.content' ).removeClass( 'hide' );
            } )
        } )

        $( ".pre-btn" ).each( function() {
            $( this ).click( function() {
                $( this ).parents( '.content' ).addClass( 'hide' ).prev( '.content' ).removeClass( 'hide' );
            } )
        } );
        //选中生源地，学校一本率联动
        $( "#stu-place input[type=radio]" ).focus( function() {
            var arr = $( this ).attr( 'data-ratio' ).split( "|" );//stu-place-ratio
            var obj = $( "#stu-place-ratio" ).find( 'label' );
            for( var i = 0; i < arr.length; i++ ) {
                obj.find( 'span' ).eq( i ).text( arr[ i ] );
            }
        } );
        function checkHasWenli() {
            var stu_grade = $( "input[name=stu_grade]:checked" ).val();
            var tmp_stu_place = $( 'input[name=stu_place]:checked' ).val();
            var tmp_arr = [ '上海', '浙江' ];
            if( stu_grade != 2016 && $.inArray( tmp_stu_place, tmp_arr ) >= 0 ) {
                $( ".wenli" ).addClass( 'hide' );
            }
            else {
                $( ".wenli" ).removeClass( 'hide' );
            }
        }

        $( 'input[name=stu_place]' ).change( function() {
            checkHasWenli();
        } );
        $( "input[name=stu_grade]" ).change( function() {
            checkHasWenli();
        } );

        $( ".other-awards" ).change( function() {
            if( $( this ).val() == 0 ) {
                $( this ).parent( 'label' ).next( 'span' ).addClass( 'hide' );
            }
            else {
                $( this ).parent( 'label' ).next( 'span' ).removeClass( 'hide' ).find('input' ).eq(0).attr("checked","checked");
                var select_value=$(this ).val();
                $( this ).parent( 'label' ).next( 'span' ).removeClass( 'hide' ).find('input' ).each(function(i){
                    var radio_value=$(this ).val();
                    var radio_tag=$(this ).attr('data-tag');
                    var first=radio_tag.split('|')[0];
                    var second=radio_tag.split('|')[1];
                    $(this ).val(select_value+first+"|"+second);
                } ).eq(0 ).attr("checked",true);
            }
        } );
        $( ".look-more" ).click( function() {
            var name = $( this ).parents( 'td' ).siblings( '.select-school-name' ).text();
            var dingwei = $( this ).parents( 'td' ).siblings( '.select-school-dingwei' ).text();
            var place = $( this ).parents( 'td' ).siblings( '.select-school-place' ).text();
            $( "#myModalLabel" ).text( name + "," + dingwei + "," + place );
            $( "#modal-body" ).text( score );
            $( "#zhuanye-modal" ).modal( 'show' );
        } );
        $( "#college-place-body input" ).change( function() {
            if( $( "#college-place-body input" ).index( $( this ) ) == 0 ) {
                $( "#college-place-body input" ).attr( "checked", true );
            }
        } );
        //报考地区
        var college_place_str="";
        $("#college-place-body input" ).change(function(){
            var tmp_obj= "";

            $("#college-place-body input:checked" ).each(function(i){
                var value=$(this ).val();
                if(value!="quanguo"){
                    tmp_obj+=$(this ).val()+"|";
                }
            });
            tmp_obj+=tmp_obj.substring(0,tmp_obj.length-1);
            college_place_str=tmp_obj;
            console.info(college_place_str);
        })
        var score_key_value=[];
        var score=0;
        //选择文理标识
        var wen_li_tag=0;
        $("input[name=wen_li]" ).change(function(){
            wen_li_tag=$(this ).val();
        })
        var awards_code=[];
        $(".score-item" ).change(function(){
            var code="";
            var score_value=0;
            var key=$(this ).attr('name');
            var value=$(this ).val();
            var awards_type={};
            var tmp_select_arr=['other_awards_rank_one_select','other_awards_rank_two_select','other_awards_rank_three_select'];
            var tmp_select_input_arr={'other_awards_rank_one_select':"other_awards_rank_one_input",'other_awards_rank_two_select':"other_awards_rank_two_input",'other_awards_rank_three_select':"other_awards_rank_three_input"};
            if( $.inArray(key,tmp_select_arr)>=0){
                key=tmp_select_input_arr[key];
                if(value!=0){
                    code=value;
                    value=$("input[name="+key+"]:checked" ).val().split('|')[1];
                    code+=$("input[name="+key+"]:checked" ).attr('data-tag').split('|')[0];

                }
            }
            if(value.match(/\|/)){
                code=value.split('|')[0];
                value=value.split('|')[1];
            }
            if(key.match(/awards/)){
                awards_code[key]=code;
            }
            for(var i=0;i<score_key_value.length;i++){
                if(score_key_value[i ].key==key){
                    score_key_value[i ].value=value;
                    console.info(score_key_value);
                    return;
                }
            }

            var tmp_obj={key:key,value:value};
            score_key_value.push(tmp_obj);
            for(var i=0;i<score_key_value.length;i++){
                console.info(score_key_value[i ].value)
                score_value+=parseInt(score_key_value[i ].value);
            }
            score=score_value;
            console.info(score_key_value);
            console.info(score);
        });

    } )
</script>
<body>

<div class="container">
    <div class="row title">
        <h1 class="text-center" style="margin: 20px auto 0 auto;">海风自主招生小工具</h1>
        <address class="text-right">

        </address>

    </div>
    <div class="row content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>填写学生基本信息</strong>&nbsp;&nbsp;>&nbsp;&nbsp;选择奖项、论文和专利&nbsp;&nbsp;>&nbsp;&nbsp;选择报考地区&nbsp;&nbsp;>&nbsp;&nbsp;查看报告结果
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt id="stu-place-title" data-tipso="生源地必选">生源地：</dt>
                    <dd id="stu-place" class="stu-place">
                        <label>
                            <input type="radio" name="stu_place" value="北京" data-ratio="90%|80%|45%">北京
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="上海" data-ratio="90%|80%|45%">上海
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="江苏" data-ratio="90%|70%|35%">江苏
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="浙江" data-ratio="90%|80%|45%">浙江
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="天津" data-ratio="90%|80%|45%">天津
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="广东" data-ratio="85%|65%|40%">广东
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="湖北" data-ratio="90%|70%|35%">湖北
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="湖南" data-ratio="90%|70%|35%">湖南
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="陕西" data-ratio="90%|70%|35%">陕西
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="四川" data-ratio="90%|70%|35%">四川
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="重庆" data-ratio="90%|70%|35%">重庆
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="山东" data-ratio="85%|65%|40%">山东
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="西藏" data-ratio="58%|25%|15%">西藏
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="广西" data-ratio="85%|65%|40%">广西
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="安徽" data-ratio="80%|55%|30%">安徽
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="黑龙江" data-ratio="85%|65%|40%">黑龙江
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="甘肃" data-ratio="70%|35%|22%">甘肃
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="辽宁" data-ratio="58%|25%|15%">辽宁
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="山西" data-ratio="58%|25%|15%">山西
                        </label>
                        <label>
                            <input type="radio" name="stu_place" value="吉林" data-ratio="75%|40%|25%">吉林
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>年级：</dt>
                    <dd class="stu-place bottom-line">
                        <label>
                            <input type="radio" checked name="stu_grade" value="2016">2016届
                        </label>
                        <label>
                            <input type="radio" name="stu_grade" value="2017">2017届
                        </label>
                        <label>
                            <input type="radio" name="stu_grade" value="2018">2018届
                        </label>
                        <label>
                            <input type="radio" name="stu_grade" value="2019">2019届
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>学校一本率：</dt>
                    <dd id="stu-place-ratio" class="stu-place bottom-line">
                        <label>
                            <input class="score-item" type="radio" checked name="school_yiben_rate" value="4"><span>90%</span>
                        </label>
                        <label>
                            <input class="score-item" type="radio" name="school_yiben_rate" value="2"><span>75%</span>
                        </label>
                        <label>
                            <input class="score-item" type="radio" name="school_yiben_rate" value="1" aria-label=""><span>25%</span>
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>年级排名：</dt>
                    <dd class="stu-place bottom-line">
                        <label>
                            <input class="score-item" type="radio" checked name="grade_rank" value="5"><span>前5%</span>
                        </label>
                        <label>
                            <input class="score-item" type="radio" name="grade_rank" value="2"><span>前10%</span>
                        </label>
                        <label>
                            <input class="score-item" type="radio" name="grade_rank" value="1"><span>前20%</span>
                        </label>
                        <label>
                            <input class="score-item" type="radio" name="grade_rank" value="0"><span>前20%~50%</span>
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal wenli">
                    <dt id="wenli-title">文理：</dt>
                    <dd class="stu-place bottom-line">
                        <label>
                            <input type="radio" name="wen_li" value="1" aria-label="1"><span>文科</span>
                        </label>
                        <label>
                            <input type="radio" name="wen_li" value="2" aria-label="2"><span>理科</span>
                        </label>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="col-xs-4 col-xs-offset-4 text-center" style="margin-bottom: 50px;">
            <button id="first-btn" class="btn  btn-primary next-btn" type="submit">下一步</button>
        </div>
    </div>
    <div class="row content">
        <div class="panel panel-default">
            <div class="panel-heading">
                填写学生基本信息&nbsp;&nbsp;>&nbsp;&nbsp;<strong>选择奖项、论文和专利</strong>&nbsp;&nbsp;>&nbsp;&nbsp;选择报考地区&nbsp;&nbsp;>&nbsp;&nbsp;查看报告结果
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>奥赛国奖：</dt>
                    <dd>
                        <label>
                            <select name="awards_guo_shuxue" style="width: 150px" class="form-control score-item">
                                <option value="0">数学无奖项</option>
                                <option value="101|10">数学国家一等奖</option>
                                <option value="102|7">数学国家二等奖</option>
                                <option value="103|5">数学国家三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_guo_wuli" style="width: 150px" class="form-control score-item">
                                <option value="0">物理无奖项</option>
                                <option value="301|10">物理国家一等奖</option>
                                <option value="302|7">物理国家二等奖</option>
                                <option value="303|5">物理国家三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_guo_huaxue" style="width: 150px" class="form-control score-item">
                                <option value="0">化学无奖项</option>
                                <option value="701|10">化学国家一等奖</option>
                                <option value="702|7">化学国家二等奖</option>
                                <option value="703|5">化学国家三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_guo_shengwu" style="width: 150px" class="form-control score-item">
                                <option value="0">生物无奖项</option>
                                <option value="501|10">生物国家一等奖</option>
                                <option value="502|7">生物国家二等奖</option>
                                <option value="503|5">生物国家三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_guo_xinxi" style="width: 150px" class="form-control score-item">
                                <option value="0">信息无奖项</option>
                                <option value="901|5">信息国家一等奖</option>
                                <option value="902|3">信息国家二等奖</option>
                                <option value="903|1">信息国家三等奖</option>
                            </select>
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>国联、奥赛省奖：</dt>
                    <dd class="bottom-line">
                        <label>
                            <select name="awards_sheng_shuxue" style="width: 150px" class="form-control score-item">
                                <option value="0">数学无奖项</option>
                                <option value="201|5">数学省级一等奖</option>
                                <option value="202|3">数学省级二等奖</option>
                                <option value="203|1">数学省级三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_sheng_wuli" style="width: 150px" class="form-control score-item">
                                <option value="0">物理无奖项</option>
                                <option value="401|5">物理省级一等奖</option>
                                <option value="402|3">物理省级二等奖</option>
                                <option value="403|1">物理省级三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_sheng_huaxue" style="width: 150px" class="form-control score-item">
                                <option value="0">化学无奖项</option>
                                <option value="801|5">化学省级一等奖</option>
                                <option value="802|3">化学省级二等奖</option>
                                <option value="803|1">化学省级三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_awards_sheng_shengwu" style="width: 150px" class="form-control score-item">
                                <option value="0">生物无奖项</option>
                                <option value="601|5">生物省级一等奖</option>
                                <option value="602|3">生物省级二等奖</option>
                                <option value="603|1">生物省级三等奖</option>
                            </select>
                        </label>
                        <label>
                            <select name="awards_sheng_xinxi" style="widt5: 150px" class="form-control score-item">
                                <option value="0">信息无奖项</option>
                                <option value="1001|5">信息省级一等奖</option>
                                <option value="1002|3">信息省级二等奖</option>
                                <option value="1003|1">信息省级三等奖</option>
                            </select>
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>其他全国竞赛：</dt>
                    <dd class="bottom-line">
                        <div class="other-awards-div">
                            <label>
                                <select name="other_awards_rank_one_select" style="width: 225px" class="form-control other-awards score-item">
                                    <option value="0">无</option>
                                    <option value="11">鲁迅青少年文学奖</option>
                                    <option value="12">明天小小科学家</option>
                                    <option value="13">全国青少年科技创新大赛</option>
                                    <option value="14">全国中小学电脑制作活动</option>
                                    <option value="15">创新作文大赛</option>
                                    <option value="16">创新英语作文大赛</option>
                                    <option value="17">“博雅杯”作文大赛</option>
                                    <option value="18">“叶圣陶杯”作文大赛</option>
                                    <option value="19">语文报杯作文大赛</option>
                                    <option value="20">新概念作文大赛</option>
                                    <option value="21">“希望杯”数学邀请赛</option>
                                    <option value="22">航空航天模型锦标赛</option>
                                    <option value="23">国际无人机飞行器创新大赛</option>
                                </select>
                            </label>
                            <span class="other-awards-rank hide">
                                <label class="stu-place">
                                    <input class="score-item diyi" type="radio" checked name="other_awards_rank_one_input" data-tag="01|7" value="01|7">一等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_one_input" data-tag="02|5" value="02|5" aria-label="">二等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_one_input" data-tag="03|2" value="03|2" aria-label="">三等奖
                                </label>
                            </span>
                        </div>
                        <div>
                            <label>
                                <select name="other_awards_rank_two_select" style="width: 225px" class="form-control other-awards score-item">
                                    <option value="0">无</option>
                                    <option value="11">鲁迅青少年文学奖</option>
                                    <option value="12">明天小小科学家</option>
                                    <option value="13">全国青少年科技创新大赛</option>
                                    <option value="14">全国中小学电脑制作活动</option>
                                    <option value="15">创新作文大赛</option>
                                    <option value="16">创新英语作文大赛</option>
                                    <option value="17">“博雅杯”作文大赛</option>
                                    <option value="18">“叶圣陶杯”作文大赛</option>
                                    <option value="19">语文报杯作文大赛</option>
                                    <option value="20">新概念作文大赛</option>
                                    <option value="21">“希望杯”数学邀请赛</option>
                                    <option value="22">航空航天模型锦标赛</option>
                                    <option value="23">国际无人机飞行器创新大赛</option>
                                </select>
                            </label>
                            <span class="other-awards-rank hide">
                                <label class="stu-place">
                                    <input class="score-item diyi" type="radio" checked name="other_awards_rank_two_input" data-tag="01|7" value="01|7">一等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_two_input" data-tag="02|5" value="02|5" aria-label="">二等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_two_input" data-tag="03|2" value="03|2" aria-label="">三等奖
                                </label>
                            </span>
                        </div>
                        <div>
                            <label>
                                <select name="other_awards_rank_three_select" style="width: 225px" class="form-control other-awards score-item">
                                    <option value="0">无</option>
                                    <option value="11">鲁迅青少年文学奖</option>
                                    <option value="12">明天小小科学家</option>
                                    <option value="13">全国青少年科技创新大赛</option>
                                    <option value="14">全国中小学电脑制作活动</option>
                                    <option value="15">创新作文大赛</option>
                                    <option value="16">创新英语作文大赛</option>
                                    <option value="17">“博雅杯”作文大赛</option>
                                    <option value="18">“叶圣陶杯”作文大赛</option>
                                    <option value="19">语文报杯作文大赛</option>
                                    <option value="20">新概念作文大赛</option>
                                    <option value="21">“希望杯”数学邀请赛</option>
                                    <option value="22">航空航天模型锦标赛</option>
                                    <option value="23">国际无人机飞行器创新大赛</option>
                                </select>
                            </label>
                            <span class="other-awards-rank hide">
                                <label class="stu-place">
                                    <input class="score-item diyi" type="radio" checked name="other_awards_rank_three_input" data-tag="01|7" value="01|7">一等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_three_input" data-tag="02|5" value="02|5" aria-label="">二等奖
                                </label>
                                <label class="stu-place">
                                    <input class="score-item" type="radio" name="other_awards_rank_three_input" data-tag="03|2" value="03|2" aria-label="">三等奖
                                </label>
                            </span>
                        </div>

                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>论文发表：</dt>
                    <dd class="stu-place bottom-line">
                        <label>
                            <input type="radio" checked name="stu_lunwen_count" value="0" aria-label="">0篇
                        </label>
                        <label>
                            <input type="radio" name="stu_lunwen_count" value="1" aria-label="">1篇
                        </label>
                        <label>
                            <input type="radio" name="stu_lunwen_count" value="2" aria-label="">2篇
                        </label>
                        <label>
                            <input type="radio" name="stu_lunwen_count" value="3" aria-label="">3篇
                        </label>
                        <label>
                            <input type="radio" name="stu_lunwen_count" value="4" aria-label="">4篇
                        </label>
                        <label>
                            <input type="radio" name="stu_lunwen_count" value="5" aria-label="">5篇及以上
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt>实用新型专利：</dt>
                    <dd class="stu-place bottom-line">
                        <label>
                            <input type="radio" checked name="stu_zhuanli_count" value="0" aria-label="">0个
                        </label>
                        <label>
                            <input type="radio" name="stu_zhuanli_count" value="1" aria-label="">1个
                        </label>
                        <label>
                            <input type="radio" name="stu_zhuanli_count" value="2" aria-label="">2个
                        </label>
                        <label>
                            <input type="radio" name="stu_zhuanli_count" value="3" aria-label="">3个及以上
                        </label>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="col-xs-4 col-xs-offset-4 text-center" style="margin-bottom: 50px;">

            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary next-btn">下一步</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary pre-btn">上一步</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row content">
        <div class="panel panel-default">
            <div class="panel-heading">
                填写学生基本信息&nbsp;&nbsp;>&nbsp;&nbsp;选择奖项、论文和专利&nbsp;&nbsp;>&nbsp;&nbsp;<strong>选择报考地区</strong>&nbsp;&nbsp;>&nbsp;&nbsp;查看报告结果
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt id="college-place-title" data-tipso="报考地区必选">报考地区：</dt>
                    <dd id="college-place-body" class="stu-place">
                        <label>
                            <input type="checkbox" name="college_place" value="quanguo" aria-label="">全国
                        </label>
                        <br/>
                        <label>
                            <input type="checkbox" name="college_place" value="110000" aria-label="">北京
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="310000">上海
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="320000">江苏
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="330000">浙江
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="120000" aria-label="">天津
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="440000" aria-label="">广东
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="420000" aria-label="">湖北
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="430000" aria-label="">湖南
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="610000" aria-label="">陕西
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="510000" aria-label="">四川
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="500000" aria-label="">重庆
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="540000" aria-label="">西藏
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="450000" aria-label="">广西
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="350000" aria-label="">福建
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="340000" aria-label="">安徽
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="370000" aria-label="">山东
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="230000" aria-label="">黑龙江
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="210000" aria-label="">辽宁
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="140000" aria-label="">山西
                        </label>
                        <label>
                            <input type="checkbox" name="college_place" value="620000" aria-label="">甘肃
                        </label>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="col-xs-4 col-xs-offset-4 text-center" style="margin-bottom: 50px;">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <button id="college-place-btn" type="button" class="btn btn-primary next-btn">生成报告</button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary pre-btn">上一步</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row content">
        <div class="panel panel-default">
            <div class="panel-heading">
                填写学生基本信息&nbsp;&nbsp;>&nbsp;&nbsp;选择奖项、论文和专利&nbsp;&nbsp;>&nbsp;&nbsp;选择报考地区&nbsp;&nbsp;>&nbsp;&nbsp;<strong>查看报告结果</strong>
                <span class="btn btn-default btn-xs" onclick="location.reload()" style="float:right"
                      role="button">重新测试</span>
            </div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt>报告结果：</dt>
                    <dd class="stu-place">
                        <label>
                            <input type="radio" checked name="stu_lunwen_count" value="0" aria-label="">销售用
                        </label>
                        <label style="margin-left: 20%;">
                            <input type="radio" name="stu_lunwen_count" value="1" aria-label="">顾问用
                        </label>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt style="padding-top: 10px">冲刺学校：</dt>
                    <dd class="bottom-line">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>学校</th>
                                <th>定位</th>
                                <th>所在地</th>
                                <th>专业1</th>
                                <th>专业2</th>
                                <th>专业3</th>
                                <th>全部专业</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="select-school-name">北京大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            <tr>
                                <td class="select-school-name">清华大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt style="padding-top: 10px">推荐学校：</dt>
                    <dd class="bottom-line">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>学校</th>
                                <th>定位</th>
                                <th>所在地</th>
                                <th>专业1</th>
                                <th>专业2</th>
                                <th>专业3</th>
                                <th>全部专业</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="select-school-name">北京大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            <tr>
                                <td class="select-school-name">清华大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </dd>
                </dl>
                <dl class="dl-horizontal">
                    <dt style="padding-top: 10px">保底学校：</dt>
                    <dd class="bottom-line">
                        <table id="baodi-table-test" class="table" width="100%">
                            <thead>
                            <tr>
                                <th>学校</th>
                                <th>定位</th>
                                <th>所在地</th>
                                <th>专业1</th>
                                <th>专业2</th>
                                <th>专业3</th>
                                <th>全部专业</th>
                            </tr>
                            </thead>
                            <!--<tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Office</th>
                                <th>Extn.</th>
                                <th>Start date</th>
                                <th>Salary</th>
                            </tr>
                            </tfoot>-->
                        </table>
                        <!--<table class="table table-striped">
                            <thead>
                            <tr>
                                <th>学校</th>
                                <th>定位</th>
                                <th>所在地</th>
                                <th>专业1</th>
                                <th>专业2</th>
                                <th>专业3</th>
                                <th>全部专业</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="select-school-name">北京大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            <tr>
                                <td class="select-school-name">清华大学</td>
                                <td class="select-school-dingwei">211</td>
                                <td class="select-school-place">北京</td>
                                <td>物理</td>
                                <td>化学</td>
                                <td>数学</td>
                                <td><span class="look-more">查看</span></td>
                            </tr>
                            </tbody>
                        </table>-->
                    </dd>
                </dl>
            </div>
        </div>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="zhuanye-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div id="modal-body" class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>