<?php
/**
 * Donation reminder modal. Opened by #supportOpenBtn (assets/support.js).
 * Copy and URL come from Appearance > Customize > Regeneracion Theme Options.
 */
$support_heading = get_theme_mod( 'regen_support_heading', 'Support Our Work' );
$support_url     = get_theme_mod( 'regen_support_url', 'https://give.ucsb.edu/campaigns/58594/donations/new' );
$support_pop_msg = get_theme_mod( 'regen_support_popover_message', 'When you check out, specify the donation is for Regeneracion Lab.' );
$support_pop_cta = get_theme_mod( 'regen_support_popover_button', 'Continue' );
?>
<div class="support-modal" id="supportModal" hidden>
    <div class="support-modal-backdrop" data-support-close></div>
    <div class="support-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="supportModalTitle">
        <h3 id="supportModalTitle" class="support-modal-title"><?php echo esc_html( $support_heading ); ?></h3>
        <p class="support-modal-text"><?php echo wp_kses_post( $support_pop_msg ); ?></p>
        <div class="support-modal-actions">
            <button type="button" class="support-modal-cancel" data-support-close><?php esc_html_e( 'Cancel', 'regen-wp' ); ?></button>
            <a href="<?php echo esc_url( $support_url ); ?>" target="_blank" rel="noopener" class="support-modal-continue" data-support-continue><?php echo esc_html( $support_pop_cta ); ?> &rarr;</a>
        </div>
    </div>
</div>
