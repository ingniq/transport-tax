<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/ingniq/transport-tax
 * @since      1.0.0
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/admin
 * @author     Igor Filatov <ingniq8@gmail.com>
 */
class Transport_Tax_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Transport_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Transport_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/transport-tax-admin.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Transport_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Transport_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/transport-tax-admin.js', [ 'jquery' ], $this->version, false );

	}

	public function transport_tax_shortcode() {
		ob_start(); ?>
      <div class="transport-tax-box">
        <div class="transport-tax-box-title" id="first_title">
          <h5>Калькулятор транспортного налога</h5>
        </div>
        <div class="transport-tax-box-content">
          <div class="notice">
            <strong>Уважаемые пользователи!</strong>
            <p>Расчет транспортного налога с помощью данного сервиса носит ознакомительный характер. Поскольку
              транспортный
              налог относится к налогам, исчисляемым налоговой инспекцией, ФНС России рекомендует Вам осуществлять
              оплату
              транспортного налога после получения налогового уведомления.</p>
          </div>
          <div class="input-group">
            <span class="input-group-addon">Регион</span>
            <select id="reg" class="form-control"></select>
          </div><!-- /input-group -->
          <div class="input-group">
            <span class="input-group-addon">Год</span>
            <select id="nalog_year" class="form-control"></select>
          </div><!-- /input-group -->
          <div class="input-group">
            <span class="input-group-addon">Месяцев во владении</span>
            <select id="month" class="form-control">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12" selected>12</option>
            </select>
          </div><!-- /input-group -->
          <div class="input-group">
            <span class="input-group-addon">Транспортное средство</span>
            <select id="auto_type" class="form-control"></select>
          </div><!-- /input-group -->

          <div class="input-group hidden">
            <span class="input-group-addon">Марка транспортного средства</span>
            <select id="auto_brand" class="form-control"></select>
          </div><!-- /input-group -->
          <div class="input-group hidden">
            <span class="input-group-addon">Модель транспортного средства</span>
            <select id="auto_model" class="form-control"></select>
          </div><!-- /input-group -->
          <div class="input-group hidden">
            <span class="input-group-addon">Год выпуска транспортного средства</span>
            <input id="model_year" type="number" class="form-control" placeholder="Год выпуска" >
          </div><!-- /input-group -->

          <div class="input-group">
            <span class="input-group-addon">Мощность</span>
            <input id="power" type="number" class="form-control" placeholder="мощность" value="100">
          </div><!-- /input-group -->
          <div class="input-group hidden">
            <span class="input-group-addon">Льгота</span>
            <select id="benefit" class="form-control">
              <option value="0">Нет</option>
            </select>
          </div><!-- /input-group -->

          <button id="calculate" class="button">Рассчитать</button>

          <div id="result" class="transport-tax-box-result"></div>
          <div id="warning" class="warning"></div>

        </div>

      </div>
      <div class="panel panel-success transport-tax-box-description">
        <div class="panel-heading">
          <h5>Порядок расчета (налоговая база, сроки уплаты)</h5>
        </div>
        <div class="panel-body">
          <p>Плата налога и авансовых платежей по налогу производится налогоплательщиками в бюджет по месту
            нахождения транспортных средств в порядке и сроки, которые установлены законами субъектов
            Российской Федерации.

            При этом срок уплаты налога для налогоплательщиков, являющихся организациями, не может быть
            установлен ранее срока, предусмотренного п. 3 ст. 363.1 НК РФ.

            Сумма налога исчисляется с учетом количества месяцев, в течение которых транспортное средство
            было зарегистрировано на налогоплательщика, по итогам каждого налогового периода на основании
            документально подтвержденных данных о транспортных средствах, подлежащих налогообложению (ст. 52
            и 54 НК РФ). </p>
          <p>Помимо оплаты транспортного налога, для управления автомобилем необходимо <strong>купить полис
              осаго</strong>. <strong>Каско</strong> – это добровольное страхование транспортного средства (ТС)
            от ущерба, хищения или угона, которое приобретается по желанию владельца ТС.
            Важно понимать, что <strong>ОСАГО</strong> – это обязательное страхование гражданской
            ответственности владельцев ТС перед третьими лицами: выплаты по полису производятся в пользу
            потерпевшего, а каско – это добровольное имущественное страхование, которое
            защищает интересы страхователя (выгодоприобретателя) независимо от его вины. Поэтому, в
            отличиеот ОСАГО, <strong>стоимость каско</strong> не регламентируются государством, а
            устанавливаются самой страховой компанией. <strong>Купить каско и полис осаго</strong> можно в
            страховых компаниях.</p>
        </div>
      </div>
		<?php
		$output_string = ob_get_contents();
		ob_end_clean();

		return $output_string;
	}

	public function transport_tax_add_tinymce_script( $plugin_array ) {
		$plugin_array['transport_tax_mce_button'] = plugin_dir_url( __FILE__ ) . 'js/transport-tax-button.js';

		return $plugin_array;
	}

	public function transport_tax_register_mce_button( $buttons ) {
		array_push( $buttons, 'transport_tax_mce_button' );

		return $buttons;
	}

}
