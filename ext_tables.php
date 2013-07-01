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

// Static
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Default styles');


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


// Pages TCA for news
$tmp_in_news_columns = array(
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
    'tx_innews_news_event_from' => array (    
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
    'tx_innews_news_event_to' => array (  
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
    'tx_innews_news_event_where' => array (   
        'exclude' => 1,     
        'label' => 'LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.where',       
        'displayCond' => 'FIELD:doktype:=:'.$newsDoktype,
        'config'      => array (
            "type" => "input",  
            "size" => "30",
        )   
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tmp_in_news_columns);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages',', --div--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_domain_model_news, --palette--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_news.informations;newsInfos, --palette--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.dates;eventDates, --palette--;LLL:EXT:in_news/Resources/Private/Language/locallang_db.xlf:tx_innews_event.informations;eventInfos', '', 'after:title');

$TCA['pages']['palettes']['newsInfos'] = array( 
    'canNotCollapse' => 1,
    'showitem' => ' tx_innews_news_teaser' );
$TCA['pages']['palettes']['eventDates'] = array( 
    'canNotCollapse' => 1,
    'showitem' => ' tx_innews_news_event_from, tx_innews_news_event_to,');
$TCA['pages']['palettes']['eventInfos'] = array( 
    'canNotCollapse' => 1,
    'showitem' => ' tx_innews_news_event_where,');

// Make news categorizable
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    $_EXTKEY, 'pages', 'tx_innews_news_category', array(
        'position' => 'after:tx_innews_news_event_where',
        'typesList' => $newsDoktype,
        'fieldConfiguration' => array(
            'maxitems' => '1',
        )
    )
);

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

$PAGES_TYPES[$newsDoktype] = Array('icon' => "EXT:".$_EXTKEY."/Resources/Public/icons/pages.gif",
                                    'allowedTables' => '*',);
$TCA['pages']['types'][$newsDoktype]['showitem'] = $TCA['pages']['types'][1]['showitem'];


// Flexform
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/news.xml');
?>