<?php
class Jwt extends Model {
	private $secret;

	public function __construct() {
		parent::__construct();
		$this->secret = "gfQ1usSLnnoUt5hSt7oO!";
	}


	public function create() {

		$date = new DateTime();
		$exp = $date->getTimestamp()+60;
		$iat = $date->getTimestamp();

		$header = json_encode(array("typ"=>"JWT", "alg"=>"HS256"));

		$payload = json_encode(array("exp"=>$exp,"iat"=>$iat));

		$hbase = $this->base64url_encode($header);
		$pbase = $this->base64url_encode($payload);

		$signature = hash_hmac("sha256", $hbase.".".$pbase, $this->secret, true);
		$bsig = $this->base64url_encode($signature);

		$jwt = $hbase.".".$pbase.".".$bsig;

		return $jwt;
	}

	public function validate($token) {
		// Passo 1: Verificar se o TOKEN tem 3 partes.
		// Passo 2: Bater a assinatura com os dados
		$array = array();
	
		$jwt_split = explode('.', $token);

		if(count($jwt_split) == 3) {
			$signature = hash_hmac("sha256", $jwt_split[0].".".$jwt_split[1], $this->secret, true);
			$bsig = $this->base64url_encode($signature);

			if($bsig == $jwt_split[2]) {

				$array = json_decode($this->base64url_decode($jwt_split[1]));
				$date = new DateTime();
				$atual = $date->getTimestamp();
				if($atual > $array->exp){
					return false;

				}else{
					return $array;
				}
				

			} else {
				return false;
			}

		} else {
			return false;

		}

	}

	private function base64url_encode( $data ){
	  return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
	}

	private function base64url_decode( $data ){
	  return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
	}

}