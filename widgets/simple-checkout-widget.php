<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Simple_Checkout_Widget extends Widget_Base {

    public function get_name() {
        return 'simple_checkout';
    }

    public function get_title() {
        return __('Simple Checkout', 'simple-checkout-widget');
    }

    public function get_icon() {
        return 'eicon-checkout';
    }

    public function get_categories() {
        return ['woocommerce-elements'];
    }

    protected function register_controls() {
        // Layout Section
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'simple-checkout-widget'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_order_summary',
            [
                'label' => __('Show Order Summary', 'simple-checkout-widget'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_coupon',
            [
                'label' => __('Show Coupon Field', 'simple-checkout-widget'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'simple-checkout-widget'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'simple-checkout-widget'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .simple-checkout-container' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'simple-checkout-widget'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .simple-checkout-container' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if (!class_exists('WooCommerce')) {
            echo 'WooCommerce must be installed and activated to use this widget.';
            return;
        }

        $settings = $this->get_settings_for_display();

        // Build container classes
        $container_classes = ['simple-checkout-container'];
        
        if ($settings['show_coupon'] === 'yes') {
            $container_classes[] = 'show-coupon';
        }
        if ($settings['show_order_summary'] === 'yes') {
            $container_classes[] = 'show-order-summary';
        }

        echo '<div class="' . esc_attr(implode(' ', $container_classes)) . '">';
        echo '<div class="woocommerce">';
        echo do_shortcode('[woocommerce_checkout]');
        echo '</div>';
        // Show [df-form] below checkout if user is not logged in
        if (!is_user_logged_in()) {
            echo '<div class="simple-checkout-df-form">';
            echo do_shortcode('[dm-login-modal]');
            echo '</div>';
        }
        echo '</div>';
    }
}
