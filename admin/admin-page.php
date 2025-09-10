<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_POST['submit'])) {
    if (wp_verify_nonce($_POST['tp_admin_nonce'], 'tp_save_settings')) {
        $tiers = array();
        
        foreach ($_POST['tiers'] as $tier_key => $tier_data) {
            $tiers[$tier_key] = array(
                'name' => sanitize_text_field($tier_data['name']),
                'price' => sanitize_text_field($tier_data['price']),
                'billing_period' => sanitize_text_field($tier_data['billing_period']),
                'features' => array_map('sanitize_text_field', explode("\n", $tier_data['features'])),
                'recommended' => isset($tier_data['recommended']) ? true : false
            );
        }
        
        update_option('tp_subscription_tiers', $tiers);
        
        if (isset($_POST['usage_pricing'])) {
            $usage_pricing = array();
            foreach ($_POST['usage_pricing'] as $usage_key => $usage_data) {
                $usage_pricing[$usage_key] = array(
                    'price' => sanitize_text_field($usage_data['price']),
                    'amount' => sanitize_text_field($usage_data['amount']),
                    'unit' => sanitize_text_field($usage_data['unit'])
                );
            }
            update_option('tp_usage_pricing', $usage_pricing);
        }
        
        echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
    }
}

$tiers = get_option('tp_subscription_tiers', array());
$usage_pricing = get_option('tp_usage_pricing', array());
?>

<div class="wrap">
    <h1>Subscription Tiers Configuration</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('tp_save_settings', 'tp_admin_nonce'); ?>
        
        <div class="tp-admin-container">
            <h2>Subscription Tiers</h2>
            
            <div class="tp-tiers-wrapper">
                <?php foreach ($tiers as $tier_key => $tier): ?>
                <div class="tp-admin-tier-card">
                    <h3><?php echo ucfirst($tier_key); ?> Tier</h3>
                    
                    <table class="form-table">
                        <tr>
                            <th><label>Tier Name</label></th>
                            <td>
                                <input type="text" 
                                       name="tiers[<?php echo $tier_key; ?>][name]" 
                                       value="<?php echo esc_attr($tier['name']); ?>" 
                                       class="regular-text" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label>Price</label></th>
                            <td>
                                <input type="number" 
                                       name="tiers[<?php echo $tier_key; ?>][price]" 
                                       value="<?php echo esc_attr($tier['price']); ?>" 
                                       class="small-text" 
                                       step="0.01" 
                                       min="0" />
                                <select name="tiers[<?php echo $tier_key; ?>][billing_period]">
                                    <option value="month" <?php selected($tier['billing_period'], 'month'); ?>>per month</option>
                                    <option value="year" <?php selected($tier['billing_period'], 'year'); ?>>per year</option>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label>Features</label></th>
                            <td>
                                <textarea name="tiers[<?php echo $tier_key; ?>][features]" 
                                          rows="8" 
                                          cols="50"
                                          placeholder="Enter one feature per line"><?php echo esc_textarea(implode("\n", $tier['features'])); ?></textarea>
                                <p class="description">Enter one feature per line</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label>Recommended</label></th>
                            <td>
                                <input type="checkbox" 
                                       name="tiers[<?php echo $tier_key; ?>][recommended]" 
                                       value="1" 
                                       <?php checked($tier['recommended'], true); ?> />
                                <label>Mark as recommended tier</label>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php endforeach; ?>
            </div>
            
            <h2>Usage-Based Pricing Extensions</h2>
            
            <div class="tp-usage-wrapper">
                <?php foreach ($usage_pricing as $usage_key => $usage): ?>
                <div class="tp-admin-usage-card">
                    <h3><?php echo ucwords(str_replace('_', ' ', $usage_key)); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th><label>Price</label></th>
                            <td>
                                $<input type="number" 
                                        name="usage_pricing[<?php echo $usage_key; ?>][price]" 
                                        value="<?php echo esc_attr($usage['price']); ?>" 
                                        class="small-text" 
                                        step="0.01" 
                                        min="0" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label>Amount</label></th>
                            <td>
                                <input type="number" 
                                       name="usage_pricing[<?php echo $usage_key; ?>][amount]" 
                                       value="<?php echo esc_attr($usage['amount']); ?>" 
                                       class="small-text" 
                                       min="1" />
                            </td>
                        </tr>
                        
                        <tr>
                            <th><label>Unit</label></th>
                            <td>
                                <input type="text" 
                                       name="usage_pricing[<?php echo $usage_key; ?>][unit]" 
                                       value="<?php echo esc_attr($usage['unit']); ?>" 
                                       class="regular-text" />
                            </td>
                        </tr>
                    </table>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="tp-admin-actions">
                <button type="button" id="add-tier" class="button">Add New Tier</button>
                <button type="button" id="add-usage" class="button">Add Usage Extension</button>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" name="submit" class="button-primary" value="Save Settings" />
        </p>
    </form>
    
    <div class="tp-admin-shortcode">
        <h2>How to Use</h2>
        <p>To display the subscription tiers on any page or post, use the following shortcode:</p>
        <code>[tp_subscription_tiers]</code>
        <p>Optional parameters:</p>
        <ul>
            <li><code>show_billing_toggle="true"</code> - Show/hide the annual/monthly billing toggle (default: true)</li>
        </ul>
        <p>Example: <code>[tp_subscription_tiers show_billing_toggle="false"]</code></p>
    </div>
</div>

<style>
.tp-admin-container {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.tp-tiers-wrapper,
.tp-usage-wrapper {
    display: grid;
    gap: 20px;
    margin-bottom: 30px;
}

.tp-admin-tier-card,
.tp-admin-usage-card {
    background: #f9f9f9;
    padding: 20px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
}

.tp-admin-tier-card h3,
.tp-admin-usage-card h3 {
    margin-top: 0;
    color: #23282d;
}

.tp-admin-actions {
    margin: 20px 0;
    padding: 20px 0;
    border-top: 1px solid #e5e5e5;
}

.tp-admin-shortcode {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.tp-admin-shortcode code {
    background: #f1f1f1;
    padding: 3px 6px;
    border-radius: 3px;
}
</style>