<?php
/**
 * @var string                     $multiple_providers_notice The notice to display when there are multiple ticket providers.
 * @var array<array<string,mixed>> $active_providers          The active ticket providers, not including RSVP.
 * @var string                     $default_module_class      The default ticket provider class.
 */

$post_id = get_the_ID();

$multiple_providers = 1 < count( $active_providers );
$current_provider   = Tribe__Tickets__Tickets::get_event_ticket_provider_object( $post_id );
// We use 'screen-reader-text' to hide it if there really aren't any choices.
$fieldset_class = $multiple_providers ? 'input_block' : 'screen-reader-text';
?>

<?php if ( tribe_is_truthy( tribe_get_request_var( 'is_admin', true ) ) ) : ?>
	<fieldset class="<?php echo esc_attr( $fieldset_class ); ?>">
		<?php if ( ! $multiple_providers ) : ?>
			<?php foreach ( $active_providers as $active_provider ) : ?>
				<input
					type="radio"
					class="tribe-ticket-editor-field-default_provider settings_field"
					name="tribe-tickets[settings][default_provider]"
					id="provider_<?php echo esc_attr( $active_provider['html_safe_class'] . '_radio' ); ?>"
					value="<?php echo esc_attr( $active_provider['class'] ); ?>"
					checked
				>
			<?php endforeach; ?>
		<?php else : ?>
			<section style="margin-bottom: 0;">
				<legend id="default_ticket_provider_legend" class="ticket_form_left"><?php
					echo esc_html(
						sprintf(
							__( 'Sell %s using:', 'event-tickets' ),
							tribe_get_ticket_label_plural_lowercase( 'default_ticket_provider' )
						)
					);
					?></legend>
				<p class="ticket_form_right">
                    <?php echo wp_kses_post( $multiple_providers_notice); ?>
				</p>
				<?php foreach ( $active_providers as $active_provider ) : ?>
					<label class="ticket_form_right" for="provider_<?php echo esc_attr( $active_provider['html_safe_class'] . '_radio' ); ?>">
						<input
							<?php checked( $current_provider->class_name, $active_provider['class'] ); ?>
							type="radio"
							name="tribe-tickets[settings][default_provider]"
							id="provider_<?php echo esc_attr( $active_provider['html_safe_class'] . '_radio' ); ?>"
							value="<?php echo esc_attr( $active_provider['class'] ); ?>"
							class="tribe-ticket-editor-field-default_provider settings_field ticket_field"
							aria-labelledby="default_ticket_provider_legend"
							<?php disabled( $active_provider['disabled'] ?? false, true ); ?>
						>
						<?php
						/**
						 * Allows for editing the module name before display
						 *
						 * @since 4.6
						 *
						 * @param string $module - the name of the module
						 */
						echo esc_html( apply_filters( 'tribe_events_tickets_module_name', $active_provider['name'] ) );
						?>
					</label>
				<?php endforeach; ?>
			</section>
		<?php endif; ?>
	</fieldset>
<?php else: ?>
	<input
		type="hidden"
		name="tribe-tickets[settings][default_provider]"
		value="<?php echo esc_attr( $default_module_class ); ?>"
		class="tribe-ticket-editor-field-default_provider settings_field ticket_field"
	>
<?php endif;
