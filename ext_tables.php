<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

// Plugin declaration
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
    'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xlf:tx_innews_domain_model_news'
);

// Wizard pi1
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';
if (TYPO3_MODE == 'BE') {
    $TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses'][$pluginSignature . '_wizicon'] =
        t3lib_extMgm::extPath($_EXTKEY) . 'Resources/Private/Php/class.' . strtolower($extensionName) . '_wizicon.php';
}

// Static
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Styles', 'News - styles');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/NewsCategoriesMenu', 'News - TS for categories menu');


// ExtConf
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
$newsDoktype = $extConf['newsDoktype'];

// Pages TCA for category
$tmp_in_news_category_columns = array(
    'tx_innews_category_list_page' => array (
        'exclude' => 0,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_domain_model_category.list_page',
        'config' => array (
            'type'     => 'group',
            'internal_type' => 'db',
            'allowed' => 'pages',
            'size'     => '1',
            'maxitems' => '1',
            'minitems' => '0',
             'wizards'  => array(
                'suggest' => array(
                    'type' => 'suggest',
                ),
            )
        )
    ),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category',$tmp_in_news_category_columns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category','tx_innews_category_list_page', '', 'after:description');

// Add field hidden on Frontend
$tmp_in_news_category_columns = array(
    'tx_innews_category_frontend_hidden' => array (
        'exclude' => 0,
        'label' => 'LLL:EXT:skin/Resources/Private/Language/locallang_db.xlf:tx_innews_domain_model_category.tx_innews_category_frontend_hidden',
        'config' => array(
            'type' => 'check',
        ),
    ),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_category',$tmp_in_news_category_columns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_category','tx_innews_category_frontend_hidden', '', 'after:title');


// Pages TCA for news
$tmp_in_news_columns = array(
    'tx_innews_news_top' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.top',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            'type' => 'check',
                'items' => array(
                    '1' => array(
                        '0' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.top.activate'
                    )
                )
        )
    ),
    'tx_innews_news_teaser' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.teaser',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            'type' => 'text',
            'cols' => 30,
            'rows' => 5,
        )
    ),
    'tx_innews_news_display_date' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.display_date',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            'type'     => 'input',
            'size'     => 8,
            'max'      => 20,
            'eval'     => 'date',
            'default'  => 0,
        )
    ),
    'tx_innews_event_from' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.from',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            'type'     => 'input',
            'size'     => 8,
            'max'      => 20,
            'eval'     => 'date',
            'default'  => 0,
        )
    ),
    'tx_innews_event_to' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.to',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            'type'     => 'input',
            'size'     => 8,
            'max'      => 20,
            'eval'     => 'date',
            'default'  => 0,
        )
    ),
    'tx_innews_event_further' => array (
        'exclude' => 1,
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.further',
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'l10n_mode' => 'noCopy',
        'config' => array(
            'type' => 'text',
            'cols' => 30,
            'rows' => 5,
            'softref' => 'rtehtmlarea_images,typolink_tag,images,email[subst],url',
            'wizards' => array(
                '_PADDING' => 2,
                'RTE' => array(
                    'notNewRecords' => 1,
                    'RTEonly' => 1,
                    'type' => 'script',
                    'title' => 'Full screen Rich Text Editing',
                    'icon' => 'wizard_rte2.gif',
                    'script' => 'wizard_rte.php',
                ),
            ),
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tmp_in_news_columns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages',', --palette--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.informations;newsInfos, --palette--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.informations;eventInfos, tx_innews_event_further;;;richtext::rte_transform[flag=rte_disabled|mode=ts_css]', '', 'after:title');

$TCA['pages']['palettes']['newsInfos'] = array(
    'canNotCollapse' => 1,
    'showitem' => ' tx_innews_news_top, tx_innews_news_display_date, --linebreak--, tx_innews_news_teaser' );
$TCA['pages']['palettes']['eventInfos'] = array(
    'canNotCollapse' => 1,
    'showitem' => ' tx_innews_event_from, tx_innews_event_to, --linebreak--,,');

// News doktype
t3lib_div::loadTCA('pages');
$newPageTypeOrder = 2;
for($i=0; $i < $newPageTypeOrder; $i++) {
    $temp[$i] = $TCA['pages']['columns']['doktype']['config']['items'][$i];
}
for($i=$newPageTypeOrder; $i < count($TCA['pages']['columns']['doktype']['config']['items']); $i++) {
    $temp[$i+1] = $TCA['pages']['columns']['doktype']['config']['items'][$i];
}
$temp[$newPageTypeOrder] = array ('0' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_domain_model_news',
                                    '1' => $newsDoktype);
$TCA['pages']['columns']['doktype']['config']['items'] = $temp;
ksort($TCA['pages']['columns']['doktype']['config']['items']);

$PAGES_TYPES[$newsDoktype] = Array('icon' => "EXT:".$_EXTKEY."/Resources/Public/icons/news.png",
                                    'allowedTables' => '*',);
$TCA['pages']['types'][$newsDoktype]['showitem'] = $TCA['pages']['types'][1]['showitem'];


// Flexform
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/news.xml');
?>