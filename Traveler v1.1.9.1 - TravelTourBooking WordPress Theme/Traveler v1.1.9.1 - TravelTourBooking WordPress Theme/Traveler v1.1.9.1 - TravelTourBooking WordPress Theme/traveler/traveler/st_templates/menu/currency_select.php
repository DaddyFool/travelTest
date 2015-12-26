<?php 
// currency dropdown
// from 1.1.9

if (empty($container)){$container = "div"; }
if (empty($class)) {$class = "nav-drop nav-symbol" ;}

$is_show = st()->get_option('show_booking_primary_currency','on'); ?>

<?php if($is_show == 'on'): ?>
<?php echo balanceTags('<'.$container.' class="'.$class.'">'); ?>
    <a class="cursor" ><?php $current_currency=TravelHelper::get_current_currency();
            if(isset($current_currency['name']))
            {
                echo esc_html( $current_currency['name']);

            }
            if(isset($current_currency['symbol']))
            {
                echo esc_html(' '.$current_currency['symbol']);

            }

        ?><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></a>
    <ul class="list nav-drop-menu">
        <?php $currency =TravelHelper::get_currency();
            if(!empty($currency)){
                foreach($currency as $key=>$value){
                    if($current_currency['name']!=$value['name'])
                    echo '<li><a href="'.esc_url(add_query_arg('currency',$value['name'])).'">'.$value['name'].'<span class="right">'.$value['symbol'].'</span></a>
                          </li>';
                }
            }
        ?>
    </ul>
<?php echo  balanceTags('</'.$container.'>') ;?>
<?php endif ?>