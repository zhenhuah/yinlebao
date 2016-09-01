<?php
//require '/kint/Kint.class.php';
//Kint::dump( $_SERVER );
//d( $_SERVER );
//dd( $_SERVER ); // same as d( $_SERVER ); die;
//Kint::trace();
//Kint::dump( 1 );
	
//|ˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉ|
//|      Baidu Music API (for PHP)        |
//|                                       |
//|            License: GPL               |
//|            Version: 2.1               |
//|          Release: 20140324            |
//|ˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉ|
//|         Author: Robin Lau             |
//|       Email: i@green-vine.me          |
//|   Website: http://green-vine.net/     |
//|ˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉ|
//| Copyright © 2014 YQ Infotech Co., Ltd.|
//| All Rights Reserved.                  |
//|ˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉˉ|
//| PLEASE KEEP AUTHOR NAME AND WEBSITE   |
//| LINK WHEN YOU DISTRIBUTE THIS SCRIPT! |
//|---------------------------------------|

	// Error Reporting: Recommend to set to 0 in production.
	error_reporting(0);
	
	// API Switch: Set to other number except 1 to disable API.
	$api_status = 1;
	if($api_status !=1) ReturnError("0000");
	
	// Validation: Check to see whether specified song ID.
	$sid = $_GET['sid'];
	$songId = $sid;
	
	// Execution: Execute the first query to get brief information (excluding download URL).
	$infoBrief = postJson(buildQuery($songId, 1));
	
	// Validation: Check to see whether returned the valid data.
	if(getReturnData("song_id") <> $songId)ReturnError("1001");
	
	// Validation: Check what to do.
	$action = "getsongurl";
	
	// Validation: Set default rate to best quality if not specified.
	$rate =  "best" ;
	
	// Validation: Set default separator to comma if not specified.
	$separator = ",";
	
	// Validation: Choose what to do.
	switch($action){
		case "getsongurl": echo getFileMeta("url", $rate); break;
		case "getsongtitle": echo getSongMeta("song_title"); break;
		case "getsongartist": echo getSongMeta("song_artist"); break;
		case "getalbumid": echo getSongMeta("album_id"); break;
		case "getalbumtitle": echo getSongMeta("album_title"); break;
		case "getalbumimageurl": echo getSongMeta("album_image_url"); break;
		case "getlyricurl": echo getSongMeta("lyric_url"); break;
		case "getsongsize": echo getFileMeta("size", $rate); break;
		case "listsongrate": echo listSongRate($separator); break;
		case "getsongduration": echo getFileMeta("duration", $rate); break;
		case "getsonghash": echo getFileMeta("hash", $rate); break;
		case "getsongformat": echo getFileMeta("format", $rate); break;
		case "getsongsource": echo getSongMeta("resource_source"); break;
		case "isflac": echo isFlac(); break;
		case "ishq": echo isHQ(); break;
		case "hasmv": echo getSongMeta("has_mv"); break;
		default: ReturnError("2000"); # Action name not match.
	}
	
	// Query: Build query strings for POST action.
	function buildQuery($songId, $type = 1, $rate = null)
	{
		$query = $type == 2 ? '{"songId":"' . $songId . '","linkType":2,"rate":' . $rate . '}' : '{"songId":"' . $songId . '","linkType":1}';
		$query_base64 = base64_encode($query); # Strings post to server must be base64-encoded.
		return $query_base64;
	}
	
	// Query: Execute a POST action to Baidu Music Server (cURL must be enabled on the server).
	function postJson($content)
	{
		global $songId;
		$api = 'http://musicmini.baidu.com/app/link/getLinks.php';
		$header = "Content-type: application/x-www-form-urlencoded";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "param=" . $content);
		return json_decode(curl_exec($ch), true);
		if(curl_errno($ch)){
			ReturnError("1000");
		}
		curl_close($ch);
	}
	
	// Data Processing: Check to see whether script can return response directly or further POST action required.
	function getReturnData($name, $index = 0, $infoDetails = null)
	{
		global $infoBrief, $infoDetails, $songId;
		$parent = array("song_id" => 0, "song_title" => 0, "song_artist" => 0, "album_id" => 0, "album_title" => 0, "album_image_url" => 0, "lyric_url" => 0, "resource_source" => 0, "has_mv" => 0, "resource_type" => 0); # Music-related properties
		$branch = array("format" => 0, "hash" => 0, "size" => 0, "kbps" => 0, "duration" => 0, "url_expire_time" => 0, "is_hq" => 0, "enhancement" => 0); # File-related properties
		$sub = array("url" => 0, "display_url" => 0); # Download-related properties
		if(array_key_exists($name, $parent))return $infoBrief[0][$name];
		else if(array_key_exists($name, $branch))return $infoBrief[0]["file_list"][$index][$name];
		else if(array_key_exists($name, $sub))
		{
			return $infoDetails[0]["file_list"][0][$name];
		}
		return false;
	}
	
	// Execution: Execute further query to get detailed information (including download URL).
	function getFileDetails($index)
	{
		global $infoDetails, $songId, $infoBrief;
		$infoDetails = postJson(buildQuery($songId, 2, $infoBrief[0]["file_list"][$index]["kbps"]));
	}
	
	// Function: Return file-related properties value.
	function getFileMeta($meta, $rate = "best")
	{
		global $infoBrief, $infoDetails, $songId, $index;
		for($i = 0; $i < count($infoBrief[0]["file_list"]); $i++) # If specified a rate.
		{
			if(getReturnData("kbps", $i) == $rate)
			{
				getFileDetails($i);
				return getReturnData($meta, $i);
			}
		}
		if(($rate == "flac" && getReturnData("format", 0) == "flac") || $rate == "best") # If specified the best or FLAC rate.
		{
			getFileDetails(0);
			return getReturnData($meta,0,$infoDetails);
		}
		ReturnError("1002"); # Specified rate not found.
	}
	
	// Function: Return music-related properties value.
	function getSongMeta($meta)
	{
		global $infoBrief;
		return getReturnData($meta);
	}
	
	// Function: List all available rate.
	function listSongRate($separator)
	{
		global $infoBrief;
		for($i = 0; $i < count($infoBrief[0]["file_list"]); $i++)
		{
			$rate_list .= getReturnData("kbps");
			if($i < count($infoBrief[0]["file_list"]) - 1)$rate_list .= $separator; # Append separator
		}
		return $rate_list;
	}
	
	// Function: Check to see whether is a High Quality music.
	function isHQ()
	{
		global $infoBrief;
		for($i = 0; $i < count($infoBrief[0]["file_list"]); $i++)
		{
			if(getReturnData("is_hq"))return 1;
		}
		return 0;
	}
	
	// Function: Check to see whether is a FLAC file.
	function isFlac()
	{
		global $infoBrief;
		return (getReturnData("format") == "flac")? 1 : 0;
	}
	
	// Error Troubleshooting: Return error code and/or error details.
	function ReturnError($code)
	{
		$errDes = array(
			"0000" => "API Temporarily Unavailable",
			"1000" => "Network Error",
			"1001" => "Song Not Exists",
			"1002" => "Specified Song Rate Not Exists",
			"2000" => "Required Argument Missed");
		$errDetails = (isset($_GET["errordetails"]) && $_GET["errordetails"]) ? ":" . $errDes[$code] : null; # Append error details if requested.
		die("ERR" . $code . $errDetails);
	}
	
?>