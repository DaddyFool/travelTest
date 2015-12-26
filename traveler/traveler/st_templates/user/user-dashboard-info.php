<?php
//$info = STUser_f::st_get_data_reports_partner(array('st_cars','st_hotel'),'10-9-2015','20-9-2015');
$_custom_date = STUser_f::get_custom_date_reports_partner();
$request_custom_date = STUser_f::get_request_custom_date_partner();


$post_type = STInput::request('type');
$obj_post_type = get_post_type_object( $post_type );

?>
<div class="row" style="margin-top: 15px;">
    <div class="col-md-4">
        <?php
        $start  = $_custom_date['y'].'-'.$_custom_date['m'].'-1';
        $end  = $_custom_date['y'].'-'.$_custom_date['m'].'-31';
        $this_month = STUser_f::st_get_data_reports_partner('all','custom_date',$start,$end);
        ?>
        <div class="st-dashboard-stat st-month-madison st-dashboard-new st-month-1">
            <div class="visual">
                <i class="fa fa fa-bar-chart"></i>
            </div>
            <div class="title">
                <?php _e("Earning This Month",ST_TEXTDOMAIN) ?>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                    if($this_month['average_total'] > 0){
                        echo TravelHelper::format_money($this_month['average_total']);
                    }else{
                        echo "0";
                    }?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
        $total_earning = STUser_f::st_get_data_reports_total_all_time_partner();
        ?>
        <div class="st-dashboard-stat st-month-madison st-dashboard-new st-month-2">
            <div class="visual">
                <i class="fa fa-calculator"></i>
            </div>
            <div class="title">
                <?php _e("Your Balance",ST_TEXTDOMAIN) ?>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                    if($total_earning['average_total'] > 0){
                        echo TravelHelper::format_money($total_earning['average_total']) ;
                    }else{
                        echo "0";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
        //$total_earning = STUser_f::st_get_data_reports_total_earning_partner();
        ?>
        <div class="st-dashboard-stat st-month-madison st-dashboard-new st-month-3">
            <div class="visual">
                <i class="fa fa-cogs"></i>
            </div>
            <div class="title">
                <?php _e("Total Earning",ST_TEXTDOMAIN) ?>
            </div>
            <div class="details">
                <div class="number">
                    <?php
                    if($total_earning['total'] > 0){
                        echo TravelHelper::format_money($total_earning['total']) ;
                    }else{
                        echo "0";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row" style="margin-top: 15px;">
    <?php
    
    $start  = $_custom_date['y'].'-'.$_custom_date['m'].'-1';
    $end  = $_custom_date['y'].'-'.$_custom_date['m'].'-31';
    $this_month = STUser_f::st_get_data_reports_partner(array($post_type),'custom_date',$start,$end);
    ?>
    <div class="col-md-12">
        <div class="panel panel-primary panel-<?php echo STInput::request('type') ?> panel-single">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-3">
                        <i class="fa <?php echo apply_filters('st_post_type_'.$post_type.'_icon','') ?> fa-5x"></i>
                        <span class="title_post_type"><?php  echo esc_html($obj_post_type->labels->singular_name); ?>  <?php _e(" Statistics",ST_TEXTDOMAIN) ?></span>
                    </div>
                    <div class="col-md-5 text-right average_total">
                        <div class="huge">
                            <?php
                            if($this_month['average_total'] > 0){
                                echo TravelHelper::format_money($this_month['average_total']);
                            }else {
                                echo "0";
                            }
                            ?>
                        </div>
                        <div class="title"><?php _e("Total Price",ST_TEXTDOMAIN) ?></div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="huge">
                            <?php echo esc_html($this_month['number_orders']) ?>
                        </div>
                        <div class="title"><?php _e("Total Order",ST_TEXTDOMAIN) ?></div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="huge">
                            <?php echo date_i18n('m/Y',strtotime($_custom_date['date_now'])) ?>
                        </div>
                        <div class="title"><?php _e("Date",ST_TEXTDOMAIN) ?></div>
                    </div>
                </div>
            </div>
            <a href="<?php echo  esc_url( add_query_arg( array('sc'=>'dashboard') , get_the_permalink() ) ) ?>">
                <div class="panel-footer">
                    <span class="pull-left"><?php _e("View All",ST_TEXTDOMAIN) ?></span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-left"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<?php
$this_month = STUser_f::st_get_data_reports_partner('all','custom_date',$start,$end);
$data_js = STUser_f::_conver_array_to_data_js_reports($this_month['post_type'][$post_type]['date'],'all','custom');
?>
<div class="st_div_canvas div_single_custom">
    <div class="head_reports head-<?php echo STInput::request('type') ?>">
        <div class="head_control">
            <div class="head_time">
                <span class="btn_single_all_time"><?php _e("All Time",ST_TEXTDOMAIN) ?></span> /
                 <span class="btn_show_month_by_year" data-title="<?php _e("View",ST_TEXTDOMAIN) ?>" data-loading="<?php _e("Loading...",ST_TEXTDOMAIN) ?>" data-post-type="<?php echo esc_html($post_type) ?>" data-year="<?php echo esc_html($_custom_date['y']) ?>" href="javascript:;">
                        <?php echo esc_html($_custom_date['y']) ?>
                 </span> /
                <span class="active">
                     <?php
                     $dt = DateTime::createFromFormat('!m', $_custom_date['m']);
                     echo esc_html($dt->format('F'))
                     ?>
                </span>
            </div>
        </div>
    </div>
    <div class="st_div_canvas">
        <canvas id="canvas_this_month"></canvas>
    </div>
    <div class="st_bortlet box <?php echo STInput::request('type') ?>" data-type="<?php echo STInput::request('type') ?>">
        <div class="st_bortlet-title">
            <div class="caption"> <?php  echo esc_html($obj_post_type->labels->singular_name); ?>  <?php _e(" Statistic Details",ST_TEXTDOMAIN) ?> </div>
        </div>
        <div class="st_bortlet-body">
            <div class="table-scrollable">
                <table class="table table-bordered table-hover st_table_partner">
                    <thead>
                    <tr>
                        <th><?php _e("Date",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Item Sales Count",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Total Income",ST_TEXTDOMAIN) ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_js['data_array_php'] as $k=>$v): ?>
                        <tr>
                            <td><?php echo esc_html($v['title']) ?></td>
                            <td class="text-center"><?php echo esc_html($v['number_orders']); ?></td>
                            <td class="text-center"><?php
                                if($v['average_total'] > 0 ){
                                    echo TravelHelper::format_money($v['average_total']);
                                }else{
                                    echo "0";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tr class="bg-white">
                        <th>
                            <?php _e("Total",ST_TEXTDOMAIN) ?>
                        </th>
                        <td class="text-center">
                            <?php echo esc_html($data_js['info_total']['number_orders']); ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if($data_js['info_total']['average_total'] > 0){
                                echo TravelHelper::format_money($data_js['info_total']['average_total']);
                            }else {
                                echo "0";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="div_single_year">
    <?php
    $data_year = STUser_f::st_get_data_reports_partner_info_year($post_type);
    $data_year_js = STUser_f::_conver_array_to_data_js_reports($data_year,'all','year')
    ;?>
    <div class="st_div_canvas">
        <div class="head_reports head-<?php echo STInput::request('type') ?>">
            <div class="head_control">
                <div class="head_time bc_single">
                    <?php _e("All Time",ST_TEXTDOMAIN) ?>
                </div>
            </div>
        </div>
        <div class="st_div_item_canvas_year"><canvas id="canvas_year"></canvas></div>
    </div>
    <div class="st_bortlet box <?php echo STInput::request('type') ?>" data-type="<?php echo STInput::request('type') ?>">
        <div class="st_bortlet-title">
            <div class="caption"> <?php  echo esc_html($obj_post_type->labels->singular_name); ?>  <?php _e(" Statistic Details",ST_TEXTDOMAIN) ?> </div>
        </div>
        <div class="st_bortlet-body">
            <div class="table-scrollable">
                <table class="table table-bordered table-hover st_table_partner">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th><?php _e("Year",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Item Sales Count",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Total Income",ST_TEXTDOMAIN) ?></th>
                        <!--<th style="width: 85px;" class="text-center"><?php /*_e("Action",ST_TEXTDOMAIN) */?></th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1;
                    $total_price = 0;
                    $number_orders = 0;
                    foreach($data_year as $k=>$v):
                        $total_price += $v['average_total'];
                        $number_orders += $v['number_orders'];
                        ?>
                        <tr>
                            <td><?php echo esc_html($i) ?></td>
                            <td>
                            <span class="btn_show_month_by_year text-color" data-title="<?php _e("View",ST_TEXTDOMAIN) ?>" data-loading="<?php _e("Loading...",ST_TEXTDOMAIN) ?>" data-post-type="<?php echo esc_html($post_type) ?>" data-year="<?php echo esc_html($k) ?>" href="javascript:;">
                                <?php echo esc_html($k) ?>
                            </span>
                            </td>
                            <td class="text-center"><?php echo esc_html($v['number_orders']); ?></td>
                            <td class="text-center">
                                <?php
                                if($v['average_total'] > 0 ){
                                    echo TravelHelper::format_money($v['average_total']);
                                }else{
                                    echo "0";
                                }
                                ?>
                            </td>
                            <!--<td class="text-center">
                                <a class="btn default btn-xs green-stripe btn_show_month_by_year" data-title="<?php /*_e("View",ST_TEXTDOMAIN) */?>" data-loading="<?php /*_e("Loading...",ST_TEXTDOMAIN) */?>" data-post-type="<?php /*echo esc_html($post_type) */?>" data-year="<?php /*echo esc_html($k) */?>" href="javascript:;"> <?php /*_e("View",ST_TEXTDOMAIN) */?> </a>
                            </td>-->
                        </tr>
                        <?php $i++; endforeach;?>
                    </tbody>
                    <tr class="bg-white">
                        <th colspan="2">
                            <?php _e("Total",ST_TEXTDOMAIN) ?>
                        </th>
                        <td class="text-center">
                            <?php echo esc_html($number_orders); ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if($total_price > 0){
                                echo TravelHelper::format_money($total_price);
                            }else {
                                echo "0";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="div_single_month">
    <div class="st_div_canvas">
        <div class="head_reports head-<?php echo STInput::request('type') ?>">
            <div class="head_control">
                <div class="head_time bc_single"></div>
            </div>
        </div>
        <div class="st_div_item_canvas_month"></div>
    </div>
    <div class="st_bortlet box <?php echo STInput::request('type') ?>" data-type="<?php echo STInput::request('type') ?>">
        <div class="st_bortlet-title">
            <div class="caption"> <?php  echo esc_html($obj_post_type->labels->singular_name); ?>  <?php _e(" Statistic Details",ST_TEXTDOMAIN) ?> </div>
        </div>
        <div class="st_bortlet-body">
            <div class="table-scrollable">
                <table class="table table-bordered table-hover st_table_partner">
                    <thead>
                    <tr>
                        <th><?php _e("Month",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Item Sales Count",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Total Income",ST_TEXTDOMAIN) ?></th>
                        <!--<th style="width: 85px;" class="text-center"><?php /*_e("Action",ST_TEXTDOMAIN) */?></th>-->
                    </tr>
                    </thead>
                    <tbody class="data_month"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="div_single_day">
    <div class="st_div_canvas">
        <div class="head_reports head-<?php echo STInput::request('type') ?>">
            <div class="head_control">
                <div class="head_time bc_single"></div>
            </div>
        </div>
        <div class="st_div_item_canvas_day"></div>
    </div>
    <div class="st_bortlet box <?php echo STInput::request('type') ?>" data-type="<?php echo STInput::request('type') ?>">
        <div class="st_bortlet-title">
            <div class="caption"> <?php  echo esc_html($obj_post_type->labels->singular_name); ?>  <?php _e(" Statistic Details",ST_TEXTDOMAIN) ?> </div>
        </div>
        <div class="st_bortlet-body">
            <div class="table-scrollable">
                <table class="table table-bordered table-hover st_table_partner">
                    <thead>
                    <tr>
                        <th><?php _e("Month",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Item Sales Count",ST_TEXTDOMAIN) ?></th>
                        <th><?php _e("Total Income",ST_TEXTDOMAIN) ?></th>
                    </tr>
                    </thead>
                    <tbody class="data_day"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    var data_lable = <?php echo balanceTags($data_js['lable']) ?>;
    var data_sets = <?php echo balanceTags($data_js['data']) ?>;
    var lineChartData_total = {
        labels : data_lable,
        datasets : [
            {
                label: "",
                fillColor : "rgba(87, 142, 190, 0.5)",
                strokeColor : "rgba(87, 142, 190, 0.8)",
                pointColor : "rgba(87, 142, 190, 0.75)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(87, 142, 190, 1)",
                data : data_sets
            }
        ]
    };
    console.log(lineChartData_total);
    jQuery(function($){
        var ctx = document.getElementById("canvas_this_month").getContext("2d");
        new Chart(ctx).Line(lineChartData_total, {
            responsive: true,
            animationEasing: "easeOutBounce"
        });
    })
</script>
<script>
    var data_lable_year = <?php echo balanceTags($data_year_js['lable']) ?>;
    var data_sets_year = <?php echo balanceTags($data_year_js['data']) ?>;
    var lineChartData_total_year = {labels : data_lable_year,
        datasets : [{fillColor : "rgba(87, 142, 190, 0.5)", strokeColor : "rgba(87, 142, 190, 0.8)", pointColor : "rgba(87, 142, 190, 0.75)", pointStrokeColor : "#fff", pointHighlightFill : "#fff", pointHighlightStroke : "rgba(87, 142, 190, 1)", data : data_sets_year}]
    }
    jQuery(function($){
        /*var ctx_year = document.getElementById("canvas_year").getContext("2d");
        var $my_char = new Chart(ctx_year).Line(lineChartData_total_year, {
            responsive: true,
            animationEasing: "easeOutBounce"
        });*/
        $(document).on('click', '.btn_single_all_time', function () {
            setTimeout(function(){
                var ctx_year = document.getElementById("canvas_year").getContext("2d");
                var $my_char = new Chart(ctx_year).Line(lineChartData_total_year, {
                    responsive: true,
                    animationEasing: "easeOutBounce"
                });
            },500);
            $('.div_single_custom').hide();
        });
    })
</script>