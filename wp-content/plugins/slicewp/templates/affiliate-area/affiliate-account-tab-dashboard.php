<?php
/**
 * Affiliate account tab: Dashboard
 *
 * This template can be overridden by copying it to yourtheme/slicewp/affiliate-area/affiliate-account-tab-dashboard.php.
 *
 * HOWEVER, on occasion SliceWP will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility.
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Get query dates.
$current_date_min = ( new DateTime() )->sub( new DateInterval( 'P29D' ) );
$current_date_min->setTime( 00, 00, 00 );

$current_date_max = new DateTime();
$current_date_max->setTime( 23, 59, 59 );

$previous_date_min = ( new DateTime() )->sub( new DateInterval( 'P59D' ) );
$previous_date_min->setTime( 00, 00, 00 );

$previous_date_max = ( new DateTime() )->sub( new DateInterval( 'P30D' ) );
$previous_date_max->setTime( 23, 59, 59 );

?>

<p class="slicewp-section-heading"><?php echo __( 'Last 30 days', 'slicewp' ); ?></p>

<div class="slicewp-grid slicewp-grid-affiliate-dashboard slicewp-grid-affiliate-dashboard-last-30-days">
    
    <?php

        // Query visits.
        $args = array(
            'affiliate_id' => $args['affiliate_id'],
            'date_min'	   => get_gmt_from_date( $current_date_min->format( 'Y-m-d H:i:s' ) ),
            'date_max'	   => get_gmt_from_date( $current_date_max->format( 'Y-m-d H:i:s' ) )
        );

        $current_visits = slicewp_get_visits( $args, true );

        $args = array(
            'affiliate_id' => $args['affiliate_id'],
            'date_min'	   => get_gmt_from_date( $previous_date_min->format( 'Y-m-d H:i:s' ) ),
            'date_max'	   => get_gmt_from_date( $previous_date_max->format( 'Y-m-d H:i:s' ) )
        );

        $previous_visits = slicewp_get_visits( $args, true );

        // Query commissions.
        $args = array(
            'number'	   => -1,
            'affiliate_id' => $args['affiliate_id'],
            'fields'	   => 'amount',
            'status'	   => array( 'unpaid', 'paid' ),
            'date_min'	   => get_gmt_from_date( $current_date_min->format( 'Y-m-d H:i:s' ) ),
            'date_max'	   => get_gmt_from_date( $current_date_max->format( 'Y-m-d H:i:s' ) )
        );

        $current_commissions = slicewp_get_commissions( $args );

        $args = array(
            'number'	   => -1,
            'affiliate_id' => $args['affiliate_id'],
            'fields'	   => 'amount',
            'status'	   => array( 'unpaid', 'paid' ),
            'date_min'	   => get_gmt_from_date( $previous_date_min->format( 'Y-m-d H:i:s' ) ),
            'date_max'	   => get_gmt_from_date( $previous_date_max->format( 'Y-m-d H:i:s' ) )
        );

        $previous_commissions = slicewp_get_commissions( $args );

    ?>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Visits', 'slicewp' ); ?></div>

            <div class="slicewp-kpi-value">

                <?php $percentage_change = slicewp_calculate_percentage_change( $previous_visits, $current_visits ); ?>

                <?php echo $current_visits; ?>

                <div class="slicewp-kpi-direction <?php echo ( is_finite( $percentage_change ) && ! empty( $percentage_change ) ? ( $percentage_change > 0 ? 'slicewp-positive' : 'slicewp-negative' ) : '' ); ?>">
                    <span class="slicewp-arrow-up"><?php echo slicewp_get_svg( 'outline-arrow-up' ); ?></span>
                    <span class="slicewp-arrow-down"><?php echo slicewp_get_svg( 'outline-arrow-down' ); ?></span>
                    <?php echo sprintf( '%s', ( is_finite( $percentage_change ) ? $percentage_change . '%' : '-' ) ); ?>
                </div>

            </div>

        </div>

        <div class="slicewp-card-footer">
            <a href="<?php echo esc_url( add_query_arg( array( 'affiliate-account-tab' => 'visits' ) ) ); ?>" data-slicewp-tab="visits"><?php echo __( 'View all visits', 'slicewp' ); ?></a>
        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Commissions', 'slicewp' ); ?></div>

            <div class="slicewp-kpi-value">

                <?php $percentage_change = slicewp_calculate_percentage_change( count( $previous_commissions ), count( $current_commissions ) ); ?>

                <?php echo count( $current_commissions ); ?>

                <div class="slicewp-kpi-direction <?php echo ( is_finite( $percentage_change ) && ! empty( $percentage_change ) ? ( $percentage_change > 0 ? 'slicewp-positive' : 'slicewp-negative' ) : '' ); ?>">
                    <span class="slicewp-arrow-up"><?php echo slicewp_get_svg( 'outline-arrow-up' ); ?></span>
                    <span class="slicewp-arrow-down"><?php echo slicewp_get_svg( 'outline-arrow-down' ); ?></span>
                    <?php echo sprintf( '%s', ( is_finite( $percentage_change ) ? $percentage_change . '%' : '-' ) ); ?>
                </div>

            </div>

        </div>

        <div class="slicewp-card-footer">
            <a href="<?php echo esc_url( add_query_arg( array( 'affiliate-account-tab' => 'commissions' ) ) ); ?>" data-slicewp-tab="commissions"><?php echo __( 'View all commissions', 'slicewp' ); ?></a>
        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Commissions Amount', 'slicewp' ); ?></div>

            <div class="slicewp-kpi-value">

                <?php $percentage_change = slicewp_calculate_percentage_change( array_sum( $previous_commissions ), array_sum( $current_commissions ) ); ?>
                
                <?php echo slicewp_format_amount( array_sum( $current_commissions ), slicewp_get_setting( 'active_currency', 'USD' ) ); ?>

                <div class="slicewp-kpi-direction <?php echo ( is_finite( $percentage_change ) && ! empty( $percentage_change ) ? ( $percentage_change > 0 ? 'slicewp-positive' : 'slicewp-negative' ) : '' ); ?>">
                    <span class="slicewp-arrow-up"><?php echo slicewp_get_svg( 'outline-arrow-up' ); ?></span>
                    <span class="slicewp-arrow-down"><?php echo slicewp_get_svg( 'outline-arrow-down' ); ?></span>
                    <?php echo sprintf( '%s', ( is_finite( $percentage_change ) ? $percentage_change . '%' : '-' ) ); ?>
                </div>
            
            </div>

        </div>

        <div class="slicewp-card-footer">
            <a href="<?php echo esc_url( add_query_arg( array( 'affiliate-account-tab' => 'commissions' ) ) ); ?>" data-slicewp-tab="commissions"><?php echo __( 'View all commissions', 'slicewp' ); ?></a>
        </div>

    </div>

</div>

<p class="slicewp-section-heading"><?php echo __( 'All time', 'slicewp' ); ?>

<div class="slicewp-grid slicewp-grid-affiliate-dashboard slicewp-grid-affiliate-dashboard-all-time">

    <?php

        $args = array(
            'affiliate_id' => $args['affiliate_id']
        );

        $visits = slicewp_get_visits( $args, true );

        $args = array(
            'number'	   => -1,
            'affiliate_id' => $args['affiliate_id'],
            'fields'	   => 'amount',
            'status'	   => 'unpaid'
        );

        $unpaid_commissions = slicewp_get_commissions( $args );

        $args = array(
            'number'	   => -1,
            'affiliate_id' => $args['affiliate_id'],
            'fields'	   => 'amount',
            'status'	   => 'paid'
        );

        $paid_commissions = slicewp_get_commissions( $args );

    ?>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Visits', 'slicewp' ); ?></div>
            <div class="slicewp-kpi-value"><?php echo $visits; ?></div>

        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Commissions', 'slicewp' ); ?></div>
            <div class="slicewp-kpi-value"><?php echo ( count( $unpaid_commissions ) + count( $paid_commissions ) ); ?></div>

        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Paid Earnings', 'slicewp' ); ?></div>
            <div class="slicewp-kpi-value"><?php echo slicewp_format_amount( array_sum( $paid_commissions ), slicewp_get_setting( 'active_currency', 'USD' ) ); ?></div>

        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Unpaid Earnings', 'slicewp' ); ?></div>
            <div class="slicewp-kpi-value"><?php echo slicewp_format_amount( array_sum( $unpaid_commissions ), slicewp_get_setting( 'active_currency', 'USD' ) ); ?></div>

        </div>

    </div>

</div>

<p class="slicewp-section-heading"><?php echo __( 'Program details', 'slicewp' ); ?>

<div class="slicewp-grid slicewp-grid-affiliate-dashboard slicewp-grid-affiliate-dashboard-program-details">
    
    <?php
        
        // Get the supported commissions.
        $available_commission_types = slicewp_get_available_commission_types();
        $affiliate_commission_rates = slicewp_get_affiliate_commission_rates( $args['affiliate_id'] );

    ?>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo ( count( $affiliate_commission_rates ) > 1 ? __( 'Commission Rates', 'slicewp' ) : __( 'Commission Rate', 'slicewp' ) ); ?></div>
            
            <?php

                foreach ( $affiliate_commission_rates as $key => $details ) {

                    if ( empty( $available_commission_types[$key] ) )
                        continue;

                    echo '<span class="slicewp-commission-rate-tag-' . str_replace( '_', '-', esc_attr( $key ) ) . '">' . sprintf ( __( '%s rate: %s', 'slicewp' ), $available_commission_types[$key]['label'], ( $details['rate_type'] == 'percentage' ? $details['rate'] . '%' : slicewp_format_amount( $details['rate'], slicewp_get_currency_symbol( slicewp_get_setting( 'active_currency', 'USD' ) ) ) ) ) . '</span>';

                }

            ?>

        </div>

    </div>

    <div class="slicewp-card slicewp-card-affiliate-dashboard">

        <div class="slicewp-card-inner">

            <div class="slicewp-card-title"><?php echo __( 'Cookie Duration', 'slicewp' ); ?></div>
            <div class="slicewp-kpi-value"><?php echo sprintf( __( '%s days', 'slicewp'), slicewp_get_setting( 'cookie_duration' ) );?></div>

        </div>

    </div>

</div>