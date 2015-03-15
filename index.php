<?php

//  Jumbotron Script v4
//  2/8/2015
//  Ted Zhu, tedz2usa@gmail.com




/*******************  Jumbotron Settings  ********************/


/**
 ** Diagnostic mode setting:
 ** 
 **    When true, will overlay useful text information.
 ** 
 **    Set to 'true' or 'false', without quotes. e.g.: 
 **
 **     $diagnostic_mode = true;
 **     $diagnostic_mode = false;
 **/
$diagnostic_mode = true;

/**
 ** Date control:
 **
 **    $enable_date_control (true or false):
 **    Whether or not to use filename format to control when an image is displayed.
 **    Filename format: "yyyymmdd-yyyymmdd-eventname.jpg"
 **
 **    $show_unformatted_images_if_date_control_enabled (true or false):
 **    The script would want to know how to treat images with filenames that aren't 
 **    formatted like "yyyymmdd-yyyymmdd-eventname.jpg" when date control is enabled.
 **     true:  Unformatted image filenames are displayed by default.
 **     false: Unformatted image filenames are hidden by default.
 **
 **    Set each to 'true' or 'false', without quotes. e.g.: 
 **
 **     $enable_date_control = true;
 **     $show_unformatted_images_if_date_control_enabled = false;
 **/
$enable_date_control = true;
$show_unformatted_images_if_date_control_enabled = false;


/**
 ** Directory of images setting:
 ** 
 **    e.g., 'JumbotronSlides/'.
 **/
$directory = 'JumbotronSlides/';

/**
 ** Image fitting setting: 
 **
 **   'cover'      =>  Scales the image so that no space is seen on any edges,
 **                      image cropping along one dimension may occur.
 **   'contain'    =>  Scales the image until the biggest dimention fits,
 **                      image cropping will not occur, 
 **                      spacing might be seen along one set of edges.
 **   'fit-width'  =>  Scales the image so that the width is guaranteed to fit. 
 **   'fit-height' =>  Scales the image so that the height is guaranteed to fit.
 **/
$image_fitting = 'cover';

/**
 ** Animation timing settings:
 **
 **   $hold_slide    => In seconds, how long to hold each slide for. 
 **                     You may use decimals.
 **
 **   $fade_duration => In seconds, the duration of the fade animation. 
 **                     You may use decimals. You may use zero to indicate no fade.
 **/
$hold_slide = 2;
$fade_duration = 0;

/**
 ** Reload Image Deck setting:
 **
 **   0 => Never reload the image deck.
 **   1 => Reload the image deck after every image.
 **   2 => Reload the image deck at the end of the deck.
 **/
$reload_image_deck = 1;

/**
 ** Time zone setting:
 **
 ** For:
 **
 **   Eastern .............  use =>  'America/New_York'
 **   Central .............  use =>  'America/Chicago'
 **   Mountain ............  use =>  'America/Denver'
 **   Mountain (no DST) ...  use =>  'America/Phoenix'
 **   Pacific .............  use =>  'America/Los_Angeles'
 **   Alaska ..............  use =>  'America/Anchorage'
 **   Hawaii ..............  use =>  'America/Adak'
 **   Hawaii (no DST) .....  use =>  'Pacific/Honolulu'
 **/
date_default_timezone_set('America/New_York');


/****************  End of Jumbotron Settings  ****************/









/*********  Do not change anything below this line.  *********/

/*********  Do not change anything below this line.  *********/

/*********  Do not change anything below this line.  *********/


$image_fitting_to_css = [
	'cover' => 'cover',
	'contain' => 'contain',
	'fit-width' => '100% auto',
	'fit-height' => 'auto 100%'
];


$files = scandir($directory);

$regular_files = [];
$file_info = [];

$now = date_create();

