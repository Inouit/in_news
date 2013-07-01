<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Inouit.' . $_EXTKEY,
	'Pi1',
	array(
		'Category' => 'list',
		'News' => 'list',
	),
	// non-cacheable actions
	array(
		'Category' => '',
		'News' => '',
	)
);

?>