<?php
/**
 * This will add boolean for pods
 */

namespace DynamicTags\Lib\DynamicTags;

use DynamicTags\Lib\ElementBase;
use Elementor\Controls_Manager;
use ElementorPro\Modules\DynamicTags\Pods\Tags\Pods_URL;
use ElementorPro\Modules\DynamicTags\Pods\pods_api;
use ElementorPro\Modules\DynamicTags\Pods\Module;

if (function_exists('pods_api')) {
    class PodsRelationPermalink extends Pods_URL {
        use ElementBase;

        public function get_name() {
            return 'dynamic-tags-pods-relation-permalink';
        }


        public function get_title() {
            return __( 'Pods', 'elementor-pro' ) . ' ' . __( 'Field', 'elementor-pro' ) . ' Relation Permalink';
        }

        public function get_value( array $options = [] ) {
            $key = $this->get_settings( 'key' );
            if ( empty( $key ) ) {
                return false;
            }

            list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );
            /**
             * @var \Pods
             */
            $pod = pods( $pod_name, get_the_ID() );

            if ( false === $pod ) {
                return [];
            }
            $link = $pod->field( $meta_key );
            if ( empty($link['pod_item_id'])) {
                return [];
            }

            $value = get_permalink($link['pod_item_id']);
    
            if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
                $value = $this->get_settings( 'fallback' );
            }
    
            return wp_kses_post( $value );
        }

        protected function register_controls() {
            $this->add_control(
                'key',
                [
                    'label' => esc_html__( 'Key', 'elementor-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'groups' => Module::get_control_options( ['pick'] ),
                ]
            );
    
            $this->add_control(
                'fallback',
                [
                    'label' => esc_html__( 'Fallback', 'elementor-pro' ),
                ]
            );
        }

    }
}