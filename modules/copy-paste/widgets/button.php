<?php

namespace EAddonsCopyPaste\Modules\CopyPaste\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use EAddonsForElementor\Base\Base_Widget;
use EAddonsForElementor\Core\Utils;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Copy button
 *
 * Elementor widget for e-addons
 *
 */
class Button extends Base_Widget {
    
    public function __construct($data = [], $args = null) {
       parent::__construct($data, $args);
       $this->register_script('assets/lib/clipboard.js/clipboard.min.js'); // from module folder
    }

    public function get_name() {
        return 'copy-button';
    }

    public function get_title() {
        return __('Copy Button', 'e-addons');
    }

    public function get_icon() {
        return 'eadd-button-copy';
    }

    public function get_categories() {
		return [ 'buttons' ];
    }

    public function get_pid() {
        return 221;
    }

    /**
     * A list of scripts that the widgets is depended in
     * @since 1.3.0
     * */
    public function get_script_depends() {
        return ['clipboard.min', 'e-addons-copy-btn'];
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
                'section_button',
                [
                    'label' => __('Button', 'elementor'),
                ]
        );

        $this->add_control(
                'button_type',
                [
                    'label' => __('Type', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => __('Default', 'elementor'),
                        'info' => __('Info', 'elementor'),
                        'success' => __('Success', 'elementor'),
                        'warning' => __('Warning', 'elementor'),
                        'danger' => __('Danger', 'elementor'),
                    ],
                    'prefix_class' => 'elementor-button-',
                ]
        );

