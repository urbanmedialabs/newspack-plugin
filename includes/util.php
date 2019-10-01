<?php
/**
 * Useful functions.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function newspack_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'newspack_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Converts a string (e.g. 'yes' or 'no') to a bool.
 *
 * @param string $string String to convert.
 * @return bool
 */
function newspack_string_to_bool( $string ) {
	return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * Activate the Newspack theme (installing it if necessary).
 *
 * @return bool | WP_Error True on success. WP_Error on failure.
 */
function newspack_install_activate_theme() {
	$theme_slug = 'newspack-theme';
	$theme_url  = 'https://github.com/Automattic/newspack-theme/releases/latest/download/newspack-theme.zip';

	$theme_object = wp_get_theme( $theme_slug );
	if ( ! $theme_object->exists() ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/theme.php';
		WP_Filesystem();

		$skin     = new \Automatic_Upgrader_Skin();
		$upgrader = new \Theme_Upgrader( $skin );
		$success  = $upgrader->install( $theme_url );

		if ( is_wp_error( $success ) ) {
			return $success;
		} else if ( $success ) {
			// Make sure `-master` or `-1.0.1` etc. are not in the theme folder name.
			// We just want the folder name to be the theme slug.
			$theme_object    = $upgrader->theme_info();
			$theme_folder    = $theme_object->get_template_directory();
			$expected_folder = $theme_object->get_theme_root() . '/' . $theme_slug;
			if ( $theme_folder !== $expected_folder ) {
				rename( $theme_folder, $expected_folder );
			}
		} else {
			return new \WP_Error(
				'newspack_theme_failed_install',
				__( 'Newspack theme installation failed.', 'newspack' )
			);
		}
	}

	switch_theme( $theme_slug );
	return true;
}

/**
 * Get full list of currency codes. Copied from https://github.com/woocommerce/woocommerce/blob/master/includes/wc-core-functions.php
 *
 * @return array
 */
function newspack_currencies() {
	$currencies = array(
		'AFN' => __( 'Afghan afghani', 'newspack' ),
		'ALL' => __( 'Albanian lek', 'newspack' ),
		'DZD' => __( 'Algerian dinar', 'newspack' ),
		'AOA' => __( 'Angolan kwanza', 'newspack' ),
		'ARS' => __( 'Argentine peso', 'newspack' ),
		'AMD' => __( 'Armenian dram', 'newspack' ),
		'AWG' => __( 'Aruban florin', 'newspack' ),
		'AUD' => __( 'Australian dollar', 'newspack' ),
		'AZN' => __( 'Azerbaijani manat', 'newspack' ),
		'BSD' => __( 'Bahamian dollar', 'newspack' ),
		'BHD' => __( 'Bahraini dinar', 'newspack' ),
		'BDT' => __( 'Bangladeshi taka', 'newspack' ),
		'BBD' => __( 'Barbadian dollar', 'newspack' ),
		'BYR' => __( 'Belarusian ruble (old)', 'newspack' ),
		'BYN' => __( 'Belarusian ruble', 'newspack' ),
		'BZD' => __( 'Belize dollar', 'newspack' ),
		'BMD' => __( 'Bermudian dollar', 'newspack' ),
		'BTN' => __( 'Bhutanese ngultrum', 'newspack' ),
		'BTC' => __( 'Bitcoin', 'newspack' ),
		'VES' => __( 'Bol&iacute;var soberano', 'newspack' ),
		'BOB' => __( 'Bolivian boliviano', 'newspack' ),
		'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'newspack' ),
		'BWP' => __( 'Botswana pula', 'newspack' ),
		'BRL' => __( 'Brazilian real', 'newspack' ),
		'BND' => __( 'Brunei dollar', 'newspack' ),
		'BGN' => __( 'Bulgarian lev', 'newspack' ),
		'MMK' => __( 'Burmese kyat', 'newspack' ),
		'BIF' => __( 'Burundian franc', 'newspack' ),
		'XPF' => __( 'CFP franc', 'newspack' ),
		'KHR' => __( 'Cambodian riel', 'newspack' ),
		'CAD' => __( 'Canadian dollar', 'newspack' ),
		'CVE' => __( 'Cape Verdean escudo', 'newspack' ),
		'KYD' => __( 'Cayman Islands dollar', 'newspack' ),
		'XAF' => __( 'Central African CFA franc', 'newspack' ),
		'CLP' => __( 'Chilean peso', 'newspack' ),
		'CNY' => __( 'Chinese yuan', 'newspack' ),
		'COP' => __( 'Colombian peso', 'newspack' ),
		'KMF' => __( 'Comorian franc', 'newspack' ),
		'CDF' => __( 'Congolese franc', 'newspack' ),
		'CRC' => __( 'Costa Rican col&oacute;n', 'newspack' ),
		'HRK' => __( 'Croatian kuna', 'newspack' ),
		'CUC' => __( 'Cuban convertible peso', 'newspack' ),
		'CUP' => __( 'Cuban peso', 'newspack' ),
		'CZK' => __( 'Czech koruna', 'newspack' ),
		'DKK' => __( 'Danish krone', 'newspack' ),
		'DJF' => __( 'Djiboutian franc', 'newspack' ),
		'DOP' => __( 'Dominican peso', 'newspack' ),
		'XCD' => __( 'East Caribbean dollar', 'newspack' ),
		'EGP' => __( 'Egyptian pound', 'newspack' ),
		'ERN' => __( 'Eritrean nakfa', 'newspack' ),
		'ETB' => __( 'Ethiopian birr', 'newspack' ),
		'EUR' => __( 'Euro', 'newspack' ),
		'FKP' => __( 'Falkland Islands pound', 'newspack' ),
		'FJD' => __( 'Fijian dollar', 'newspack' ),
		'GMD' => __( 'Gambian dalasi', 'newspack' ),
		'GEL' => __( 'Georgian lari', 'newspack' ),
		'GHS' => __( 'Ghana cedi', 'newspack' ),
		'GIP' => __( 'Gibraltar pound', 'newspack' ),
		'GTQ' => __( 'Guatemalan quetzal', 'newspack' ),
		'GGP' => __( 'Guernsey pound', 'newspack' ),
		'GNF' => __( 'Guinean franc', 'newspack' ),
		'GYD' => __( 'Guyanese dollar', 'newspack' ),
		'HTG' => __( 'Haitian gourde', 'newspack' ),
		'HNL' => __( 'Honduran lempira', 'newspack' ),
		'HKD' => __( 'Hong Kong dollar', 'newspack' ),
		'HUF' => __( 'Hungarian forint', 'newspack' ),
		'ISK' => __( 'Icelandic kr&oacute;na', 'newspack' ),
		'INR' => __( 'Indian rupee', 'newspack' ),
		'IDR' => __( 'Indonesian rupiah', 'newspack' ),
		'IRR' => __( 'Iranian rial', 'newspack' ),
		'IRT' => __( 'Iranian toman', 'newspack' ),
		'IQD' => __( 'Iraqi dinar', 'newspack' ),
		'ILS' => __( 'Israeli new shekel', 'newspack' ),
		'JMD' => __( 'Jamaican dollar', 'newspack' ),
		'JPY' => __( 'Japanese yen', 'newspack' ),
		'JEP' => __( 'Jersey pound', 'newspack' ),
		'JOD' => __( 'Jordanian dinar', 'newspack' ),
		'KZT' => __( 'Kazakhstani tenge', 'newspack' ),
		'KES' => __( 'Kenyan shilling', 'newspack' ),
		'KWD' => __( 'Kuwaiti dinar', 'newspack' ),
		'KGS' => __( 'Kyrgyzstani som', 'newspack' ),
		'LAK' => __( 'Lao kip', 'newspack' ),
		'LBP' => __( 'Lebanese pound', 'newspack' ),
		'LSL' => __( 'Lesotho loti', 'newspack' ),
		'LRD' => __( 'Liberian dollar', 'newspack' ),
		'LYD' => __( 'Libyan dinar', 'newspack' ),
		'MOP' => __( 'Macanese pataca', 'newspack' ),
		'MKD' => __( 'Macedonian denar', 'newspack' ),
		'MGA' => __( 'Malagasy ariary', 'newspack' ),
		'MWK' => __( 'Malawian kwacha', 'newspack' ),
		'MYR' => __( 'Malaysian ringgit', 'newspack' ),
		'MVR' => __( 'Maldivian rufiyaa', 'newspack' ),
		'IMP' => __( 'Manx pound', 'newspack' ),
		'MRO' => __( 'Mauritanian ouguiya', 'newspack' ),
		'MUR' => __( 'Mauritian rupee', 'newspack' ),
		'MXN' => __( 'Mexican peso', 'newspack' ),
		'MDL' => __( 'Moldovan leu', 'newspack' ),
		'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'newspack' ),
		'MAD' => __( 'Moroccan dirham', 'newspack' ),
		'MZN' => __( 'Mozambican metical', 'newspack' ),
		'NAD' => __( 'Namibian dollar', 'newspack' ),
		'NPR' => __( 'Nepalese rupee', 'newspack' ),
		'ANG' => __( 'Netherlands Antillean guilder', 'newspack' ),
		'TWD' => __( 'New Taiwan dollar', 'newspack' ),
		'NZD' => __( 'New Zealand dollar', 'newspack' ),
		'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'newspack' ),
		'NGN' => __( 'Nigerian naira', 'newspack' ),
		'KPW' => __( 'North Korean won', 'newspack' ),
		'NOK' => __( 'Norwegian krone', 'newspack' ),
		'OMR' => __( 'Omani rial', 'newspack' ),
		'PKR' => __( 'Pakistani rupee', 'newspack' ),
		'PAB' => __( 'Panamanian balboa', 'newspack' ),
		'PGK' => __( 'Papua New Guinean kina', 'newspack' ),
		'PYG' => __( 'Paraguayan guaran&iacute;', 'newspack' ),
		'PHP' => __( 'Philippine peso', 'newspack' ),
		'PLN' => __( 'Polish z&#x142;oty', 'newspack' ),
		'GBP' => __( 'Pound sterling', 'newspack' ),
		'QAR' => __( 'Qatari riyal', 'newspack' ),
		'RON' => __( 'Romanian leu', 'newspack' ),
		'RUB' => __( 'Russian ruble', 'newspack' ),
		'RWF' => __( 'Rwandan franc', 'newspack' ),
		'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'newspack' ),
		'SHP' => __( 'Saint Helena pound', 'newspack' ),
		'WST' => __( 'Samoan t&#x101;l&#x101;', 'newspack' ),
		'SAR' => __( 'Saudi riyal', 'newspack' ),
		'RSD' => __( 'Serbian dinar', 'newspack' ),
		'SCR' => __( 'Seychellois rupee', 'newspack' ),
		'SLL' => __( 'Sierra Leonean leone', 'newspack' ),
		'SGD' => __( 'Singapore dollar', 'newspack' ),
		'PEN' => __( 'Sol', 'newspack' ),
		'SBD' => __( 'Solomon Islands dollar', 'newspack' ),
		'SOS' => __( 'Somali shilling', 'newspack' ),
		'ZAR' => __( 'South African rand', 'newspack' ),
		'KRW' => __( 'South Korean won', 'newspack' ),
		'SSP' => __( 'South Sudanese pound', 'newspack' ),
		'LKR' => __( 'Sri Lankan rupee', 'newspack' ),
		'SDG' => __( 'Sudanese pound', 'newspack' ),
		'SRD' => __( 'Surinamese dollar', 'newspack' ),
		'SZL' => __( 'Swazi lilangeni', 'newspack' ),
		'SEK' => __( 'Swedish krona', 'newspack' ),
		'CHF' => __( 'Swiss franc', 'newspack' ),
		'SYP' => __( 'Syrian pound', 'newspack' ),
		'TJS' => __( 'Tajikistani somoni', 'newspack' ),
		'TZS' => __( 'Tanzanian shilling', 'newspack' ),
		'THB' => __( 'Thai baht', 'newspack' ),
		'TOP' => __( 'Tongan pa&#x2bb;anga', 'newspack' ),
		'PRB' => __( 'Transnistrian ruble', 'newspack' ),
		'TTD' => __( 'Trinidad and Tobago dollar', 'newspack' ),
		'TND' => __( 'Tunisian dinar', 'newspack' ),
		'TRY' => __( 'Turkish lira', 'newspack' ),
		'TMT' => __( 'Turkmenistan manat', 'newspack' ),
		'UGX' => __( 'Ugandan shilling', 'newspack' ),
		'UAH' => __( 'Ukrainian hryvnia', 'newspack' ),
		'AED' => __( 'United Arab Emirates dirham', 'newspack' ),
		'USD' => __( 'United States (US) dollar', 'newspack' ),
		'UYU' => __( 'Uruguayan peso', 'newspack' ),
		'UZS' => __( 'Uzbekistani som', 'newspack' ),
		'VUV' => __( 'Vanuatu vatu', 'newspack' ),
		'VEF' => __( 'Venezuelan bol&iacute;var', 'newspack' ),
		'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'newspack' ),
		'XOF' => __( 'West African CFA franc', 'newspack' ),
		'YER' => __( 'Yemeni rial', 'newspack' ),
		'ZMW' => __( 'Zambian kwacha', 'newspack' ),
	);
	return $currencies;
}

/**
 * Get full list of country codes. Copied from https://github.com/woocommerce/woocommerce/blob/master/includes/class-wc-countries.php
 *
 * @return array
 */
function newspack_countries() {
	$countries = array(
		'AF' => __( 'Afghanistan', 'newspack' ),
		'AX' => __( '&#197;land Islands', 'newspack' ),
		'AL' => __( 'Albania', 'newspack' ),
		'DZ' => __( 'Algeria', 'newspack' ),
		'AS' => __( 'American Samoa', 'newspack' ),
		'AD' => __( 'Andorra', 'newspack' ),
		'AO' => __( 'Angola', 'newspack' ),
		'AI' => __( 'Anguilla', 'newspack' ),
		'AQ' => __( 'Antarctica', 'newspack' ),
		'AG' => __( 'Antigua and Barbuda', 'newspack' ),
		'AR' => __( 'Argentina', 'newspack' ),
		'AM' => __( 'Armenia', 'newspack' ),
		'AW' => __( 'Aruba', 'newspack' ),
		'AU' => __( 'Australia', 'newspack' ),
		'AT' => __( 'Austria', 'newspack' ),
		'AZ' => __( 'Azerbaijan', 'newspack' ),
		'BS' => __( 'Bahamas', 'newspack' ),
		'BH' => __( 'Bahrain', 'newspack' ),
		'BD' => __( 'Bangladesh', 'newspack' ),
		'BB' => __( 'Barbados', 'newspack' ),
		'BY' => __( 'Belarus', 'newspack' ),
		'BE' => __( 'Belgium', 'newspack' ),
		'PW' => __( 'Belau', 'newspack' ),
		'BZ' => __( 'Belize', 'newspack' ),
		'BJ' => __( 'Benin', 'newspack' ),
		'BM' => __( 'Bermuda', 'newspack' ),
		'BT' => __( 'Bhutan', 'newspack' ),
		'BO' => __( 'Bolivia', 'newspack' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'newspack' ),
		'BA' => __( 'Bosnia and Herzegovina', 'newspack' ),
		'BW' => __( 'Botswana', 'newspack' ),
		'BV' => __( 'Bouvet Island', 'newspack' ),
		'BR' => __( 'Brazil', 'newspack' ),
		'IO' => __( 'British Indian Ocean Territory', 'newspack' ),
		'BN' => __( 'Brunei', 'newspack' ),
		'BG' => __( 'Bulgaria', 'newspack' ),
		'BF' => __( 'Burkina Faso', 'newspack' ),
		'BI' => __( 'Burundi', 'newspack' ),
		'KH' => __( 'Cambodia', 'newspack' ),
		'CM' => __( 'Cameroon', 'newspack' ),
		'CA' => __( 'Canada', 'newspack' ),
		'CV' => __( 'Cape Verde', 'newspack' ),
		'KY' => __( 'Cayman Islands', 'newspack' ),
		'CF' => __( 'Central African Republic', 'newspack' ),
		'TD' => __( 'Chad', 'newspack' ),
		'CL' => __( 'Chile', 'newspack' ),
		'CN' => __( 'China', 'newspack' ),
		'CX' => __( 'Christmas Island', 'newspack' ),
		'CC' => __( 'Cocos (Keeling) Islands', 'newspack' ),
		'CO' => __( 'Colombia', 'newspack' ),
		'KM' => __( 'Comoros', 'newspack' ),
		'CG' => __( 'Congo (Brazzaville)', 'newspack' ),
		'CD' => __( 'Congo (Kinshasa)', 'newspack' ),
		'CK' => __( 'Cook Islands', 'newspack' ),
		'CR' => __( 'Costa Rica', 'newspack' ),
		'HR' => __( 'Croatia', 'newspack' ),
		'CU' => __( 'Cuba', 'newspack' ),
		'CW' => __( 'Cura&ccedil;ao', 'newspack' ),
		'CY' => __( 'Cyprus', 'newspack' ),
		'CZ' => __( 'Czech Republic', 'newspack' ),
		'DK' => __( 'Denmark', 'newspack' ),
		'DJ' => __( 'Djibouti', 'newspack' ),
		'DM' => __( 'Dominica', 'newspack' ),
		'DO' => __( 'Dominican Republic', 'newspack' ),
		'EC' => __( 'Ecuador', 'newspack' ),
		'EG' => __( 'Egypt', 'newspack' ),
		'SV' => __( 'El Salvador', 'newspack' ),
		'GQ' => __( 'Equatorial Guinea', 'newspack' ),
		'ER' => __( 'Eritrea', 'newspack' ),
		'EE' => __( 'Estonia', 'newspack' ),
		'ET' => __( 'Ethiopia', 'newspack' ),
		'FK' => __( 'Falkland Islands', 'newspack' ),
		'FO' => __( 'Faroe Islands', 'newspack' ),
		'FJ' => __( 'Fiji', 'newspack' ),
		'FI' => __( 'Finland', 'newspack' ),
		'FR' => __( 'France', 'newspack' ),
		'GF' => __( 'French Guiana', 'newspack' ),
		'PF' => __( 'French Polynesia', 'newspack' ),
		'TF' => __( 'French Southern Territories', 'newspack' ),
		'GA' => __( 'Gabon', 'newspack' ),
		'GM' => __( 'Gambia', 'newspack' ),
		'GE' => __( 'Georgia', 'newspack' ),
		'DE' => __( 'Germany', 'newspack' ),
		'GH' => __( 'Ghana', 'newspack' ),
		'GI' => __( 'Gibraltar', 'newspack' ),
		'GR' => __( 'Greece', 'newspack' ),
		'GL' => __( 'Greenland', 'newspack' ),
		'GD' => __( 'Grenada', 'newspack' ),
		'GP' => __( 'Guadeloupe', 'newspack' ),
		'GU' => __( 'Guam', 'newspack' ),
		'GT' => __( 'Guatemala', 'newspack' ),
		'GG' => __( 'Guernsey', 'newspack' ),
		'GN' => __( 'Guinea', 'newspack' ),
		'GW' => __( 'Guinea-Bissau', 'newspack' ),
		'GY' => __( 'Guyana', 'newspack' ),
		'HT' => __( 'Haiti', 'newspack' ),
		'HM' => __( 'Heard Island and McDonald Islands', 'newspack' ),
		'HN' => __( 'Honduras', 'newspack' ),
		'HK' => __( 'Hong Kong', 'newspack' ),
		'HU' => __( 'Hungary', 'newspack' ),
		'IS' => __( 'Iceland', 'newspack' ),
		'IN' => __( 'India', 'newspack' ),
		'ID' => __( 'Indonesia', 'newspack' ),
		'IR' => __( 'Iran', 'newspack' ),
		'IQ' => __( 'Iraq', 'newspack' ),
		'IE' => __( 'Ireland', 'newspack' ),
		'IM' => __( 'Isle of Man', 'newspack' ),
		'IL' => __( 'Israel', 'newspack' ),
		'IT' => __( 'Italy', 'newspack' ),
		'CI' => __( 'Ivory Coast', 'newspack' ),
		'JM' => __( 'Jamaica', 'newspack' ),
		'JP' => __( 'Japan', 'newspack' ),
		'JE' => __( 'Jersey', 'newspack' ),
		'JO' => __( 'Jordan', 'newspack' ),
		'KZ' => __( 'Kazakhstan', 'newspack' ),
		'KE' => __( 'Kenya', 'newspack' ),
		'KI' => __( 'Kiribati', 'newspack' ),
		'KW' => __( 'Kuwait', 'newspack' ),
		'KG' => __( 'Kyrgyzstan', 'newspack' ),
		'LA' => __( 'Laos', 'newspack' ),
		'LV' => __( 'Latvia', 'newspack' ),
		'LB' => __( 'Lebanon', 'newspack' ),
		'LS' => __( 'Lesotho', 'newspack' ),
		'LR' => __( 'Liberia', 'newspack' ),
		'LY' => __( 'Libya', 'newspack' ),
		'LI' => __( 'Liechtenstein', 'newspack' ),
		'LT' => __( 'Lithuania', 'newspack' ),
		'LU' => __( 'Luxembourg', 'newspack' ),
		'MO' => __( 'Macao S.A.R., China', 'newspack' ),
		'MK' => __( 'North Macedonia', 'newspack' ),
		'MG' => __( 'Madagascar', 'newspack' ),
		'MW' => __( 'Malawi', 'newspack' ),
		'MY' => __( 'Malaysia', 'newspack' ),
		'MV' => __( 'Maldives', 'newspack' ),
		'ML' => __( 'Mali', 'newspack' ),
		'MT' => __( 'Malta', 'newspack' ),
		'MH' => __( 'Marshall Islands', 'newspack' ),
		'MQ' => __( 'Martinique', 'newspack' ),
		'MR' => __( 'Mauritania', 'newspack' ),
		'MU' => __( 'Mauritius', 'newspack' ),
		'YT' => __( 'Mayotte', 'newspack' ),
		'MX' => __( 'Mexico', 'newspack' ),
		'FM' => __( 'Micronesia', 'newspack' ),
		'MD' => __( 'Moldova', 'newspack' ),
		'MC' => __( 'Monaco', 'newspack' ),
		'MN' => __( 'Mongolia', 'newspack' ),
		'ME' => __( 'Montenegro', 'newspack' ),
		'MS' => __( 'Montserrat', 'newspack' ),
		'MA' => __( 'Morocco', 'newspack' ),
		'MZ' => __( 'Mozambique', 'newspack' ),
		'MM' => __( 'Myanmar', 'newspack' ),
		'NA' => __( 'Namibia', 'newspack' ),
		'NR' => __( 'Nauru', 'newspack' ),
		'NP' => __( 'Nepal', 'newspack' ),
		'NL' => __( 'Netherlands', 'newspack' ),
		'NC' => __( 'New Caledonia', 'newspack' ),
		'NZ' => __( 'New Zealand', 'newspack' ),
		'NI' => __( 'Nicaragua', 'newspack' ),
		'NE' => __( 'Niger', 'newspack' ),
		'NG' => __( 'Nigeria', 'newspack' ),
		'NU' => __( 'Niue', 'newspack' ),
		'NF' => __( 'Norfolk Island', 'newspack' ),
		'MP' => __( 'Northern Mariana Islands', 'newspack' ),
		'KP' => __( 'North Korea', 'newspack' ),
		'NO' => __( 'Norway', 'newspack' ),
		'OM' => __( 'Oman', 'newspack' ),
		'PK' => __( 'Pakistan', 'newspack' ),
		'PS' => __( 'Palestinian Territory', 'newspack' ),
		'PA' => __( 'Panama', 'newspack' ),
		'PG' => __( 'Papua New Guinea', 'newspack' ),
		'PY' => __( 'Paraguay', 'newspack' ),
		'PE' => __( 'Peru', 'newspack' ),
		'PH' => __( 'Philippines', 'newspack' ),
		'PN' => __( 'Pitcairn', 'newspack' ),
		'PL' => __( 'Poland', 'newspack' ),
		'PT' => __( 'Portugal', 'newspack' ),
		'PR' => __( 'Puerto Rico', 'newspack' ),
		'QA' => __( 'Qatar', 'newspack' ),
		'RE' => __( 'Reunion', 'newspack' ),
		'RO' => __( 'Romania', 'newspack' ),
		'RU' => __( 'Russia', 'newspack' ),
		'RW' => __( 'Rwanda', 'newspack' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'newspack' ),
		'SH' => __( 'Saint Helena', 'newspack' ),
		'KN' => __( 'Saint Kitts and Nevis', 'newspack' ),
		'LC' => __( 'Saint Lucia', 'newspack' ),
		'MF' => __( 'Saint Martin (French part)', 'newspack' ),
		'SX' => __( 'Saint Martin (Dutch part)', 'newspack' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'newspack' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'newspack' ),
		'SM' => __( 'San Marino', 'newspack' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'newspack' ),
		'SA' => __( 'Saudi Arabia', 'newspack' ),
		'SN' => __( 'Senegal', 'newspack' ),
		'RS' => __( 'Serbia', 'newspack' ),
		'SC' => __( 'Seychelles', 'newspack' ),
		'SL' => __( 'Sierra Leone', 'newspack' ),
		'SG' => __( 'Singapore', 'newspack' ),
		'SK' => __( 'Slovakia', 'newspack' ),
		'SI' => __( 'Slovenia', 'newspack' ),
		'SB' => __( 'Solomon Islands', 'newspack' ),
		'SO' => __( 'Somalia', 'newspack' ),
		'ZA' => __( 'South Africa', 'newspack' ),
		'GS' => __( 'South Georgia/Sandwich Islands', 'newspack' ),
		'KR' => __( 'South Korea', 'newspack' ),
		'SS' => __( 'South Sudan', 'newspack' ),
		'ES' => __( 'Spain', 'newspack' ),
		'LK' => __( 'Sri Lanka', 'newspack' ),
		'SD' => __( 'Sudan', 'newspack' ),
		'SR' => __( 'Suriname', 'newspack' ),
		'SJ' => __( 'Svalbard and Jan Mayen', 'newspack' ),
		'SZ' => __( 'Swaziland', 'newspack' ),
		'SE' => __( 'Sweden', 'newspack' ),
		'CH' => __( 'Switzerland', 'newspack' ),
		'SY' => __( 'Syria', 'newspack' ),
		'TW' => __( 'Taiwan', 'newspack' ),
		'TJ' => __( 'Tajikistan', 'newspack' ),
		'TZ' => __( 'Tanzania', 'newspack' ),
		'TH' => __( 'Thailand', 'newspack' ),
		'TL' => __( 'Timor-Leste', 'newspack' ),
		'TG' => __( 'Togo', 'newspack' ),
		'TK' => __( 'Tokelau', 'newspack' ),
		'TO' => __( 'Tonga', 'newspack' ),
		'TT' => __( 'Trinidad and Tobago', 'newspack' ),
		'TN' => __( 'Tunisia', 'newspack' ),
		'TR' => __( 'Turkey', 'newspack' ),
		'TM' => __( 'Turkmenistan', 'newspack' ),
		'TC' => __( 'Turks and Caicos Islands', 'newspack' ),
		'TV' => __( 'Tuvalu', 'newspack' ),
		'UG' => __( 'Uganda', 'newspack' ),
		'UA' => __( 'Ukraine', 'newspack' ),
		'AE' => __( 'United Arab Emirates', 'newspack' ),
		'GB' => __( 'United Kingdom (UK)', 'newspack' ),
		'US' => __( 'United States (US)', 'newspack' ),
		'UM' => __( 'United States (US) Minor Outlying Islands', 'newspack' ),
		'UY' => __( 'Uruguay', 'newspack' ),
		'UZ' => __( 'Uzbekistan', 'newspack' ),
		'VU' => __( 'Vanuatu', 'newspack' ),
		'VA' => __( 'Vatican', 'newspack' ),
		'VE' => __( 'Venezuela', 'newspack' ),
		'VN' => __( 'Vietnam', 'newspack' ),
		'VG' => __( 'Virgin Islands (British)', 'newspack' ),
		'VI' => __( 'Virgin Islands (US)', 'newspack' ),
		'WF' => __( 'Wallis and Futuna', 'newspack' ),
		'EH' => __( 'Western Sahara', 'newspack' ),
		'WS' => __( 'Samoa', 'newspack' ),
		'YE' => __( 'Yemen', 'newspack' ),
		'ZM' => __( 'Zambia', 'newspack' ),
		'ZW' => __( 'Zimbabwe', 'newspack' ),
	);
	return $countries;
}

/**
 * Convert an associative array into array of objects prepped for selectControl.
 *
 * @param array $arr Array to be converted.
 * @return array
 */
function newspack_select_prepare( $arr ) {
	$result = array();
	foreach ( $arr as $key => $value ) {
		$result[] = [
			'label' => html_entity_decode( $value ),
			'value' => $key,
		];
	}
	return $result;
}
