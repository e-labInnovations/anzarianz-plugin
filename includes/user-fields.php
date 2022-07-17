<?php

//Showing the user field
add_action( 'show_user_profile', 'anzarianz_fields' );
add_action( 'edit_user_profile', 'anzarianz_fields' );
add_action( "user_new_form", "anzarianz_fields" );

function anzarianz_fields( $user ) {
	$room_no = get_the_author_meta( 'room_no', $user->ID );
	$room_type = get_the_author_meta( 'room_type', $user->ID );
	$mobile_no = get_the_author_meta( 'mobile_no', $user->ID );
	$permanent_address = get_the_author_meta( 'permanent_address', $user->ID );
	?>
	<h3><?php esc_html_e( 'Room Details', 'anzarianz' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="room_no"><?php esc_html_e( 'Room NO', 'anzarianz' ); ?></label></th>
			<td>
				<input type="text"
			       id="room_no"
			       name="room_no"
			       value="<?php echo esc_attr( $room_no ); ?>"
			       class="regular-text"
				/>
			</td>
		</tr>
		<tr>
			<th><label for="room_type"><?php esc_html_e( 'Room Type', 'anzarianz' ); ?></label></th>
			<td>
                <select name="room_type" id="room_type">
                    <option value="normal" <?php echo $room_type == 'normal'?'selected':''; ?>>Normal</option>
                    <option value="single" <?php echo $room_type == 'single'?'selected':''; ?>>Single</option>
                </select>
			</td>
		</tr>
	</table>

	<h3><?php esc_html_e( 'Contact Details', 'anzarianz' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="mobile_no"><?php esc_html_e( 'Mobile NO', 'anzarianz' ); ?></label></th>
			<td>
				<input type="text"
			       id="mobile_no"
			       name="mobile_no"
			       value="<?php echo esc_attr( $mobile_no ); ?>"
			       class="regular-text"
				/>
			</td>
		</tr>
		<tr>
			<th><label for="permanent_address"><?php esc_html_e( 'Permanent Address', 'anzarianz' ); ?></label></th>
			<td>
				<textarea name="permanent_address"
					id="permanent_address"
					rows="5"
					cols="30">
					<?php echo esc_attr( $permanent_address ); ?>
				</textarea>
			</td>
		</tr>
	</table>
    <?php
}

//Validating the field
add_action( 'user_profile_update_errors', 'anzarianz_user_profile_update_errors', 10, 3 );
function anzarianz_user_profile_update_errors( $errors, $update, $user ) {
	if ( $update ) {
		return;
	}
}

//Saving the field
add_action( 'personal_options_update', 'anzarianz_update_profile_fields' );
add_action( 'edit_user_profile_update', 'anzarianz_update_profile_fields' );

function anzarianz_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

    update_user_meta( $user_id, 'room_no', $_POST['room_no'] );
    update_user_meta( $user_id, 'room_type', $_POST['room_type'] );
}

?>