foreach ($files as $file) {
	$relative_path = $directory . $file;
	if (is_file($relative_path) && ($file[0] != '.')) { // Ignore files that begin with '.'
	
		// Found a file. 
		
		$info = [];
		$info['filename'] = $file;
		$info['relative-path'] = $relative_path;
		$info['start-date'] = '---';
		$info['end-date'] = '---';
		$info['shown'] = 'No';
		$info['tr-classes'] = '';
		$yes_shown = "Yes";
		
		
		// Check date control settings.
		
		if ($enable_date_control) { 
		
			if ( strlen($file) >= 18 ) {
			
				$part_one = substr($file, 0, 8);
				$part_two = substr($file, 9, 8);
			
				// Checking "yyyymmdd-yyyymmdd-" ...				
				$start_date = date_create_from_format('Ymd', $part_one);
				$end_date = date_create_from_format('Ymd', $part_two);
				
				// Check if a valid dates were parsed.				
				if ( $start_date && $end_date ) {
					// Valid dates were parsed from start and end.
					$info['start-date'] = $start_date->format('F j, Y');
					$info['end-date'] = $end_date->format('F j, Y');
					if ( ($start_date <= $now) && ($now <= $end_date) ) {				
						array_push($regular_files, $relative_path);
						$info['shown'] = $yes_shown;
						$info['tr-classes'] = ' shown';
					}
				} else {
					// Valid dates were NOT parsed from start and end.
					if ($show_unformatted_images_if_date_control_enabled) {
						array_push($regular_files, $relative_path);
						$info['shown'] = $yes_shown;
						$info['tr-classes'] = ' shown';
					}
				}
				
			} // End String length was greater than or equal to 18.
			
		
		} else {  // Date control was not enabled. Display everything.
			array_push($regular_files, $relative_path);			
			$info['shown'] = $yes_shown;
			$info['tr-classes'] = ' shown';
		}
		
		array_push($file_info, $info);
		
	} // End if $file is a regular file.
	
} // End foreach $files as $file.

$settings = [
	'picturePaths'      => $regular_files,
	'holdSlide'         => $hold_slide,
	'fadeDuration'      => $fade_duration,
	'slidePeriod'       => $hold_slide * 1000,
	'fadePeriod'        => $fade_duration * 1000,
	'diagnosticMode'    => $diagnostic_mode,
	'fileInformation'   => $file_info,
	'enableDateControl' => $enable_date_control,
	'showUnformatted'   => $show_unformatted_images_if_date_control_enabled,
	'currentDate'       => $now->format('F j, Y'),
	'reloadImageDeck'  => $reload_image_deck
];


// Handle AJAX HTTP GET request for images and settings.
if (isset($_GET['updated_images'])) {
	echo json_encode($settings);
	exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Jumbotron Test</title>

<style>

body {
	margin: 0;
	padding: 0;
}

div.absolute {
	position: absolute;
}

div.slide {
	width: 100%;
	height: 100%;
	background-color: #000;
	background-size: <?php echo $image_fitting_to_css[$image_fitting] ?>;
	background-position: center;
	background-repeat: no-repeat;
	opacity: 1;
}

div#diagnostics {
	background-color: rgba(255, 255, 255, 0.9);
	padding: 20px 15px;
	margin: 10px;
	border: 1px solid #000;
	font-size: 16px;
	display: none;
}

div#diagnostics table {
	text-align: center;
	border: 1px solid #000;
	border-collapse: collapse;
	margin: 5px 0 20px 0;
}

div#diagnostics table th {
	border-bottom: 1px solid #000;
}

div#diagnostics table td, th {
	padding: 3px 8px;
}

div#diagnostics table tr.shown {
	font-weight: bold;
	color: #090;
}

div#diagnostics .d_unit {
	margin-bottom: 10px;
}

div#diagnostics .d_label {
	padding-bottom: 2px;
	font-size: 16px;
}

div#diagnostics .d_value {
	font-family: "Courier New", Courier, monospace;
	font-size: 14px;
	margin-left: 10px;
	margin-right: 10px;
}


div#diagnostics .anim_note {
	margin-bottom: 10px;
	font-style: italic;
	color: #a00;
	font-size: 16px;
}

