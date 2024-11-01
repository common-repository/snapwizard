<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php if (isset($snapWizardLastRun)): ?>
    <?php \SnapWizard\Helpers\Intl::i_esc_html_e($snapWizardLastRun); ?>
<?php elseif (isset($snapWizardComputations)): ?>
    <?php \SnapWizard\Helpers\Intl::i_esc_html_e($snapWizardComputations); ?> (ms)
<?php elseif (isset($snapWizardSystemCalls)): ?>
    <?php \SnapWizard\Helpers\Intl::i_esc_html_e($snapWizardSystemCalls); ?> (ms)
<?php endif; ?>
