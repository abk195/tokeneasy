<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserIdentity extends Model
{
	protected $fillable = [
		'user_id',
		'first_name',
		'last_name',
		'dob',
		'citizenship',
		'residence',
		'ssn_tax_id',
		'document',
		'photo',
		'primary_phone',
		'primary_country_code',
		'secondary_phone',
		'secondary_country_code',
		'address_line_1',
		'address_line_2',
		'country_code',
		'city_id',
		'province',
		'postal_code'
	];


	public function country()
	{
		return $this->hasOne('App\Country', 'code', 'country_code');
	}

	public function city()
	{
		return $this->hasOne('App\City', 'country_code', 'country_code');
	}

	/**
	 * Two profile forms share this record, and store the phone differently:
	 *
	 *  - the investor profile saves primary_phone as bare digits, the dial code in
	 *    primary_country_code, and the ISO country (e.g. "AE") in country_code;
	 *  - the issuer profile packs "<dial code>-<number>" into primary_phone and
	 *    leaves country_code empty, reading both halves back through the
	 *    accessors below.
	 *
	 * The accessors used to parse primary_phone unconditionally. That replaced the
	 * investor's saved country with their phone number wherever country_code was
	 * read — so the profile never pre-selected the country, and re-saving it put
	 * the investor in whichever country sorted first — and phone threw on any
	 * number without a dash.
	 */
	public function getPhoneAttribute()
	{
		$phone = (string) $this->primary_phone;

		return strpos($phone, '-') !== false ? explode('-', $phone, 2)[1] : $phone;
	}

	public function getCountryCodeAttribute($value)
	{
		if ($value !== null && $value !== '') {
			return $value;
		}

		$phone = (string) $this->primary_phone;

		return strpos($phone, '-') !== false ? explode('-', $phone, 2)[0] : null;
	}
}
