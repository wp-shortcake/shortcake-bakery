<?php

namespace Shortcake_Bakery\Shortcodes;

class Infogram extends Shortcode
{

    public static function get_shortcode_ui_args() 
    {
        return array(
        'label'          => esc_html__('Infogram', 'shortcake-bakery'),
        'listItemImage'  => 'TK',
        'attrs'          => array(
        array(
        'label'        => esc_html__('URL', 'shortcake-bakery'),
        'attr'         => 'url',
        'type'         => 'text',
        'description'  => esc_html__('URL to the Infogram', 'shortcake-bakery'),
        ),
        ),
        );
    }

    public static function reversal( $content ) 
    {
        if (preg_match_all('#<script id="[^<]+" src="//e\.infogr\.am/js/embed\.js\?[^>]+" type="text/javascript"></script>?#', $content, $matches) ) {
            $replacements = array();
            $shortcode_tag = self::get_shortcode_tag();
            foreach ( $matches[0] as $key=>$value) {
                $parts = explode('"', $value);
                $id = $parts[1];
                $url_string = str_replace('infogram_0_', '', $id);
                $replacements[ $value ] = '[' . $shortcode_tag . ' url="http://infogr.am/' . $url_string . '"]';
            }
            $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        }
        return $content;
    }

    public static function callback( $attrs, $content = '' ) 
    {

        if (empty( $attrs['url'] ) ) {
            return '';
        }
        $id = preg_replace('((http|https)\:\/\/infogr\.am\/)', '', $attrs['url']);
        $out = '<script async src="//e.infogr.am/js/embed.js" id="infogram_0_';
        $out =. $id;
        $out =. '" type="text/javascript"></script>';
        return $out;
    }

}