.align-left {
	text-align: left;
}

div#loadingMessage {
	font-size: 30px;
	font-weight: bold;
	color: #aaa;
	text-align: center;
	width: 100%;
	height: 100%;
}

</style>
<script>

var log = console.log.bind(console);

var currentImage, // The DOM Node representing the currentImage div.
  nextImage,      // The DOM Node representing the nextImage div.
  length,         // The of the array of pictures in the slideshow.
  index,          // The index for the current image of the slideshow.
  fadeBeginTime;  // The begin time of the fade animation.

var diagnostics = {
	dom: null,      // The DOM Node representing the diagnostics div.
	dataDom: {},    // The DOM elements that hold the data to be displayed.
	dataFields: []  // The data fields to be displayed.	
}

var frameRate = 0, // The calculated frame rate based on animation frame timestamps.
	lastFrameTime,   // The timestamp of the last frame.
	frameRateTimer;  // Timer variable to slow down frame rate display update.

var settings = <?php echo json_encode($settings); ?>;

var picturePaths,
	holdSlide,
	fadeDuration,
	slidePeriod,
	fadePeriod,
	diagnosticMode,
	fileInformation,
	enableDateControl,
	showUnformatted,
	currentDate,
	reloadImageDeck;

var canAnimate = !!(window.requestAnimationFrame) && false;

var httpRequest;

// Initialization routine that should only be run once.
window.onload = function() {
	
	diagnostics.dom = document.getElementById('diagnostics');
	currentImage = document.getElementById('currentImage');
	nextImage = document.getElementById('nextImage');
	loading = document.getElementById('loadingMessage');
	
	index  = 0;
	loadSettings(settings);
	
	// Initially load the first image.
	var image = new Image();
	image.src = picturePaths[index];
	image.onload = (function() {
		loading.style.display = 'none';
		// After first image is loaded, 
		// we begin timing for the next slide change.
		setTimeout(changeSlide, slidePeriod);
	});

	setBackgroundUrl(currentImage, picturePaths[index]);
	setBackgroundUrl(nextImage, picturePaths[nextIndex()]);
	
	httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = ajaxCallback;

};

// Given an object with settings information, update the client script behavior.
function loadSettings(obj) {
	picturePaths      = obj.picturePaths;
	holdSlide         = obj.holdSlide;
	fadeDuration      = obj.fadeDuration
	slidePeriod       = obj.slidePeriod;
	fadePeriod        = obj.fadePeriod;
	diagnosticMode    = obj.diagnosticMode;
	fileInformation   = obj.fileInformation;
	enableDateControl = obj.enableDateControl;
	showUnformatted   = obj.showUnformatted;
	currentDate       = obj.currentDate;
	reloadImageDeck   = obj.reloadImageDeck;
	length            = picturePaths.length;
	
	if (diagnosticMode) { 
		initDiagnostics();
	} else {
		diagnostics.dom.style.display = 'none';
	}
	
}

// This function is called to initiate a slide change.
function changeSlide() {
	if (canAnimate) {
		// Begin the fadeout animation by calling its first frame.
		window.requestAnimationFrame(fadeoutFrame);
	} else {
		advanceBackgrounds();
	}
}

// This function is called to update the background image for currentImage and nextImage.
function advanceBackgrounds() {

	incrementIndex();
	
	// Let the currentImage div have its background be the new current image, 
	//   and set its opacity back to 1.
	setBackgroundUrl(currentImage, picturePaths[index]);
	currentImage.style.opacity = 1;
	
	// Depending on how often to reload the image deck...
	if (reloadImageDeck == 0) {
		
		// Never reload the image deck.
		finishAdvanceBackgrounds();
		
	} else if (reloadImageDeck == 1) {
		
		// Reload the image deck after every image.
		makeAjaxRequest();
		
	} else if (reloadImageDeck == 2) {
		
		// Reload the image deck only if at the end of the slide show.
		if (nextIndex() == 0) {
			makeAjaxRequest();
		} else {
			finishAdvanceBackgrounds();
		}
		
	} else {
	
		finishAdvanceBackgrounds();
		
	}	
	
}

