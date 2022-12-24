<?php

function anzarianz_food_add_new_html() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_food_timetable';
    $food_id = isset($_GET['food_id'])?$_GET['food_id']:null;
    if($food_id) {
        $food_query = "SELECT * FROM $table_name WHERE id = $food_id";
        $food_data = $wpdb->get_results($food_query)[0];
        
        $primary_food = $food_data->primary_food;
        $secondary_food = $food_data->secondary_food;
        $time = $food_data->time;
        $day = $food_data->day;
        $guest_price = $food_data->guest_price;
    } else {
        $time = 'breakfast';
        $day = 7;
        $guest_price = 0;
    }
?>
    <div class="wrap">    
        <h2>Add Food</h2>
        <?php
            if(isset($_POST['justsubmitted'])?$_POST['justsubmitted']:false) {
                if(wp_verify_nonce($_POST['afanNonce'], 'save_anzarianz_food_add_new') && current_user_can('manage_options')) {
                    $primary_food = sanitize_text_field($_POST['primary_food']);
                    $secondary_food = sanitize_text_field($_POST['secondary_food']);
                    $time = sanitize_text_field($_POST['time']);
                    $day = intval(sanitize_text_field($_POST['day']));
                    $guest_price = intval(sanitize_text_field($_POST['guest_price']));
                    if(!$primary_food || !$secondary_food || !$time || !$day || !$guest_price) {
                        ?>
                        <div class="notice notice-error is-dismissible"> 
                            <p><strong>All fileds are required</strong></p>
                            <button type="button" class="notice-dismiss">
                                <span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
                        <?php
                    } else {
                        //Update to db
                        if($food_id) {
                            $wpdb_success = $wpdb->query("update $table_name set primary_food='$primary_food', secondary_food='$secondary_food', time='$time', day='$day', guest_price='$guest_price' WHERE id = $food_id");
                        } else {
                            $wpdb_success = $wpdb->insert($table_name, array(
                                'primary_food' => $primary_food,
                                'secondary_food' => $secondary_food,
                                'time' => $time,
                                'day' => $day,
                                'guest_price' => $guest_price
                            ));
                        }

                        if($wpdb_success) {
                            $page_url = menu_page_url("anzarianz_food_timetable");
                            wp_redirect($page_url);
                            echo("<script>location.href = '".$page_url."'</script>");
                            exit;
                        } else {
                            ?>
                            <div class="notice notice-error is-dismissible"> 
                                <p><strong>Database Error</strong></p>
                                <button type="button" class="notice-dismiss">
                                    <span class="screen-reader-text">Dismiss this notice.</span>
                                </button>
                            </div>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div class="notice notice-error is-dismissible"> 
                        <p><strong>Sorry you don't have permission to perform that apache_get_version</strong></p>
                        <button type="button" class="notice-dismiss">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </div>
                    <?php
                }
            }
        ?>
        <form method="post">
            <input type="hidden" name="justsubmitted" value="true">
            <?php wp_nonce_field('save_anzarianz_food_add_new', 'afanNonce') ?>
            <table class="form-table">
                <tr>
                    <th><label for="primary_food">Main Food</label></th>
                    <td>
                        <input type="text"
                        id="primary_food"
                        name="primary_food"
                        value="<?php echo $food_id?$primary_food:'' ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="secondary_food">Seconadry Food</label></th>
                    <td>
                        <input type="text"
                        id="secondary_food"
                        name="secondary_food"
                        value="<?php echo $food_id?$secondary_food:'' ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="time">Time</label></th>
                    <td>
                        <select name="time" id="time">
                            <option value="breakfast" <?php echo $time == 'breakfast'?'selected':''; ?>>Breakfast</option>
                            <option value="lunch" <?php echo $time == 'lunch'?'selected':''; ?>>Lunch</option>
                            <option value="tea" <?php echo $time == 'tea'?'selected':''; ?>>Tea</option>
                            <option value="dinner" <?php echo $time == 'dinner'?'selected':''; ?>>Dinner</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="day">Day</label></th>
                    <td>
                        <select name="day" id="day">
                            <option value="7" <?php echo $day == 7?'selected':''; ?>>Sunday</option>
                            <option value="1" <?php echo $day == 1?'selected':''; ?>>Monday</option>
                            <option value="2" <?php echo $day == 2?'selected':''; ?>>Tuesday</option>
                            <option value="3" <?php echo $day == 3?'selected':''; ?>>Wednesday</option>
                            <option value="4" <?php echo $day == 4?'selected':''; ?>>Thursday</option>
                            <option value="5" <?php echo $day == 5?'selected':''; ?>>Friday</option>
                            <option value="6" <?php echo $day == 6?'selected':''; ?>>Saturday</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="guest_price">Guest Price</label></th>
                    <td>
                        <input type="number"
                            id="guest_price"
                            name="guest_price"
                            value="<?php echo $food_id?$guest_price:0 ?>"
                            class="regular-text"
                        />
                    </td>
                </tr>
            </table>

            <input type="submit" name="submit" id="submit" class="button button-primary" value="Add">
        </form>
    </div>
<?php
}