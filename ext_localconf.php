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
preg_match_all('/options.pageTree.doktypesToShowInNewPageDragArea = ([0-9,]*)/', $GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'], $matches);
$inserted = false;
if($matches && count($matches) > 1){
	foreach($matches[1] as $subject){
		if(trim($subject)) {
			$doktypes = explode(',', $subject);
			array_splice($doktypes, 1, 0, $extConf['newsDoktype']);
			$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] = preg_replace( '/options.pageTree.doktypesToShowInNewPageDragArea = '.$subject.'/',
				'options.pageTree.doktypesToShowInNewPageDragArea = '.implode(',', $doktypes),
				$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] );
		}
	}
}
if(!$inserted) {
	$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] .= '
		options.pageTree.doktypesToShowInNewPageDragArea = 1,'.$extConf['newsDoktype'].',6,4,7,3,254,255,199';
}

// Manage news display in pagetree
if($extConf['hideNewsInPageTree']) {
	$inserted = false;
	preg_match_all('/options.pageTree.excludeDoktypes = ([0-9,]*)/', $GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'], $matches);
	if($matches && count($matches) > 1){
		foreach($matches[1] as $subject){
			if(trim($subject)) {
				$doktypes = explode(',', $subject);
				array_push($doktypes, $extConf['newsDoktype']);
				$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] = preg_replace( '/options.pageTree.excludeDoktypes = '.$subject.'/',
					'options.pageTree.excludeDoktypes = '.implode(',', $doktypes),
					$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] );
				$inserted = true;
			}
		}
	}
}
if(!$inserted) {
	$GLOBALS['TYPO3_CONF_VARS']['BE']['defaultUserTSconfig'] .= '
		options.pageTree.excludeDoktypes = '.$extConf['newsDoktype'];
}

?>