// Finishes what advanceBackground is supposed to do. 
//   But will only be called after a successful ajax response.
function finishAdvanceBackgrounds() {
	// Set the nextImage div background to the next image.
	setBackgroundUrl(nextImage, picturePaths[nextIndex()]);	
	// Initiate a timer for the next slide change.
	setTimeout(changeSlide, slidePeriod);
}

// Makes an Ajax request for latest images and settings.
function makeAjaxRequest() {
	httpRequest.open('GET', '?updated_images=1', true);
	httpRequest.send();
}

// This callback will run when the Ajax request is successful.
function ajaxCallback() {
	if (httpRequest.readyState === 4) {
		if (httpRequest.status === 200) {
				// AJAX request was successful!
				var response = JSON.parse(httpRequest.responseText);
				loadSettings(response);
				finishAdvanceBackgrounds();
		} else {
				// there was a problem with the request,
				// for example the response may contain a 404 (Not Found)
				// or 500 (Internal Server Error) response code
		}
	}
}

// This function is called to render a frame of the fadeout animation.
function fadeoutFrame(currentTimeStamp) {
	
	var fadeProgress;
	
	// Store the begin time of the fade animation, if this is the first frame.
	if (!fadeBeginTime) {
		fadeBeginTime = currentTimeStamp;
	}
	
	// Calculate the fade progress as a percentage from 0 to 1.
	if (fadePeriod == 0) {
		fadeProgress = 1;
	} else {
		fadeProgress = (currentTimeStamp - fadeBeginTime) / fadePeriod;
	}
	
	// Set the opacity value based on progress of animation.
	currentImage.style.opacity = 1 - fadeProgress;
	
	// If we've reached the end of the animation...
	if (fadeProgress >= 1) {
		fadeBeginTime = null;
		advanceBackgrounds();
	} else {
		// Otherwise, we are still in the middle of the animation;
		//   request the next frame of animation.
		window.requestAnimationFrame(fadeoutFrame);	
	}
	
	if (diagnosticMode) {
		if (frameRateTimer < 5) {
			frameRateTimer++;
		} else {
			frameRate = 1000 / (currentTimeStamp - lastFrameTime);
			frameRate = frameRate || 0;
			updateFrameRate();
			frameRateTimer = 0;
		}
		lastFrameTime = currentTimeStamp;		
	}
	
}

