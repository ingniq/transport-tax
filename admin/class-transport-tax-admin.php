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
            <select id="reg" class="form-control">
              <option value="21">Алтайский край</option>
              <option value="27">Амурская область</option>
              <option value="28">Архангельская область</option>
              <option value="29">Астраханская область</option>
              <option value="30">Белгородская область</option>
              <option value="31">Брянская область</option>
              <option value="32">Владимирская область</option>
              <option value="33">Волгоградская область</option>
              <option value="34">Вологодская область</option>
              <option value="35">Воронежская область</option>
              <option value="78">Еврейская автономная область</option>
              <option value="74">Забайкальский край</option>
              <option value="36">Ивановская область</option>
              <option value="37">Иркутская область</option>
              <option value="7">Кабардино-Балкарская Республика</option>
              <option value="38">Калининградская область</option>
              <option value="39">Калужская область</option>
              <option value="40">Камчатский край</option>
              <option value="9">Карачаево-Черкесская Республика</option>
              <option value="41">Кемеровская область</option>
              <option value="42">Кировская область</option>
              <option value="43">Костромская область</option>
              <option value="22">Краснодарский край</option>
              <option value="23">Красноярский край</option>
              <option value="44">Курганская область</option>
              <option value="45">Курская область</option>
              <option value="46">Ленинградская область</option>
              <option value="47">Липецкая область</option>
              <option value="48">Магаданская область</option>
              <option value="76" selected>Москва</option>
              <option value="49">Московская область</option>
              <option value="50">Мурманская область</option>
              <option value="80">Ненецкий автономный округ</option>
              <option value="51">Нижегородская область</option>
              <option value="52">Новгородская область</option>
              <option value="53">Новосибирская область</option>
              <option value="54">Омская область</option>
              <option value="55">Оренбургская область</option>
              <option value="56">Орловская область</option>
              <option value="57">Пензенская область</option>
              <option value="58">Пермский край</option>
              <option value="24">Приморский край</option>
              <option value="59">Псковская область</option>
              <option value="1">Республика Адыгея</option>
              <option value="4">Республика Алтай</option>
              <option value="2">Республика Башкортостан</option>
              <option value="3">Республика Бурятия</option>
              <option value="5">Республика Дагестан</option>
              <option value="6">Республика Ингушетия</option>
              <option value="8">Республика Калмыкия</option>
              <option value="10">Республика Карелия</option>
              <option value="11">Республика Коми</option>
              <option value="79">Республика Крым</option>
              <option value="12">Республика Марий Эл</option>
              <option value="13">Республика Мордовия</option>
              <option value="14">Республика Саха (Якутия)</option>
              <option value="15">Республика Северная Осетия - Алания</option>
              <option value="16">Республика Татарстан</option>
              <option value="17">Республика Тыва</option>
              <option value="19">Республика Хакасия</option>
              <option value="60">Ростовская область</option>
              <option value="61">Рязанская область</option>
              <option value="62">Самарская область</option>
              <option value="77">Санкт-Петербург</option>
              <option value="63">Саратовская область</option>
              <option value="64">Сахалинская область</option>
              <option value="65">Свердловская область</option>
              <option value="84">Севастополь</option>
              <option value="66">Смоленская область</option>
              <option value="25">Ставропольский край</option>
              <option value="67">Тамбовская область</option>
              <option value="68">Тверская область</option>
              <option value="69">Томская область</option>
              <option value="70">Тульская область</option>
              <option value="71">Тюменская область</option>
              <option value="18">Удмуртская Республика</option>
              <option value="72">Ульяновская область</option>
              <option value="26">Хабаровский край</option>
              <option value="81">Ханты-Мансийский автономный округ - Югра</option>
              <option value="73">Челябинская область</option>
              <option value="85">Чеченская Республика</option>
              <option value="20">Чувашская Республика</option>
              <option value="82">Чукотский автономный округ</option>
              <option value="83">Ямало-Ненецкий автономный округ</option>
              <option value="75">Ярославская область</option>
            </select>
          </div><!-- /input-group -->
          <div class="input-group">
            <span id="transport_sr" class="input-group-addon">Транспортное средство</span>
            <select id="autoType" class="form-control">
              <option value="1">Легковой автомобиль</option>
              <option value="2">Мотоцикл, мотороллер</option>
              <option value="3">Автобус</option>
              <option value="4">Грузовой автомобиль</option>
              <option value="6">Снегоход, мотосани</option>
              <option value="7">Катер, моторная лодка, другое водное ТС</option>
              <option value="8">Яхта, другое парусно-моторное судно</option>
              <option value="9">Гидроцикл</option>
              <option value="10">Несамоходное (буксируемое) судно</option>
            </select>
          </div><!-- /input-group -->
          <div class="input-group">
            <span class="input-group-addon">Год</span>
            <select id="nalog_year" class="form-control">
              <option>2015</option>
              <option>2016</option>
              <option>2017</option>
              <option selected>2018</option>
            </select>
          </div><!-- /input-group -->

          <div class="input-group">
            <span class="input-group-addon">Мощность</span>
            <input id="power" type="number" class="form-control" placeholder="мощность" value="100">
            <span class="input-group-addon" id="input_group_ls">л.с.</span>
          </div><!-- /input-group -->

          <div id="result" class="transport-tax-box-result"></div>

        </div>

        <table id="mainTable" class="table table-striped">
          <thead>
          <tr>
            <th>Наименование объекта налогообложения</th>
            <th>Ставка, руб</th>
          </tr>
          </thead>
          <tbody>
          <tr hidden="true">
            <td>01</td>
            <td></td>
          </tr>
          </tbody>
        </table>
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
