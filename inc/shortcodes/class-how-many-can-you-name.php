<?php

namespace Shortcake_Bakery\Shortcodes;

class How_Many_Can_You_Name extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'    => esc_html__( 'Quiz: How Many Can You Name?', 'how-many-can-you-name-shortcake' ),
			'listItemImage' => 'dashicons-clock',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'Answers', 'shortcake-bakery' ),
					'attr'     => 'answers',
					'type'     => 'textarea',
					'meta'     => array(
						'placeholder' => esc_html__( 'Grumpy, Sleepy, Sneezy, Doc, Bashful, Dopey, Happy', 'how-many-can-you-name-shortcake' ),
					),
				),
				array(
					'label'        => esc_html__( 'Time Limit', 'shortcake-bakery' ),
					'attr'     => 'time-limit',
					'type'     => 'number',
				),
			)
		);
	}

	public static function setup_actions() {
		add_action( 'wp_enqueue_scripts', 'Shortcake_Bakery\Shortcodes\How_Many_Can_You_Name::action_init_register_scripts' );
		add_action( 'shortcode_ui_after_do_shortcode', function( $shortcode ) {
			if ( false !== stripos( $shortcode, '[' . self::get_shortcode_tag() ) ) {
				echo '<link rel="stylesheet" href="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/how-many-can-you-name/www/css/style-howmany.css' ) . '">';
				echo '<script type="text/javascript" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/how-many-can-you-name/www/js/app-howmany.js' ) . '"></script>';
			}
		});
	}

	public static function action_init_register_scripts() {
		wp_register_script( 'how-many-can-you-name-js', SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/how-many-can-you-name/www/js/app-howmany.js', array( 'jquery' ) );
		wp_register_style( 'how-many-can-you-name-css', SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/how-many-can-you-name/www/css/style-howmany.css' );
	}

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['answers'] ) || empty( $attrs['time-limit'] ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="shortcake-bakery-error"><p>' . esc_html__( 'Time limit and answers required for quiz.', 'shortcake-bakery' ) . '</p></div>';
			} else {
				return '';
			}
		}
		wp_enqueue_script( 'how-many-can-you-name-js' );
		wp_enqueue_style( 'how-many-can-you-name-css' );
		ob_start();
		?>
			<form name="timecount">
				<input id="timer" type="text" readonly="true" value="Wait for the timer..." name="timer" />
			</form>
				<div id="answerfield">
				<label for="answer" style="display:none;">Enter your answers here:</label>
				<input type="text" onKeyUp="quizzer.checkAnswer(this);" name="input" id="answer" />
				<span id="remain"></span>
				</div>
				
				<h3>
				Correct Answers:
				</h3>
				<p id="correct">None</p>
				<div id="missed"></div>
				<button class="btn-show-more-headlines" id="end-it" onClick="quizzer.quit();">End the quiz now</button>
		<?php
		$markup = ob_get_clean();
		$markup .= '<input id="time_limit" type="hidden" value="' . esc_attr( $attrs['time-limit'] ) . '" />';
		$markup .= '<input id="answerkey" type="hidden" value="' . esc_attr( $attrs['answers'] ) . '" />';
		return $markup;
	}
}