// Initializes the diagnostics view.
function initDiagnostics() {

	var dataLabels = {
		'current-date': 'Current Date:',
		'image-dir': 'Relative Image Directory:',
		'date-control': 'Date Controls Images to Display:',
		'show-hidden': 'Images with No Date Info in their Filename:',
		'found-files': 'Found Files:',
		'reload-deck': 'Reload Image Deck:',
		'animation-timings': 'Animation Timings:',
		'frame-rate': 'Frame Rate:'
	};
	
	diagnostics.dataFields = [];
	diagnostics.dataFields.push('current-date');
	diagnostics.dataFields.push('date-control');
	
	if (enableDateControl) {
		diagnostics.dataFields.push('show-hidden');
	}

	diagnostics.dataFields.push('image-dir');
	diagnostics.dataFields.push('found-files');
	diagnostics.dataFields.push('reload-deck');
	diagnostics.dataFields.push('animation-timings');
	diagnostics.dataFields.push('frame-rate');
	
	diagnostics.dom.innerHTML = '';
	
	if (!canAnimate && fadeDuration!=0) {
		var notice = document.createElement('div');
		notice.innerText = 'Please update your browser so that fade animations can work.';
		notice.className = 'anim_note';
		diagnostics.dom.appendChild(notice);
	}

	var field;
	
	for ( var index in diagnostics.dataFields ) {
		var field = diagnostics.dataFields[index];
		var dom = document.createElement('div');
		dom.className = 'd_unit';
		var label = document.createElement('div');
		label.innerText = dataLabels[field];
		label.className = 'd_label';
		var value = document.createElement('div');
		value.className = 'd_value';
		diagnostics.dataDom[field] = value;
		dom.appendChild(label);
		dom.appendChild(value);
		diagnostics.dom.appendChild(dom);
	}
	
	field = 'current-date';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		diagnostics.dataDom[field].innerText = currentDate;
	}
	
	field = 'image-dir';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		diagnostics.dataDom[field].innerText = '<?php echo $directory; ?>';
	}
	
	field = 'date-control';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		diagnostics.dataDom[field].innerText = enableDateControl ? 'Yes' : 'No';
	}
	
	field = 'show-hidden';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		diagnostics.dataDom[field].innerText = showUnformatted ? 'Shown' : 'Not Shown';
	}
	
	field = 'found-files';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		
		var fileTable = document.createElement('table');
		var fileTableHead = document.createElement('thead');
		var fileTableBody = document.createElement('tbody');
		var infoFields;
		if (enableDateControl) {
			infoFields = ['filename', 'start-date', 'end-date', 'shown'];
		} else {
			infoFields = ['filename', 'shown'];
		}
		var fieldToHead = {
			'filename': 'Filename',
			'start-date': 'Start Date',
			'end-date': 'End Date',
			'shown': 'Showing'
		}
	
		for (var i = 0; i < infoFields.length; i++) {
			var th = document.createElement('th');
			th.innerHTML = fieldToHead[infoFields[i]];
			if (i == 0) {
				th.className += ' align-left';
			}
			fileTableHead.appendChild(th);
		}
		fileTable.appendChild(fileTableHead);
	
		for (var i = 0; i < fileInformation.length; i++) {
			var tr = document.createElement('tr');
			tr.className += ' ' + fileInformation[i]['tr-classes'];
			for (var j = 0; j < infoFields.length; j++) {
				var td = document.createElement('td');
				td.innerHTML = fileInformation[i][infoFields[j]];
				if (j == 0) {
					td.className += ' align-left';
				}
				tr.appendChild(td);
			}
			fileTable.appendChild(tr);
		}
		diagnostics.dataDom[field].appendChild(fileTable);
		
	}
	
	field = 'reload-deck';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		var reloadDeckValues = {
			 0: 'Never reload the image deck.',
			 1: 'Reload the image deck after every image.',
			 2: 'Reload the image deck at the end of the deck.'
		}
		diagnostics.dataDom[field].innerText = reloadDeckValues[reloadImageDeck];
	}
	
	field = 'animation-timings';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		diagnostics.dataDom[field].innerHTML = 'Hold slide:&nbsp;&nbsp;&nbsp;&nbsp;' + holdSlide + ' sec '; // + '<br> Fade duration: ' + fadeDuration + ' sec';
	}
	
	field = 'frame-rate';
	if (diagnostics.dataFields.indexOf(field) > -1) {
		updateFrameRate();
	}
	
	diagnostics.dom.style.display = 'block';
	
}

// Update the frame rate value, called only if in diagnostics mode.
function updateFrameRate() {
 	diagnostics.dataDom['frame-rate'].innerHTML = frameRate.toFixed(1) + ' fps';
}

// Given an element, sets its background image to the given url.
function setBackgroundUrl(element, url) {
	element.style.backgroundImage = "url('" + url + "')";
}

// Gives the index of the next image, looping back to zero when needed.
function nextIndex() {
	return (index + 1) % length;
}

// Updates the current index to be the next index value.
function incrementIndex() {
	index = nextIndex();
}

</script>
	</head>
	<body>
		<div id='nextImage' class='slide absolute'></div>
		<div id='currentImage' class='slide absolute'></div>
		<div id='diagnostics' class='absolute'></div>
		<div id='loadingMessage' class='absolute'>Image Loading...</div>
	</body>
<html>
