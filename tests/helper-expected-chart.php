<?php
$sec = chr(34);
// Expected longitudes for Steve Jobs
$this->expected_planets_longitude[] = array(

	0 =>  335.7481352,
	1 =>   7.7473318,
	2 =>  314.3617290,
	3 =>  291.1718709,
	4 =>  29.0904872,
	5 =>  110.5079439,
	6 =>  231.1626621,
	7 =>  114.1348946,
	8 =>  208.0512444,
	9 =>  145.3228415,
	10 =>  302.3322506,
	11 =>  238.5218775,
	12 =>  273.4098987,
	13 =>  172.2929065,
	14 =>  198.2462317,
	15 =>  228.3570045,
	16 =>  261.3176341,
	17 =>  294.4748123,
	18 =>  325.2096023,
	19 =>  352.2929065,
	20 =>  18.2462317,
	21 =>  48.3570045,
	22 =>  81.3176341,
	23 =>  114.4748123,
	24 =>  145.2096023,
	25 =>  172.2929065,
	26 =>  81.3176341,
	27 =>  80.5498363,
	28 =>  337.2441236
);

// Expected longitudes for Michael Jackson
$this->expected_planets_longitude[] = array(
	0 => 156.1435386,
	1 => 344.9068847,
	2 => 145.4145043,
	3 => 137.0706532,
	4 => 52.0368654,
	5 => 208.5379838,
	6 => 259.1249107,
	7 => 133.5047823,
	8 => 212.5772881,
	9 => 152.1683911,
	10 => 319.3030159,
	11 => 21.2325960,
	12 => 203.0844753,
	13 => 340.1094400,
	14 => 26.7290851,
	15 => 56.9998791,
	16 => 79.5219779,
	17 => 100.3027210,
	18 => 124.2701026,
	19 => 160.1094400,
	20 => 206.7290851,
	21 => 236.9998791,
	22 => 259.5219779,
	23 => 280.3027210,
	24 => 304.2701026,
	25 => 340.1094400,
	26 => 259.5219779,
	27 => 258.6030072,
	28 => 171.6569659
	);

// Expected cusps for Steve Jobs [0]
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

// Expected cusps for Michael Jackson
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
