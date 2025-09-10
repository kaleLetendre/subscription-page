# TP Subscription Tiers WordPress Plugin

A WordPress plugin that displays customizable subscription tiers for a link shortening service.

## Features

- **Three Default Tiers**: Basic (Free), Simple ($19/month), Pro ($49/month)
- **Annual/Monthly Billing Toggle**: Users can switch between billing periods with automatic price updates
- **Usage-Based Extensions**: Add-on pricing for extra links and QR codes
- **Fully Customizable**: Admin interface to modify all tier details
- **Responsive Design**: Mobile-friendly layout inspired by Bitly's pricing page
- **Clean Styling**: Uses styling patterns from your tp_homepage.html

## Installation

1. Upload the `tp-subscription-tiers` folder to your WordPress `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your tiers in the admin panel under "Subscription Tiers"

## Usage

### Display Subscription Tiers

Add the shortcode to any page or post:

```
[tp_subscription_tiers]
```

### Shortcode Parameters

- `show_billing_toggle` - Show/hide the annual/monthly toggle (default: "true")

Example:
```
[tp_subscription_tiers show_billing_toggle="false"]
```

## Admin Configuration

Navigate to **WordPress Admin → Subscription Tiers** to:

- Edit tier names and prices
- Modify feature lists
- Set recommended tier
- Configure usage-based extensions
- Add/remove tiers dynamically

## Default Tier Structure

### Basic (Free)
- 10 Short Links/month
- 1 QR Code/month
- Basic Analytics
- Standard Support

### Simple ($19/month)
- 100 Short Links/month
- 10 QR Codes/month
- Advanced Analytics
- Custom Branded Links
- Priority Support
- API Access

### Pro ($49/month)
- Unlimited Short Links
- Unlimited QR Codes
- Full Analytics Suite
- Custom Branded Links
- White Label Options
- Dedicated Support
- Full API Access
- Team Collaboration

## Usage-Based Extensions

- **Extra Links**: +50 links for $5/month
- **Extra QR Codes**: +20 QR codes for $10/month

## JavaScript Features

- Dynamic billing period switching
- Modal confirmations for upgrades
- AJAX-ready for future integrations
- Smooth animations and transitions
- Loading states and success messages

## Styling

The plugin uses:
- Modern gradient effects for CTAs
- Clean card-based layout
- Responsive grid system
- Hover effects and animations
- Mobile-optimized design

## File Structure

```
tp-subscription-tiers/
├── tp-subscription-tiers.php    # Main plugin file
├── admin/
│   └── admin-page.php           # Admin configuration page
├── assets/
│   ├── style.css                # Frontend styles
│   ├── script.js                # Frontend JavaScript
│   ├── admin.css                # Admin styles
│   └── admin.js                 # Admin JavaScript
├── templates/
│   └── subscription-tiers.php   # Main template file
└── README.md                     # Documentation
```

## Customization

### Modify Styles
Edit `/assets/style.css` to change colors, fonts, or layout

### Add Custom Features
Extend the JavaScript in `/assets/script.js` to add custom functionality

### Database Options
- `tp_subscription_tiers` - Stores tier configuration
- `tp_usage_pricing` - Stores usage-based pricing options

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## Support

For issues or feature requests, please contact your development team.# subscription-page
