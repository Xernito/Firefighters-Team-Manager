<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;


defined('JPATH_BASE') or die;

$app = Factory::getApplication();
$params = $app->getParams('com_firefighters');

$data = $displayData;

// Receive overridable options
$data['options'] = !empty($data['options']) ? $data['options'] : array();

// Set some basic options
$customOptions = array(
    'filtersHidden' => isset($data['options']['filtersHidden']) ? $data['options']['filtersHidden'] : empty($data['view']->activeFilters),
    'defaultLimit' => isset($data['options']['defaultLimit']) ? $data['options']['defaultLimit'] : Factory::getApplication()->get('list_limit', 20),
    'searchFieldSelector' => '#filter_search',
    'orderFieldSelector' => '#list_fullordering'
);

$data['options'] = array_unique(array_merge($customOptions, $data['options']));

$formSelector = !empty($data['options']['formSelector']) ? $data['options']['formSelector'] : '#adminForm';
$filters = false;
if (isset($data['view']->filterForm)) {
    $filters = $data['view']->filterForm->getGroup('filter');
}

if ($params->get('show_filter','1')) {

// Load search tools
HTMLHelper::_('searchtools.form', $formSelector, $data['options']);
?>

<div class="js-stools clearfix">
    <div class="clearfix">
        <div class="js-stools-container-bar">

            <label for="filter_search" class="element-invisible" aria-invalid="false"><?php echo Text::_(''); ?></label>

            <div class="btn-wrapper input-append">
                <?php echo $filters['filter_search']->input; ?>
                <button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo Text::_('Suchen'); ?>">
                    <i class="icon-search"></i>
                </button>
            </div>
            <?php if ($filters) : ?>
            <div class="btn-wrapper d-none d-md-table-cell">
                <button type="button" class="btn hasTooltip js-stools-btn-filter" title=""
                        data-original-title="<?php echo Text::_('Filter'); ?>">
                    <?php echo Text::_('Such-Filter'); ?> <i class="caret"></i>
                </button>
            </div>
            <?php endif; ?>
            <div class="btn-wrapper">
                <button type="button" class="btn hasTooltip js-stools-btn-clear" title="" data-original-title="<?php echo Text::_('Alle Filter zurücksetzen'); ?>">
                    <?php echo Text::_('Alle Filter zurücksetzen'); ?>
                </button>
            </div>
        </div>
    </div>
    <!-- Filters div -->
    <div class="js-stools-container-filters d-none d-md-table-cell clearfix" style="">
        <?php // Load the form filters ?>
        <?php if ($filters) : ?>
            <?php foreach ($filters as $fieldName => $field) : ?>
                <?php if ($fieldName != 'filter_search') : ?>
                    <div class="js-stools-field-filter">
                        <?php echo $field->input; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
}