<?php
global $wpdb;
$table_name = $wpdb->prefix . 'anzarianz_leaves';

$leave_query = "SELECT * FROM $table_name WHERE id = $leave_id";
$leave_data = $wpdb->get_results($leave_query)[0];
$action_nonce = wp_create_nonce( 'action_nonce' );

$user_info = get_userdata($leave_data->user_id);
$avatar = get_avatar_url($leave_data->user_id, ['size' => '96']);
if($avatar == '	http://1.gravatar.com/avatar/?s=96&d=mm&r=g') {
    $email_md5 = md5($user_info->email);
    $avatar = "https://www.gravatar.com/avatar/" . $email_md5 . ".jpg?s=96";
}

?>
<div class="wrap">    
    <h2><?php echo $leave_data->subject ?></h2>
    <img src="<?php echo $avatar ?>" class="avatar avatar-96 photo" height="96" width="96" loading="lazy" />
    <p><b>Name: </b> <?php echo $user_info->display_name ?></p>
    <p><b>Room NO: </b> <?php echo get_the_author_meta( 'room_no', $leave_data->user_id ); ?></p>
    <p><b>Reason: </b> <?php echo $leave_data->reason ?></p>
    <p><b>Leaving At: </b> <?php echo date('m-d-Y h:i A', strtotime($leave_data->leaving_at)) ?></p>
    <p><b>Rejoining At: </b> <?php echo date('m-d-Y h:i A', strtotime($leave_data->rejoining_at)) ?></p>
    <p><b>Status: </b> <?php echo $leave_data->status ?></p>

    <form action="" method="get">
        <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
        <input type="hidden" name="action" value="rejected" />
        <input type="hidden" name="leave_id" value="<?php echo $leave_id; ?>" />
        <input type="hidden" name="_wpnonce" value="<?php echo $action_nonce; ?>" />
        <label for="rejection_note">Rejection Note</label>
        </br>
        <textarea name="rejection_note" id="rejection_note" cols="50" rows="5"><?php echo $leave_data->rejection_note; ?></textarea>
        </br>
        <button type="submit" class="button"style="color: #d63638;border-color: #d63638;">Reject</button>
    </form>

    <p>
        <a href="<?php echo '?page='.$_GET['page'] ?>" class="button">Back</a>
        <a href="<?php echo '?page='.$_GET['page'].'&_wpnonce='.$action_nonce.'&action=trash&leave_id='.$leave_data->id ?>" class="button delete">Trash</a>
        <a href="<?php echo '?page='.$_GET['page'].'&_wpnonce='.$action_nonce.'&action=spam&leave_id='.$leave_data->id ?>" class="button">Spam</a>
        <a href="<?php echo '?page='.$_GET['page'].'&_wpnonce='.$action_nonce.'&action=approved&leave_id='.$leave_data->id ?>" class="button">Approve</a>
    </p>
    <div id='calendar'></div>
</div>