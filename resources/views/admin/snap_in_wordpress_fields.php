<?php if (isset($snapwizardAuthorsAll)): ?>
<select id="snapwizard_author" name="snapwizard_author">
    <option value="-1"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('--Please choose an option--'); ?></option>
    <?php foreach($snapwizardAuthorsAll as $author): ?>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($author->ID); ?>"
        <?php (intval($snapwizardAuthor) === $author->ID) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e($author->data->display_name); ?>
    </option>
    <?php endforeach; ?>
</select>
<p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Attribute an author to the elements, or create a new one.'); ?></p>
<p>
    <a href="/wp-admin/user-new.php">
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Add new author'); ?>
    </a>
</p>
<?php elseif (isset($snapwizardPostCategoriesAll)): ?>
<select id="snapwizard_post_categories" name="snapwizard_post_categories[]" multiple>
    <option value=""><?php \SnapWizard\Helpers\Intl::i_esc_html_e('--Please choose an option--'); ?></option>
    <?php foreach($snapwizardPostCategoriesAll as $category): ?>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($category->term_id); ?>"
        <?php (in_array($category->term_id, ($snapwizardPostCategories) ? $snapwizardPostCategories : [])) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e($category->cat_name); ?>
    </option>
    <?php endforeach; ?>
</select>
<p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Assign a category to the posts, or create a new one.'); ?></p>
<p>
    <a href="/wp-admin/edit-tags.php?taxonomy=category">
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Add new category'); ?>
    </a>
</p>
<?php elseif (isset($snapwizardMediaCategoriesAll)): ?>
<select id="snapwizard_media_categories" name="snapwizard_media_categories[]" multiple>
    <option value=""><?php \SnapWizard\Helpers\Intl::i_esc_html_e('--Please choose an option--'); ?></option>
    <?php foreach($snapwizardMediaCategoriesAll as $category): ?>
    <option value="<?php \SnapWizard\Helpers\Intl::i_esc_attr_e($category->term_id); ?>"
        <?php (in_array($category->term_id, ($snapwizardMediaCategories) ? $snapwizardMediaCategories : [])) ? \SnapWizard\Helpers\Intl::i_esc_attr_e('selected') : ''; ?>>
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e($category->cat_name); ?>
    </option>
    <?php endforeach; ?>
</select>
<p class="info-field"><?php \SnapWizard\Helpers\Intl::i_esc_html_e('Assign a category to the media, or create a new one.'); ?></p>
<p>
    <a href="/wp-admin/edit-tags.php?taxonomy=category">
        <?php \SnapWizard\Helpers\Intl::i_esc_html_e('Add new category'); ?>
    </a>
</p>
<?php endif; ?>