        $this->add_control(
                'text',
                [
                    'label' => __('Text', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'placeholder' => __('Copy to Clipboard', 'e-addons'),
                ]
        );

        $this->add_responsive_control(
                'align',
                [
                    'label' => __('Alignment', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __('Center', 'elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'elementor'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __('Justified', 'elementor'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'render_type' => 'template',
                    'prefix_class' => 'elementor%s-align-',
                    'default' => '',
                ]
        );

        $this->add_control(
                'size',
                [
                    'label' => __('Size', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'sm',
                    'options' => [
                        'xs' => __('Extra Small', 'elementor'),
                        'sm' => __('Small', 'elementor'),
                        'md' => __('Medium', 'elementor'),
                        'lg' => __('Large', 'elementor'),
                        'xl' => __('Extra Large', 'elementor'),
                    ],
                    'style_transfer' => true,
                ]
        );

        $this->add_control(
                'selected_icon',
                [
                    'label' => __('Icon', 'elementor'),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'render_type' => 'template',
                    'fa4compatibility' => 'icon',
                    'default' => ['value' => 'far fa-clipboard', 'library' => 'fa-regular'],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-left: 0; margin-right: 0;',
                    ],
                ]
        );

        $this->add_control(
                'icon_align',
                [
                    'label' => __('Icon Position', 'elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => __('Before', 'elementor'),
                        'right' => __('After', 'elementor'),
                    ],                    
                    'condition' => [
                        'selected_icon[value]!' => '',
                    ],
                ]
        );

        $this->add_control(
                'icon_indent',
                [
                    'label' => __('Icon Spacing', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'default' => ['size' => 15, 'unit' => 'px'],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'text!' => '',
                        'selected_icon[value]!' => '',
                    ]
                ]
        );
        $this->add_control(
                'icon_size',
                [
                    'label' => __('Icon Size', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-button-text' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'selected_icon[value]!' => '',
                    ]
                ]
        );

        $this->add_control(
                'button_css_id',
                [
                    'label' => __('Button ID', 'elementor'),
                    'type' => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default' => '',
                    'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
                    'label_block' => false,
                    'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor'),
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_style',
                [
                    'label' => __('Button', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography',
                    'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
                ]
        );

        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'text_shadow',
                    'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
                ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'background_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );

        $this->add_control(
                'hover_color',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                        '{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_background_hover_color',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => __('Border Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hover_animation',
                [
                    'label' => __('Hover Animation', 'elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'border',
                    'selector' => '{{WRAPPER}} .elementor-button',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'border_radius',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'selector' => '{{WRAPPER}} .elementor-button',
                ]
        );

        $this->add_responsive_control(
                'text_padding',
                [
                    'label' => __('Padding', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'elementor'),
                ]
        );

        $this->add_control(
                'e_clipboard_type',
                [
                    'label' => __('Type', 'e-addons'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text' => [
                            'title' => __('Text', 'e-addons'),
                            'icon' => 'fa fa-window-minimize',
                        ],
                        'textarea' => [
                            'title' => __('Textarea', 'e-addons'),
                            'icon' => 'fa fa-bars',
                        ],
                        'code' => [
                            'title' => __('Code', 'e-addons'),
                            'icon' => 'fa fa-code',
                        ],
                    ],
                    //'label_block' => true,
                    'default' => 'text',
                    'toggle' => false,
                ]
        );
        
        $this->add_control(
                'e_clipboard_visible', [
            'label' => __('Show Content', 'e-addons'),
            'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'yes' => [
                            'title' => __('Yes', 'e-addons'),
                            'icon' => 'fa fa-eye',
                        ],
                        'no' => [
                            'title' => __('No', 'e-addons'),
                            'icon' => 'fa fa-eye-slash',
                        ],                        
                    ],
            'default' => 'yes',
            'toggle' => false,
            'render_type' => 'template',
            'selectors' => [
                '{{WRAPPER}} .e-offscreen' => 'position: absolute; left: -999em; display: block !important;',
                '{{WRAPPER}} .e-input-group' => 'display: flex;position: relative;flex-wrap: wrap;align-items: stretch;width: 100%;',
                '{{WRAPPER}} .e-input-group-append, {{WRAPPER}} .e-input-group-prepend' => 'display: flex;',
                '{{WRAPPER}} .e-input-group-append' => 'margin-left: -1px;',
                '{{WRAPPER}} .e-input-group > .e-form-control' => 'position: relative; flex: 1 1 auto; width: 1%; margin-bottom: 0;',
            ]
                ]
        );
        $this->add_control(
                'e_clipboard_readonly', [
            'label' => __('Read Only', 'e-addons'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'condition' => [
                'e_clipboard_visible' => 'yes',
            ]
                ]
        );

        // title
        $this->add_control(
                'e_clipboard_text', [
            'label' => __('Text', 'e-addons'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'default' => get_home_url(),
            'condition' => [
                'e_clipboard_type' => 'text',
            ]
                ]
        );
        $this->add_control(
                'e_clipboard_textarea', [
            'label' => __('Text', 'e-addons'),
            'type' => Controls_Manager::TEXTAREA,
            'label_block' => true,
            'default' => 'I am a sample demo text.' . PHP_EOL . 'Find more at e-addons.com',
            'condition' => [
                'e_clipboard_type' => 'textarea',
            ]
                ]
        );
        $this->add_control(
                'e_clipboard_code', [
            'label' => __('Code', 'e-addons'),
            'type' => Controls_Manager::CODE,
            'label_block' => true,
            'default' => "<?php".PHP_EOL."echo 'Hello World!';",
            'condition' => [
                'e_clipboard_type' => 'code',
            ]
                ]
        );

        $code_modes = array('' => 'other');
        $modes_files = glob($this->get_module_path() . 'assets/lib/codemirror/mode/*');
        if (!empty($modes_files)) {
            foreach ($modes_files as $key => $value) {
                $mname = basename($value);
                $code_modes[$mname] = $mname;
            }
        }
        $this->add_control(
                'e_clipboard_code_type', [
            'label' => __('Language', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => $code_modes,
            'label_block' => true,
            'default' => 'php',
            'condition' => [
                'e_clipboard_type' => 'code',
            ]
                ]
        );

        $code_themes = array('default' => 'default');
        $themes_files = glob($this->get_module_path() . 'assets/lib/codemirror/theme/*.css');
        //var_dump($themes_files); die();
        if (!empty($themes_files)) {
            foreach ($themes_files as $key => $value) {
                $tname = str_replace('.css', '', basename($value));
                $code_themes[$tname] = $tname;
            }
        }
        $this->add_control(
                'e_clipboard_code_theme', [
            'label' => __('Theme', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => $code_themes,
            'label_block' => true,
            'condition' => [
                'e_clipboard_type' => 'code',
            ]
                ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
                'section_style_value',
                [
                    'label' => __('Content', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'e_clipboard_visible' => 'yes',
                        'e_clipboard_type!' => 'code',
                    ]
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'typography_value',
                    'selector' => '{{WRAPPER}} .e-clipboard-value',
                ]
        );

        $this->add_group_control(
                Group_Control_Text_Shadow::get_type(),
                [
                    'name' => 'text_shadow_value',
                    'selector' => '{{WRAPPER}} .e-clipboard-value',
                ]
        );

        $this->start_controls_tabs('tabs_button_style_value');

        $this->start_controls_tab(
                'tab_button_normal_value',
                [
                    'label' => __('Normal', 'elementor'),
                ]
        );

        $this->add_control(
                'button_text_color_value',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'background_color_value',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover_value',
                [
                    'label' => __('Hover', 'elementor'),
                ]
        );

        $this->add_control(
                'hover_color_value',
                [
                    'label' => __('Text Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value:hover, {{WRAPPER}} .e-clipboard-value:focus' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .e-clipboard-value:hover svg, {{WRAPPER}} .e-clipboard-value:focus svg' => 'fill: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_background_hover_color_value',
                [
                    'label' => __('Background Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value:hover, {{WRAPPER}} .e-clipboard-value:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color_value',
                [
                    'label' => __('Border Color', 'elementor'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'border_border!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value:hover, {{WRAPPER}} .e-clipboard-value:focus' => 'border-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hover_animation_value',
                [
                    'label' => __('Hover Animation', 'elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'border_value',
                    'selector' => '{{WRAPPER}} .e-clipboard-value',
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'border_radius_value',
                [
                    'label' => __('Border Radius', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow_value',
                    'selector' => '{{WRAPPER}} .e-clipboard-value',
                ]
        );

        $this->add_responsive_control(
                'text_padding_value',
                [
                    'label' => __('Padding', 'elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
                'section_style_textarea',
                [
                    'label' => __('Textarea', 'elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'e_clipboard_visible' => 'yes',
                        'e_clipboard_type!' => 'text',
                    ]
                ]
        );
        $this->add_control(
                'e_clipboard_textarea_height',
                [
                    'label' => __('Height', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-value' => 'height: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .CodeMirror' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'default' => ['size' => 150, 'unit' => 'px'],
                ]
        );
        $this->add_control(
                'e_clipboard_btn_position',
                [
                    'label' => __('Button Position', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'static',
                    'options' => [
                        'static' => [
                            'title' => __('Static', 'elementor'),
                            'icon' => 'fa fa-square',
                        ],
                        'absolute' => [
                            'title' => __('Absolute', 'elementor'),
                            'icon' => 'fa fa-square-o',
                        ],
                    ],
                    'toggle' => false,
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button' => 'position: {{VALUE}};',
                    ],
                    'render_type' => 'template',
                ]
        );
        $this->add_control(
                'e_clipboard_btn_position_top',
                [
                    'label' => __('Top', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                    'default' => ['size' => 0, 'unit' => 'px'],
                    'condition' => [
                        'e_clipboard_btn_position' => 'absolute',
                    ]
                ]
        );
        $this->add_control(
                'e_clipboard_btn_position_right',
                [
                    'label' => __('Right', 'elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', '%' ],
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                    'default' => ['size' => 0, 'unit' => 'px'],
                    'condition' => [
                        'e_clipboard_btn_position' => 'absolute',
                    ]
                ]
        );

        $this->add_control(
                'e_clipboard_btn_hide',
                [
                    'label' => __('Button Visibility', 'elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => '1',
                    'options' => [
                        '1' => [
                            'title' => __('Always visible', 'elementor'),
                            'icon' => 'fa fa-square',
                        ],
                        '0' => [
                            'title' => __('On Hover', 'elementor'),
                            'icon' => 'fa fa-square-o',
                        ],
                    ],
                    'toggle' => false,
                    'selectors' => [
                        '{{WRAPPER}} .e-clipboard-wrapper .elementor-button' => 'opacity: {{VALUE}}; z-index: 3;',
                        '{{WRAPPER}} .e-clipboard-wrapper:hover .elementor-button' => 'opacity: 1;',
                        '{{WRAPPER}} .e-clipboard-wrapper .elementor-button.animated' => 'opacity: 1;',
                    ],
                    'render_type' => 'template',
                    'condition' => [
                        'e_clipboard_btn_position' => 'absolute',
                    ]
                ]
        );
        $this->end_controls_section();
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'e-clipboard-wrapper');
        $this->add_render_attribute('wrapper', 'class', 'e-clipboard-wrapper-' . $settings['e_clipboard_type']);
        if ($settings['e_clipboard_type'] == 'text' && $settings['e_clipboard_visible'] == 'yes') {
            $this->add_render_attribute('wrapper', 'class', 'elementor-field-group');
            $this->add_render_attribute('wrapper', 'class', 'e-input-group');

            if ($settings['align'] == 'right') {
                $this->add_render_attribute('wrapper-btn', 'class', 'e-input-group-append');
            } else {
                $this->add_render_attribute('wrapper-btn', 'class', 'e-input-group-prepend');
            }
            $this->add_render_attribute('wrapper-btn', 'class', 'elementor-field-type-submit');
        }

        if ($settings['e_clipboard_type'] == 'code' && $settings['e_clipboard_visible'] == 'yes') {
            wp_enqueue_script('wp-codemirror');
            wp_enqueue_code_editor(array(//));
                'type' => $settings['e_clipboard_code_type'],
                'codemirror' => array(
                    'indentUnit' => 2,
                    'tabSize' => 2,
                ),
            ));
            if ($settings['e_clipboard_code_type']) {
                wp_enqueue_script('codemirror-mode', $this->get_module_url() . 'assets/lib/codemirror/mode/' . $settings['e_clipboard_code_type'] . '/' . $settings['e_clipboard_code_type'] . '.js');
            }
            if ($settings['e_clipboard_code_theme'] && $settings['e_clipboard_code_theme'] != 'default') {
                wp_enqueue_style('codemirror-theme', $this->get_module_url() . 'assets/lib/codemirror/theme/' . $settings['e_clipboard_code_theme'] . '.css');
            }
        }

        $this->add_render_attribute('button', 'class', 'elementor-button');

        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('button', 'id', $settings['button_css_id']);
        }

        if (!empty($settings['size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
            $this->add_render_attribute('input', 'class', 'elementor-size-' . $settings['size']);
        }

        if ($settings['hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        $this->add_render_attribute('input', 'class', 'e-clipboard-value');
        $this->add_render_attribute('input', 'id', 'e-clipboard-value-' . $this->get_id() . '-' . get_the_id());
        $this->add_render_attribute('input', 'class', 'elementor-field-textual');

        $this->add_render_attribute('button', 'type', 'button');
        $this->add_render_attribute('button', 'id', 'e-clipboard-btn-' . $this->get_id() . '-' . get_the_id());
        $this->add_render_attribute('button', 'data-clipboard-target', '#e-clipboard-value-' . $this->get_id() . '-' . get_the_id());

        if ($settings['e_clipboard_visible'] == 'no' || $settings['e_clipboard_type'] == 'code') {
            $this->add_render_attribute('input', 'aria-hidden', 'true');
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $this->add_render_attribute('input', 'class', 'elementor-hidden');
            } else {
                $this->add_render_attribute('input', 'class', 'e-offscreen');
            }
        }

        if (!empty($settings['e_clipboard_readonly'])) {
            $this->add_render_attribute('input', 'readonly');
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
        <?php
        if ($settings['e_clipboard_type'] == 'text') {
            $this->add_render_attribute('input', 'type', 'text');
            $this->add_render_attribute('input', 'value', $settings['e_clipboard_text']);
            $this->add_render_attribute('input', 'class', 'e-form-control');
            ?>
                <?php if ($settings['align'] != 'right') { ?>
                    <div <?php echo $this->get_render_attribute_string('wrapper-btn'); ?>>       
                    <?php $this->render_text(); ?>              
                    </div>
                <?php } ?>
                <input <?php echo $this->get_render_attribute_string('input'); ?>>            
                    <?php if ($settings['align'] == 'right') { ?>
                    <div <?php echo $this->get_render_attribute_string('wrapper-btn'); ?>>       
                    <?php $this->render_text(); ?>              
                    </div>
                <?php
                }
            }

            if ($settings['e_clipboard_type'] == 'textarea' || $settings['e_clipboard_type'] == 'code') {
                $this->add_render_attribute('input', 'class', 'e-block');
                $code_settings = wp_json_encode(array('type' => $settings['e_clipboard_code_type'], 'readonly' => $settings['e_clipboard_readonly'], 'theme' => $settings['e_clipboard_code_theme']));
                if ($settings['e_clipboard_type'] == 'code') {
                    $this->add_render_attribute('input', 'class', 'e-codemirror');
                    $this->add_render_attribute('input', 'data-code', $code_settings);
                }
                ?>
                <?php $this->render_text(); ?>
                <textarea <?php echo $this->get_render_attribute_string('input'); ?>><?php echo $settings['e_clipboard_type'] == 'textarea' ? $settings['e_clipboard_textarea'] : $settings['e_clipboard_code']; ?></textarea>    
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => ['elementor-button-content-wrapper', 'e-flexbox'],
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ]);

        $this->add_inline_editing_attributes('text', 'none');
        ?>
        <button <?php echo $this->get_render_attribute_string('button'); ?>>
            <span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>
        <?php if (!empty($settings['icon']) || !empty($settings['selected_icon']['value'])) : ?>
                    <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                    <?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                    <?php endif; ?>
                <span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $settings['text']; ?></span>
            </span>
        </button>
        <?php
    }

    public function on_import($element) {
        return Icons_Manager::on_import_migration($element, 'icon', 'selected_icon');
    }

}
