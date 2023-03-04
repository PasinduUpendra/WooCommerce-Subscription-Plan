<?php 
/**
 * Add "Recurring Plan" product type.
 */
function add_recurring_plan_product_type() {
    // Declare the new product type.
    $product_type = 'recurring_plan';

    // Register the new product type.
    if ( ! class_exists( 'WC_Product_Type' ) ) {
        return;
    }

    class WC_Product_Recurring_Plan extends WC_Product {
        public function __construct( $product ) {
            $this->product_type = $product_type;
            parent::__construct( $product );
        }
    }

    add_filter( 'product_type_selector', 'add_recurring_plan_to_product_type_selector' );
}

/**
 * Add the "Recurring Plan" product type to the product type selector.
 *
 * @param array $types The product types.
 * @return array The modified product types.
 */
function add_recurring_plan_to_product_type_selector( $types ) {
    $types[ 'recurring_plan' ] = __( 'Recurring Plan', 'subscription-woocommerce-extension' );
    return $types;
}

add_action( 'init', 'add_recurring_plan_product_type' );

/**
 * Add meta fields for the "Recurring Plan" product type.
 */
function add_recurring_plan_meta_fields() {
    // Add meta fields.
    woocommerce_wp_text_input(
        array(
            'id'          => '_recurring_plan_starting_price',
            'label'       => __( 'Plan Starting Price', 'subscription-woocommerce-extension' ),
            'description' => __( 'Enter the starting price for the recurring plan.', 'subscription-woocommerce-extension' ),
            'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // Add a checkbox field to allow the buyers to pause/resume their plan.
    woocommerce_wp_checkbox(
        array(
            'id'          => '_allow_pause_resume',
            'label'       => __( 'Allow Buyers to Pause/Resume Plan', 'subscription-woocommerce-extension' ),
            'description' => __( 'Check this box to allow buyers to pause or resume the plan.', 'subscription-woocommerce-extension' ),
            'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // Add a text input field to enter the grace period before the buyers can pause their plans.
    woocommerce_wp_text_input(
        array(
            'id'          => '_plan_halt_grace_period',
            'label'       => __( 'Plan Halt Grace Period', 'subscription-woocommerce-extension' ),
            'description' => __( 'Enter the number of days buyers are allowed to pause the plan before their next recurring shipment.', 'subscription-woocommerce-extension' ),
            'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // Add a text input field for the switching grace period
    woocommerce_wp_text_input(
        array(
            'id'          => '_switching_grace_period',
            'label'       => __( 'Switching Grace Period', 'subscription-woocommerce-extension' ),
            'description' => __( 'Enter the number of days buyers are allowed to switch to a different plan before their next shipment.', 'subscription-woocommerce-extension' ),
            'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // Add a select dropdown list for the recurring shipment order day
    woocommerce_wp_select(
        array(
            'id'          => '_recurring_shipment_order_day',
            'label'       => __( 'Recurring Shipment Order Day', 'subscription-woocommerce-extension' ),
            'description' => __( 'Select how many days before an order should be created for the next shipment from the next recurring product shipment date.', 'subscription-woocommerce-extension' ),
            'desc_tip'    => true,
            'options'     => array(
                'same_day' => __( 'Create on the Same Day', 'subscription-woocommerce-extension' ),
                'one_day_before' => __( 'Create One Day Before', 'subscription-woocommerce-extension' ),
                'three_days_before' => __( 'Create Three Days Before', 'subscription-woocommerce-extension' ),
                'one_week_before' => __( 'Create One Week Before', 'subscription-woocommerce-extension' ),
            ),
            'wrapper_class' => 'form-row form-row-full',
        )
    );

    // Create a new options group and add a text input for the welcome product name
    echo '<div class="options_group">';

    echo '<p class="form-field">';
    woocommerce_wp_text_input(
        array(
            'id'          => '_welcome_product_name',
            'label'       => __( 'Welcome Product Name', 'subscription-woocommerce-extension' ),
            'description' => __( 'Enter the name of the welcome product.', 'subscription-wosubscription-woocommerce-extension' ),
            echo '</p>';
            echo '<div class="options_group">';
            echo '<p class="form-field">';
            woocommerce_wp_text_input(
                array(
                    'id'          => '_welcome_product_name',
                    'label'       => __( 'Welcome Product Name', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Enter the name of the welcome product.', 'subscription-woocommerce-extension' ),
                )
            );
        )
    );
        echo '</p>';
    echo '</div>';
}

// Add a custom meta field for the "Recurring Plan" product type.
add_action( 'woocommerce_product_options_general_product_data', 'add_recurring_plan_tab_fields' );
function add_recurring_plan_tab_fields() {
    global $product_object;

    // Only for "Recurring Plan" product type.
    if ( $product_object->get_type() !== 'recurring_plan' ) {
        return;
    }

    // Add new "Recurring Plans" tab.
    ?>
    <div id="recurring_plans" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_text_input( array(
                'id'          => '_recurring_plan_values',
                'label'       => __( 'Recurring Plan Values', 'subscription-woocommerce-extension' ),
                'description' => __( 'Enter the values for the recurring plan separated by a vertical bar "|" character.', 'subscription-woocommerce-extension' ),
                'desc_tip'    => true,
                'wrapper_class' => 'form-row form-row-full',
            ) );
            ?>
        </div>
    </div>
    <?php
}

// Save the custom meta field for the "Recurring Plan" product type.
add_action( 'woocommerce_admin_process_product_object', 'save_recurring_plan_tab_fields', 10, 1 );
function save_recurring_plan_tab_fields( $product ) {
    // Only for "Recurring Plan" product type.
    if ( $product->get_type() !== 'recurring_plan' ) {
        return;
    }

    $values = isset( $_POST['_recurring_plan_values'] ) ? sanitize_text_field( $_POST['_recurring_plan_values'] ) : '';
    $product->update_meta_data( '_recurring_plan_values', $values );
}


/**
 * Add meta fields for each recurring plan under "Recurring Plans" tab.
 */
function add_recurring_plan_details_meta_fields() {
    global $post;
    $recurring_plans = get_post_meta( $post->ID, '_recurring_plans', true );
    if ( $recurring_plans ) {
        foreach ( $recurring_plans as $key => $plan ) {
            echo '<div class="recurring-plan">';
            echo '<h3>Recurring Plan ' . ( $key + 1 ) . '</h3>';

            woocommerce_wp_text_input(
                array(
                    'id'          => '_recurring_plan_short_description_' . $key,
                    'label'       => __( 'Plan Short Description', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Enter the short description for the recurring plan.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'wrapper_class' => 'form-row form-row-full',
                    'value'       => isset( $plan['short_description'] ) ? $plan['short_description'] : '',
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id'          => '_recurring_plan_product_name_' . $key,
                    'label'       => __( 'Plan Recurring Product Name', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Enter the name for the recurring product.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'wrapper_class' => 'form-row form-row-full',
                    'value'       => isset( $plan['product_name'] ) ? $plan['product_name'] : '',
                )
            );

            woocommerce_wp_media_input(
                array(
                    'id'          => '_recurring_plan_product_image_' . $key,
                    'label'       => __( 'Plan Recurring Product Image', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Upload or select the image for the recurring product.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'wrapper_class' => 'form-row form-row-full',
                    'value'       => isset( $plan['product_image'] ) ? $plan['product_image'] : '',
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id'          => '_recurring_plan_product_sku_' . $key,
                    'label'       => __( 'Plan Recurring Product SKU', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Enter the SKU for the recurring product.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'wrapper_class' => 'form-row form-row-full',
                    'value'       => isset( $plan['product_sku'] ) ? $plan['product_sku'] : '',
                )
            );

            woocommerce_wp_select(
                array(
                    'id'          => '_recurring_plan_interval_' . $key,
                    'label'       => __( 'Plan Recurring Interval', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Select the recurring interval for the plan.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'wrapper_class' => 'form-row form-row-full',
                    'options'     => array(
                        'week' => __( 'Every Week', 'subscription-woocommerce-extension' ),
                        '2_weeks' => __( 'Every Two Weeks', 'subscription-woocommerce-extension' ),
                        'month' => __( 'Every Month', 'subscription-woocommerce-extension' ),
                        '2_months' => __( 'Every Two Months', 'subscription-woocommerce-extension' ),
                        '3_months' => __( 'Every Three Months', 'subscription-woocommerce-extension' ),
                        '4_months' => __( 'Every Four Months', 'subscription-woocommerce-extension' ),
                        '6_months' => __( 'Every Six Months', 'subscription-woocommerce-extension' ),
                        ),
                    )
                );

             woocommerce_wp_text_input(
                array(
                    'id'          => '_recurring_price_' . $key,
                    'label'       => __( 'Recurring Price', 'subscription-woocommerce-extension' ),
                    'description' => __( 'Enter the recurring price for the plan.', 'subscription-woocommerce-extension' ),
                    'desc_tip'    => true,
                    'type'        => 'number',
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min'  => '0',
                    ),
                    'wrapper_class' => 'form-row form-row-full',
                )
            );

        echo '<button type="button" class="button delete_recurring_plan">' . __( 'Delete Plan', 'subscription-woocommerce-extension' ) . '</button>';
        echo '</div>';
    }
}

echo '</div>';
}
add_action( 'woocommerce_product_data_panels', 'add_recurring_plan_product_data_tab' );


add_action( 'woocommerce_process_product_meta', 'save_recurring_plan_meta_fields_data' );

function save_recurring_plan_meta_fields_data( $post_id ) {
    // Check if the product is a recurring plan.
    $product_type = get_post_meta( $post_id, '_product_type', true );
    if ( $product_type !== 'recurring_plan' ) {
        return;
    }

    // Save plan data.
    if ( isset( $_POST['_recurring_plans'] ) ) {
        $recurring_plans = array();
        $plans = explode( '|', sanitize_text_field( $_POST['_recurring_plans'] ) );
        foreach ( $plans as $plan ) {
            $plan_data = explode( ',', $plan );
            $recurring_plans[] = array(
                'short_description' => sanitize_text_field( $plan_data[0] ),
                'product_name' => sanitize_text_field( $plan_data[1] ),
                'product_image' => sanitize_text_field( $plan_data[2] ),
                'product_sku' => sanitize_text_field( $plan_data[3] ),
                'recurring_interval' => sanitize_text_field( $plan_data[4] ),
                'recurring_price' => sanitize_text_field( $plan_data[5] ),
            );
        }
        update_post_meta( $post_id, '_recurring_plans', $recurring_plans );
    }
}

/**Save recurring plan meta fields.
@param int $post_id The ID of the post being saved.
*/
function save_recurring_plan_meta_fields( $post_id ) {
if ( isset( $_POST['_recurring_plans'] ) ) {
$recurring_plans = array();

php
Copy code
 foreach ( $_POST['_recurring_plans'] as $key => $plan ) {
     $recurring_plans[] = array(
         'plan_short_description'   => sanitize_text_field( $plan['plan_short_description'] ),
         'recurring_product_name'   => sanitize_text_field( $plan['recurring_product_name'] ),
         'recurring_product_image'  => sanitize_text_field( $plan['recurring_product_image'] ),
         'recurring_product_sku'    => sanitize_text_field( $plan['recurring_product_sku'] ),
         'recurring_interval'       => sanitize_text_field( $plan['recurring_interval'] ),
         'recurring_price'          => floatval( $plan['recurring_price'] ),
     );
 }

 update_post_meta( $post_id, '_recurring_plans', $recurring_plans );
}
}
add_action( 'woocommerce_process_product_meta', 'save_recurring_plan_meta_fields' );
        
/**
 * Save meta fields for the "Recurring Plan" product type.
 *
 * @param int $post_id The ID of the current post.
 */
function save_recurring_plan_meta_fields( $post_id ) {
    $starting_price        = isset( $_POST['_recurring_plan_starting_price'] ) ? wc_clean( wp_unslash( $_POST['_recurring_plan_starting_price'] ) ) : '';
    $allow_pause_resume    = isset( $_POST['_allow_pause_resume'] ) ? 'yes' : 'no';
    $plan_halt_grace_period = isset( $_POST['_plan_halt_grace_period'] ) ? absint( $_POST['_plan_halt_grace_period'] ) : '';
    $switching_grace_period = isset( $_POST['_switching_grace_period'] ) ? absint( $_POST['_switching_grace_period'] ) : '';
    $shipment_order_day    = isset( $_POST['_recurring_shipment_order_day'] ) ? wc_clean( wp_unslash( $_POST['_recurring_shipment_order_day'] ) ) : '';
    $welcome_product_name  = isset( $_POST['_welcome_product_name'] ) ? wc_clean( wp_unslash( $_POST['_welcome_product_name'] ) ) : '';

    // Save meta data.
    update_post_meta( $post_id, '_recurring_plan_starting_price', $starting_price );
    update_post_meta( $post_id, '_allow_pause_resume', $allow_pause_resume );
    update_post_meta( $post_id, '_plan_halt_grace_period', $plan_halt_grace_period );
    update_post_meta( $post_id, '_switching_grace_period', $switching_grace_period );
    update_post_meta( $post_id, '_recurring_shipment_order_day', $shipment_order_day );
    update_post_meta( $post_id, '_welcome_product_name', $welcome_product_name );
}
add_action( 'woocommerce_process_product_meta_recurring_plan', 'save_recurring_plan_meta_fields' );

?>