<?php
function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    $get_users_in_group = get_post_meta($object->ID, "_user_in_group", true);
    // query array
    $users = get_users();
    if( empty($users) )
    return;

    echo'<select name="user">';
        echo '<option value="">Select a user</option>';
        foreach( $users as $user ){
            echo '<option value="'.$user->data->ID.'">'.$user->data->display_name.'</option>';
        }
    echo'</select>';
    ?>
        <div>
            </br>
            <?php foreach ($get_users_in_group as $userid) {
                $user = get_user_by('id', $userid);
                echo $user->user_nicename;
                echo ' - ';
                echo $user->user_email;
                echo ' - ';
                echo '<span class="remove_user_group_btn" user-id="'.$userid.'" group-id="'.$object->ID.'">X</span>';
                echo '</br>';
            } ?>
        </div>
    <?php  
}
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Users in the group", "custom_meta_box_markup", "groups", "side", "high", null);
}
add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "groups";
    if($slug != $post->post_type)
        return $post_id;

    if(isset($_POST["user"]))
    {

        $i = 0;
        $user_id = $_POST["user"];
        $new_group = get_post( intval( $post_id ));

        $get_meta_user_group = get_user_meta( $user_id, '_school_group', true );
        $get_current_post_group_users = get_post_meta( intval($get_meta_user_group), '_user_in_group', true );
        $get_post_group_users = get_post_meta( $new_group->ID, '_user_in_group', true );

        if ($user_id != "") {
            if ( $get_meta_user_group != $post_id ) {
                foreach ($get_current_post_group_users as $get_current_post_group_user) {
                    if ($get_current_post_group_user == $user_id) {
                        array_splice($get_current_post_group_users, $i, 1);
                        if ($get_current_post_group_users) {
                            update_post_meta( intval($get_meta_user_group), '_user_in_group', $get_current_post_group_users );
                        } else {
                            delete_post_meta( intval($get_meta_user_group), '_user_in_group' );
                        }
                    }
                    $i++;
                }
            }

            if ($get_post_group_users) {
                if (!in_array($user_id, $get_post_group_users)) {
                    array_push($get_post_group_users, $user_id);
                    update_post_meta( $new_group->ID, '_user_in_group', $get_post_group_users );
                }
            } else {
                add_post_meta( $new_group->ID, '_user_in_group', [$user_id] );
            }

            if ($get_meta_user_group != null) {
                update_user_meta( $user_id, '_school_group', $new_group->ID );
            } else {
                add_user_meta( $user_id, '_school_group', $new_group->ID );
            }
        
        }

    }

}
add_action("save_post", "save_custom_meta_box", 10, 3);

function schedule_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    // The Query

    $myposts = get_posts( array(
        'posts_per_page' => -1,
        'post_type'         => 'classes'
    ) );
 
    if ( $myposts ) {
        echo'<select name="classes" id="classes_to_add">';
        echo '<option value="">Select a classes</option>';
        foreach ( $myposts as $post ) : 
            echo '<option value="'. $post->ID .'">' . $post->post_title . '</option>';
        endforeach;
        echo'</select>';
        wp_reset_postdata();
    }
    echo' - ';
    echo'<input type="number" name="day" id="day" value="">';
    echo' - ';
    echo'<input type="time" name="starttime" id="starttime" value="" style="width: 150px;">';
    echo' - ';
    echo'<input type="time" name="endtime" id="endtime" value="" style="width: 150px;">';
    echo' - ';
    echo'<button type="button" id="add_to_schedule">Add classe to schedule</button>';
    echo'</br>';

}
function add_schedule_meta_box()
{
    add_meta_box("demo-meta-box", "Schedule for the group", "schedule_meta_box_markup", "schedules", "normal", "high", null);
}
add_action("add_meta_boxes", "add_schedule_meta_box");

function save_schedule_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "schedules";
    if($slug != $post->post_type)
        return $post_id;

    if(isset($_POST["user"]))
    {

    }

}
add_action("save_post", "save_schedule_meta_box", 10, 3);

