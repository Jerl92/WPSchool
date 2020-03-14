<?php

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
    <?php
    
    $get_groups_args = array(
        'post_type' => 'groups',
        'posts_per_page' => -1,
        'order' => 'ASC'
    );
    $get_groups = get_posts($get_groups_args);
    $get_user_group = get_user_meta( $user->ID, '_school_group', true );
    ?>
    <h3><?php _e("Group information", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="groups"><?php _e("Groups"); ?></label></th>
        <td> 
            <select name="groups">
            <option>Select a group</option>            
            <?php
            foreach ( $get_groups as $group ) {
                if ($get_user_group == $group->ID ) {
                    ?> <option value="<?php echo $group->ID; ?>" selected><?php echo $group->post_title ?></option> <?php
                } else {
                    ?> <option value="<?php echo $group->ID; ?>"><?php echo $group->post_title ?></option> <?php
                }
            } ?>
            </select>
        </td>
    </tr>
    </table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    $i = 0;
    $new_group = get_post( intval( $_POST['groups'] ));

    $get_meta_user_group = get_user_meta( $user_id, '_school_group', true );
    $get_current_post_group_users = get_post_meta( intval($get_meta_user_group), '_user_in_group', true );
    $get_post_group_users = get_post_meta( $new_group->ID, '_user_in_group', true );

    if ( $get_meta_user_group != $_POST['groups'] ) {
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

    update_user_meta( $user_id, '_school_group', $new_group->ID );
}

add_action( 'admin_footer', 'remove_user_from_group' ); // Write our JS below here

function remove_user_from_group() { ?>
	<script type="text/javascript" >
    function remove_user_group_js($) {
        $(".remove_user_group_btn").on( "click", function(event) {
		event.preventDefault();

            var data = {
                'action': 'remove_user_group',
                'user_id': $(this).attr('user-id'),
                'group_id': $(this).attr('group-id')
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                console.log(response);
            });

        });
	}
    jQuery(document).ready(function($) {
        remove_user_group_js($);
    });
	</script> <?php
}

add_action( 'wp_ajax_remove_user_group', 'remove_user_group' );

function remove_user_group() {
	global $wpdb; // this is how you get access to the database

    $userid = intval( $_POST['user_id'] );
    
    $groupid = intval( $_POST['group_id'] );

    $html[] = $userid;
    $html[] .= $groupid;

    $i = 0;
    $get_meta_user_group = get_user_meta( $userid, '_school_group', true );
    $get_current_post_group_users = get_post_meta( intval($groupid), '_user_in_group', true );

    foreach ($get_current_post_group_users as $get_current_post_group_user) {
        if ($get_current_post_group_user == $userid) {
            array_splice($get_current_post_group_users, $i, 1);
            if ($get_current_post_group_users) {
                update_post_meta( intval($groupid), '_user_in_group', $get_current_post_group_users );
            } else {
                delete_post_meta( intval($groupid), '_user_in_group' );
            }
        }
        $i++;
    }


    delete_user_meta( $userid, '_school_group' );

    return wp_send_json ( $html );
}

?>