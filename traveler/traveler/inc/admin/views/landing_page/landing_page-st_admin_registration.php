<div class="traveler-registration-steps">
    	<div class="feature-section col three-col">
        	<div>
				<h4><?php echo __("Step 1 - Signup for Support",ST_TEXTDOMAIN) ; ?></h4>
				<p><a href="#" target="_blank">Click here</a> to signup at our support center.&nbsp;View a tutorial&nbsp;<a href="#" target="_blank"><?php echo __("here ", ST_TEXTDOMAIN ) ; ?>.</a>&nbsp;<?php echo __("This gives you access to our documentation, knowledgebase, video tutorials and ticket system.", ST_TEXTDOMAIN ) ; ?></p>
            </div>
            <div>
				<h4><?php echo __("Step 2 - Generate an API Key",ST_TEXTDOMAIN) ; ?></h4>
				<p><?php echo __('Once you registered at our support center, you need to generate a product API key under the "Licenses" section of your Themeforest account. View a tutorial ',ST_TEXTDOMAIN) ; ?>&nbsp;<a href="#" target="_blank"><?php echo __("here ", ST_TEXTDOMAIN ) ; ?></a>.</p>
            </div>
        	<div class="last-feature">
				<h4><?php echo __("Step 3 - Purchase Validation", ST_TEXTDOMAIN ) ; ?></h4>
				<p><?php echo __("Enter your ThemeForest username, purchase code and generated API key into the fields below. This will give you access to automatic theme updates. ", ST_TEXTDOMAIN ) ; ?></p>
            </div>
        </div>
    </div>
<div class="feature-section">
        <div class="traveler-important-notice registration-form-container">
                        <p class="about-description"><?php echo __("After Steps 1-2 are complete, enter your credentials below to complete product registration.", ST_TEXTDOMAIN ) ; ?></p>
                        <div class="traveler-registration-form">
                <form id="traveler_product_registration">
                    <input type="hidden" name="action" value="traveler_update_registration">
                    <input type="text" name="tf_username" id="tf_username" placeholder="Themeforest Username" value="">
                    <input type="text" name="tf_purchase_code" id="tf_purchase_code" placeholder="Enter Themeforest Purchase Code" value="">
                    <input type="text" name="tf_api" id="tf_api" placeholder="Enter API Key" value="">
                </form>
            </div>
            <button class="button button-large button-primary traveler-large-button traveler-register"><?php echo __("Submit", ST_TEXTDOMAIN ) ; ?></button>
            <span class="traveler-loader"><i class="dashicons dashicons-update loader-icon"></i><span></span></span>
        </div>
    </div>
