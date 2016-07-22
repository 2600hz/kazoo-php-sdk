<?php

namespace Kazoo\Common;

class Utils
{
    /**
     * Class name without namespace
     *
     */
    public static function shortClassName($class) {
        return join('', array_slice(explode('\\', get_class($class)), -1));
    }

    /**
     * Camel-case class name to lower underscore
     *
     */
    public static function underscoreClassName($class) {
        $class_name = self::fixResrvedClassName(self::shortClassName($class));
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class_name));
    }

    /**
     * Use reserved Class Name
     *
     */
    public static function fixResrvedClassName($class_name) {
        return str_replace("My","",$class_name);
    }

    /**
     * Very basic depluralize function. Used on
     * the limited and simple API resource names.
     * Retrived from:
     *   https://sites.google.com/site/chrelad/notes-1/pluraltosingularwithphp
     */
    public static function depluralize($word) {
        $rules = array(
            'ss' => false,
            'os' => 'o',
            'ies' => 'y',
            'xes' => 'x',
            'oes' => 'o',
            'ies' => 'y',
            'ves' => 'f',
            'hes' => 'h',
            's' => '');

        foreach(array_keys($rules) as $key) {

            if(substr($word, (strlen($key) * -1)) != $key) {
                continue;
            }

            if($rules[$key] === false) {
                return $word;
            }

            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }

        return $word;
    }

    /**
     * Very basic pluralize function. Used on
     * the limited and simple API resource names.
     * Retrived from:
     *   https://stackoverflow.com/questions/1534127/pluralize-in-php
     */
    public static function pluralize($word) {
        if ($word == "clicktocall") {
            return $word;
        }

        switch(strtolower($word[strlen($word) - 1])) {
        case 'y':
            return substr($word, 0, -1) . 'ies';
        case 's':
        case 'h':
        case 'x':
            return $word. 'es';
        default:
            return $word . 's';
        }
    }
}
