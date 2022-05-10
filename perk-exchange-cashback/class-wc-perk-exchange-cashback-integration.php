<?php

/**
 * Give crypto as cashback for completed orders.
 *
 * @package  PerkExchange_Cashback_Integration
 * @category Integration
 * @author   Perk.Exchange <geoff.whittington@gmail.com>
 */
if (!class_exists('WC_Perk_Exchange_Cashback_Integration')) :
    class WC_Perk_Exchange_Cashback_Integration extends WC_Integration
    {
        /**
         * Init and hook in the integration.
         */
        public function __construct()
        {
            $this->id                 = 'wc_perkexchange_cashback-integration';
            $this->method_title       = __('Perk.Exchange Cashback');
            $this->method_description = __('Give crypto cashbacks.');

            $this->init_form_fields();
            $this->init_settings();

            $this->active    = $this->get_option('active');
            $this->fixed_amount    = $this->get_option('fixed_amount');
            $this->percent_amount    = $this->get_option('percent_amount');
            $this->message    = $this->get_option('message');
            $this->campaign_secret    = $this->get_option('campaign_secret');

            add_action('woocommerce_update_options_integration_' .  $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_order_status_completed', [$this, 'wc_perkexchange_cashback_order_status_completed']);
        }

        function admin_options()
        {
            if (!$this->wc_perkexchange_cashback_valid_campaign_secret_field()) {
                <p><?php _e(
                    "Campaign secret is not valid",
                    "perk-exchange-cashback-integration"
                ); ?></p>
        </div>
            <?php }
            if (!$this->wc_perkexchange_cashback_valid_fixed_amount_field()) {
                <p><?php _e(
                    "Fixed Amount is not valid",
                    "perk-exchange-cashback-integration"
                ); ?></p>
        </div>
            <?php }
            if (!$this->wc_perkexchange_cashback_valid_percent_amount_field()) {
                <p><?php _e(
                    "Percent Amount is not valid",
                    "perk-exchange-cashback-integration"
                ); ?></p>
        </div>
            <?php }
            parent::admin_options();
        }
        public function wc_perkexchange_cashback_valid_fixed_amount_field()
        {
            return ($this->get_option('fixed_amount') > 0 && $this->get_option('percent_amount') == 0) || ($this->get_option('fixed_amount') == 0 && $this->get_option('percent_amount') > 0);
        }
        public function wc_perkexchange_cashback_valid_percent_amount_field()
        {
            return ($this->get_option('fixed_amount') > 0 && $this->get_option('percent_amount') == 0) || ($this->get_option('fixed_amount') == 0 && $this->get_option('percent_amount') > 0);
        }
        public function wc_perkexchange_cashback_valid_campaign_secret_field()
        {
            $response = wp_remote_get(
                "https://perk.exchange/api/invoices", [
                "timeout" => 45,
                "redirection" => 5,
                "httpversion" => "1.0",
                "blocking" => true,
                "headers" => [
                "Authorization" => "Bearer " . $this->get_option('campaign_secret'),
                "Content-Type" => "application/json",
                ],
                ]
            );

            return !is_wp_error($response) && $response["response"]["code"] == 200;
        }

        function wc_perkexchange_cashback_order_status_completed($order_id)
        {
            if (!$this->active) {
                return;
            }

            $order = wc_get_order($order_id); // Order object

            if ($this->fixed_amount > 0) {
                $totalAmount = $this->fixed_amount;
            } else if ($this->percent_amount > 0) {
                $totalAmount = $this->percent_amount * $order->get_total();
            } else {
                $totalAmount = 15;
            }

            $billing_email = $order->get_billing_email();

            if (!$totalAmount || !$billing_email) {
                return;
            }

            $endpoint = 'https://perk.exchange/api/rewards';
            $token = $this->campaign_secret;

            if (empty($token)) {
                return;
            }

            $body = [
            "email" => $billing_email,
            "amount"  => $totalAmount,
            "message" => $this->message,
            ];
            $body = wp_json_encode($body);

            $response = wp_remote_post(
                $endpoint, array(
                "body"    => $body,
                "headers" => array(
                "Authorization" => "Bearer $token",
                "Content-Type" => "application/json",
                ),
                )
            );
        }

        /**
         * Initialize integration settings form fields.
         */
        public function init_form_fields()
        {
            $this->form_fields = array(
            "active" => [
            "title" => __("Enable/Disable", "wc_perkexchange_cashback-integration"),
            "type" => "checkbox",
            "label" => __("Enable Cashback", "wc_perkexchange_cashback-integration"),
            "default" => "yes",
            ],
            'campaign_secret' => array(
            'title'             => __('Campaign Secret'),
            'type'              => 'password',
            'description'       => __('A campaign secret key from Perk.Exchange'),
            'desc_tip'          => true,
            'default'           => '',
            'required'          => true,
            'css'      => 'width:170px;',
            ),
            'fixed_amount' => array(
            'title'             => __('Fixed Amount'),
            'type'              => 'number',
            'description'       => __('Give a specific amount of rewards. Set to 0 if using Percent Amount'),
            'desc_tip'          => true,
            'default'           => 100,
            'css'      => 'width:170px;',
            ),
            'percent_amount' => array(
            'title'             => __('Percent Amount'),
            'type'              => 'number',
            'description'       => __('Give an amount of rewards as a percentage of order payment. Set to 0 if using Fixed Amount'),
            'desc_tip'          => true,
            'default'           => 0,
            'css'      => 'width:170px;',
            ),
            'message' => array(
            'title'             => __('Message'),
            'type'              => 'textarea',
            'description'       => __('Include a custom message with the Reward email'),
            'desc_tip'          => true,
            'default'           => 'Thank-you for your business!',
            'css'      => 'width:170px;',
            ),
            );
        }
    }
endif;
