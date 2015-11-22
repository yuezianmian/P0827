<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['dashboard_wel_system_info'];?><!--<?php echo $lang['dashboard_wel_lase_login'].$lang['nc_colon'];?><?php echo $output['admin_info']['admin_login_time'];?>--></h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="info-panel">
        <dl class="member">
            <dt>
            <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_member'];?>"><span><em id="statistics_member"></em></span></sub></div>
            <h3>会员信息</h3>
            <h5>本周新增代理商/本周新增店铺</h5>
            </dt>
            <dd>
                <ul>
                    <li class="w50pre normal"><a href="index.php?act=member&op=member">本周新增代理商<sub><em id="statistics_week_add_agent">0</em></sub></a></li>
                    <li class="w50pre normal"><a href="index.php?act=member&op=member">本周新增店铺<sub><em id="statistics_week_add_shop">0</em></sub></a></li>
                </ul>
            </dd>
        </dl>
        <dl class="shop">
            <dt>
            <div class="ico"><i></i><sub title="积分提现"><span><em id="statistics_extract_cash"></em></span></sub></div>
            <h3>积分提现信息</h3>
            <h5>已提现次数/待处理总数</h5>
            </dt>
            <dd>
                <ul>
                    <li class="w50pre normal"><a href="index.php?act=extract_cash&op=extract_cash">已提现次数<sub><em id="statistics_extract_cash_amount">0</em></sub></a></li>
                    <li class="w50pre normal"><a href="index.php?act=extract_cash&op=extract_cash">待提现次数<sub><em id="statistics_to_extract_cash_amount">0</em></sub></a></li>
                </ul>
            </dd>
        </dl>
        <dl class="goods">
            <dt>
            <div class="ico"><i></i><sub title="积分兑换订单"><span><em id="statistics_points_order"></em></span></sub></div>
            <h3>积分兑换订单信息</h3>
            <h5>已兑换订单数/未兑换订单数</h5>
            </dt>
            <dd>
                <ul>
                    <li class="w50pre normal"><a href="index.php?act=points_order&op=points_order">已兑换订单数<sub><em id="statistics_points_order_amount">0</em></sub></a></li>
                    <li class="w50pre normal"><a href="index.php?act=points_order&op=points_order">未兑换订单数<sub><em id="statistics_to_points_order_amount">0</em></sub></a></li>
                </ul>
            </dd>
        </dl>
        <dl class="trade">
            <dt>
            <div class="ico"><i></i><sub title="二维码扫描数"><span><em id="statistics_qrcode_scan_amount"></em></span></sub></div>
            <h3>二维码扫描信息</h3>
            <h5>本周新增扫描数</h5>
            </dt>
            <dd>
                <ul>
                    <li class="w100pre normal"><a href="index.php?act=record_list&op=record_list">本周新增扫描数<sub><em id="statistics_qrcode_scan_weeken_add"></em></sub></a></li>
                </ul>
            </dd>
        </dl>
        <dl class="trade">
            <dt>
            <div class="ico"><i></i><sub title="反馈消息"><span><em id="statistics_feedback_amount"></em></span></sub></div>
            <h3>反馈信息</h3>
            <h5>本周新增反馈数</h5>
            </dt>
            <dd>
                <ul>
                    <li class="w100pre normal"><a href="index.php?act=feedback&op=feedback">本周新增反馈数<sub><em id="statistics_feedback_weeken_add"></em></sub></a></li>
                </ul>
            </dd>
        </dl>

    </div>
</div>
<script type="text/javascript">
    var normal = ['week_add_member','week_add_product'];
    var work = ['member','week_add_agent','week_add_shop','extract_cash_amount','extract_cash_amount_points','extract_cash_amount_number','to_extract_cash_amount_points','points_order_amount','points_order','to_points_order_amount','qrcode_scan_amount', 'qrcode_scan_weeken_add','feedback_amount','feedback_weeken_add'];
    $(document).ready(function(){
        $.getJSON("index.php?act=dashboard&op=statistics", function(data){
            $.each(data, function(k,v){
                $("#statistics_"+k).html(v);
//                if (v!= 0 && $.inArray(k,work) !== -1){
//                    $("#statistics_"+k).parent().parent().parent().removeClass('none').addClass('high');
//                }else if (v == 0 && $.inArray(k,normal) !== -1){
//                    $("#statistics_"+k).parent().parent().parent().removeClass('normal').addClass('none');
//                }
            });
        });
        //自定义滚定条
        $('#system-info').perfectScrollbar();
    });
</script>
