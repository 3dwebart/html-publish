<?php
/**
* Description of BaseDAO
* @author admin@bugnote.net
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
* 원본 소스 출처 http://blog.naver.com/PostView.nhn?blogId=ehomebuild&logNo=150079256210
*/

class StringChecker {
    
        //@ 문자를 체크(Ascii 문자 코드를 활용) 한다
	public $str;
	public $len = 0;
	
	public function init($s){
            if(!empty($s)){
                $this->str = trim($s);
                $this->len = strlen($s);
            }
	}
        
        public function isAlphabetUnderbar(){
            if (preg_match("/[^0-9a-z_]+/i", $this->str))
                return false;
            else
                return true;
        }
	
	// null 값인지 체크한다 [ 널값이면 : true / 아니면 : false ]
	public function isNull(){
            $result = false;
            $asciiNumber = Ord($this->str);
            if(empty($asciiNumber)) return true;
            return $result;
	}

	// 문자와 문자사이 공백이 있는지 체크 [ 공백 있으면 : true / 없으면 : false ]
	public function isSpace(){
            $result = false;
            $str_split	= split("[[:space:]]+",$this->str);
            $count = count($str_split);	
            for($i=0; $i<$count; $i++){
                    if($i>0){
                            $result = true;
                            break;
                    }
            }
            return $result;
	}
	
	// 연속적으로 똑같은 문자는 입력할 수 없다  [ 반복문자 max 이상이면 : true / 아니면 : false ]
	// ex : 010-111-1111,010-222-1111 형태제한
	// max = 3; // 반복문자 3개 "초과" 입력제한
	public function isSameRepeatString($max=3){
            $result = false;
            $sameCount = 0;
            $preAsciiNumber = 0;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if( ($preAsciiNumber == $asciiNumber) && ($preAsciiNumber>0) )
                            $sameCount += 1;
                    else
                            $preAsciiNumber = $asciiNumber;

                    if($sameCount==$max){
                            $result = true;
                            break;
                    }
            }		
            return $result;
	}
	
	// 숫자인지 체크 [ 숫자면 : true / 아니면 : false ]
	// Ascii table = 48 ~ 57
	public function isNumber(){
            $result = true;
            for($i=0; $i<$this->len; $i++){
                $asciiNumber = Ord($this->str[$i]);
                if($asciiNumber<47 || $asciiNumber>57){
                        $result = false;
                        break;
                }
            }
            return $result;
	}

	// 영문인지 체크 [ 영문이면 : true / 아니면 : false ]
	// Ascii table = 대문자[75~90], 소문자[97~122]
	public function isAlphabet(){
            $result = true;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if(($asciiNumber>64 && $asciiNumber<91) || ($asciiNumber>96 && $asciiNumber<123)){}
                    else{ $result = false; }
            }
            return $result;
	}
        
	// 영문이 대문자 인지체크 [ 대문자이면 : true / 아니면 : false ]
	// Ascii table = 대문자[75~90]
	public function isUpAlphabet(){
            $result = true;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if($asciiNumber<65 || $asciiNumber>90){
                            $result = false;
                            break;
                    }
            }
            return $result;
	}

	// 영문이 소문자 인지체크 [ 소문자면 : true / 아니면 : false ]
	// Ascii table = 소문자[97~122]
	public function isLowAlphabet(){
            $result = true;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if($asciiNumber<97 || $asciiNumber>122){
                            $result = false;
                            break;
                    }
            }
            return $result;
	}
	
	// 한글인지 체크한다 [ 한글이면 : true / 아니면 : false ]
	// Ascii table = 128 > 
	public function isKorean(){
            $result = true;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if($asciiNumber<128){
                            $result = false;
                            break;
                    }
            }
            return $result;
	}
	
	// 특수문자 입력여부 체크 [ 특수문자 찾으면 : true / 못찾으면 : false ]
	// allow = "-,_"; 허용시킬 
	// space 공백은 자동 제외
	public function isEtcString($allow){
            // 허용된 특수문자 키
            $allowArgs = array();
            $tmpArgs = (!empty($allow)) ? explode(',',$allow) : '';
            if(is_array($tmpArgs)){
                    foreach($tmpArgs as $k => $v){
                            $knumber = Ord($v);
                            $allowArgs['s'.$knumber] = $v;
                    }
            }

            $result = false;
            for($i=0; $i<$this->len; $i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if(array_key_exists('s'.$asciiNumber, $allowArgs) === false){
                            if( ($asciiNumber<48) && ($asciiNumber != 32) ){ $result = true; break; }
                            else if($asciiNumber>57 && $asciiNumber<65){ $result = true; break; }
                            else if($asciiNumber>90 && $asciiNumber<97){ $result = true; break; }
                            else if($asciiNumber>122 && $asciiNumber<128){ $result = true; break; }
                    }
            }
            return $result;
	}
	
	// 첫번째 문자가 영문인지 체크한다[ 찾으면 : true / 못찾으면 : false ]
	public function isFirstAlphabet(){
            $result = true;
            $asciiNumber = Ord($this->str[0]);
            if(($asciiNumber>64 && $asciiNumber<91) || ($asciiNumber>96 && $asciiNumber<123)){}
            else{ $result = false; }
            return $result;
	}
	
	// 문자길이 체크 한글/영문/숫자/특수문자/공백 전부포함
	// min : 최소길이 / max : 최대길이
	public function isStringLength($min,$max){
            $strCount = 0;
            for($i=0;$i<$this->len;$i++){
                    $asciiNumber = Ord($this->str[$i]);
                    if($asciiNumber<=127 && $asciiNumber>=0){ $strCount++; } 
                    else if($asciiNumber<=223 && $asciiNumber>=194){ $strCount++; $i+1; }
                    else if($asciiNumber<=239 && $asciiNumber>=224){ $strCount++; $i+2; }
                    else if($asciiNumber<=244 && $asciiNumber>=240){ $strCount++; $i+3; }
            }

            if($strCount<$min) return false;
            else if($strCount>$max) return false;
            else return true;
	}
	
	// 두 문자가 서로 같은지 비교
	public function equals($s){
            $result = true;
            if(is_string($eStr)){ // 문자인지 체크
                    if(strcmp($this->str, $s)) $result= false;
            }else{
                    if($this->str != $s ) $result = false;
            }
            return $result;
	}

}

?>
