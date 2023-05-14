<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap slicewp-wrap slicewp-wrap-add-ons">

	<!-- Page Heading -->
	<h1 class="wp-heading-inline"><?php echo __( 'Add-ons', 'slicewp' ); ?></h1>
	<hr class="wp-header-end" />

	<?php if ( ! slicewp_is_website_registered() && ! in_array( 'slicewp-pro/index.php', get_option( 'active_plugins', array() ) ) ): ?>

		<div class="slicewp-card slicewp-card-price-notice">
			<div class="slicewp-card-inner">
				<span><?php echo __( 'Take your affiliate program to the next level!', 'slicewp' ); ?></span>
				<p><?php echo __( 'Extend your affiliate program with the PRO growth tools your affiliates need to stand out in a crowded market.', 'slicewp' ); ?></p>
				<a href="https://slicewp.com/pricing/?utm_source=plugin-free&amp;utm_medium=plugin-add-ons-page&amp;utm_campaign=SliceWPFree" target="_blank" class="slicewp-button-secondary"><?php echo __( 'Get started with PRO', 'slicewp' ); ?></a>
			</div>
		</div>

	<?php endif; ?>

	<?php wp_nonce_field( 'slicewp_activate_deactivate_add_on', 'slicewp_token', false ); ?>

	<div class="slicewp-grid slicewp-grid-columns-3" style="margin-top: 1.5rem;">

		<?php foreach ( slicewp()->add_ons as $add_on ): ?>

			<div class="slicewp-card slicewp-card-add-on">

				<div class="slicewp-card-inner">

					<div class="slicewp-flex">

						<div>
							<?php if ( ! empty( $add_on->get( 'icon_url' ) ) ): ?>
								<img src="<?php echo esc_attr( $add_on->get( 'icon_url' ) ); ?>" />
							<?php else: ?>
								<?php
									/**
									 * @todo Add placeholder image. 
									 *
									 */	
								?>
							<?php endif; ?>
						</div>

						<div>
							<?php if ( ! empty( $add_on->get( 'documentation_url' ) ) ): ?>
								<h4>
									<a href="<?php echo esc_url( $add_on->get( 'documentation_url' ) ); ?>" title="<?php echo esc_attr( __( 'Click to learn more', 'slicewp' ) ); ?>" target="_blank">
										<span><?php echo esc_html( $add_on->get( 'name' ) ); ?></span>
										<?php echo slicewp_get_svg( 'outline-arrow-top-right-on-square' ); ?>
									</a>
								</h4>
							<?php else: ?>
								<h4><span><?php echo esc_html( $add_on->get( 'name' ) ); ?></span></h4>
							<?php endif; ?>
							<p><?php echo esc_html( $add_on->get( 'description' ) ); ?></p>
						</div>

					</div>

				</div>

				<div class="slicewp-card-footer">

					<div>
						<div class="slicewp-switch slicewp-is-ajax">

							<span class="slicewp-loader"></span>

							<input id="slicewp-enable-add-on-<?php echo esc_attr( $add_on->get( 'slug' ) ); ?>" class="slicewp-toggle slicewp-toggle-round" name="slicewp_active_add_ons[]" type="checkbox" value="<?php echo esc_attr( $add_on->get( 'slug' ) ); ?>" <?php echo ( $add_on->is_active() ? 'checked="checked"' : '' ); ?> />
							<label for="slicewp-enable-add-on-<?php echo esc_attr( $add_on->get( 'slug' ) ); ?>"></label>

						</div>
					
						<div class="slicewp-tag-wrapper">
							<span class="slicewp-tag-add-on-active" <?php echo ( $add_on->is_active() ? 'style="display: inline-block;"' : '' ); ?>><?php echo __( 'Active', 'slicewp' ); ?></span>
							<span class="slicewp-tag-add-on-inactive" <?php echo ( ! $add_on->is_active() ? 'style="display: inline-block;"' : '' ); ?>><?php echo __( 'Inactive', 'slicewp' ); ?></span>
						</div>
					</div>

					<div class="slicewp-card-add-on-actions">
						<?php if ( ! empty( $add_on->get( 'settings_url' ) ) ): ?>
							<a href="<?php echo esc_url( $add_on->get( 'settings_url' ) ); ?>" class="slicewp-button-secondary" <?php echo ( ! $add_on->is_active() ? 'style="display: none;"' : '' ); ?>>
								<?php echo slicewp_get_svg( 'outline-cog' ); ?>
								<span><?php echo __( 'Settings', 'slicewp' ); ?></span>
							</a>
						<?php endif; ?>
					</div>

				</div>

			</div>

		<?php endforeach; ?>

		<?php if ( ! in_array( 'slicewp-pro/index.php', get_option( 'active_plugins', array() ) ) ): ?>

			<?php foreach ( $add_ons as $add_on ): ?>

				<div class="slicewp-card slicewp-card-add-on">

					<div class="slicewp-card-inner">

						<div class="slicewp-flex">

							<div>
								<?php if ( ! empty( $add_on['slug'] ) ): ?>
									<img src="<?php echo SLICEWP_PLUGIN_DIR_URL . '/assets/img/add-on-icon-' . esc_attr( $add_on['slug'] ) . '.png'; ?>" />
								<?php endif; ?>
							</div>

							<div>
								<h4><?php echo esc_html( $add_on['name'] ); ?></h4>
								<p><?php echo esc_html( $add_on['description'] ); ?></p>
							</div>

						</div>

					</div>

					<div class="slicewp-card-footer">
						<a href="<?php echo esc_url( add_query_arg( array( 'utm_source' => 'add-on-' . sanitize_title( $add_on['name'] ), 'utm_medium' => 'plugin-add-ons-page', 'utm_campaign' => 'SliceWPFree' ), $add_on['url'] ) ); ?>" target="_blank" class="slicewp-button-secondary"><?php echo __( 'Get this add-on', 'slicewp' ) ?></a>
					</div>

				</div>

			<?php endforeach; ?>

		<?php endif; ?>

	</div>

</div>