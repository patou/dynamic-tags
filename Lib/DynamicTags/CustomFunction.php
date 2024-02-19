<?php
namespace DynamicTags\Lib\DynamicTags;


use ElementorPro\Modules\DynamicTags\Module;
use Elementor\Core\DynamicTags\Manager;
use Elementor\Plugin;

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
        return __('Custom Callback', 'dynamic-tags');
    }
  
    protected function _register_controls() {
        $this->add_control(
            'function',
            [
                'label' => __( 'Function', 'dynamic-tags' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'ai' => [
                    'active' => false,
                ],
            ]
        );
        $this->add_control(
            'param1',
            [
                'label' => __( 'Params', 'dynamic-tags' ) . ' 1',
                'description' => 'A parameter 1 for the function.',
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $this->add_control(
            'param2',
            [
                'label' => __( 'Params', 'dynamic-tags' ) . ' 2',
                'description' => 'A parameter 2 for the function.',
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $this->add_control(
            'param3',
            [
                'label' => __( 'Params', 'dynamic-tags' ) . ' 3',
                'description' => 'A parameter 3 for the function.',
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
    }
    public function render() {
        global $post;
  
        $function = $this->get_settings( 'function' );
        $settings = $this->get_settings();
        $param1 = $this->get_settings( 'param1' );
        $param2 = $this->get_settings( 'param2' );
        $param3 = $this->get_settings( 'param3' );
        if (isset($settings[Manager::DYNAMIC_SETTING_KEY])) {
            foreach ($settings[Manager::DYNAMIC_SETTING_KEY] as $key => $setting) {
                $tag = Plugin::$instance->dynamic_tags->tag_text_to_tag_data($setting);
                $value = Plugin::$instance->dynamic_tags->get_tag_data_content($tag['id'], $tag['name'], $tag['settings']);
                $$key = $value;
            }
        }
        $args = [];
        if (!empty($param1)) {
            $args[] = $param1;
        }
        if (!empty($param2)) {
            $args[] = $param2;
        }
        if (!empty($param3)) {
            $args[] = $param3;
        }
  
        if (is_callable($function)) {
            echo wp_kses_post(call_user_func_array($function, $args));
        }
    }
  }