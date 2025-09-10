(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initBillingToggle();
        initTierButtons();
        initUsageButtons();
        loadTierData();
    });
    
    function initBillingToggle() {
        const toggle = document.getElementById('billing-toggle');
        const container = document.querySelector('.tp-subscription-container');
        
        if (!toggle || !container) return;
        
        toggle.addEventListener('change', function() {
            const isMonthly = this.checked;
            container.setAttribute('data-billing', isMonthly ? 'monthly' : 'annual');
            
            // Update toggle labels
            document.querySelectorAll('.tp-toggle-switch .tp-label').forEach(label => {
                label.classList.remove('active');
            });
            
            const activeLabel = document.querySelector(`.tp-toggle-switch .tp-label[data-billing="${isMonthly ? 'monthly' : 'annual'}"]`);
            if (activeLabel) {
                activeLabel.classList.add('active');
            }
            
            // Update prices
            updatePrices(isMonthly);
        });
        
        // Click on labels to toggle
        document.querySelectorAll('.tp-toggle-switch .tp-label').forEach(label => {
            label.addEventListener('click', function() {
                const billing = this.getAttribute('data-billing');
                toggle.checked = (billing === 'monthly');
                toggle.dispatchEvent(new Event('change'));
            });
        });
    }
    
    function updatePrices(isMonthly) {
        document.querySelectorAll('.tp-price-value').forEach(priceElement => {
            const monthlyPrice = priceElement.getAttribute('data-monthly');
            const annualPrice = priceElement.getAttribute('data-annual');
            
            if (monthlyPrice && annualPrice) {
                priceElement.textContent = isMonthly ? monthlyPrice : annualPrice;
            }
        });
        
        document.querySelectorAll('.tp-period-text').forEach(periodElement => {
            const monthlyText = periodElement.getAttribute('data-monthly');
            const annualText = periodElement.getAttribute('data-annual');
            
            if (monthlyText && annualText) {
                periodElement.textContent = isMonthly ? monthlyText : annualText;
            }
        });
    }
    
    function initTierButtons() {
        document.querySelectorAll('.tp-tier-button').forEach(button => {
            button.addEventListener('click', function() {
                const tier = this.getAttribute('data-tier');
                handleTierSelection(tier);
            });
        });
    }
    
    function initUsageButtons() {
        document.querySelectorAll('.tp-usage-button').forEach(button => {
            button.addEventListener('click', function() {
                const usage = this.getAttribute('data-usage');
                handleUsageSelection(usage);
            });
        });
    }
    
    function handleTierSelection(tier) {
        // Get current billing period
        const container = document.querySelector('.tp-subscription-container');
        const billingPeriod = container.getAttribute('data-billing') || 'annual';
        
        // Create modal or redirect to checkout
        showUpgradeModal(tier, billingPeriod);
    }
    
    function handleUsageSelection(usage) {
        // Handle usage extension selection
        showUsageModal(usage);
    }
    
    function showUpgradeModal(tier, billingPeriod) {
        // Remove existing modal if any
        const existingModal = document.getElementById('tp-upgrade-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal HTML
        const modalHtml = `
            <div id="tp-upgrade-modal" class="tp-modal">
                <div class="tp-modal-content">
                    <span class="tp-modal-close">&times;</span>
                    <h2>Upgrade to ${tier.charAt(0).toUpperCase() + tier.slice(1)} Plan</h2>
                    <p>You're about to upgrade to the ${tier} plan (${billingPeriod} billing).</p>
                    <div class="tp-modal-actions">
                        <button class="tp-modal-confirm" data-tier="${tier}">Confirm Upgrade</button>
                        <button class="tp-modal-cancel">Cancel</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Add modal event listeners
        const modal = document.getElementById('tp-upgrade-modal');
        const closeBtn = modal.querySelector('.tp-modal-close');
        const cancelBtn = modal.querySelector('.tp-modal-cancel');
        const confirmBtn = modal.querySelector('.tp-modal-confirm');
        
        closeBtn.addEventListener('click', () => modal.remove());
        cancelBtn.addEventListener('click', () => modal.remove());
        confirmBtn.addEventListener('click', function() {
            processTierUpgrade(this.getAttribute('data-tier'), billingPeriod);
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
    
    function showUsageModal(usage) {
        // Remove existing modal if any
        const existingModal = document.getElementById('tp-usage-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal HTML
        const modalHtml = `
            <div id="tp-usage-modal" class="tp-modal">
                <div class="tp-modal-content">
                    <span class="tp-modal-close">&times;</span>
                    <h2>Add Usage Extension</h2>
                    <p>Add extra ${usage.replace('_', ' ')} to your current plan.</p>
                    <div class="tp-modal-actions">
                        <button class="tp-modal-confirm" data-usage="${usage}">Add to Plan</button>
                        <button class="tp-modal-cancel">Cancel</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Add modal event listeners
        const modal = document.getElementById('tp-usage-modal');
        const closeBtn = modal.querySelector('.tp-modal-close');
        const cancelBtn = modal.querySelector('.tp-modal-cancel');
        const confirmBtn = modal.querySelector('.tp-modal-confirm');
        
        closeBtn.addEventListener('click', () => modal.remove());
        cancelBtn.addEventListener('click', () => modal.remove());
        confirmBtn.addEventListener('click', function() {
            processUsageExtension(this.getAttribute('data-usage'));
        });
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
    
    function processTierUpgrade(tier, billingPeriod) {
        // Show loading state
        showLoadingState();
        
        // In a real implementation, this would make an API call to process the upgrade
        // For now, we'll simulate with a timeout
        setTimeout(() => {
            hideLoadingState();
            showSuccessMessage(`Successfully upgraded to ${tier} plan!`);
            
            // Close modal
            const modal = document.getElementById('tp-upgrade-modal');
            if (modal) {
                modal.remove();
            }
        }, 1500);
    }
    
    function processUsageExtension(usage) {
        // Show loading state
        showLoadingState();
        
        // In a real implementation, this would make an API call to add the extension
        setTimeout(() => {
            hideLoadingState();
            showSuccessMessage(`Successfully added ${usage.replace('_', ' ')} to your plan!`);
            
            // Close modal
            const modal = document.getElementById('tp-usage-modal');
            if (modal) {
                modal.remove();
            }
        }, 1500);
    }
    
    function loadTierData() {
        // This function can be used to load tier data dynamically via AJAX
        if (typeof tp_ajax !== 'undefined') {
            // Example AJAX call to get tier data
            fetch(tp_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'tp_get_tiers',
                    nonce: tp_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI with fetched data if needed
                    console.log('Tier data loaded:', data.data);
                }
            })
            .catch(error => {
                console.error('Error loading tier data:', error);
            });
        }
    }
    
    function showLoadingState() {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'tp-loading';
        loadingDiv.className = 'tp-loading-overlay';
        loadingDiv.innerHTML = '<div class="tp-spinner"></div>';
        document.body.appendChild(loadingDiv);
    }
    
    function hideLoadingState() {
        const loadingDiv = document.getElementById('tp-loading');
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }
    
    function showSuccessMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'tp-success-message';
        messageDiv.textContent = message;
        document.body.appendChild(messageDiv);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            messageDiv.classList.add('tp-fade-out');
            setTimeout(() => messageDiv.remove(), 300);
        }, 3000);
    }
    
    // Smooth scroll to comparison table
    const comparisonLink = document.querySelector('a[href="#comparison"]');
    if (comparisonLink) {
        comparisonLink.addEventListener('click', function(e) {
            e.preventDefault();
            const comparisonSection = document.querySelector('.tp-comparison');
            if (comparisonSection) {
                comparisonSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // Add hover effects to cards
    document.querySelectorAll('.tp-tier-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('tp-recommended')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
})();