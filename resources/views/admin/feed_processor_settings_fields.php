<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>

<?php if (isset($snapWizardLimitPerPage)): ?>
    <div class="notice notice-info">
        <p>
            <span><?php \SnapWizard\Helpers\Intl::i_esc_html_e('If your feed contains hundreds or thousands of elements, begin by selecting the "Only Images" media type, save the Settings, then run the Feed Processor for the initial pass, and subsequently proceed with "Only Video" and other types as needed. Additionally, ensure a high limit per page to minimize excessive calls to Instagram. If you encounter a timeout error, just re-run the processor. Feel free to reach out if you encounter any bugs.'); ?></span>
        </p>
    </div>

<input type="text" id="snapwizard_limit_per_page" name="snapwizard_limit_per_page" size="50"
       value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($snapWizardLimitPerPage ? $snapWizardLimitPerPage : \SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_LIMIT_PER_PAGE); ?>" />

<p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Increase the limit (e.g. 500) if you have lot of content on your Instagram.'); ?></p>
<?php elseif (isset($snapWizardFileType)): ?>
<select id="snapwizard_file_type" name="snapwizard_file_type">
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ALL); ?>"
        <?php ($snapWizardFileType === \SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ALL) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('All'); ?>
    </option>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_IMAGES); ?>"
        <?php ($snapWizardFileType === \SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_IMAGES) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Only images'); ?>
    </option>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_VIDEOS); ?>"
        <?php ($snapWizardFileType === \SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_VIDEOS) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Only videos'); ?>
    </option>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e(\SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_CAROUSEL_ALBUM); ?>"
        <?php ($snapWizardFileType === \SnapWizard\Helpers\Constants::SNAPWIZARD_INSTAGRAM_MEDIA_TYPE_SELECT_ONLY_CAROUSEL_ALBUM) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Only carousel'); ?>
    </option>
</select>
<p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Choose the type of element you want to snap from your feed.'); ?></p>
<?php elseif (isset($snapWizardExclude)): ?>
    <textarea id="snapwizard_exclude" name="snapwizard_exclude" rows="10" cols="50"><?php \SnapWizard\Helpers\Intl::i_esc_html_e($snapWizardExclude); ?></textarea>
    <p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Insert the filenames you want to exclude from your feed, one per line.'); ?></p>
<?php endif; ?>
