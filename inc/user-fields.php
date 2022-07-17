<?php

//Showing the user field
add_action( 'show_user_profile', 'anzarianz_fields' );
add_action( 'edit_user_profile', 'anzarianz_fields' );

function anzarianz_fields( $user ) {
	$room_no = get_the_author_meta( 'room_no', $user->ID );
	$room_type = get_the_author_meta( 'room_type', $user->ID );
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
			<th><label for="room_type"><?php esc_html_e( 'Is single room?', 'anzarianz' ); ?></label></th>
			<td>
                <select name="room_type" id="room_type">
                    <option value="normal" <?php echo $room_type == 'normal'?'selected':''; ?>>Normal</option>
                    <option value="single" <?php echo $room_type == 'single'?'selected':''; ?>>Single</option>
                </select>
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

	// if ( empty( $_POST['year_of_birth'] ) ) {
	// 	$errors->add( 'year_of_birth_error', __( '<strong>ERROR</strong>: Please enter your year of birth.', 'crf' ) );
	// }
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