<?php
/**
 * Display the next shipment date for the current recurring product plan with a shortcode in the frontend.
 *
 * @return string The next shipment date for the current recurring product plan.
 */
function display_next_shipment_date_shortcode() {
    global $product;

    if (empty($product) || $product->get_type() !== 'recurring_plan') {
        return '';
    }

    // Get the next shipment date.
    $next_shipment_date = get_next_recurring_shipment_date($product->get_id());

    // Format the date.
    $formatted_date = date_i18n(get_option('date_format'), strtotime($next_shipment_date));

    // Return the formatted date as a string.
    return '<p>' . sprintf(__('Next shipment date: %s', 'subscription-woocommerce-extension'), $formatted_date) . '</p>';
}

// Register the shortcode.
add_shortcode('next_shipment_date', 'display_next_shipment_date_shortcode');

/**
 * Display the switch recurring plan form with a shortcode in the frontend.
 *
 * @return string The next shipment date for the current recurring product plan.
 */
function switch_recurring_plan_shortcode() {
    global $product;
  
    if ( !is_a( $product, 'WC_Product' ) || $product->get_type() !== 'recurring_plan' ) {
      return;
    }
  
    $current_plan_id = get_post_meta( $product->get_id(), '_recurring_plan_current_plan_id', true );
    $available_plans = get_post_meta( $product->get_id(), '_recurring_plan_plans', true );
  
    if ( empty( $available_plans ) ) {
      echo '<p>No recurring plans have been created yet.</p>';
      return;
    }
  
    $available_plans = explode( '|', $available_plans );
  
    ob_start();
    ?>
    <form class="switch-recurring-plan-form" method="post">
      <p>Available plans:</p>
      <?php foreach ( $available_plans as $plan ) {
        $plan = explode( ',', $plan );
        $plan_id = $plan[0];
        $plan_name = $plan[1];
        $checked = ( $current_plan_id == $plan_id ) ? 'checked' : ''; ?>
        <label>
          <input type="radio" name="recurring_plan_id" value="<?php echo $plan_id; ?>" <?php echo $checked; ?>>
          <?php echo $plan_name; ?>
        </label><br>
      <?php } ?>
      <input type="submit" name="switch_recurring_plan" value="Save">
      <?php wp_nonce_field( 'switch_recurring_plan_nonce', 'switch_recurring_plan_nonce' ); ?>
    </form>
    <?php
    $output = ob_get_clean();
    echo $output;
  
    if ( isset( $_POST['switch_recurring_plan'] ) && isset( $_POST['recurring_plan_id'] ) && wp_verify_nonce( $_POST['switch_recurring_plan_nonce'], 'switch_recurring_plan_nonce' ) ) {
  
      $new_plan_id = $_POST['recurring_plan_id'];
  
      if ( !in_array( $new_plan_id, $available_plans ) ) {
        echo '<p>Invalid plan selection.</p>';
        return;
      }
  
      $new_plan = explode( ',', $new_plan_id );
      $new_plan_name = $new_plan[1];
      $new_plan_id = $new_plan[0];
  
      update_post_meta( $product->get_id(), '_recurring_plan_current_plan_id', $new_plan_id );
  
      $switch_date = date( 'Y-m-d H:i:s' );
      update_post_meta( $product->get_id(), '_recurring_plan_switch_date', $switch_date );
  
      $current_plan_name = get_post_meta( $product->get_id(), '_recurring_plan_current_plan_name', true );
      $message = sprintf( 'Recurring plan for product %s has been switched from %s to %s.', $product->get_name(), $current_plan_name, $new_plan_name );
  
      wp_mail( get_option( 'admin_email' ), 'Recurring plan switched', $message );
  
      // Handle changes to the recurring product and shipping schedule here
  
    }
  }
  

?>