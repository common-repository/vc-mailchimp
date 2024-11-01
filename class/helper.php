<?php

class VCMC_Helper {
	public static function subscribe( $data, $apiKey, $listId ) {
		$dataCenter = substr( $apiKey, strpos( $apiKey, '-' ) + 1 );
		$url        = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/';

		$data_json = json_encode( $data );
		$auth      = base64_encode( 'user:' . $apiKey );

		$response = wp_remote_post( $url, array(
				'timeout'   => 45,
				'headers'   => array( 'Content-Type: application/json', 'Authorization' => "Basic $auth" ),
				'body'      => $data_json,
				'sslverify' => false
			)
		);

		if ( ! is_wp_error( $response ) ) {
			if ( isset( $response['response']['code'] ) && ( $response['response']['code'] == 400 ) ) {
				$body = json_decode( $response['body'] );
				if ( isset( $body->title ) && ( $body->title == 'Invalid Resource' ) ) {
					// invalid resource
					return '21';
				} elseif ( isset( $body->title ) && ( $body->title == 'Member Exists' ) ) {
					// member exists
					return '22';
				} else {
					// other errors
					return '23';
				}
			} elseif ( isset( $response['response']['code'] ) && ( $response['response']['code'] == 200 ) ) {
				// successful
				return '1';
			} else {
				// have an error
				return '0';
			}
		} else {
			// have an error
			return '0';
		}
	}

	public static function get_lists( $apiKey ) {
		$dataCenter = substr( $apiKey, strpos( $apiKey, '-' ) + 1 );
		$url        = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists';

		$auth = base64_encode( 'user:' . $apiKey );

		$response = wp_remote_get( $url, array(
				'timeout'   => 45,
				'headers'   => array( 'Content-Type: application/json', 'Authorization' => "Basic $auth" ),
				'sslverify' => false
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$body = json_decode( $response['body'] );
			if ( isset( $body->lists ) && count( $body->lists ) > 0 ) {
				$result_arr = array();
				$result_str = '<select class="vcmc-lists-select"><option value="">Please choose a list</option>';
				foreach ( $body->lists as $item ) {
					$result_str .= '<option value="' . $item->id . '">' . $item->name . '</option>';
					$result_arr[ $item->id ] = $item->name;
				}
				$key_option = self::generate_key( 'vcmc_lists_', $apiKey );
				update_option( $key_option, $result_arr );
				$result_str .= '</select>';
			} else {
				$result_str = 'Have no list in this account, please login to MailChimp and create a list first.';
			}
		} else {
			$result_str = 'Have an error with this API key, please check again!';
		}

		return $result_str;
	}

	public static function generate_key( $prefix = 'vcmc_', $key = '' ) {
		$new_key = '';
		$key_arr = explode( '-', $key );
		if ( strlen( $key_arr[0] ) > 10 ) {
			$new_key = $prefix . substr( $key_arr[0], 0, 10 );
		}

		return $new_key;
	}

}
