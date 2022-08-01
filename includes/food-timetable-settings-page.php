<?php

function anzarianz_food_timetable_settings_html() {
    $food_timetable_data = get_option('anzarianz_food_timetable_timings',array(
        'breakfast' => array('start' => '08:00', 'end' => '09:00'),
        'lunch'     => array('start' => '12:00', 'end' => '14:00'),
        'tea'       => array('start' => '15:00', 'end' => '17:00'),
        'dinner'    => array('start' => '19:00', 'end' => '20:00')
    ));
?>
    <div class="wrap">    
        <h2>Food Timetable Settings</h2>
        <?php
            if($_POST['justsubmitted'] == 'true') {
                if(wp_verify_nonce($_POST['afttNonce'], 'save_anzarianz_food_timetable_timings') && current_user_can('manage_options')) {
                    $food_timetable_data['breakfast']['start'] = $_POST['breakfast_start'];
                    $food_timetable_data['breakfast']['end']   = $_POST['breakfast_end'];
                    $food_timetable_data['lunch']['start']     = $_POST['lunch_start'];
                    $food_timetable_data['lunch']['end']       = $_POST['lunch_end'];
                    $food_timetable_data['tea']['start']       = $_POST['tea_start'];
                    $food_timetable_data['tea']['end']         = $_POST['tea_end'];
                    $food_timetable_data['dinner']['start']    = $_POST['dinner_start'];
                    $food_timetable_data['dinner']['end']      = $_POST['dinner_end'];
                    
                    update_option('anzarianz_food_timetable_timings', $food_timetable_data);
                    ?>
                    <div class="notice notice-success is-dismissible"> 
                        <p><strong>Settings saved.</strong></p>
                        <button type="button" class="notice-dismiss">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </div>
                    <?php
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
            <?php wp_nonce_field('save_anzarianz_food_timetable_timings', 'afttNonce') ?>
            <h2 class="title">Breakfast</h2>
            <table class="form-table">
                <tr>
                    <th><label for="breakfast_start">Starting Time</label></th>
                    <td>
                        <input type="time"
                        id="breakfast_start"
                        name="breakfast_start"
                        value="<?php echo $food_timetable_data['breakfast']['start'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="breakfast_end">Ending Time</label></th>
                    <td>
                        <input type="time"
                        id="breakfast_end"
                        name="breakfast_end"
                        value="<?php echo $food_timetable_data['breakfast']['end'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
            </table>
            
            <h2 class="title">Lunch</h2>
            <table class="form-table">
                <tr>
                    <th><label for="lunch_start">Starting Time</label></th>
                    <td>
                        <input type="time"
                        id="lunch_start"
                        name="lunch_start"
                        value="<?php echo $food_timetable_data['lunch']['start'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="lunch_end">Ending Time</label></th>
                    <td>
                        <input type="time"
                        id="lunch_end"
                        name="lunch_end"
                        value="<?php echo $food_timetable_data['lunch']['end'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
            </table>
            
            <h2 class="title">Tea</h2>
            <table class="form-table">
                <tr>
                    <th><label for="tea_start">Starting Time</label></th>
                    <td>
                        <input type="time"
                        id="tea_start"
                        name="tea_start"
                        value="<?php echo $food_timetable_data['tea']['start'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="tea_end">Ending Time</label></th>
                    <td>
                        <input type="time"
                        id="tea_end"
                        name="tea_end"
                        value="<?php echo $food_timetable_data['tea']['end'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
            </table>
            
            <h2 class="title">Dinner</h2>
            <table class="form-table">
                <tr>
                    <th><label for="dinner_start">Starting Time</label></th>
                    <td>
                        <input type="time"
                        id="dinner_start"
                        name="dinner_start"
                        value="<?php echo $food_timetable_data['dinner']['start'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
                <tr>
                    <th><label for="dinner_end">Ending Time</label></th>
                    <td>
                        <input type="time"
                        id="dinner_end"
                        name="dinner_end"
                        value="<?php echo $food_timetable_data['dinner']['end'] ?>"
                        class="regular-text"
                        />
                    </td>
                </tr>
            </table>

            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>
<?php
}