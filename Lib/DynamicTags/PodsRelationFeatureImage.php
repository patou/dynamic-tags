<?php
/**
 * This will add boolean for pods
 */

namespace DynamicTags\Lib\DynamicTags;

use DynamicTags\Lib\ElementBase;
use Elementor\Controls_Manager;
use ElementorPro\Modules\DynamicTags\Pods\Tags\Pods_Image;
use ElementorPro\Modules\DynamicTags\Pods\pods_api;
use ElementorPro\Modules\DynamicTags\Pods\Module;

if (function_exists('pods_api')) {
    class PodsRelationFeatureImage extends Pods_Image {
        use ElementBase;

        public function get_name() {
            return 'dynamic-tags-pods-relation-feature-image';
        }


        public function get_title() {
            return __( 'Pods', 'elementor-pro' ) . ' ' . __( 'Field', 'elementor-pro' ) . ' Relation Feature image';
        }

        public function get_value( array $options = [] ) {
            $key = $this->get_settings( 'key' );
            $image_fallback = $this->get_settings( 'fallback' );
            
            if ( empty( $key ) ) {
                return $image_fallback;
            }

            list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );
            /**
             * @var \Pods
             */
            $pod = pods( $pod_name, get_the_ID() );

            if ( false === $pod ) {
                return $image_fallback;
            }
            
            $relation = $pod->field( $meta_key );
            if ( empty( $relation ) ) {
                return $image_fallback;
            }
            
            $imageID = get_post_thumbnail_id($relation['ID']);
            $image = get_post( $imageID );
            
            if ( empty($image)) {
                return $image_fallback;
            }

            $image_data = [
                'id' => empty( $imageID ) ? $image_fallback['id'] : $imageID,
                'url' => empty( $image->guid ) ? $image_fallback['url'] : $image->guid,
            ];
            return $image_data;
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
                    'type' => Controls_Manager::MEDIA,
                ]
            );
        }

    }
}