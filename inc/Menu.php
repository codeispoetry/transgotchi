<?php
/**
 * The Menu Class.
 *
 * @package WapuuGotchi
 */

namespace Wapuugotchi\Wapuugotchi;

if ( ! defined( 'ABSPATH' ) ) :
	exit();
endif; // No direct access allowed.

/**
 * Class Menu
 */
class Menu {

	/**
	 * "Constructor" of this Class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'create_menu_page' ) );
		add_action( 'current_screen', array( $this, 'force_redirect_to_dashboard' ) );
	}

	/**
	 * Add manu to backend sidebar.
	 *
	 * @return void
	 */
	public function create_menu_page() {
		$capability = 'manage_options';
		$slug       = 'wapuugotchi';

		add_menu_page(
			__( 'Wapuugotchi', 'wapuugotchi' ),
			__( 'WapuuGotchi', 'wapuugotchi' ),
			$capability,
			$slug,
			array( $this, 'customizer_page_template' ),
			'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSItMTAgLTUgMTAzNCAxMDM0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgogIDxwYXRoIGQ9Ik01MzAgNzZxLTg0IDAtMTU0IDM1dC0xMTAgOThxLTEzIDEzLTM2IDI2LTIwIDEyLTI4IDE0LTEyIDMtMTkgMTIuNXQtNiAyMC41cTEgMTAgOCAxOHQyMCAxM2wtNiA2cS0xOSAyMy0xNSA0NyAxIDUgMyA5LTQxIDY5LTQxIDE0OSAwIDIwIDMgNDAtNi0yLTEzLTItMTUgMC0yOCA2LjVUODUgNTg2cS0yMSAyNi0xMyA2NCA1IDI0IDI4LjUgNTZ0NTMuNSA1NnEzMyAyNyA2MSAzMyA5IDIgMTcgMiAyNCAwIDM3LTE4IDMtNiA1LTEyIDYyIDQyIDEzNyA1MCA3IDIzIDI2IDMzIDEzIDcgMzEgNyA0MSAwIDY3LTMxaDVxMjAgMCA0Mi0zLTM4IDQyLTkxIDY4LTQ5IDI0LTkyIDI0LTUxIDAtNzItMzQtMTYtMjktNDEtNDV0LTUyLjUtMTYtNDUgMTgtMTguNSA0N3EtMSAzMiAzMiA2NiAzMCAzMSA3NiA1MiA1MyAyMyAxMDYgMjMgMjUgMCA0OC01IDY2LTE2IDEyNS41LTU0VDY2MCA4NzZ0NjAtMTExcTU5LTQ2IDg2LTE0MiAyMi04MyAxNy0xODUtNS05Mi0yOC0xNTctNC0xMy05LTI0aDE1cTE3IDAgMzQtNCAzMS0xMCA1NC0yOCAyMC0xNiAzMS0zNiAxMS0xOCAxMC0zMiAwLTExLTYtMTl0LTE0LjUtMTAtNDkuNS0yaC04NnEtNjAgMC05MC0xLTYzLTQ2LTE0NC00OWgtMTB6bTAgMzEgMjUgMXExNCAxIDI3IDRsMTEgM3ExNSA0IDI5IDEwbDE3IDggMTYgOS0yIDItMiA0LTEgNCAxIDUgNCA4cTMgNSA4IDEwbDE0IDEyIDI1IDE5IDMzIDE5IDIgNHE4IDEzIDE1IDI3bDYgMTMgOCAyMiA5IDI5cTUgMTggOCA0MCA2IDMzIDggNjkgNCA1MSAwIDk5bC0zIDI1cS0yIDE3LTUgMzNsLTYgMjQtNyAyNi0xMyAzMi0xMiAyMy0xMiAxOC0xNCAxNnEtMTEgMTEtMjMgMTlsLTEyIDgtMTIgN3EtMTEgNy0yMyAxMmwtMTEgNS0xNiA2cS0xOCA2LTM1IDlsLTE0IDItMTcgMnEtOCAxLTE3IDAgNC05IDYtMThsNC0xNSAzLTE0IDEtMTh2LTlxMC0xMC0yLTIwbC0zLTE2LTYtMTkgOS0xNSAxMC0xMyA5LTggMTAtOCA5LTZxMjEtMTEgNDctMTRsMTUtMSAxNiAyIDExIDJxMTAgMiAxOCA2IDUgMiA5IDVsNCAycTQgNCA3IDggMSAxIDMgMiA0IDIgOC0xbDItMyAxLTMtMi01LTgtOC0xMS04cTMtOSA1LTE4bDItMTAgMy0zMyAxLTIzLTItMjQtMy0yMC0zLTEyIDMgMWg1cTItMSAzLTJsMS0ycTEtMiAxLTVsLTItMy01MC00NC0yMy0xOC0xMi03LTE1LTktMjAtOC0xOS01LTIxLTNxLTggMC0xOCAxbC0xNSAycS0xMCAxLTI0IDRsLTE3IDQtMzYgN3EtOSAxLTE1IDAtMTItMS0yMS03bC05LTdxLTItMy0yLTYtMi02IDEtMTRsNy0xMSAxNi0xNSAxMS04cTYtMyAxMy02bDEzLTYgMTctNiAxOC00cTktMSAxOS0ybDE1LTEgMTYgMXExNiAxIDMwIDRsNiAycTkgMiAxNyA1bDQgMXEyIDAgMy0xbDMtNCAxLTNxMC0yLTEtNGwtMS0xcS0xLTEtMy0ybC0xNC01cS0xOS01LTM5LTdsLTEwLTFoLTE1di0ybDEtMy0yLTRxLTEtMS0zLTJoLTVxLTE5IDYtMzIgOWwtMTIgM3EtMzQgNi02MC0ybC0xMS0zLTE4LTlxLTIgMC0zLjUuNVQzODQgMjU0bC0xIDJxLTMgNC02IDlsLTggOC02IDUtMTQgOXEtMTAgNi0yMSAxMWwtMTAgM3EtNiAzLTEyIDRsLTQgMWgtNmwtNy0zLTUtNXEtNC01LTctMTF2LTJxLTItNC0yLTh2LTEzbDQtMTVxMy04IDktMTdsMTUtMjIgMzgtNDAgNTMtMzMgNTktMjF6bTEzOSA0OGgzbDUwIDJoMTAwbDc3IDEtMiA4LTMgOC0xMCAxM3EtMjIgMjUtNTggMzYtOSAzLTE5IDRoLTZsLTE1LTFxLTEzLTItMjYtN2wtMTktOHEtMTAtNS0yMC0xMWwtMTQtOS0xNC0xMS0xNy0xNS0xMC0xMGgzem0tMTY3IDlxLTYgMC0xMC41IDQuNXQtNC41IDExIDQuNSAxMVQ1MDIgMTk1dDEwLjUtNC41IDQuNS0xMS00LjUtMTFUNTAyIDE2NHptLTkxIDQxaC0zcS0yMiAyLTMxLjUgOS41VDM2OSAyMjl0MTIgOGwyIDFxOSAxIDEzIDB0MTMtOCAxMC0xNi04LTl6bS04NiAycS03IDAtMTEuNSA0LjVUMzA5IDIyMnQ0LjUgMTAuNSAxMSA0LjUgMTEtNC41VDM0MCAyMjJ0LTQuNS0xMC41VDMyNSAyMDd6bS02MiA0NHEtMSAxLTEgMmwtMiAxMnEtMSA1IDAgMTF2NHEtMTcgNS0zMCA1aC0xMHEtNy0xLTEwLTRsLTItMmgxcTQtMSAxMC00bDYtMnExOC05IDM4LTIyem0tNyA2OGgybDUgNHEyIDIgMyA0bDMgMTAtMiAxM3EtMyA3LTkgMTMtMTAgMTAtMjIgMTJoLTlsLTQtMXEtMy0yLTUtNGwtMS0yLTMtOXYtNWw0LTkgNC02IDQtNXE5LTkgMjAtMTNsNC0xcTMtMSA2LTF6bTg5IDVxMjQgMCA0OCA5LTQgOC00IDE2LTI1LTEyLTUxLTExLTI5IDAtNTYgMTUgMi03IDItMTMgMjYtMTMgNTQtMTZoN3ptNTUgNDhxMTMgMTQgMzYgMTYgMTEgMSAyOS0yIDMyIDQzIDM1IDk4IDIgNTAtMjQgOTgtMTAtNS0xOS01IDI1LTQzIDIzLTkxLTItNDUtMjUtODIgNSAyNS0xIDUxLTcgMzItMjcgOTQtMTIgMzgtMTcgNTctNSAxMC02IDIzbC0zIDEwIDItMnEwIDEwIDIgMjMtNDAgMjUtODMgMjQtNDctMy04OC0zOS0zOC0zNC01MC04MS0xMi00NC0uNS05MC41VDIyMyAzOTBxNCAxIDkgMWgycS0xMSAxMi0yMCAyNyA0LTEgMTItNSAxNS02IDI0LTQgMiAwIDEuNSA0dC0zIDYuNS04IDQtNy41IDIuNXEyIDQ2IDEzLjUgODcuNVQyODUgNjA4cTEtMzUgNi02OCAzLTE5IDEyLTUzLTgtMjQtMTItMzktMi0xMC02LTMxLTEzIDEtMTUuNS41VDI2OCA0MTNxMS04IDctOWw0MC0zcTM5LTQgNDAtMyA2IDYtMyAxMC01IDItMjAgNCAxMSA0NiAyOSA5NCAxMiAzNCAzNSA5MCA2LTI2IDE0LTU5IDEzLTM5IDExLjUtNjIuNVQ0MDQgNDI4cS0yNy00MS00LTU2em0tMTk5IDcxcS0xNiAzOS0xNCA4MCAyIDQzIDI0IDc3IDI0IDM4IDY3IDU3LTQxLTYxLTU4LjUtMTA5LjVUMjAxIDQ0M3ptMTA5IDY3cS0xMSA1MS0xMyA5Mi0yIDI5IDIgNjIgMTYgMyAzMiAyIDIyLTEgNDctMTMtNC01LTIyLTQxLTI2LTUwLTQ2LTEwMnptLTE3NCA4M2gybDQgMSAxMCAyIDggNCA2IDQgNiA1cTUgNSAxMiAxNCAyNCAzMyA1MSA4OWw2IDEzIDMgOXEyIDkgMiAxOC0xIDMtMSA2bC0yIDQtNSA0cS0zIDEtNiAxbC0xMC0xLTgtM3EtMTItNS0yNi0xNC0yMC0xNC00MC0zNC0xOC0xOS0zMC41LTM4LjVUMTAyIDY0M3EtNS0yMSA2LTM1bDQtNSA0LTQgMy0yIDgtM3ptMzE3IDRxNiAwIDEyIDNsMTAgNiAxNiAxNSAyOCA0MyAxMCAzM3E3IDMxIDAgNjBsLTYgMjMtMyA3LTYgMTItNyA5LTEwIDgtNiAzLTYgMy03IDEtMTAgMWgtNmwtNi0xLTQtMnEtMi0yLTQtM2wtMy01LTQtMTFxLTEtNi0xLTE1di01OWwtOS00Ni03LTM2cS0xLTE5IDQtMzFsNC01IDktOXE2LTQgMTItNHptMjM0IDE3MXEtNCAxMC04IDE5LjV0LTkgMTguNWwtMTcgMjgtOSAxM3EtNyA5LTE0IDE3bC03IDhxLTI4IDMxLTYzIDU3bC0xMCA2cS0xNCAxMC0yOSAxOGwtMTUgOC0xNiA4LTI2IDEwLTExIDQtMjggOC05IDJxLTEyIDItMjMgMmgtMjNxLTEzLTEtMjYtNGwtNi0xLTIyLTYtMTctNi0xNy04LTE2LTgtMTQtOS0yMC0xNi0xMy0xMy03LTlxLTExLTE2LTExLTI4dDQtMjBsMy01cTYtOCAxNS41LTEwdDE4LjUtMWwxMCAzIDExIDRxNiA0IDEyIDlsMTAgOXE5IDkgMTYgMjBsMTAgMTQgOCA4IDEwIDggMTMgNyAxMyA2cTE4IDYgMzkgNmgxN2wyMS0yIDI2LTZxNDUtMTMgODgtNDJsMTktMTUgMjItMTkgMjAtMjAgNi04IDIyLTMwIDctMTJxMTEtNSAyMi0xMC41dDIzLTEyLjV6IiBmaWxsPSIjZmZmIi8+Cjwvc3ZnPgo='
		);
		add_submenu_page(
			$slug,
			__( 'Wapuugotchi', 'wapuugotchi' ),
			__( 'Customizer', 'wapuugotchi' ),
			$capability,
			$slug,
			array( $this, 'customizer_page_template' )
		);
		add_submenu_page(
			$slug,
			__( 'Wapuugotchi', 'wapuugotchi' ),
			__( 'Quest Log', 'wapuugotchi' ),
			$capability,
			$slug . '-quests',
			array( $this, 'quests_page_template' )
		);
		add_submenu_page(
			$slug,
			__( 'Wapuugotchi', 'wapuugotchi' ),
			__( 'Tour', 'wapuugotchi' ),
			$capability,
			$slug . '-onboarding',
			array( $this, 'onboarding_page_template' )
		);
	}

	/**
	 * Add html starting point to customizer manu page.
	 *
	 * @return void
	 */
	public function customizer_page_template() {
		echo '<div class="wrap"><div id="wapuugotchi-app"></div></div>';
	}

	/**
	 * Add html starting point to hunt manu page.
	 *
	 * @return void
	 */
	public function hunt_page_template() {
		echo '<div class="wrap"><div id="wapuugotchi-app"></div></div>';
	}

	/**
	 * Add html starting point to quest manu page.
	 *
	 * @return void
	 */
	public function quests_page_template() {
		echo '<div class="wrap"><div id="wapuugotchi-app"></div></div>';
	}

	/**
	 * Add html starting point to quest manu page.
	 *
	 * @return void
	 */
	public function onboarding_page_template() {
		echo '<div class="wrap"><div id="wapuugotchi-app"></div></div>';
	}

	public function force_redirect_to_dashboard() {
		global $current_screen;
		if ( isset( $current_screen->id ) && $current_screen->base == 'wapuugotchi_page_wapuugotchi-onboarding' ) {
			wp_redirect(
				admin_url( 'index.php?onboarding_mode=true' )
			);
			exit;
		}
	}
}
