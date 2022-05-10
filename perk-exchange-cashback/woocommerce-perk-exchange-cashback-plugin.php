<?php

/**
 * Plugin Name: Perk.Exchange Cashback
 * Plugin URI: https://perk.exchange/
 * Description: Plugin to give crypto cashbacks for WooCommerce orders.
 * Author:  Perk.Exchange
 * Author URI: https://perk.exchange/
 * Version: 1.0
 */
if (!class_exists('WC_Perk_Exchange_Cashback_Plugin')) :
    class WC_Perk_Exchange_Cashback_Plugin
    {
        /**
         * Construct the plugin.
         */
        public function __construct()
        {
            add_action('plugins_loaded', array($this, 'init'));
        }
        /**
         * Initialize the plugin.
         */
        public function init()
        {
            // Checks if WooCommerce is installed.
            if (class_exists('WC_Integration')) {
                // Include our integration class.
                include_once 'class-wc-perk-exchange-cashback-integration.php';
                // Register the integration.
                add_filter('woocommerce_integrations', array($this, 'add_integration'));
            }
        }
        /**
         * Add a new integration to WooCommerce.
         */
        public function add_integration($integrations)
        {
            $integrations[] = 'WC_Perk_Exchange_Cashback_Integration';
            return $integrations;
        }
    }
    $WC_my_custom_plugin = new WC_Perk_Exchange_Cashback_Plugin(__FILE__);
endif;
