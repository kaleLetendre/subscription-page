<?php
if (!defined('ABSPATH')) {
    exit;
}

$tiers = get_option('tp_subscription_tiers', array());
$usage_pricing = get_option('tp_usage_pricing', array());
?>

<div class="tp-subscription-container" data-billing="annual">
    <section class="tp-subscription-hero">
        <h1 class="tp-heading">Choose Your Perfect Plan</h1>
        <p class="tp-subheading">Unlock powerful link shortening and analytics features</p>
        
        <?php if ($atts['show_billing_toggle'] === 'true'): ?>
        <div class="tp-billing-toggle">
            <span class="tp-badge">Save up to 20%</span>
            <div class="tp-toggle-switch">
                <span class="tp-label active" data-billing="annual">Annually</span>
                <label class="tp-toggle">
                    <input type="checkbox" id="billing-toggle">
                    <span class="tp-slider"></span>
                </label>
                <span class="tp-label" data-billing="monthly">Monthly</span>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <section class="tp-tier-cards">
        <div class="tp-cards-container">
            <?php foreach ($tiers as $tier_key => $tier): ?>
            <article class="tp-tier-card <?php echo $tier['recommended'] ? 'tp-recommended' : ''; ?>" data-tier="<?php echo esc_attr($tier_key); ?>">
                <?php if ($tier['recommended']): ?>
                <div class="tp-recommended-badge">
                    <span>RECOMMENDED</span>
                </div>
                <?php endif; ?>
                
                <div class="tp-card-inner">
                    <h2 class="tp-tier-name"><?php echo esc_html($tier['name']); ?></h2>
                    
                    <div class="tp-price">
                        <?php if ($tier['price'] == '0'): ?>
                            <strong class="tp-amount">Free</strong>
                        <?php else: ?>
                            <strong class="tp-amount">
                                <span class="tp-currency">$</span>
                                <span class="tp-price-value" data-monthly="<?php echo esc_attr($tier['price']); ?>" data-annual="<?php echo esc_attr(round($tier['price'] * 10)); ?>">
                                    <?php echo esc_html(round($tier['price'] * 10)); ?>
                                </span>
                            </strong>
                            <small class="tp-period">
                                <span class="tp-period-text" data-monthly="/month" data-annual="/year">/year</span>
                            </small>
                            <p class="tp-annual-savings">
                                <span class="tp-monthly-equivalent">$<?php echo esc_html(round($tier['price'] * 10 / 12, 2)); ?>/month</span>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <button class="tp-tier-button <?php echo $tier['recommended'] ? 'tp-primary' : 'tp-secondary'; ?>" data-tier="<?php echo esc_attr($tier_key); ?>">
                        <?php echo $tier['price'] == '0' ? 'Get Started' : 'Upgrade to ' . esc_html($tier['name']); ?>
                    </button>
                    
                    <div class="tp-features">
                        <h4 class="tp-features-heading">Features included:</h4>
                        <ul class="tp-feature-list">
                            <?php foreach ($tier['features'] as $feature): ?>
                            <li>
                                <svg class="tp-check-icon" viewBox="0 0 24 24" width="16" height="16">
                                    <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path>
                                </svg>
                                <?php echo esc_html($feature); ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if (!empty($usage_pricing)): ?>
    <section class="tp-usage-pricing">
        <h3 class="tp-section-title">Need More? Add Usage-Based Extensions</h3>
        <div class="tp-usage-cards">
            <?php foreach ($usage_pricing as $usage_key => $usage): ?>
            <div class="tp-usage-card">
                <div class="tp-usage-icon">
                    <?php if ($usage_key === 'extra_links'): ?>
                    <svg viewBox="0 0 24 24" width="32" height="32">
                        <path fill="currentColor" d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
                    </svg>
                    <?php else: ?>
                    <svg viewBox="0 0 24 24" width="32" height="32">
                        <path fill="currentColor" d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm8 0h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2zm4-4h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2z"/>
                    </svg>
                    <?php endif; ?>
                </div>
                <h4 class="tp-usage-title">
                    +<?php echo esc_html($usage['amount']); ?> <?php echo esc_html($usage['unit']); ?>
                </h4>
                <p class="tp-usage-price">
                    $<?php echo esc_html($usage['price']); ?>/month
                </p>
                <button class="tp-usage-button" data-usage="<?php echo esc_attr($usage_key); ?>">
                    Add to Plan
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="tp-comparison">
        <h3 class="tp-section-title">Detailed Feature Comparison</h3>
        <div class="tp-comparison-table-wrapper">
            <table class="tp-comparison-table">
                <thead>
                    <tr>
                        <th>Features</th>
                        <?php foreach ($tiers as $tier): ?>
                        <th><?php echo esc_html($tier['name']); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Short Links</td>
                        <td>10/month</td>
                        <td>100/month</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>QR Codes</td>
                        <td>1/month</td>
                        <td>10/month</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>Analytics</td>
                        <td>Basic</td>
                        <td>Advanced</td>
                        <td>Full Suite</td>
                    </tr>
                    <tr>
                        <td>Custom Branding</td>
                        <td><span class="tp-dash">—</span></td>
                        <td><svg class="tp-check-icon" viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg></td>
                        <td><svg class="tp-check-icon" viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg></td>
                    </tr>
                    <tr>
                        <td>API Access</td>
                        <td><span class="tp-dash">—</span></td>
                        <td>Standard</td>
                        <td>Full</td>
                    </tr>
                    <tr>
                        <td>Support</td>
                        <td>Standard</td>
                        <td>Priority</td>
                        <td>Dedicated</td>
                    </tr>
                    <tr>
                        <td>Team Members</td>
                        <td>1</td>
                        <td>3</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>White Label</td>
                        <td><span class="tp-dash">—</span></td>
                        <td><span class="tp-dash">—</span></td>
                        <td><svg class="tp-check-icon" viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>