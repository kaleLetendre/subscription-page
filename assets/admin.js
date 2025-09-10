jQuery(document).ready(function($) {
    'use strict';
    
    // Add new tier functionality
    $('#add-tier').on('click', function() {
        var tierCount = $('.tp-admin-tier-card').length;
        var newTierKey = 'tier_' + Date.now();
        
        var newTierHtml = `
            <div class="tp-admin-tier-card">
                <h3>New Tier</h3>
                <button type="button" class="remove-tier" style="float: right; color: red;">Remove</button>
                
                <table class="form-table">
                    <tr>
                        <th><label>Tier Name</label></th>
                        <td>
                            <input type="text" 
                                   name="tiers[${newTierKey}][name]" 
                                   value="" 
                                   class="regular-text" 
                                   placeholder="Enter tier name" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Price</label></th>
                        <td>
                            <input type="number" 
                                   name="tiers[${newTierKey}][price]" 
                                   value="0" 
                                   class="small-text" 
                                   step="0.01" 
                                   min="0" />
                            <select name="tiers[${newTierKey}][billing_period]">
                                <option value="month">per month</option>
                                <option value="year">per year</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Features</label></th>
                        <td>
                            <textarea name="tiers[${newTierKey}][features]" 
                                      rows="8" 
                                      cols="50"
                                      placeholder="Enter one feature per line"></textarea>
                            <p class="description">Enter one feature per line</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Recommended</label></th>
                        <td>
                            <input type="checkbox" 
                                   name="tiers[${newTierKey}][recommended]" 
                                   value="1" />
                            <label>Mark as recommended tier</label>
                        </td>
                    </tr>
                </table>
            </div>
        `;
        
        $('.tp-tiers-wrapper').append(newTierHtml);
    });
    
    // Add new usage extension functionality
    $('#add-usage').on('click', function() {
        var usageCount = $('.tp-admin-usage-card').length;
        var newUsageKey = 'usage_' + Date.now();
        
        var newUsageHtml = `
            <div class="tp-admin-usage-card">
                <h3>New Usage Extension</h3>
                <button type="button" class="remove-usage" style="float: right; color: red;">Remove</button>
                
                <table class="form-table">
                    <tr>
                        <th><label>Price</label></th>
                        <td>
                            $<input type="number" 
                                    name="usage_pricing[${newUsageKey}][price]" 
                                    value="0" 
                                    class="small-text" 
                                    step="0.01" 
                                    min="0" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Amount</label></th>
                        <td>
                            <input type="number" 
                                   name="usage_pricing[${newUsageKey}][amount]" 
                                   value="1" 
                                   class="small-text" 
                                   min="1" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th><label>Unit</label></th>
                        <td>
                            <input type="text" 
                                   name="usage_pricing[${newUsageKey}][unit]" 
                                   value="" 
                                   class="regular-text" 
                                   placeholder="e.g., links, QR codes" />
                        </td>
                    </tr>
                </table>
            </div>
        `;
        
        $('.tp-usage-wrapper').append(newUsageHtml);
    });
    
    // Remove tier functionality
    $(document).on('click', '.remove-tier', function() {
        if (confirm('Are you sure you want to remove this tier?')) {
            $(this).closest('.tp-admin-tier-card').remove();
        }
    });
    
    // Remove usage extension functionality
    $(document).on('click', '.remove-usage', function() {
        if (confirm('Are you sure you want to remove this usage extension?')) {
            $(this).closest('.tp-admin-usage-card').remove();
        }
    });
    
    // Recommended tier validation - only one can be selected
    $(document).on('change', 'input[name*="[recommended]"]', function() {
        if ($(this).is(':checked')) {
            $('input[name*="[recommended]"]').not(this).prop('checked', false);
        }
    });
    
    // Form validation before submit
    $('form').on('submit', function(e) {
        var valid = true;
        var errors = [];
        
        // Validate tier names
        $('.tp-admin-tier-card').each(function() {
            var tierName = $(this).find('input[name*="[name]"]').val();
            if (!tierName || tierName.trim() === '') {
                errors.push('All tiers must have a name');
                valid = false;
                return false;
            }
        });
        
        // Validate at least one tier exists
        if ($('.tp-admin-tier-card').length === 0) {
            errors.push('At least one tier must be configured');
            valid = false;
        }
        
        // Validate usage extensions
        $('.tp-admin-usage-card').each(function() {
            var unit = $(this).find('input[name*="[unit]"]').val();
            if (!unit || unit.trim() === '') {
                errors.push('All usage extensions must have a unit specified');
                valid = false;
                return false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Please fix the following errors:\n\n' + errors.join('\n'));
        }
    });
    
    // Preview shortcode
    $('.tp-admin-shortcode code').on('click', function() {
        var $this = $(this);
        var originalText = $this.text();
        
        // Select the text
        var range = document.createRange();
        range.selectNodeContents(this);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        
        // Copy to clipboard
        try {
            document.execCommand('copy');
            $this.text('Copied!');
            setTimeout(function() {
                $this.text(originalText);
            }, 2000);
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
        
        selection.removeAllRanges();
    });
    
    // Add tooltips for help
    $('label').each(function() {
        var labelText = $(this).text().toLowerCase();
        var tooltip = '';
        
        if (labelText.includes('recommended')) {
            tooltip = 'This tier will be highlighted as the recommended option';
        } else if (labelText.includes('features')) {
            tooltip = 'List the features included in this tier, one per line';
        } else if (labelText.includes('billing period')) {
            tooltip = 'Choose whether the price is monthly or annual';
        } else if (labelText.includes('unit')) {
            tooltip = 'What the user gets (e.g., "links", "QR codes", "API calls")';
        }
        
        if (tooltip) {
            $(this).append(' <span class="dashicons dashicons-info" title="' + tooltip + '" style="cursor: help; color: #666;"></span>');
        }
    });
    
    // Initialize tooltips
    $('.dashicons-info').on('mouseenter', function() {
        var $tooltip = $('<div class="tp-admin-tooltip">' + $(this).attr('title') + '</div>');
        $('body').append($tooltip);
        
        var offset = $(this).offset();
        $tooltip.css({
            top: offset.top - 30,
            left: offset.left + 20
        }).fadeIn(200);
    }).on('mouseleave', function() {
        $('.tp-admin-tooltip').fadeOut(200, function() {
            $(this).remove();
        });
    });
});

// Add tooltip styles
jQuery(document).ready(function($) {
    if (!$('#tp-admin-tooltip-styles').length) {
        $('head').append(`
            <style id="tp-admin-tooltip-styles">
                .tp-admin-tooltip {
                    position: absolute;
                    background: #333;
                    color: #fff;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 12px;
                    z-index: 9999;
                    max-width: 200px;
                    display: none;
                }
                .tp-admin-tooltip:after {
                    content: '';
                    position: absolute;
                    top: 100%;
                    left: 10px;
                    border: 5px solid transparent;
                    border-top-color: #333;
                }
            </style>
        `);
    }
});