jQuery(document).ready(function($) {
    /*console.log(current_location );*/
    /*console.log(default_icon_gmap3_location);*/
    //console.log(list_post_type);
    /*var location_list = new Array();
    var map_zoom_location = current_location.location_map_zoom;
    var count_lat = 0;
    var count_lng = 0;
    for (var i = 0; i < list_post_type.length; i++) {

        if (list_post_type[i].post_type == 'st_cars') {
            icon = default_icon_gmap3_location.st_cars;
        }
        if (list_post_type[i].post_type == 'st_tours') {
            icon = default_icon_gmap3_location.st_tours;
        }
        if (list_post_type[i].post_type == 'st_hotel') {
            icon = default_icon_gmap3_location.st_hotel;
        }
        if (list_post_type[i].post_type == 'st_rental') {
            icon = default_icon_gmap3_location.st_rental;
        }
        if (list_post_type[i].post_type == 'st_activity') {
            icon = default_icon_gmap3_location.st_activity;
        }

        if (list_post_type[i].lat != "" && list_post_type[i].lng != "") {
            var data = '';
            location_list.push({
                'latLng': [list_post_type[i].lat, list_post_type[i].lng],
                'data': list_post_type[i].info,
                'options': {
                    'icon': icon
                }
            });
            count_lat += (parseFloat(list_post_type[i].lat));
            count_lng += (parseFloat(list_post_type[i].lng));

        }


    }
    var avg_lat = (count_lat / list_post_type.length);
    var avg_lng = (count_lng / list_post_type.length);

    lat_center = avg_lat;
    lng_center = avg_lng;



    $(".location_map").width("100%").height("500px");

    // fix tab boostrap

    $('.nav-tabs').on('shown.bs.tab', function() {
        var st_gmap3 = $(".location_map").gmap3({
            marker: {
                values: location_list,
                options: {
                    draggable: false,                    
                },
                events: { 
                    click: function(marker, event, context) {
                        var map = $(this).gmap3("get"),
                            infowindow = $(this).gmap3({
                                get: {
                                    name: "infowindow"
                                }
                            });
                        if (infowindow) {
                            infowindow.open(map, marker);
                            infowindow.setContent(context.data);
                        } else {
                            $(this).gmap3({
                                infowindow: {
                                    anchor: marker,
                                    options: {
                                        content: context.data
                                    }
                                }
                            });
                        }
                    }
                } ,
                cluster: {
                    radius: 100,
                    events: { // events trigged by clusters 
                        mouseover: function(cluster) {
                            $(cluster.main.getDOMElement()).css("border", "1px solid red");
                        },
                        mouseout: function(cluster) {
                            $(cluster.main.getDOMElement()).css("border", "0px");
                        },
                        click:function(cluster, event, data) {
                            
                          }
                    },
                    0: {
                        content: "<div class='cluster cluster-1'>CLUSTER_COUNT</div>",
                        width: 0,
                        height: 0
                    },
                    20: {
                        content: "<div class='cluster cluster-2'>CLUSTER_COUNT</div>",
                        width: 56,
                        height: 55
                    },
                    50: {
                        content: "<div class='cluster cluster-3'>CLUSTER_COUNT</div>",
                        width: 66,
                        height: 65
                    }
                }
            },
            trigger: "resize",
            map: {
                options: {
                    center: [current_location.map_lat, current_location.map_lng],
                    zoom: parseInt(map_zoom_location),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                        mapTypeIds: [
                                google.maps.MapTypeId.ROADMAP,
                                google.maps.MapTypeId.TERRAIN
                            ]
                    },
                }
            },
        });
    });*/
    $('.nav-tabs').on('shown.bs.tab', function(){
        console.log(current_location.location_map_zoom);
        var st_location_gmap3 = $("#list_map").gmap3({
            trigger: "resize",
            map: {
                options: {
                    center: [current_location.map_lat, current_location.map_lng],
                    zoom: parseInt(current_location.location_map_zoom)
                }
            }
        })
    });


});