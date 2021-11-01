<?php
/*
 * date:2017-2-28
 * todo:码隆接口，分析图片，返回图片信息（如：衣服、鞋子、帽子、围巾等在图片中的方位）
 */
class POP_CostumeAnalyse{
	private $access_key_id="popop"; 						// require: 你的用户配置 access_key_id
	private $secret_key="s8ygcYsyiTKC0Ihcl1blVZpfntg";		// require: 你的用户配置 secret_key(秘钥)
	private $service_type="detect_cloth";					// require: 你的服务类型 可在 我的服务-测试服务 页面查看
	private $service_id="_0000025";							// require: 你的服务ID
	
	private $ssf_signature_len = 32;
	private $ssf_client_api = "https://api.productai.cn/";	//接口URL
	private $ssf_client_version = "1";
	
	
	public function popCostumeAnalyse($imgurl){
		$imgInfo = @getimagesize($imgurl);
		if(!$imgInfo){
			return false;
		}
		list($http_code, $http_resp) = $this->client_service_reps(
				"post",                 // 默认 的请求方法post
				$this->access_key_id,  
				$this->secret_key,      
				$this->service_type,    
				$this->service_id,      
				$imgurl // require: 图片的url
		);
		$a_http_resp = json_decode($http_resp , true);
		foreach( $a_http_resp['boxes_detected'] as $k => $results ) {
			if( preg_match( "/(boots|shoes|bag|pumps|slippers|sandals|fishhead|sneakers)/im" , $results[ 'type'] ) ) {
				unset( $a_http_resp['boxes_detected'][ $k ] );
				continue;
			}
			
			//转换坐标
			$a_http_resp['boxes_detected'][ $k ]['box'][0] = $imgInfo[0] * $results['box'][0]; 	//参数的 x
			$a_http_resp['boxes_detected'][ $k ]['box'][1] = $imgInfo[1] * $results['box'][1];	//参数的 y 
			$a_http_resp['boxes_detected'][ $k ]['box'][2] = $imgInfo[0] * $results['box'][2];	//参数的 w
			$a_http_resp['boxes_detected'][ $k ]['box'][3] = $imgInfo[1] * $results['box'][3];	//参数的 h
		}
		if( $a_http_resp['boxes_detected'] ) {
			$res['res'] = array_values( $a_http_resp['boxes_detected'] );
			$res['success'] = 1;
		} else {
			$res['success'] = 0;
		}
		return $res;
	}
	
	public function client_service_reps($request_method, $access_key_id, $secret_key, $service_type, $service_id, $image_url)
	{
		list($method, $api_url, $headers, $body) = $this->client_service_parser(
				$request_method,
				$access_key_id,
				$secret_key,
				$service_type,
				$service_id,
				$image_url
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->convert_headers($headers));
		curl_setopt($ch, CURLOPT_POST, true);
		@curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // required as of PHP 5.6.0
		$body = http_build_query($body);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		$http_resp = curl_exec($ch);
		$e1 = curl_error($ch);
		$e2 = curl_errno($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return array($http_code, $http_resp);
	}
	
	private function short_uuid($length){
		$str = null;
		$strPol = "abcdefghijklmnopqrstuvwxyz0123456789";
		$max = strlen($strPol)-1;
	
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}
	
		return $str;
	}
	
	private function get_millisecond() {
		list($s1, $s2) = explode(' ', microtime());
		return substr(sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000), 0, 10);
	}
	
	private function client_signature($headers, $form, $secret_key, $body) {
		$sort_value = array();
	
		foreach ($body as $key => $val) {
			$headers[$key] = $val;
		}
	
		ksort($headers);
		foreach ($headers as $key => $val) {
			if ($key != "x-ca-signature") {
				$merged = sprintf("%s=%s", trim($key), trim($val));
				array_push($sort_value, $merged);
			}
		}
	
		foreach ($body as $key => $val) {
			unset($headers[$key]);
		}
	
		$string_to_signature = join("&", $sort_value);
		$signature = hash_hmac('sha1', $string_to_signature, $secret_key, true);
		$signature = base64_encode($signature);
		return $signature;
	}
	
	private function client_service_parser($request_method, $access_key_id, $secret_key, $service_type, $service_id, $image_url) {
		$api_url = sprintf("%s/%s/%s/", $this->ssf_client_api, $service_type, $service_id);
	
		$timestamp = $this->get_millisecond();
		$headers = array(
				"x-ca-accesskeyid" => $access_key_id,
				"x-ca-version" => $this->ssf_client_version,
				"x-ca-timestamp" => $timestamp,
				"x-ca-signaturenonce" => $this->short_uuid($this->ssf_signature_len),
				"x-ca-signature" => "",
				"requestmethod" => $request_method
		);
		$body = array( "url" => $image_url );
		$signature = $this->client_signature($headers, $body, $secret_key, $body);
		$headers["x-ca-signature"] = $signature;
		return array($request_method, $api_url, $headers, $body);
	}
	
	private function convert_headers($headers) {
		$ret = array();
		foreach ($headers as $key => $val) {
			$ret[] = sprintf("%s:%s", $key, $val);
		}
		return $ret;
	}
}