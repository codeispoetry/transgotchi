<?php
/**
 * The QuestStart Class.
 *
 * @package WapuuGotchi
 */

namespace Wapuugotchi\Quest\Filters;

use Wapuugotchi\Quest\Handler\QuestHandler;
use Wapuugotchi\Quest\Models\Quest;

if ( ! defined( 'ABSPATH' ) ) :
	exit();
endif; // No direct access allowed.

/**
 * Class AutoMessage
 */
class AutoMessage {

	/**
	 * "Constructor" of the class
	 */
	public function __construct() {
		\add_filter( 'wapuugotchi_speech_bubble', array( $this, 'add_wapuugotchi_messages' ) );
	}

	/**
	 * Initialization filter for QuestStart
	 *
	 * @param array $messages The messages.
	 *
	 * @return array|Quest[]
	 */
	public function add_wapuugotchi_messages( $messages ) {

		$message = class_exists( '\Wapuugotchi\Avatar\Models\Message' );
		if ( ! $message ) {
			return $messages;
		}

		$completed_quests = \get_user_meta( \get_current_user_id(), 'wapuugotchi_quest_completed__alpha', true );
		if ( empty( $completed_quests ) ) {
			return $messages;
		}

		foreach ( $completed_quests as $id => $completed_quest ) {
			if ( ! isset( $completed_quest['notified'] ) || true === $completed_quest['notified'] ) {
				continue;
			}

			$quest = QuestHandler::get_quest_by_id( $id );
			if ( ! $quest instanceof Quest ) {
				continue;
			}

			$messages[] = new \Wapuugotchi\Avatar\Models\Message( $quest->get_id(), $quest->get_message(), $quest->get_type(), 'Wapuugotchi\Quest\Handler\MessageHandler::is_active', 'Wapuugotchi\Quest\Handler\MessageHandler::set_message_submitted' );
		}

		return $messages;
	}
}