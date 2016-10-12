<?php
$sec = chr(34);
// Expected Placidus cusps for Steve Jobs [0]
$this->expected_cusps[] = array(
	1 => '22&#176; <span class="zp-icon-virgo"> </span> 17\' 34' . $sec,// astro.com gets 39
	2 => '18&#176; <span class="zp-icon-libra"> </span> 14\' 46' . $sec,// astro.com gets 39
	3 => '18&#176; <span class="zp-icon-scorpio"> </span> 21\' 25' . $sec,// astro.com gets 16
	4 => '21&#176; <span class="zp-icon-sagittarius"> </span> 19\' 03' . $sec,
	5 => '24&#176; <span class="zp-icon-capricorn"> </span> 28\' 29' . $sec,
	6 => '25&#176; <span class="zp-icon-aquarius"> </span> 12\' 35' . $sec,
	7 => '22&#176; <span class="zp-icon-pisces"> </span> 17\' 34' . $sec,
	8 => '18&#176; <span class="zp-icon-aries"> </span> 14\' 46' . $sec,
	9 => '18&#176; <span class="zp-icon-taurus"> </span> 21\' 25' . $sec,
	10 => '21&#176; <span class="zp-icon-gemini"> </span> 19\' 03' . $sec,
	11 => '24&#176; <span class="zp-icon-cancer"> </span> 28\' 29' . $sec,
	12 => '25&#176; <span class="zp-icon-leo"> </span> 12\' 35' . $sec,
);

// Expected Placidus cusps for Michael Jackson
$this->expected_cusps[] = array(
	1 => '10&#176; <span class="zp-icon-pisces"> </span> 06\' 34' . $sec,
	2 => '26&#176; <span class="zp-icon-aries"> </span> 43\' 45' . $sec,
	3 => '27&#176; <span class="zp-icon-taurus"> </span> 00\' 00' . $sec,
	4 => '19&#176; <span class="zp-icon-gemini"> </span> 31\' 19' . $sec,
	5 => '10&#176; <span class="zp-icon-cancer"> </span> 18\' 10' . $sec,
	6 => '&#160;4&#176; <span class="zp-icon-leo"> </span> 16\' 12' . $sec,
	7 => '10&#176; <span class="zp-icon-virgo"> </span> 06\' 34' . $sec,
	8 => '26&#176; <span class="zp-icon-libra"> </span> 43\' 45' . $sec,
	9 => '27&#176; <span class="zp-icon-scorpio"> </span> 00\' 00' . $sec,
	10 => '19&#176; <span class="zp-icon-sagittarius"> </span> 31\' 19' . $sec,
	11 => '10&#176; <span class="zp-icon-capricorn"> </span> 18\' 10' . $sec,
	12 => '&#160;4&#176; <span class="zp-icon-aquarius"> </span> 16\' 12' . $sec,
);

// Expected house positions for Steve Jobs [0]
$this->expected_h_pos[] = array(
		'0' => 6,
		'1' => 7,
		'2' => 5,
		'3' => 4,
		'4' => 8,
		'5' => 10,
		'6' => 3,
		'7' => 10,
		'8' => 2,
		'9' => 12,
		'10' => 5,
		'11' => 3,
		'12' => 4,
		'13' => 11,
		'14' => 6
	);

// Expected house positions for Michael Jackson
$this->expected_h_pos[] = array(
		'0' => 6,
		'1' => 1,
		'2' => 6,
		'3' => 6,
		'4' => 2,
		'5' => 8,
		'6' => 9,
		'7' => 6,
		'8' => 8,
		'9' => 6,
		'10' => 12,
		'11' => 1,
		'12' => 7,
		'13' => 6,
		'14' => 7
	);

// Expected conjunctions for Steve Jobs [0]
$this->expected_conjunctions[] = array(
	0 => '',
	1 => '',
	2 => '',
	3 => '',
	4 => '',
	5 => '',
	6 => '',
	7 => 1,
	8 => '',
	9 => '',
	10 => '',
	11 => '',
	12 => '',
	13 => '',
	14 => ''
);
// Expected conjunctions for Michael Jackson
$this->expected_conjunctions[] = array(
	0 => '',
	1 => '',
	2 => '',
	3 => '',
	4 => '',
	5 => '',
	6 => 1,
	7 => '',
	8 => '',
	9 => '',
	10 => '',
	11 => '',
	12 => '',
	13 => '',
	14 => ''
);
