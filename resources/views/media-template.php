<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Load the content of the modal.
 *
 * @package   wp-simple-menu-icons
 * @author    Sematico LTD <hello@sematico.com>
 * @copyright 2020 Sematico LTD
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://directorystack.com
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $pagenow;

if ( $pagenow !== 'nav-menus.php' ) {
	return;
}

$icons = json_decode( file_get_contents( WP_SMI_PLUGIN_DIR . 'dist/icons.json' ) );

?>

<script type="text/html" id='tmpl-wpsmi-modal-backdrop'>
	<div class="media-modal-backdrop">&nbsp;</div>
</script>

<script type="text/html" id='tmpl-wpsmi-modal-window'>
	<div id="wpsmi_modal" class="media-modal wp-core-ui">
		<button type="button" class="media-modal-close close">
			<span class="media-modal-icon">
				<span class="screen-reader-text"><?php esc_html_e( 'Close panel' ); ?></span>
			</span>
		</button>
		<div class="media-frame mode-select wp-core-ui hide-menu">
			<div class="media-frame-title">
				<h1>
					<?php esc_html_e( 'Menu icons' ); ?>
				</h1>
			</div>
			<div class="media-frame-router">
				<div class="media-router">
					<a href="#" class="media-menu-item active"><?php esc_html_e( 'Browse icons' ); ?></a>
				</div>
			</div>
			<div class="media-modal-content">
				<div class="media-frame mode-select wp-core-ui">
					<div class="media-frame-menu">
						<div class="media-menu">
							<a href="#" class="media-menu-item active"><?php esc_html_e( 'Featured Image' ); ?></a>
						</div>
					</div>
					<div class="media-frame-content" data-columns="8">
						<div class="attachments-browser">
							<div class="media-toolbar">
								<div class="media-toolbar-secondary">
									<p><em><?php esc_html_e( 'Search' ); ?></em></p>
								</div>
								<div class="media-toolbar-primary search-form">
									<input type="search" placeholder="<?php esc_html_e( 'Search...' ); ?>" id="media-search-input" class="search">
								</div>
							</div>
							<ul tabindex="-1" class="attachments">

								<?php foreach ( $icons as $icon ) : ?>
									<li tabindex="0" role="checkbox" aria-label="<?php echo esc_attr( $icon->id ); ?>" aria-checked="false" data-id="<?php echo esc_attr( $icon->id ); ?>" class="attachment save-ready icon _<?php echo esc_attr( str_replace( ' ', '_', trim( $icon->id ) ) ); ?>">
										<div class="attachment-preview js--select-attachment type-image subtype-jpeg landscape">
											<div class="thumbnail">
												<i class="fas fa-<?php echo esc_attr( $icon->id ); ?>"></i>
											</div>
										</div>
										<button type="button" class="check" tabindex="-1">
											<span class="media-modal-icon"></span>
											<span class="screen-reader-text"><?php esc_html_e( 'Deselect' ); ?></span>
										</button>
									</li>
								<?php endforeach; ?>

							</ul>
							<div class="media-sidebar">
								<div tabindex="0" class="attachment-details save-ready">
									<h2>
										<?php esc_html_e( 'Icon' ); ?>
										<span class="settings-save-status">
											<span class="spinner"></span>
											<span class="saved">
												<?php esc_html_e( 'Saved' ); ?>
											</span>
										</span>
									</h2>
								</div>
							</div>
						</div>
					</div>
					<div class="media-frame-toolbar">
						<div class="media-toolbar">
							<div class="media-toolbar-secondary"></div>
							<div class="media-toolbar-primary search-form">
								<button type="button" class="button media-button button-large button-primary media-button-select save"><?php esc_html_e( 'Save' ); ?></button>
								<button type="button" class="button media-button button-large button-secondary remove"><?php esc_html_e( 'Remove' ); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/html" id='tmpl-wpsmi-modal-preview'>
	<div class="attachment-info">
		<div class="thumbnail thumbnail-image">
			<i class="{{ data.icon }}"></i>
		</div>
		<div class="details">
			<div class="filename">{{ data.icon }}</div>
			<div class="uploaded">{{ data.align }}</div>
			<div class="file-size">{{ data.size }} <em>(em)</em></div>
		</div>
	</div>
</script>

<script type="text/html" id='tmpl-wpsmi-modal-settings'>
	<div class="attachment-info">
		<form>
			<label class="setting">
				<span><?php esc_html_e( 'Hide Label' ); ?></span>
				<select id="wpsmi-input-label" class="wpsmi-input" name="wpsmi[label]">
					<option <# if ( data.label !=1) { #>selected<# } #> value=""><?php esc_html_e( 'No' ); ?></option>
					<option <# if ( data.label==1) { #>selected<# } #> value="1"><?php esc_html_e( 'Yes' ); ?></option>
				</select>
			</label>
			<label class="setting">
				<span><?php esc_html_e( 'Position' ); ?></span>
				<select id="wpsmi-input-position" class="wpsmi-input" name="wpsmi[position]">
					<option <# if ( data.position=='before' ) { #>selected<# } #> value="before"><?php esc_html_e( 'Before' ); ?></option>
					<option <# if ( data.position=='after' ) { #>selected<# } #> value="after"><?php esc_html_e( 'After' ); ?></option>
				</select>
			</label>
			<label class="setting">
				<span><?php esc_html_e( 'Vertical Align' ); ?></span>
				<select id="wpsmi-input-align" class="wpsmi-input" name="wpsmi[align]">
					<option <# if ( data.align=='top' ) { #>selected<# } #> value="top"><?php esc_html_e( 'Top' ); ?></option>
					<option <# if ( data.align=='middle' ) { #>selected<# } #> value="middle"><?php esc_html_e( 'Middle' ); ?></option>
					<option <# if ( data.align=='bottom' ) { #>selected<# } #> value="bottom"><?php esc_html_e( 'Bottom' ); ?></option>
				</select>
			</label>
			<label class="setting">
				<span><?php esc_html_e( 'Size' ); ?> <em>(em)</em></span>
				<input id="wpsmi-input-size" class="wpsmi-input" name="wpsmi[size]" type="number" min="0.1" step="0.1" value="{{ data.size }}">
			</label>
		</form>
	</div>
</script>
