<?php
/**
 * The QuestContent Class.
 *
 * @package WapuuGotchi
 */

namespace Wapuugotchi\Onboarding\Data;

use Wapuugotchi\Models\OnboardingItem as Item;
use Wapuugotchi\Models\OnboardingPage as Page;
use Wapuugotchi\Wapuugotchi\Onboarding;
use Wapuugotchi\Wapuugotchi\OnboardingTarget as Target;

if ( ! defined( 'ABSPATH' ) ) :
	exit();
endif; // No direct access allowed.

/**
 * Class QuestContent
 */
class PageData {

	/**
	 * 'Constructor" of the class
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
						->set_page( 'page' )
						->set_file( 'post-new.php?post_type=page' )
							->add_item(
								Item::create()
									->set_title( __( 'Tutorial', 'wapuugotchi' ) )
									->set_text( __( 'We\'re now in the "Add New" section. Just let me know once you\'re done with the Gutenberg tutorial, and I\'ll show you around some more.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( null )->set_overlay( null ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Gutenberg Editor', 'wapuugotchi' ) )
									->set_text( __( 'As you might have learned in the tutorial, this area is where you can manage the content of your pages.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( null )->set_overlay( null ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Top Navigation', 'wapuugotchi' ) )
									->set_text( __( 'The top navigation gives you quick access to basic controls in the editor. It\'s split into the toolbar and the settings bar.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( '.interface-navigable-region.interface-interface-skeleton__header' )->set_overlay( '.interface-navigable-region.interface-interface-skeleton__header' ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'The Toolbar', 'wapuugotchi' ) )
									->set_text( __( 'The toolbar provides functions to help you build your page step by step. Here you can add new block elements and undo changes. Want to see how it works? Hit "Play"!', 'wapuugotchi' ) )
									->set_freeze( 38000 )
									->add_target( Target::create() )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Settings Bar', 'wapuugotchi' ) )
									->set_text( __( 'In the settings bar, you can save and publish your page. Plus, you can switch between different screen modes like desktop, mobile, and tablet.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( '.edit-post-header__settings' )->set_overlay( '.edit-post-header__settings' )->set_click( '.interface-pinned-items button[aria-expanded=\'false\']' ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Side Navigation', 'wapuugotchi' ) )
									->set_text( __( 'Here you can view additional information about your blog post and make settings on the individual blocks you\'ve added.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( '.interface-interface-skeleton__sidebar' )->set_overlay( '.interface-interface-skeleton__sidebar' ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Content', 'wapuugotchi' ) )
									->set_text( __( 'In the content area, you can edit your contents... write texts, insert images, edit blocks. The sky\'s the limit with your creativity!', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( '.interface-interface-skeleton__content' )->set_overlay( '.interface-interface-skeleton__content' )->set_click( '.interface-pinned-items button[aria-expanded=\'false\']' ) )
							)
							->add_item(
								Item::create()
									->set_title( __( 'Wrap Up', 'wapuugotchi' ) )
									->set_text( __( 'The text editor has so many features, and I\'m sure I forgot to explain half of them! I hope I at least gave you a good overview. Let\'s take a look at the next area.', 'wapuugotchi' ) )
									->add_target( Target::create()->set_active( true )->set_focus( null )->set_overlay( null ) )
							);

		return array_merge( $tour, array( $page ) );
	}
}
