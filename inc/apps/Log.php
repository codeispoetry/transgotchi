<?php

namespace Wapuugotchi\Wapuugotchi;

if ( ! defined( 'ABSPATH' ) ) :
	exit();
endif; // No direct access allowed.

class Log {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'init' ) );
	}

	public function init( $hook_suffix ) {
		if ( $hook_suffix === 'wapuugotchi_page_wapuugotchi-quests' ) {
			$this->load_scripts();
		}
	}

	public function load_scripts() {
		$assets = require_once WAPUUGOTCHI_PATH . 'build/quest-log.asset.php';
		wp_enqueue_style( 'wapuugotchi-log', WAPUUGOTCHI_URL . 'build/quest-log.css', array(), $assets['version'] );
		wp_enqueue_script( 'wapuugotchi-log', WAPUUGOTCHI_URL . 'build/quest-log.js', $assets['dependencies'], $assets['version'], true );
		wp_add_inline_script(
			'wapuugotchi-log',
			'window.extWapuugotchiLogData = ' . wp_json_encode(
				array(
					'active_quests'    => $this->get_active_quests(),
					'completed_quests' => $this->get_completed_quests()
				)
			),
			'before'
		);

		\wp_set_script_translations( 'wapuugotchi-log', 'wapuugotchi', WAPUUGOTCHI_PATH . 'languages/' );
	}

	private function get_active_quests() {
		$result        = array();
		$active_quests = QuestManager::get_active_quests();
		foreach ( $active_quests as $active_quest ) {
			$result[] = array(
				'title'  => $active_quest->get_title(),
				'pearls' => $active_quest->get_pearls()
			);
		}

		return $result;
	}

	private function get_completed_quests() {
		$result           = array();
		$all_quests       = QuestManager::get_all_quests();
		$completed_quests = QuestManager::get_completed_quests();
		foreach ( $completed_quests as $completed_quest_key => $completed_quest ) {
			foreach ( $all_quests as $quest ) {
				if ( $completed_quest_key === $quest->get_id() ) {
					$result[] = array(
						'title'  => $quest->get_title(),
						'pearls' => $quest->get_pearls(),
						'date'   => $completed_quest['date']
					);

					break;
				}
			}
		}

		return $result;
	}
}