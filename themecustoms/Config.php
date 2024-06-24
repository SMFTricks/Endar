<?php

/**
 * @package Theme Customs
 * @author Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2023, SMF Tricks
 * @license GNU GPLv3
 */

namespace ThemeCustoms;

use ThemeCustoms\Theme\Init;

class Config extends Init
{
	/**
	 * @var string Theme Name
	 */
	protected string $name = 'Endar';

	/**
	 * @var string Theme Version
	 */
	protected string $version = '2.1';

	/**
	 * @var array Theme Author
	 */
	protected string $author = 'Diego Andrés';

	/**
	 * @var int Theme Author SMF ID
	 */
	protected int $authorID = 254071;

	/**
	 * @var string Theme Default Color
	 */
	protected string $color = '#AC9B80';

	/**
	 * @var string GitHub URL
	 */
	protected string $github = 'https://github.com/SMFTricks/Endar';

	/**
	 * @var int SMF Customization Site ID
	 */
	protected int $customizationId = 3;

	/**
	 * @var int Theme Support Topic ID
	 */
	protected int $customizationSupport = 76635;

	/**
	 * @var string Custom Suport URL
	 */
	protected string $supportURL = 'https://smftricks.com/index.php?topic=2343.0';

	/**
	 * Load the additional hooks
	 */
	public function loadHooks() : void
	{
		// Load fonts
		add_integration_function('integrate_pre_css_output', __CLASS__ . '::fonts#', false, __FILE__);
		// Load JS
		add_integration_function('integrate_pre_javascript_output', __CLASS__ . '::js#', false, __FILE__);
	}

	/**
	 * Load some fonts
	 */
	public function fonts() : void
	{
		global $context, $settings;

		// Loading locally?
		if (empty($settings['st_fonts_source'])) {
			// Load the fonts
			loadCSSFile('font.css', ['order_pos' => -800]);
			return;
		}

		// Noto Sans Font
		if (empty($context['header_logo_url_html_safe'])) {
			loadCSSFile('https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap', ['external' => true, 'order_pos' => -800]);
		}

		// Lato Font
		loadCSSFile('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap', ['external' => true, 'order_pos' => -800]);
	}

	/**
	 * Load custom javascript
	 */
	public function js() : void
	{
		// Custom js
		loadJavascriptFile('custom.js', [
			'force_current' => true,
			'defer' => true,
		], 'themecustom_js');
	}
}