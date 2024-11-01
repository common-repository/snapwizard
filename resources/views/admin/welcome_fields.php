<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php if ($field === 'description'): ?>
    <span><?php esc_html_e('Embed Your Instagram Feed in your WordPress website in a Snap with SnapWizard!'); ?></span>
<?php elseif ($field === 'how_to'): ?>
    <p><?php esc_html_e('SnapWizard is based on Instagram Basic Display API.'); ?></p>
    <p><?php esc_html_e('The Instagram Basic Display API allows users of your app to get basic profile information, photos, and videos in their Instagram accounts.'); ?></p>
    <a href="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('https://developers.facebook.com/docs/instagram-basic-display-api/getting-started'); ?>" target="_blank">
        <p><?php esc_html_e('Follow the instructions in the Getting Started page and start using SnapWizard!'); ?></p>
    </a>
<?php endif; ?>
