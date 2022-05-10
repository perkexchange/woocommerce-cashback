=== Perk.Exchange Crypto Cashback ===
Contributors: perkexchange
Tags: kin, cryptocurrency
Requires at least: 5.8
Tested up to: 5.8.3
Stable tag: 1.0.0
Requires PHP: 7.0
License: MIT
License URI: https://opensource.org/licenses/MIT

This integration provides [KIN](https://kin.org/) cryptocurrency cashback for completed WooCommerce orders.

== Description ==

This plugin:

- Adds a new integration provided by https://perk.exchange

== Order Flow ==

- Customers pay for their order using any payment gateway and currency setup on the shop
- Once the order is paid the user is provisioned [KIN](https://kin.org) cryptocurrency according to the integration setup
- An email is sent to the user's billing email address with a link to pickup their KIN

== Requirements ==

1. [Campaign manager permissions](https://perkexchange.gitbook.io/docs/master) to be able to create a dedicated campaign
2. The campaign's **Campaign Secret**

== Configuration ==

1. Go to WooCommerce >> Settings >> Integrations
2. Click "Perk.Exchange Cashback"
3. Select **Enable Cashback** to activate the integration
4. Enter your **Campaign Secret** from Perk.Exchange.
5. Configure how much KIN to provide as cashback. Configure either Fixed Amount or Percent Amount and set the unused option to zero (0). Cashack is calculated regardless of currency in use:

- **Fixed Amount** Give a fixed amount of KIN to a user no matter the order total. For example, if the order total is `$24` and the fixed amount is `15` then the user receives `15 KIN`
- **Percent Amount** Give an amount of KIN based on a percentage of the order total. For example, if the order total is `$24` and the percent amount is set to `0.5` a total of `12 KIN` is given to the user.

7. Provide an optional message that is included in the email to the user
8. Click **Save Changes**

== Troubleshooting ==

1. **Campaign secret is invalid**

- Generate the campaign secret for your Campaign. Refer to https://perkexchange.gitbook.io/docs/master for more information.

2. **Store is not Internet accessible** or not accessible to https://perk.exchange

- Make the store reachable to The Internet or manually mark orders as completed.

== Changelog ==

= 1.0 =

- Initial public release.
