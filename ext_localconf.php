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

?>