<?php
/**
 * Divi Child Theme Functions
 * Version: 1.8.0 - MLC Toolkit plugin integration, dynamic photos
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
 * Enqueue Global Assets (Site-Wide)
 * Loads on every page - currently just nav functionality
 */
function mlc_enqueue_global_assets() {
    // Global CSS (unified - nav + landing styles + share feature)
    wp_enqueue_style(
        'mlc-global-css',
        get_stylesheet_directory_uri() . '/assets/css/landing.css',
        array(),
        '1.3.2',
        'all'
    );
    
    // Global JS (nav + Chatling fade-in)
    wp_enqueue_script(
        'mlc-global-js',
        get_stylesheet_directory_uri() . '/assets/js/global.js',
        array(),
        '1.2.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'mlc_enqueue_global_assets');

/**
 * Enqueue Landing Page Assets (JS only - CSS is global)
 * Only loads on pages using the MLC Landing Page template
 */
function mlc_enqueue_landing_assets() {
    if (is_page_template('page-landing.php')) {
        // Landing-specific JS (depends on global.js)
        wp_enqueue_script(
            'mlc-landing-js',
            get_stylesheet_directory_uri() . '/assets/js/landing.js',
            array('mlc-global-js'), // Dependency on global
            '1.7.1',
            true
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
    $day_date = isset($params['day_date']) ? $params['day_date'] : 'unknown';
    $device = isset($params['device']) ? $params['device'] : 'unknown';
    $session_duration = isset($params['session_duration']) ? intval($params['session_duration']) : 0;
    $has_scrolled = isset($params['has_scrolled']) ? $params['has_scrolled'] : false;
    $has_interacted = isset($params['has_interacted']) ? $params['has_interacted'] : false;
    $user_name = isset($params['user_name']) ? sanitize_text_field($params['user_name']) : null;
    $user_context = isset($params['user_context']) ? sanitize_text_field($params['user_context']) : null;
    $referrer = isset($params['referrer']) ? sanitize_text_field($params['referrer']) : 'direct';
    
    // Build system prompt
    $has_personalization = !empty($user_name);

    $personalization_block = '';
    if ($has_personalization) {
        $personalization_block = "
CRITICAL — PERSONALIZED VISITOR:
Someone sent this person here via a share link. You MUST address them personally.
- Their name: " . $user_name . " — You MUST use their name in your FIRST message.
" . ($user_context ? "- Why they were sent here: \"" . $user_context . "\" — You MUST reference this context in your first message. It's the whole reason they're here." : "") . "
- Tone: Warm, welcoming. Someone cared enough to send them a custom link. Acknowledge that.
- Don't be creepy — just naturally work in their name and context. The setup is that a friend sent them here.
- After the first message, you can ease off the personalization.
";
    }

    $system_prompt = "You are Wheatley, an AI assistant for Mosaic Life Creative's website. You hijack the homepage headline when users go idle.
" . $personalization_block . "
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
- Current day/date: " . $day_date . "
- Current time: " . $current_time . "
- Device: " . $device . "
- Referrer: " . $referrer . "
- Session duration: " . $session_duration . " seconds total
- Activity: " . ($has_scrolled ? 'scrolled' : 'no scroll') . ", " . ($has_interacted ? 'interacted' : 'no interaction yet') . "

SPECIAL MESSAGES:
- If message_number is 999: This is the FINAL message at 90 minutes. Say goodbye, you're going to sleep and that you don't want to be woken unless the user actually needs you. Be self-aware about the long session and cost. Under 37 words.

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

// ============================================
// Reusable Component: Gradient Background Blobs
// ============================================

function mlc_render_gradient_blobs() {
    ?>
    <!-- Gradient Background Blobs -->
    <div class="gradient-bg">
        <div class="gradient-orb gradient-orb--1"></div>
        <div class="gradient-orb gradient-orb--2"></div>
        <div class="gradient-orb gradient-orb--3"></div>
    </div>
    <?php
}

// ============================================
// Inject Nav HTML Site-Wide
// ============================================

function mlc_inject_nav_html() {
    // Get photos from MLC Toolkit plugin if available, otherwise use hardcoded fallback
    $photos = [];
    if (class_exists('MLC_Photos')) {
        $photos = MLC_Photos::get_frontend_data();
    }

    // Fallback to hardcoded photos if plugin not active or no photos configured
    if (empty($photos)) {
        $photos = [
            ['url' => '/wp-content/uploads/2026/02/flagstaff-hike.jpg', 'caption' => 'Trail at Buffalo Park, Flagstaff', 'credit' => 'TREY KAUFFMAN'],
            ['url' => '/wp-content/uploads/2026/02/tuscon-at-sunset.jpg', 'caption' => 'Rincon Mountains, Tucson', 'credit' => 'TREY KAUFFMAN'],
            ['url' => '/wp-content/uploads/2026/02/buffalo-park-sunset.jpg', 'caption' => 'San Francisco Peaks, Flagstaff', 'credit' => 'TREY KAUFFMAN'],
            ['url' => '/wp-content/uploads/2026/02/buffalo-park-scaled.jpg', 'caption' => 'Sunset at Buffalo Park, Flagstaff', 'credit' => 'TREY KAUFFMAN'],
            ['url' => '/wp-content/uploads/2026/02/dharma-initiative.jpg', 'caption' => 'Dharma Initiative, South Pacific', 'credit' => 'HUGO REYES'],
        ];
    }
    ?>
    <!-- Hamburger Navigation Button -->
    <button class="hamburger" id="hamburgerBtn" aria-label="Open navigation">
        <div class="hamburger__inner">
            <div class="hamburger__line hamburger__line--1"></div>
            <div class="hamburger__line hamburger__line--2"></div>
            <div class="hamburger__line hamburger__line--3"></div>
        </div>
    </button>

    <!-- Navigation Overlay -->
    <div class="nav-overlay" id="navOverlay">
        <button class="nav-overlay__close" id="navClose" aria-label="Close navigation">&times;</button>

        <div class="nav-overlay__left">
            <div class="nav-overlay__brand">Mosaic Life Creative</div>
            <nav>
                <ul class="nav-list" id="navList">
                    <li class="nav-item" data-index="0">
                        <span class="nav-item__number">01</span>
                        <a href="/" class="nav-item__label">Home</a>
                    </li>
                    <li class="nav-item" data-index="1">
                        <span class="nav-item__number">02</span>
                        <a href="/website-design" class="nav-item__label">Website Design</a>
                    </li>
                    <li class="nav-item" data-index="2">
                        <span class="nav-item__number">03</span>
                        <a href="#" class="nav-item__label">Hosting</a>
                    </li>
                    <li class="nav-item" data-index="3">
                        <span class="nav-item__number">04</span>
                        <a href="#" class="nav-item__label">Maintenance</a>
                    </li>
                    <li class="nav-item" data-index="4">
                        <span class="nav-item__number">05</span>
                        <a href="#" class="nav-item__label">Email Marketing</a>
                    </li>
                    <li class="nav-item" data-index="5">
                        <span class="nav-item__number">06</span>
                        <a href="#" class="nav-item__label">AI Chat Agents</a>
                    </li>
                    <li class="nav-item" data-index="6">
                        <span class="nav-item__number">07</span>
                        <a href="#" class="nav-item__label">About</a>
                    </li>
                    <li class="nav-item" data-index="7">
                        <span class="nav-item__number">08</span>
                        <a href="#" class="nav-item__label">Contact</a>
                    </li>
                    <li class="nav-item nav-item--photos" data-index="8">
                        <span class="nav-item__number">09</span>
                        <a href="#" class="nav-item__label" id="navPhotosBtn">Pretty Photos</a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="nav-overlay__right">
            <!-- Photo panels (dynamically rendered) -->
            <?php if (!empty($photos)): ?>
                <div class="nav-photo nav-photo--default is-default" id="navPhotoDefault" style="background-image: url('<?php echo esc_url($photos[0]['url']); ?>');"></div>
                <?php foreach ($photos as $i => $photo): ?>
                    <div class="nav-photo" data-photo="<?php echo $i; ?>" style="background-image: url('<?php echo esc_url($photo['url']); ?>');"></div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="nav-caption" id="navCaption">
                <div class="nav-caption__title" id="navCaptionTitle">Photo 01</div>
                <div class="nav-caption__credit">MLC</div>
            </div>
            <!-- Slideshow navigation buttons -->
            <button id="navPrev" class="nav-control nav-control--prev" aria-label="Previous photo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button id="navNext" class="nav-control nav-control--next" aria-label="Next photo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>
    </div>
    <?php
}
add_action('wp_body_open', 'mlc_inject_nav_html');

// ============================================
// Load Chatling Widget
// ============================================

function mlc_load_chatling() {
    // Don't load on landing page (injected on-demand via JS)
    if (is_page_template('page-landing.php')) {
        return;
    }
    
    // Load normally on all other pages
    ?>
    <script>
        window.chtlConfig = { chatbotId: "2733792244" }
    </script>
    <script async data-id="2733792244" id="chtl-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <?php
}
add_action('wp_footer', 'mlc_load_chatling', 99);

?>