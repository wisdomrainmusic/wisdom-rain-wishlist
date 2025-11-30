<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WRW_Session {

    const COOKIE_NAME = 'wrw_session_id';
    const COOKIE_LIFETIME = 86400 * 30; // 30 days

    /**
     * Get a unique session ID (guest or user)
     */
    public static function get_session_id() {

        // Logged-in user → use user ID directly
        if ( is_user_logged_in() ) {
            return 'user_' . get_current_user_id();
        }

        // Guest user → create or get cookie session
        if ( ! empty( $_COOKIE[ self::COOKIE_NAME ] ) ) {
            return sanitize_text_field( $_COOKIE[ self::COOKIE_NAME ] );
        }

        // Create new session for guest
        $token = self::generate_token();

        setcookie(
            self::COOKIE_NAME,
            $token,
            time() + self::COOKIE_LIFETIME,
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );

        $_COOKIE[ self::COOKIE_NAME ] = $token; // Immediate availability

        return $token;
    }

    /**
     * Generate random secure token
     */
    private static function generate_token() {
        return 'guest_' . wp_generate_uuid4();
    }
}
