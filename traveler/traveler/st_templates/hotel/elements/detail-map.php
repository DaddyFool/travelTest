<div id="gmap_wrapper" class="st_list_map">
    <div class="content_map" style="height: <?php echo esc_html( $height ) ?>px">
        <div id="list_map" class="gmap3" style="height: <?php echo esc_html( $height ) ?>px; width: 100%"></div>
    </div>
    <div class="st-gmap-loading-bg"></div>
    <div id="st-gmap-loading"><?php _e( 'Loading Maps' , ST_TEXTDOMAIN ); ?>
        <div class="spinner spinner_map ">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <div class="gmap-controls hidden">
        <input type="text" id="google-default-search" name="google-default-search" placeholder="<?php _e( 'Google Maps Search' , 'wpestate' );?>" value="" class="advanced_select  form-control">
    </div>
</div>
<div class="data_content hidden">
    <?php
    $data_map[0]['content_html']  = str_ireplace("'",'"',$data_map[0]['content_html']);
    echo balanceTags($data_map[0]['content_html']) ?>
</div>
<?php
$data_map       = json_encode( $data_map , JSON_FORCE_OBJECT );
$data_style_map = '[{featureType: "road.highway",elementType: "geometry",stylers: [{ hue: "#ff0022" },{ saturation: 60 },{ lightness: -20 }]}]';
?>
<script type="text/javascript">
    var my_div_map = jQuery('#list_map');
    var data_show = <?php echo balanceTags($data_map) ?>;
    var map_height =<?php echo esc_html($height) ?>;
    var style_map = <?php echo balanceTags($data_style_map)?>;

    jQuery(function ($) {
        $(document).on("click",".ui-tabs-anchor",function() {
            $('#list_map').gmap3({
                action: 'destroy'
            });
            var container = $('#list_map').parent();
            $('#list_map').remove();
            container.append('<div id="list_map"></div>');
            $('#list_map').height(<?php echo esc_html($height) ?>);
            init_list_map($('#list_map'),<?php echo balanceTags($data_map) ?>, <?php echo esc_html($location_center) ?>,<?php echo esc_html($zoom) ?>, <?php echo balanceTags($data_style_map) ?>);
        });
        $(document).on("click",".vc_tta-tab",function() {
            $('#list_map').gmap3({
                action: 'destroy'
            });
            var container = $('#list_map').parent();
            $('#list_map').remove();
            container.append('<div id="list_map"></div>');
            $('#list_map').height(<?php echo esc_html($height) ?>);
            init_list_map($('#list_map'),<?php echo balanceTags($data_map) ?>, <?php echo esc_html($location_center) ?>,<?php echo esc_html($zoom) ?>, <?php echo balanceTags($data_style_map) ?>);
        });
        init_list_map(my_div_map, data_show, <?php echo esc_html($location_center) ?>, <?php echo esc_html($zoom) ?>, <?php echo balanceTags($data_style_map) ?>);
        function init_list_map(div_map, data_map, map_center, data_zoom, style_map) {
            var map = div_map;
            var list = [];
            for (var key in data_map) {
                var tmp_data = data_map[key];
                list.push({
                    latLng: [tmp_data.lat, tmp_data.lng],
                    options: {
                        icon: tmp_data.icon_mk,
                        animation: google.maps.Animation.DROP
                    },
                    tag: "st_tag_" + tmp_data.id,
                    data: tmp_data
                });
            }
            data_zoom = parseInt(data_zoom);
            map.gmap3({
                map: {
                    options: {
                        center: map_center,
                        zoom: data_zoom,
                        mapTypeId: google.maps.MapTypeId.TERRAIN,
                        styles: style_map,
                        navigationControl: true,
                        scrollwheel: true,
                        streetViewControl: true,
                        scaleControl: true,
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DEFAULT,
                            mapTypeIds: [
                                google.maps.MapTypeId.ROADMAP,
                                google.maps.MapTypeId.TERRAIN
                            ]
                        },
                        disableDefaultUI: true,
                        zoomControl: true,
                        zoomControlOptions: {
                            style: google.maps.ZoomControlStyle.SMALL
                        }
                    },
                    events: {
                        zoom_changed: function (map) {
                            $(this).attr('data-zoom', map.getZoom());
                        },
                        tilesloaded:function(map){
                            jQuery('#st-gmap-loading').fadeOut(700);
                            jQuery('.st-gmap-loading-bg').fadeOut(700);

                        }
                    }
                },
                circle:{
                    options: {
                        center: <?php echo esc_html($location_center) ?>,
                        radius : <?php echo esc_attr($range*1000) ?>,
                        fillColor : "#008BB2",
                        strokeColor : "transparent"
                    }
                },
                overlay:{
                    latLng: <?php echo esc_html($location_center) ?>,
                    options:{
                        content:  $('.data_content').html(),
                        offset:{
                            y: -210,
                            x: 20
                        }
                    }
                },
                marker: {
                    values: list,
                    events: {
                        mouseover: function (marker, event, context) {
                            icon_tmp = (marker.icon);
                        },
                        mouseout: function (marker, event, context) {
                        },
                        click: function (marker, event, context) {
                            var zoom = parseInt(map.attr('data-zoom'));
                            if (!zoom)zoom = data_zoom;
                            var map_g = $(this).gmap3("get");
                            map_g.panTo(marker.getPosition());
                            $(this).gmap3({clear: "overlay"}, {overlay: {pane: "floatPane", latLng: marker.getPosition(), options: {content: context.data.content_html, offset: {x: 20, y: -210}}}});
                        }

                    }
                }
            });
        }
    });
</script>