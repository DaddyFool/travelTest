<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST List Review", ST_TEXTDOMAIN),
            "base" => "st_list_review",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                array(
                    "type" => "textfield",
                    "heading" => __("Number", ST_TEXTDOMAIN),
                    "param_name" => "number",
                    "description" =>"",
                ),
            )
        ) );
    }

    if(!function_exists('st_vc_st_list_review')){
        function st_vc_st_list_review($arg,$content=false)
        {
            if(is_singular()){
                global $st_list_review_number;
                $data = shortcode_atts(array(
                    'number' =>5,
                ), $arg, 'st_list_review' );
                 extract($data);
                $st_list_review_number = $number;
                ob_start();
                comments_template( '/st_templates/vc-elements/st-list-review/html.php' );
                $html = @ob_get_clean();
                unset($st_list_review_number);
                return $html;
            }

        }
    }
    st_reg_shortcode('st_list_review','st_vc_st_list_review');
