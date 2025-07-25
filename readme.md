# MultiCurrency for WooCommerce

**Contributors:** jthemesstudio
**Tags:** WooCommerce, multi-currency, currency switcher, currency converter, ecommerce
**Tested up to:** 6.8
**Stable tag:** 1.0.0
**License:** GPL-2.0+
**License URI:** http://www.gnu.org/licenses/gpl-2.0.txt 

Add unlimited currencies to WooCommerce with a switcher, custom rates, and full checkout compatibility.

## Presentation

[**Documentation**](https://documentation.jthemesstudio.com/multi-currency-for-woocommerce/documents/getting-started/introduction/)

## Key Features

### Currency Control

* ⭐ **Add Unlimited Currencies** – Manage any number of currencies in your WooCommerce store.
* ⭐ **Fixed Price Control** – Manually set product prices for specific currencies (overrides auto-conversion).
* ⭐ **Live or Manual Exchange Rates** – Use real-time rates or enter your own custom conversions.
* ⭐ **Currency Symbol Customization** – Use custom icons or WooCommerce’s default symbols.
* ⭐ **Decimal & Position Settings** – Control decimal precision and symbol placement for each currency.
* ⭐ **Enable/Disable Currencies** – Easily toggle currencies on/off without removing their configuration.
* ⭐ **Extra Fee Support** – Add additional fees per currency to cover conversion or payment costs.

### Performance & Compatibility

* ⭐ **Use PHP Sessions** – Store selected currency using PHP sessions for fast switching and consistency.
* ⭐ **JavaScript-Based Switching** – No messy URL parameters – clean, SEO-friendly currency switching.
* ⭐ **Caching Compatibility Modes** – AJAX or JSON mode to work with WP Rocket, W3TC, WP Super Cache, etc.
* ⭐ **rel="nofollow" Support** – Prevent search engines from following currency switcher links for SEO control.

### GeoIP & Automation

* ⭐ **Auto Detect Currency** – Detect users’ location and switch currency automatically (via GeoIP or integration).
* ⭐ **Currency by Country Mapping** – Assign default currencies for specific countries.
* ⭐ **Fallback Control** – Set a default currency if no country or logic match is found.

### Checkout Compatibility

* ⭐ **Set Checkout Currency by Payment Method** – Automatically use the correct currency for gateways like PayPal, Razorpay, etc.
* ⭐ **Dynamic Currency Mapping** – Assign currencies to gateways with drag-and-assign simplicity.
* ⭐ **Real Payment Compatibility Alerts** – Prevent unsupported currencies per gateway.
* ⭐ **Fallback Currency Support** – Ensures a supported currency is always available for checkout.

### Switcher Design Options

* ⭐ **Customizable Switcher Title & Alignment**
* ⭐ **Multiple Layout Options**

  * Flag only
  * Symbol only
  * Code only
  * Flag + Symbol
  * Flag + Code
  * Flag + Code + Symbol
* ⭐ **Text & Background Color Control**
* ⭐ **Sidebar-Friendly Display**
* ⭐ **Shortcode Styling Support**
* ⭐ **Positioning via WooCommerce Hooks**
* ⭐ **Expandable Switcher Design & Button Styles**
* ⭐ **Custom Flags Mapping & CSS Support**

### Display Rules

* ⭐ **Hide Switcher on Specific Pages:**

  * Home
  * Shop
  * Single Product
  * Cart
  * Checkout
  * Product Categories
* ⭐ **Advanced Conditional Logic Support** – Use WordPress conditional tags like `is_home()` or `!is_page()` to control switcher display.

### Exchange Rate Auto Update

* ⭐ **Auto Update via WP Cron** – Automatically refresh rates on a schedule (in minutes).
* ⭐ **Multiple Rate Providers Supported:**

  * Yahoo Finance
  * Google Finance
  * Cuex
  * Wise
  * XE
  * Open Exchange Rates
  * ExchangeRate API
  * CurrencyAPI
  * Custom Provider (developer hook)
* ⭐ **Wise & Open Exchange Support (via Token / App ID)**
* ⭐ **Custom Decimal Precision & Notification Emails**

## Pro Features

* ⭐ **Estimated Price Display** – Show prices in a different currency without changing cart/store currency.
* ⭐ **Auto Currency Switch on Login** – Detect currency based on billing or shipping address.
* ⭐ **TranslatePress / Polylang Language Detection**
* ⭐ **Custom Country-Currency Mapping**
* ⭐ **Advanced Auto Currency Switch Mode**
* ⭐ **WPML Integration (Coming Soon)**

## Installation

1. Upload the plugin to `/wp-content/plugins/multicurrency-for-woocommerce`.
2. Activate via the "Plugins" menu in WordPress.
3. Go to **WooCommerce > Multi Currency** to configure settings.

## Frequently Asked Questions

### Is the plugin compatible with all themes?

Yes! It works out of the box with any WordPress theme and popular page builders.

### Can I manually set prices per product per currency?

Yes! Fixed pricing lets you fully control product prices in each currency.

### Does the plugin support caching plugins?

Absolutely. Choose between AJAX and JSON compatibility modes for seamless integration.

### Can I restrict certain currencies from the checkout?

Yes, with checkout currency mapping, only supported currencies per payment method are allowed.

## Screenshots

1. **Currency Switcher on Frontend** – Flag + symbol layout
2. **Switcher Color Settings** – Customize button, text, hover, and layout
3. **Checkout Currency Mapping Interface** – Drag and assign method
4. **Admin Panel Settings Overview**
5. **Exchange Rate Update Settings**
6. **Fixed Pricing Per Currency**
7. **Conditional Display Rules Example**
8. **Switcher Shortcode Example in Sidebar**

## Changelog

### 1.0.0

* Initial release
* Added fixed pricing, AJAX switching, checkout currency mapping, and auto exchange update system

## License

This plugin is licensed under the [GNU General Public License v2.0+](http://www.gnu.org/licenses/gpl-2.0.txt).