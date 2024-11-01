<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php if (isset($snapWizardToken)): ?>
    <?php if ($instagram === null): ?>
        <?php esc_html_e('Before proceeding, please ensure to save App ID and App Secret as they are mandatory.'); ?>
    <?php else: ?>
        <?php if (strlen($snapWizardToken) === 0): ?>
            <a href="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($instagram->getLoginUrl()); ?>"
               title="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Action needed!'); ?>">
                <span class="dashicons dashicons-sos"></span>
                <span><?php esc_html_e('Login with Instagram') ?></span>
            </a>
        <?php else: ?>
            <p>
                <span class="dashicons dashicons-smiley"></span>
                <span><?php esc_html_e('Instagram linked correctly!') ?></span>
            </p>
            <p>
                <a class="snapwizard_delete_existing_token" href="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(add_query_arg([
                    'delete_token' => $instagram->getAppId(),
                    'page' => \SnapWizard\Helpers\Constants::SNAPWIZARD_MENU_SLUG
                ])); ?>">
                    <span class="dashicons dashicons-trash"></span>
                    <span><?php esc_html_e('Delete existing token') ?></span>
                </a>
            </p>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($snapWizardAppId)): ?>
    <input type="text" id="snapwizard_app_id" name="snapwizard_app_id" size="50"
           value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardAppId); ?>" />
<?php elseif (isset($snapWizardAppSecret)): ?>
    <input type="password" id="snapwizard_app_secret" name="snapwizard_app_secret" size="50"
           value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardAppSecret); ?>" />
    <a href="#" target="_blank" class="snapwizard_dashicons show-app_secret"
       title="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Show App Secret'); ?>">
        <span class="dashicons dashicons-welcome-view-site" data-inputid="snapwizard_app_secret"></span>
    </a>
<?php elseif (isset($snapWizardSecretKey)): ?>
    <input type="text" id="snapwizard_secret_key" name="snapwizard_secret_key" size="50"
           value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardSecretKey); ?>" />
<?php elseif (isset($snapWizardRedirectUrl)): ?>
    <input type="text" id="snapwizard_redirect_url" name="snapwizard_redirect_url" size="50"
           value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardRedirectUrl); ?>" readonly />
    <a href="#" target="_blank" class="snapwizard_dashicons snapwizard_actions copy-to-clipboard"
       title="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Copy to clipboard'); ?>"
       data-inputid="snapwizard_redirect_url">
        <span class="dashicons dashicons-clipboard"></span>
    </a>
<?php elseif (isset($snapWizardProcessIGFeedUrl)): ?>
    <?php if (!$loggedIn): ?>
        <p>
            <span class="dashicons dashicons-lock"></span>
            <span><?php \SnapWizard\Helpers\Intl::i_esc_html_e('You need to log in on Instagram first!'); ?></span>
        </p>
    <?php else: ?>
        <input type="text" id="snapwizard_process_ig_feed_url" name="snapwizard_process_ig_feed_url" size="50"
               value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardProcessIGFeedUrl); ?>" readonly />

        <a href="#" target="_blank" class="snapwizard_dashicons snapwizard_actions copy-to-clipboard"
           title="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Copy to clipboard'); ?>"
           data-inputid="snapwizard_process_ig_feed_url">
            <span class="dashicons dashicons-clipboard"></span>
        </a>

        <p class="submit">
            <input type="button" name="snapwizard_dashicons_process" id="snapwizard_dashicons_process"
                   class="button button-primary snapwizard_dashicons snapwizard_actions process"
                    data-url="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardProcessIGFeedUrl); ?>"
                   value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Run in a new tab'); ?>" />

            <input type="button" name="snapwizard_dashicons_process_ajax" id="snapwizard_dashicons_process_ajax"
                   class="button button-primary snapwizard_dashicons snapwizard_actions process ajax"
                   data-url="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardProcessIGFeedUrl); ?>"
                   value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e('Run in background'); ?>" />

            <textarea id="snapwizard_actions_process_ajax_log" rows=10 cols=20 autofocus></textarea>
        </p>
    <?php endif; ?>
<?php endif; ?>
