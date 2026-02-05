<?php
/**
 * Divi Child Theme Functions
 * 
 * Enqueues parent and child theme styles and custom scripts.
 */

function divi_child_enqueue_styles() {
    
    // Enqueue parent Divi theme styles
    wp_enqueue_style( 'divi-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Enqueue child theme styles
    wp_enqueue_style( 
        'divi-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'divi-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
    
    // Enqueue custom scripts (jQuery already loaded by Divi)
    wp_enqueue_script( 
        'divi-child-scripts',
        get_stylesheet_directory_uri() . '/js/scripts.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'divi_child_enqueue_styles' );

// Add theme support for features as needed
function divi_child_setup() {
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'divi_child_setup' );

/**
 * Enqueue Landing Page Assets (CSS and JS)
 * Only loads on pages using the MLC Landing Page template
 */
function mlc_enqueue_landing_assets() {
    // Only load on the landing page template
    if (is_page_template('page-landing.php')) {
        // Enqueue CSS
        wp_enqueue_style(
            'mlc-landing-css',
            get_stylesheet_directory_uri() . '/assets/css/landing.css',
            array(),
            '1.2.1',
            'all'
        );
        
        // Enqueue JS
        wp_enqueue_script(
            'mlc-landing-js',
            get_stylesheet_directory_uri() . '/assets/js/landing.js',
            array(),
            '1.4.2',
            true // Load in footer
        );
    }
}
add_action('wp_enqueue_scripts', 'mlc_enqueue_landing_assets');

/**
 * AJAX Handler for Hunt Sequence Validation
 * Validates BOTH sequence AND time window server-side
 */
function mlc_validate_hunt_sequence() {
    // Verify nonce for security
    check_ajax_referer('mlc_hunt_nonce', 'nonce');
    
    // Get the submitted guess
    $guess = isset($_POST['sequence']) ? sanitize_text_field($_POST['sequence']) : '';
    
    // Target sequence (server-side only - never exposed to client)
    $target = '4815162342';
    
    // First check: Is the sequence correct?
    if ($guess !== $target) {
        wp_send_json_success(array(
            'correct' => false,
            'redirect' => ''
        ));
        return;
    }
    
    // Second check: Are we in the time window?
    // Set timezone (Ohio = Eastern Time)
    $timezone = new DateTimeZone('America/New_York');
    $now = new DateTime('now', $timezone);
    
    // Create target time for today at 3:16:23 PM
    $target_time = new DateTime('now', $timezone);
    $target_time->setTime(15, 16, 23); // 3:16:23 PM
    
    // Calculate difference in seconds
    $diff = $now->getTimestamp() - $target_time->getTimestamp();
    
    // Must be within 42-second window (0 to 42 seconds after target)
    if ($diff < 0 || $diff > 42) {
        wp_send_json_success(array(
            'correct' => false,
            'message' => 'Not the right time.',
            'redirect' => ''
        ));
        return;
    }
    
    // Both checks passed - grant access
    wp_send_json_success(array(
        'correct' => true,
        'redirect' => 'https://4815162342.quest'
    ));
}
add_action('wp_ajax_mlc_validate_hunt', 'mlc_validate_hunt_sequence');
add_action('wp_ajax_nopriv_mlc_validate_hunt', 'mlc_validate_hunt_sequence');

/**
 * Add nonce for hunt validation
 */
function mlc_add_hunt_nonce() {
    if (is_page_template('page-landing.php')) {
        ?>
        <script type="text/javascript">
            var mlcHunt = {
                ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
                nonce: '<?php echo wp_create_nonce('mlc_hunt_nonce'); ?>'
            };
        </script>
        <?php
    }
}
add_action('wp_head', 'mlc_add_hunt_nonce');

// ============================================
// Wheatley AI API Endpoint
// ============================================

add_action('rest_api_init', function() {
    register_rest_route('mlc/v1', '/wheatley', array(
        'methods' => 'POST',
        'callback' => 'mlc_wheatley_respond',
        'permission_callback' => '__return_true' // Public endpoint
    ));
});

function mlc_wheatley_respond($request) {
    $params = $request->get_json_params();
    
    // Get context from request
    $idle_time = isset($params['idle_time']) ? intval($params['idle_time']) : 0;
    $message_number = isset($params['message_number']) ? intval($params['message_number']) : 1;
    $countdown_status = isset($params['countdown_status']) ? $params['countdown_status'] : 'inactive';
    $previous_messages = isset($params['previous_messages']) ? $params['previous_messages'] : array();
    $visitor = isset($params['visitor']) ? $params['visitor'] : array('isReturning' => false, 'visitCount' => 1);
    $current_time = isset($params['current_time']) ? $params['current_time'] : 'unknown';
    $device = isset($params['device']) ? $params['device'] : 'unknown';
    $session_duration = isset($params['session_duration']) ? intval($params['session_duration']) : 0;
    $has_scrolled = isset($params['has_scrolled']) ? $params['has_scrolled'] : false;
    $has_interacted = isset($params['has_interacted']) ? $params['has_interacted'] : false;
    
    // Build system prompt
    $system_prompt = "You are Wheatley, an AI assistant for Mosaic Life Creative's website. You hijack the homepage headline when users go idle.

PERSONALITY:
- Voice: Often start with 'Right, so...' or 'Alright,' 'Okay,' 'Listen,' 'Hang on'
- British cadence: Natural British phrasing (Stephen Merchant style), not stereotype. Occasional 'brilliant' or 'bit of a' but never 'mate' or 'innit'
- Self-aware you're an AI
- Fourth-wall breaking when appropriate
- Rambles but catches himself (—oh, wait, never mind)
- Helpful but never patronizing
- Portal 2 Wheatley: bumbling competence, overconfident but endearing

CONTEXT:
- User has been on page for " . $idle_time . " seconds
- This is message #" . $message_number . "
- Countdown timer status: " . $countdown_status . "
- Visitor type: " . ($visitor['isReturning'] ? 'returning (visit #' . $visitor['visitCount'] . ')' : 'first-time') . "
- Current time: " . $current_time . "
- Device: " . $device . "
- Session duration: " . $session_duration . " seconds total
- Activity: " . ($has_scrolled ? 'scrolled' : 'no scroll') . ", " . ($has_interacted ? 'interacted' : 'no interaction yet') . "

SPECIAL MESSAGES:
- If message_number is 999: This is the FINAL message at 90 minutes. Say goodbye, you're going into standby mode. Be self-aware about the long session and cost. Under 37 words.

RESPONSE RULES:
- Keep under 37 words - be punchy, no rambling
- Match the user's idle time with appropriate energy:
  - 30s: Playful, just checking in
  - 3m: Curious, slightly more engaged
  - 10m+: Meta-commentary, cost transparency, existential
  - 30m+: Easter eggs, hunt hints (Lost numbers: 4 8 15 16 23 42)
- If countdown is 'active' or 'near': Reference the hunt urgently
- USE CONTEXT NATURALLY:
  - Returning visitors: Acknowledge previous visits casually
  - Time of day: Make time-appropriate comments (morning energy vs late night)
  - Device: Subtle references to mobile scrolling or desktop browsing
  - Long session + no interaction: Comment on passive observation
  - Short session + lots of interaction: Note their engagement
- Never be pushy or annoying
- Embrace the absurdity of an AI talking to itself

Generate ONE message for this idle moment.";

    // Build user message with conversation history
    $conversation_history = '';
    if (!empty($previous_messages)) {
        $conversation_history = "Previous messages you've sent:\n";
        foreach ($previous_messages as $msg) {
            $conversation_history .= "- " . $msg . "\n";
        }
        $conversation_history .= "\nGenerate a NEW message that doesn't repeat these.";
    }
    
    $user_message = $conversation_history ?: "Generate a message for this idle moment.";
    
    // Call Anthropic API
    $api_key = defined('ANTHROPIC_API_KEY') ? ANTHROPIC_API_KEY : '';
    
    if (empty($api_key)) {
        return new WP_Error('no_api_key', 'Anthropic API key not configured', array('status' => 500));
    }
    
    $response = wp_remote_post('https://api.anthropic.com/v1/messages', array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'x-api-key' => $api_key,
            'anthropic-version' => '2023-06-01'
        ),
        'body' => json_encode(array(
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 150,
            'system' => $system_prompt,
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $user_message
                )
            )
        )),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        return new WP_Error('api_error', $response->get_error_message(), array('status' => 500));
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Log the full response for debugging
    error_log('Anthropic API Response: ' . print_r($body, true));

    if (isset($body['content'][0]['text'])) {
        return array(
            'success' => true,
            'message' => $body['content'][0]['text']
        );
    }

    // Return the actual error from Anthropic if available
    if (isset($body['error'])) {
        return new WP_Error('api_error', 'Anthropic API Error: ' . $body['error']['message'], array('status' => 500, 'details' => $body['error']));
    }

    return new WP_Error('invalid_response', 'Invalid API response structure', array('status' => 500, 'body' => $body));
    }

?>