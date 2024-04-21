<?php
/**
 * The QuestContent Class.
 *
 * @package WapuuGotchi
 */

namespace Wapuugotchi\Onboarding\Filters;

use Wapuugotchi\Models\OnboardingItem as Item;
use Wapuugotchi\Models\OnboardingPage as Page;
use Wapuugotchi\Onboarding\Data\OnboardingPage;
use Wapuugotchi\Wapuugotchi\OnboardingTarget as Target;
use function Wapuugotchi\Onboarding\Data\__;
use function Wapuugotchi\Onboarding\Data\add_filter;

if ( ! defined( 'ABSPATH' ) ) :
	exit();
endif; // No direct access allowed.

/**
 * Class QuestContent
 */
class OptionsPermalinkData {

	/**
	 * "Constructor" of the class
	 */
	public function __construct() {
		add_filter( 'wapuugotchi_onboarding_filter', array( $this, 'add_wapuugotchi_filter' ) );
	}

	/**
	 * Initialization filter for OnboardingData
	 *
	 * @param array $tour Array of onboarding objects.
	 *
	 * @return array|OnboardingPage[]
	 */
	public function add_wapuugotchi_filter( $tour ) {
		$page[] = Page::create()
						->set_page( 'options-permalink' )
						->set_file( 'options-permalink.php' )
						->add_item(
							Item::create()
								->set_title( __( 'Permalink', 'wapuugotchi' ) )
								->set_text( __( 'The "Permalink" page deals with settings for the URL structure of your website.', 'wapuugotchi' ) )
								->add_target( Target::create()->set_active( true )->set_focus( '#wpcontent' )->set_overlay( '#wpcontent' ) )
						)
						->add_item(
							Item::create()
								->set_title( __( 'Permalink Structure', 'wapuugotchi' ) )
								->set_text( __( 'It\'s important to carefully choose your permalink structure, as it affects not just the aesthetics of your URLs but also your website’s search engine optimization (SEO) and user-friendliness.', 'wapuugotchi' ) )
								->add_target( Target::create()->set_active( true )->set_focus( '.permalink-structure' )->set_overlay( '.permalink-structure' ) )
						)
						->add_item(
							Item::create()
								->set_title( __( 'Tip 2', 'wapuugotchi' ) )
								->set_text( __( 'To support search engines, it’s advisable to choose a permalink structure that uses ‘speaking URLs’. Ideally, your URLs should contain the keywords of your posts and pages.', 'wapuugotchi' ) )
								->add_target( Target::create()->set_active( true )->set_focus( null )->set_overlay( null ) )
						);

		return array_merge( $tour, array( $page ) );
	}
}
