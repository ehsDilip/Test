<?php
// Password Generator Class

if(!is_object($pwdGen) || !isset($pwdGen)){
	$pwdGen = new Password;
}

class Password{

	/**
	 * The capitalized alphabetic characters
	 *
	 * @var array
	 */
	protected $uppercase_chars;

	/**
	 * The small alphabetic characters
	 *
	 * @var array
	 */
	protected $lowercase_chars;

	/**
	 * The numeric characters
	 *
	 * @var array
	 */
	protected $number_chars;

	/**
	 * The special characters
	 *
	 * @var array
	 */
	protected $special_chars;

	/**
	 * The extras special characters
	 *
	 * @var array
	 */
	protected $extra_chars;

	/**
	 * The List of all characters which used to generrate password
	 *
	 * @var array
	 */
	protected $chars = array();

	/**
	 * The length of password
	 *
	 * @var array
	 */
	var $length;

	/**
	 * Whether password using capitalized alphabetic characters
	 *
	 * @var boolean
	 */
	var $uppercase;

	/**
	 * Whether password using small alphabetic characters
	 *
	 * @var boolean
	 */
	var $lowercase;

	/**
	 * Whether password using numeric characters
	 *
	 * @var boolean
	 */
	var $number;

	/**
	 * Whether password using special characters
	 *
	 * @var boolean
	 */
	var $special;

	/**
	 * Whether password using extras special characters
	 *
	 * @var boolean
	 */
	var $extra;

	/**
	 * Initialize password config
	 *
	 * @param int $length
	 */
	function Password($length = 8){
		$this->length = $length;
		
		$this->configure(true, true, true, true, false);
	}

	/**
	 * Initialize 
	 */
	function configure($uppercase = false, $lowercase = false, $number = false, $special = false, $extra = false){
		$this->chars = array();

		$this->uppercase = $uppercase;
		$this->lowercase = $lowercase;
		$this->number = $number;
		$this->special = $special;
		$this->extra = $extra;

		$this->upper_chars = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		$this->lower_chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
		$this->number_chars = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
		$this->special_chars = array("!", "@", "#", "$", "%", "^", "&", "*");
		$this->extra_chars = array("[", "]", "{", "}", "-", "_", "+", "=", "<", ">", "?", "/", "`", "~", "|", ",", ".", ";", ":");
        
		if($this->uppercase === true){
			$this->chars = array_merge($this->chars, $this->upper_chars);
		}
		if($this->lowercase === true){
			$this->chars = array_merge($this->chars, $this->lower_chars);
		}
		if($this->number === true){
			$this->chars = array_merge($this->number_chars, $this->chars);
		}
		if($this->special === true){
			$this->chars = array_merge($this->chars, $this->special_chars);
		}
		if($this->extra === true){
			$this->chars = array_merge($this->chars, $this->extra_chars);
		}

		$this->chars = array_unique($this->chars);
	}
	
	/**
	 * Generates a random password from the defined set of characters.
	 *
	 * @return string
	 **/
	function generate(){
		if(empty($this->chars)){
			return false;
		}

		$hash = "";
		for ($i = 0; $i < $this->length; $i++) {
			$hash .= $this->chars[$this->random(0, count($this->chars) - 1)];
		}

		return $hash;
	}

	/**
	 * Generates a random number
	 *
	 * @return int
	 */
	function random($min = 0, $max = 0) {
		$max_random = 4294967295;

		$random = uniqid(microtime() . mt_rand(), true);
		$random = md5($random);
		$random = sha1($random);

		$value = substr($random, 0, 8);

		$value = abs(hexdec($value));

		if ($max != 0){
			$value = $min + ($max - $min + 1) * $value / ($max_random + 1);
		}

		return abs(intval($value));
	}
}
