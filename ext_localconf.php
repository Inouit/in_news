<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Inouit.' . $_EXTKEY,
	'Pi1',
	array(
		'News' => 'list, show',
		'Category' => 'list',
	),
	// non-cacheable actions
	array(
		'News' => 'list, show',
		'Category' => '',
	)
);


// define top panel shorcut
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] .= '
  options.pageTree.doktypesToShowInNewPageDragArea = 1,'.$extConf['newsDoktype'].',6,4,7,3,254,255,199';

// Manage news display in pagetree
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
if($extConf['hideNewsInPageTree']) {
	$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] .= '
	  options.pageTree.excludeDoktypes = '.$extConf['newsDoktype'];
}

?>