add_action( 'admin_footer', 'add_to_schedule_javascript' ); // Write our JS below here

function add_to_schedule_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
        $('#add_to_schedule').on('click', function(event) {

            var data = {
                'action': 'add_to_schedule',
                'classesid': document.getElementById("classes_to_add").value,
                'scheduleid': getUrlParameter('post'),
                'starttime': document.getElementById('starttime').value,
                'endtime': document.getElementById('endtime').value,
                'day': document.getElementById('day').value
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                console.log(data);
                document.getElementById("classes_to_add").options[0].selected=true
            });
        });
    });
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
	</script> <?php
}


add_action( 'wp_ajax_add_to_schedule', 'add_to_schedule' );

function add_to_schedule() {
	global $wpdb; // this is how you get access to the database

    $classesid = intval( $_POST['classesid'] );
    $scheduleid = intval( $_POST['scheduleid'] );    
    $starttime = $_POST['starttime'];    
    $endtime = $_POST['endtime'];
    $day = $_POST['day'];

    $classe = array(
        'classesid' => $classesid,
        'starttime' => $starttime,
        'endtime' => $endtime,
        'day' => $day,
    );

    $get_post_classes_in_schedule = get_post_meta( $scheduleid, '_classes_in_schedule', true );

    if ($classesid != '') {
        if ($get_post_classes_in_schedule) {
                array_push($get_post_classes_in_schedule, $classe);
                update_post_meta( $scheduleid, '_classes_in_schedule', $get_post_classes_in_schedule );
        } else {
            add_post_meta( $scheduleid, '_classes_in_schedule', [$classe] );
        }
    }

    return wp_send_json ( $classe ); 
}

function schedule_rending_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    // The Query

    $get_post_classes_in_schedule = get_post_meta( $object->ID, '_classes_in_schedule', true );
    if ( $get_post_classes_in_schedule ) {
        foreach ($get_post_classes_in_schedule as $classeid ) {
            $post = get_post($classeid['classesid']);
            $day[] = $classeid['day'];
            $days[] = array(
                'id'  =>  $classeid['classesid'],
                'day'  =>  $classeid['day'],
                'start'  =>  $classeid['starttime'],
                'end'  =>  $classeid['endtime'],
            );
        }
        $daymax = max($day);
        echo '<table style="width:100%;">';
        for ($x = 1; $x <= $daymax; null) {
                echo '<tr>';
                for ($i = 0; $i <= 4; $i++) {
                    echo '<th>';
                    if ($x <= $daymax) {
                        echo 'Day ' . $x . '';
                    }
                    echo '</th>';
                    $x++;
                }
                echo '</tr>';
                echo '<tr>';
                $x = $x - 5;
                for ($i = 0; $i <= 4; $i++) {
                        echo '<td>';
                        if ($x <= $daymax) {
                            foreach ($days as $day_) {
                                if ($x == $day_['day']) {
                                    $classe_post = get_post($day_['id']);
                                    echo $classe_post->post_title;
                                    echo ' - ';
                                    echo $day_['start'];
                                    echo ' - ';
                                    echo $day_['end'];
                                    echo ' - ';
                                    echo '</br>';
                                }
                            }
                        }
                        echo '</td>';
                        $x++;
                }
                echo '</tr>';
        }
        echo '</table>';
    }

}
function add_schedule_rending_meta_box()
{
    add_meta_box("schedule-meta-box", "schedule_rending for the group", "schedule_rending_meta_box_markup", "schedules", "normal", "high", null);
}
add_action("add_meta_boxes", "add_schedule_rending_meta_box");

function save_schedule_rending_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
    if(!current_user_can("edit_post", $post_id))
        return $post_id;
    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "schedules";
    if($slug != $post->post_type)
        return $post_id;

    if(isset($_POST["user"]))
    {

    }

}
add_action("save_post", "save_schedule_rending_meta_box", 10, 3);
?>