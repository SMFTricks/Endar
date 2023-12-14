<?php

/**
 * @package Theme Customs
 * @author Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, SMF Tricks
 * @license GNU GPLv3
 */

namespace ThemeCustoms;

use ThemeCustoms\Config\ { Config };

class Init extends Config
{
	/**
	 * @var string Theme Name
	 */
	protected $_theme_name = 'Endar';

	/**
	 * @var string Theme Version
	 */
	protected $_theme_version = '1.0';

	/**
	 * @var array Theme Author
	 */
	protected $_theme_author = [
		'name' => 'Diego Andrés',
		'smf_id' => 254071,
	];

	/**
	 * @var array Theme support details
	 */
	protected $_theme_details = [
		'support' => [
			'github_url' => 'https://github.com/SMFTricks/Endar',
			'smf_site_id' => 3,
			'smf_support_topic' => 76635,
			'custom_support_url' => 'https://smftricks.com/',
		],
	];

	/**
	 * Like Button
	 */
	public static $_likes_quickbutton = false;

	/**
	 * Init::loadHooks()
	 * 
	 * @return void
	 */
	protected function loadHooks() : void
	{
		// Load fonts
		add_integration_function('integrate_pre_css_output', __CLASS__ . '::fonts', false, '$themedir/themecustoms/Init.php');
	}

	/**
	 * Init::fonts()
	 * 
	 * Load some google fonts
	 * 
	 * @return void
	 */
	public static function fonts() : void
	{
		global $context, $settings;

		// Loading locally?
		if (empty($settings['st_fonts_source']))
		{
			// Load the fonts
			loadCSSFile('font.css', ['order_pos' => -800]);
			return;
		}

		// Passion One Font
		if (empty($context['header_logo_url_html_safe']))
			loadCSSFile('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap', ['external' => true, 'order_pos' => -800]);

		// Lato Font
		loadCSSFile('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap', ['external' => true, 'order_pos' => -800]);
	}
}