<?php
/**
 * Plugin Name: TP Subscription Tiers
 * Plugin URI: https://trafficportal.com
 * Description: Displays service tiers for link shortening website
 * Version: 1.0.0
 * Author: Traffic Portal
 * License: GPL v2 or later
 * Text Domain: tp-subscription-tiers
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TP_SUB_VERSION', '1.0.0');
define('TP_SUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TP_SUB_PLUGIN_URL', plugin_dir_url(__FILE__));

class TPSubscriptionTiers {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_shortcode('tp_subscription_tiers', array($this, 'render_subscription_tiers'));
        add_action('wp_ajax_tp_get_tiers', array($this, 'ajax_get_tiers'));
        add_action('wp_ajax_nopriv_tp_get_tiers', array($this, 'ajax_get_tiers'));
    }
    
    public function init() {
        $this->create_default_options();
    }
    
    private function create_default_options() {
        $default_tiers = get_option('tp_subscription_tiers');
        if (!$default_tiers) {
            $default_tiers = array(
                'basic' => array(
                    'name' => 'Basic',
                    'price' => '0',
                    'billing_period' => 'month',
                    'features' => array(
                        '10 Short Links/month',
                        '1 QR Code/month',
                        'Basic Analytics',
                        'Standard Support'
                    ),
                    'recommended' => false
                ),
                'simple' => array(
                    'name' => 'Simple',
                    'price' => '19',
                    'billing_period' => 'month',
                    'features' => array(
                        '100 Short Links/month',
                        '10 QR Codes/month',
                        'Advanced Analytics',
                        'Custom Branded Links',
                        'Priority Support',
                        'API Access'
                    ),
                    'recommended' => true
                ),
                'pro' => array(
                    'name' => 'Pro',
                    'price' => '49',
                    'billing_period' => 'month',
                    'features' => array(
                        'Unlimited Short Links',
                        'Unlimited QR Codes',
                        'Full Analytics Suite',
                        'Custom Branded Links',
                        'White Label Options',
                        'Dedicated Support',
                        'Full API Access',
                        'Team Collaboration'
                    ),
                    'recommended' => false
                )
            );
            update_option('tp_subscription_tiers', $default_tiers);
        }
        
        $usage_pricing = get_option('tp_usage_pricing');
        if (!$usage_pricing) {
            $usage_pricing = array(
                'extra_links' => array(
                    'price' => '5',
                    'amount' => '50',
                    'unit' => 'links'
                ),
                'extra_qr' => array(
                    'price' => '10',
                    'amount' => '20',
                    'unit' => 'QR codes'
                )
            );
            update_option('tp_usage_pricing', $usage_pricing);
        }
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('tp-subscription-style', TP_SUB_PLUGIN_URL . 'assets/style.css', array(), TP_SUB_VERSION);
        wp_enqueue_script('tp-subscription-script', TP_SUB_PLUGIN_URL . 'assets/script.js', array(), TP_SUB_VERSION, true);
        wp_localize_script('tp-subscription-script', 'tp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tp_subscription_nonce')
        ));
    }
    
    public function admin_enqueue_scripts($hook) {
        if ($hook != 'toplevel_page_tp-subscription-tiers') {
            return;
        }
        wp_enqueue_style('tp-subscription-admin', TP_SUB_PLUGIN_URL . 'assets/admin.css', array(), TP_SUB_VERSION);
        wp_enqueue_script('tp-subscription-admin', TP_SUB_PLUGIN_URL . 'assets/admin.js', array('jquery'), TP_SUB_VERSION, true);
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Subscription Tiers',
            'Subscription Tiers',
            'manage_options',
            'tp-subscription-tiers',
            array($this, 'admin_page'),
            'dashicons-money-alt',
            30
        );
    }
    
    public function admin_page() {
        require_once TP_SUB_PLUGIN_DIR . 'admin/admin-page.php';
    }
    
    public function render_subscription_tiers($atts) {
        $atts = shortcode_atts(array(
            'show_billing_toggle' => 'true'
        ), $atts);
        
        ob_start();
        require TP_SUB_PLUGIN_DIR . 'templates/subscription-tiers.php';
        return ob_get_clean();
    }
    
    public function ajax_get_tiers() {
        check_ajax_referer('tp_subscription_nonce', 'nonce');
        
        $tiers = get_option('tp_subscription_tiers', array());
        $usage_pricing = get_option('tp_usage_pricing', array());
        
        wp_send_json_success(array(
            'tiers' => $tiers,
            'usage_pricing' => $usage_pricing
        ));
    }
}

new TPSubscriptionTiers();