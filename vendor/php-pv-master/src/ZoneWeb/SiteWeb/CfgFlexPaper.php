<?php

namespace Pv\ZoneWeb\SiteWeb ;

class CfgFlexPaper
{
	public $Scale ;
	public $ZoomTransition = 'easeOut' ;
	public $ZoomTime = 0.5 ;
	public $ZoomInterval = 0.2 ;
	public $FitPageOnLoad = false ;
	public $FitWidthOnLoad = true ;
	public $PrintEnabled = true ;
	public $FullScreenAsMaxWindow = false ;
	public $ProgressiveLoading = false ;
	public $MinZoomSize = 0.2 ;
	public $MaxZoomSize = 5 ;
	public $SearchMatchAll = false ;
	public $InitViewMode = 'Portrait' ;
	public $ViewModeToolsVisible = true ;
	public $ZoomToolsVisible = true ;
	public $NavToolsVisible = true ;
	public $CursorToolsVisible = true ;
	public $SearchToolsVisible = true ;
	public $localeChain = 'fr_FR' ;
	public $SwfFile = '' ;
}