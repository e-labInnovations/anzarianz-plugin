<?php
global $wpdb;
$table_name = $wpdb->prefix . 'anzarianz_leaves';

$leave_query = "SELECT * FROM $table_name WHERE id = $leave_id";
$leave_data = $wpdb->get_results($leave_query)[0];
// $wpdb->query("update $table_name set status='read' WHERE id IN($leave_id)");

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
    <p><b>Message: </b> <br> <?php echo $leave_data->message ?></p>

    <p>
        <a href="<?php echo '?page='.$_GET['page'] ?>" class="button">Back</a>
        <a href="<?php echo '?page='.$_GET['page'].'&action=trash&leave_id='.$leave_data->id ?>" class="button">Trash</a>
        <a href="<?php echo '?page='.$_GET['page'].'&action=spam&leave_id='.$leave_data->id ?>" class="button">Spam</a>
    </p>
</div>