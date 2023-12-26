<?php
namespace DynamicTags\Lib\DynamicTags;

use ElementorPro\Modules\DynamicTags\Module;

class CustomFunction extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'dynamic-tags-custom-function';
    }
  
    public function get_categories() {
        return [
          \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
          \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }
  
    public function get_group() {
        return [ Module::SITE_GROUP ];
    }
  
    public function get_title() {
        return __('Custom Callback', 'solid-dynamics');
    }
  
    protected function _register_controls() {
        $this->add_control(
            'function',
            [
                'label' => __( 'Function', 'dynamic-tags' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'params',
            [
                'label' => __( 'Params', 'dynamic-tags' ),
                'description' => 'A parameter for the function.',
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
    }
    public function render() {
        global $post;
  
        $function = $this->get_settings( 'function' );
        $params = $this->get_settings( 'params' );
  
        if (is_callable($function)) {
            echo wp_kses_post($function($params, $post));
        }
    }
  }