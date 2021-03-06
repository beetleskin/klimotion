<?php

//namespace Rickard\UTCW;

/**
 * Ultimate Tag Cloud Widget
 *
 * @author     Rickard Andersson <rickard@0x539.se>
 * @version    2.2.3
 * @license    GPLv2
 * @package    utcw
 * @subpackage main
 * @since      2.0
 */

//use Rickard\UTCW\Selection\PopularityStrategy;
//use Rickard\UTCW\Selection\RandomStrategy;
//use Rickard\UTCW\Selection\SelectionStrategy;
//use wpdb;
//use stdClass;

/**
 * Class for loading data for the cloud
 *
 * @since      2.0
 * @package    utcw
 * @subpackage main
 */
class UTCW_Data
{

    /**
     * Reference to the current configuration
     *
     * @var UTCW_Config
     * @since 2.0
     */
    protected $config;

    /**
     * Reference to WPDB object
     *
     * @var wpdb
     * @since 2.0
     */
    protected $db;

    /**
     * Reference to main plugin instance
     *
     * @var UTCW_Plugin
     * @since 2.0
     */
    protected $plugin;

    /**
     * Reference to the selection strategy used
     *
     * @var UTCW_SelectionStrategy
     * @since 2.2
     */
    protected $strategy;

    /**
     * Creates a new instance
     *
     * @param UTCW_Config $config   Current configuration
     * @param UTCW_Plugin $plugin   Main plugin instance
     * @param wpdb   $db       WordPress DB instance
     *
     * @since 2.0
     */
    public function __construct(UTCW_Config $config, UTCW_Plugin $plugin, wpdb $db)
    {
        $this->config = $config;
        $this->db     = $db;
        $this->plugin = $plugin;

        switch ($this->config->strategy) {
            case 'popularity':
                $this->strategy = new UTCW_PopularityStrategy($this->config, $this->plugin, $this->db);
                break;
            case 'random':
                $this->strategy = new UTCW_RandomStrategy($this->config, $this->plugin, $this->db);
                break;
        }
    }

    /**
     * Loads terms based on current configuration
     *
     * @return UTCW_Term[]
     * @since 2.0
     */
    public function getTerms()
    {
        $terms  = array();
        $result = $this->strategy->getData();

        // Calculate sizes
        $min_count = PHP_INT_MAX;
        $max_count = 0;

        // Get translation handler if a translation plugin is active
        $translationHandler = $this->plugin->getTranslationHandler();

        foreach ($result as $item) {
            if ($item->count < $min_count) {
                $min_count = $item->count;
            }

            if ($item->count > $max_count) {
                $max_count = $item->count;
            }

            if ($translationHandler) {

                // Let the translation handler determine if the term should be included or not
                $term = $translationHandler->createTerm($item, $this->plugin);

                if ($term) {
                    $terms[] = $term;
                }
            } else {
                $terms[] = new UTCW_Term($item, $this->plugin);
            }
        }

        $size_from = floatval($this->config->size_from);
        $size_to   = floatval($this->config->size_to);
        $unit      = preg_replace('/' . UTCW_DECIMAL_REGEX . '/', '', $this->config->size_from);

        $font_step = $this->calcStep($min_count, $max_count, $size_from, $size_to);

        foreach ($terms as $term) {
            $term->size = $this->calcSize($size_from, $term->count, $min_count, $font_step) . $unit;
        }

        // Set colors
        switch ($this->config->color) {
            case 'random':
                foreach ($terms as $term) {
                    $term->color = sprintf(UTCW_HEX_COLOR_FORMAT, rand() % 255, rand() % 255, rand() % 255);
                }
                break;
            case 'set':
                if ($this->config->color_set) {
                    foreach ($terms as $term) {
                        $term->color = $this->config->color_set[array_rand($this->config->color_set)];
                    }
                }
                break;
            case 'span':
                if ($this->config->color_span_from && $this->config->color_span_to) {
                    preg_match_all('/[0-9a-f]{2}/i', $this->config->color_span_from, $cf_rgb_matches);
                    list($red_from, $green_from, $blue_from) = array_map('hexdec', $cf_rgb_matches[0]);

                    preg_match_all('/[0-9a-f]{2}/i', $this->config->color_span_to, $ct_rgb_matches);
                    list($red_to, $green_to, $blue_to) = array_map('hexdec', $ct_rgb_matches[0]);

                    $colors             = new stdClass;
                    $colors->red_from   = $red_from;
                    $colors->red_to     = $red_to;
                    $colors->green_from = $green_from;
                    $colors->green_to   = $green_to;
                    $colors->blue_from  = $blue_from;
                    $colors->blue_to    = $blue_to;

                    foreach ($terms as $term) {
                        $term->color = $this->calcColor($min_count, $max_count, $colors, $term->count);
                    }
                }
        }

        // Last order by color if selected, this is the only order which can't be done in the DB
        if ($this->config->order == 'color') {
            // Change the argument order to change the sort order
            $sort_fn_arguments = $this->config->reverse ? '$b,$a' : '$a,$b';

            // There's no difference in sortin case sensitive or case in-sensitive since
            // the colors are always lower case and internally generated

            $sort_fn = create_function($sort_fn_arguments, 'return strcmp( $a->color, $b->color );');

            usort($terms, $sort_fn);
        }

        return $terms;
    }

    /**
     * Calculate term color
     *
     * @param int      $min_count Min count of all the terms
     * @param int      $max_count Max count of all the terms
     * @param stdClass $colors    Object with red/green/blue_from/to properties
     * @param int      $count     Count of current term
     *
     * @return string
     * @since 2.0
     */
    private function calcColor($min_count, $max_count, stdClass $colors, $count)
    {
        $red_step   = $this->calcStep($min_count, $max_count, $colors->red_from, $colors->red_to);
        $green_step = $this->calcStep($min_count, $max_count, $colors->green_from, $colors->green_to);
        $blue_step  = $this->calcStep($min_count, $max_count, $colors->blue_from, $colors->blue_to);

        $red   = $this->calcSize($colors->red_from, $count, $min_count, $red_step);
        $green = $this->calcSize($colors->green_from, $count, $min_count, $green_step);
        $blue  = $this->calcSize($colors->blue_from, $count, $min_count, $blue_step);

        $color = sprintf(UTCW_HEX_COLOR_FORMAT, $red, $green, $blue);

        return $color;
    }

    /**
     * Calculate term size
     *
     * @param int $size_from Configured min size
     * @param int $count     Current count
     * @param int $min_count Configured max count
     * @param int $font_step Calculated step
     *
     * @return int
     * @since 2.0
     */
    private function calcSize($size_from, $count, $min_count, $font_step)
    {
        return $size_from + (($count - $min_count) * $font_step);
    }

    /**
     * Calculate step size
     *
     * @param int $min  Minimum count
     * @param int $max  Maximum count
     * @param int $from Minimum size
     * @param int $to   Maximum size
     *
     * @return int
     * @since 2.0
     */
    private function calcStep($min, $max, $from, $to)
    {
        if ($min === $max) {
            return 0;
        }

        $spread      = $max - $min;
        $font_spread = $to - $from;
        $step        = $font_spread / $spread;

        return $step;
    }

    /**
     * Cleans up sensitive data before being used in debug output
     */
    public function cleanupForDebug()
    {
        unset($this->db);
        $this->strategy->cleanupForDebug();
    }
}
