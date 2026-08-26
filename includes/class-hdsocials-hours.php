<?php
namespace htrxuan\hdsocials;

// Prevent direct file access.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Business Hours Logic Class.
 *
 * Determines whether the business is currently "open" based on
 * the configured weekly schedule and timezone. Uses PHP's
 * DateTimeZone so the result is independent of server timezone.
 */
class Hours {

    /**
     * Day index map: PHP date('N') returns 1=Mon … 7=Sun.
     * We store schedule keys as 'mon', 'tue', … 'sun'.
     *
     * @var string[]
     */
    private static $day_keys = ['', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    /**
     * Check whether the business is open right now.
     *
     * @param array $options Plugin options from Admin::get_options().
     * @return bool True if open, false if closed.
     */
    public static function is_open( array $options ) {
        // Feature must be enabled.
        if ( empty( $options['bh_enabled'] ) ) {
            return true; // Not restricted — always treat as open.
        }

        $timezone_id = isset( $options['bh_timezone'] ) ? $options['bh_timezone'] : 'UTC';

        // Guard against invalid timezone strings.
        try {
            $tz = new \DateTimeZone( $timezone_id );
        } catch ( \Exception $e ) {
            $tz = new \DateTimeZone( 'UTC' );
        }

        $now     = new \DateTime( 'now', $tz );
        $day_num = (int) $now->format('N'); // 1=Mon … 7=Sun
        $day_key = self::$day_keys[ $day_num ];

        $schedule = isset( $options['bh_schedule'] ) ? $options['bh_schedule'] : [];
        $day      = isset( $schedule[ $day_key ] ) ? $schedule[ $day_key ] : [];

        $status = isset( $day['status'] ) ? $day['status'] : 'open';

        if ( $status === 'closed' ) {
            return false;
        }

        if ( $status === '24h' ) {
            return true;
        }

        // status === 'open': check open_time / close_time
        $open_time  = isset( $day['open'] )  ? $day['open']  : '09:00';
        $close_time = isset( $day['close'] ) ? $day['close'] : '17:00';

        $current_minutes = (int) $now->format('G') * 60 + (int) $now->format('i');

        list( $oh, $om ) = array_map( 'intval', explode( ':', $open_time  ) );
        list( $ch, $cm ) = array_map( 'intval', explode( ':', $close_time ) );

        $open_minutes  = $oh * 60 + $om;
        $close_minutes = $ch * 60 + $cm;

        // Handle overnight schedules (e.g. 22:00 – 02:00).
        if ( $close_minutes <= $open_minutes ) {
            return ( $current_minutes >= $open_minutes || $current_minutes < $close_minutes );
        }

        return ( $current_minutes >= $open_minutes && $current_minutes < $close_minutes );
    }

    /**
     * Get the default schedule (Mon–Fri 9am–5pm, Sat–Sun closed).
     *
     * @return array
     */
    public static function default_schedule() {
        $default = [];
        $weekdays = ['mon', 'tue', 'wed', 'thu', 'fri'];
        $weekend  = ['sat', 'sun'];

        foreach ( $weekdays as $day ) {
            $default[ $day ] = [ 'status' => 'open', 'open' => '09:00', 'close' => '17:00' ];
        }
        foreach ( $weekend as $day ) {
            $default[ $day ] = [ 'status' => 'closed', 'open' => '09:00', 'close' => '17:00' ];
        }

        return $default;
    }
}
