<?php
$sec = chr(34);
// Expected planets in signs for Steve Jobs
$expected_planets_in_signs[] = array(
	0 => 'asc_virgo',
	1 => 'sun_pisces',
	2 => 'moon_aries',
	3 => 'mercury_aquarius',
	4 => 'venus_capricorn',
	5 => 'mars_aries',
	6 => 'jupiter_cancer',
	7 => 'saturn_scorpio',
	8 => 'uranus_cancer',
	9 => 'neptune_libra',
	10 => 'pluto_leo',
	11 => 'chiron_aquarius',
	12 => 'lilith_scorpio',
	13 => 'nn_capricorn',
	14 => 'pof_leo',
	15 => 'vertex_pisces',
	16 => 'mc_gemini',
);

// Expected planets in signs for Michael Jackson
$expected_planets_in_signs[] = array(
	0 => 'asc_pisces',
	1 => 'sun_virgo',
	2 => 'moon_pisces',
	3 => 'mercury_leo',
	4 => 'venus_leo',
	5 => 'mars_taurus',
	6 => 'jupiter_libra',
	7 => 'saturn_sagittarius',
	8 => 'uranus_leo',
	9 => 'neptune_scorpio',
	10 => 'pluto_virgo',
	11 => 'chiron_aquarius',
	12 => 'lilith_aries',
	13 => 'nn_libra',
	14 => 'pof_virgo',
	15 => 'vertex_virgo',
	16 => 'mc_sagittarius',
);

// Expected zodiacal degrees, minutes, seconds of planets for Steve Jobs [0]
$expected_zodiacal_dms[] = array(
	0 => '22&#176; <span class="zp-icon-virgo"> </span> 17\' 34' . $sec,// astro gets 39
	1 => '&#160;5&#176; <span class="zp-icon-pisces"> </span> 44\' 53' . $sec,
	2 => '&#160;7&#176; <span class="zp-icon-aries"> </span> 44\' 50' . $sec,
	3 => '14&#176; <span class="zp-icon-aquarius"> </span> 21\' 42' . $sec . '&nbsp; R<sub>x</sub> ',
	4 => '21&#176; <span class="zp-icon-capricorn"> </span> 10\' 19' . $sec,
	5 => '29&#176; <span class="zp-icon-aries"> </span> 05\' 26' . $sec,
	6 => '20&#176; <span class="zp-icon-cancer"> </span> 30\' 29' . $sec . '&nbsp; R<sub>x</sub> ',
	7 => '21&#176; <span class="zp-icon-scorpio"> </span> 09\' 46' . $sec,
	8 => '24&#176; <span class="zp-icon-cancer"> </span> 08\' 06' . $sec . '&nbsp; R<sub>x</sub> ',
	9 => '28&#176; <span class="zp-icon-libra"> </span> 03\' 04' . $sec . '&nbsp; R<sub>x</sub> ',
	10 => '25&#176; <span class="zp-icon-leo"> </span> 19\' 22' . $sec . '&nbsp; R<sub>x</sub> ',
	11 => '&#160;2&#176; <span class="zp-icon-aquarius"> </span> 19\' 56' . $sec,
	12 => '28&#176; <span class="zp-icon-scorpio"> </span> 31\' 19' . $sec,
	13 => '&#160;3&#176; <span class="zp-icon-capricorn"> </span> 24\' 36' . $sec . '&nbsp; R<sub>x</sub> ',
	14 => '20&#176; <span class="zp-icon-leo"> </span> 17\' 37' . $sec,
	15 => '&#160;7&#176; <span class="zp-icon-pisces"> </span> 14\' 39' . $sec,// astro gets 15'37"
	16 => '21&#176; <span class="zp-icon-gemini"> </span> 19\' 03' . $sec,
);

// Expected zodiacal degrees, minutes, seconds of planets for Michael Jackson
$expected_zodiacal_dms[] = array(
	0 => '10&#176; <span class="zp-icon-pisces"> </span> 06\' 34' . $sec,
	1 => '&#160;6&#176; <span class="zp-icon-virgo"> </span> 08\' 37' . $sec,
	2 => '14&#176; <span class="zp-icon-pisces"> </span> 54\' 25' . $sec,
	3 => '25&#176; <span class="zp-icon-leo"> </span> 24\' 52' . $sec . '&nbsp; R<sub>x</sub> ',
	4 => '17&#176; <span class="zp-icon-leo"> </span> 04\' 14' . $sec,
	5 => '22&#176; <span class="zp-icon-taurus"> </span> 02\' 13' . $sec,
	6 => '28&#176; <span class="zp-icon-libra"> </span> 32\' 17' . $sec,
	7 => '19&#176; <span class="zp-icon-sagittarius"> </span> 07\' 30' . $sec,
	8 => '13&#176; <span class="zp-icon-leo"> </span> 30\' 17' . $sec,
	9 => '&#160;2&#176; <span class="zp-icon-scorpio"> </span> 34\' 38' . $sec,
	10 => '&#160;2&#176; <span class="zp-icon-virgo"> </span> 10\' 06' . $sec,
	11 => '19&#176; <span class="zp-icon-aquarius"> </span> 18\' 11' . $sec . '&nbsp; R<sub>x</sub> ',
	12 => '21&#176; <span class="zp-icon-aries"> </span> 13\' 57' . $sec,
	13 => '23&#176; <span class="zp-icon-libra"> </span> 05\' 04' . $sec . '&nbsp; R<sub>x</sub> ',
	14 => '&#160;1&#176; <span class="zp-icon-virgo"> </span> 20\' 46' . $sec,
	15 => '21&#176; <span class="zp-icon-virgo"> </span> 39\' 25' . $sec,
	16 => '19&#176; <span class="zp-icon-sagittarius"> </span> 31\' 19' . $sec,
);
