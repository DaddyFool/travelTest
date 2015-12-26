<div id='top_toolbar'>
    <div class='container'>
        <div class="row">
            <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 text-left left_topbar'>                
                    <?php 
                    $topbar_left = st()->get_option('topbar_left' , 'text');
                    switch ($topbar_left) {
                        case 'menu':
                            echo st()->load_template('menu/dropdown-menu' , null , array('menu'=>st()->get_option('topbar_left_menu')));
                            break;
                        default:
                            echo do_shortcode(st()->get_option('topbar_left_text'));
                            break;
                    }
                        
                    ?>                
            </div>
            <div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right right_topbar'>                
                    <?php 
                    $topbar_right = st()->get_option('topbar_right' , 'text');
                    switch ($topbar_right) {
                        case 'menu':
                            echo st()->load_template('menu/dropdown-menu' , null , array('menu'=>st()->get_option('topbar_right_menu')));
                            break;
                        default:
                            echo do_shortcode(st()->get_option('topbar_right_text'));
                            break;
                    }                        
                    ?>                
            </div>
        </div>
    </div>
</div>