<?php

function Websites_before_Streams_Stream_save_Websites_metadata($params)
{
	$stream = $params['stream'];
	if (!$stream->wasModified('attributes')) return;

	$uri = $stream->getAttribute('uri', null);
	if (!$uri) return;
	
	$url = $stream->getAttribute('url', '');
	if (preg_match('/\s/', $url)) {
		throw new Q_Exception_WrongValue(array(
			'field' => 'url',
			'range' => "no spaces",
			'value' => $url
		));
	}
	
	$wp = new Websites_Permalink();
	$wp->uri = $uri;
	$wp->retrieve(null, array('ignoreCache' => true));
	
	$url = $stream->getAttribute('url', '');
	if ($url) {
		$url = Q_Html::themedUrl($url);
		if (!isset($wp->url) or $wp->url !== $url) {
			$wp->url = $url;
			$wp->save();
		}
	} else {
		$wp->remove();
	}
}