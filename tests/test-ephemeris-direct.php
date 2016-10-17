<?php
/**
 * Interect directly with the ephemeris, not via the Ephemeris class.
 */
class Test_Ephemeris_Direct extends WP_UnitTestCase {

	// path to ephemeris
	protected $sweph;

	public function setUp() {
	
		// Set up Swiss Ephemeris path
		$this->sweph = ZODIACPRESS_PATH . 'sweph';
		$PATH = '';
		putenv( "PATH=$PATH:{$this->sweph}" );
		
	}
	/**
	 * Test ephemeris queries
	 */
	public function test_query_ephemeris_regular() {

		/*
		 help commands:
        -?, -h  display whole info
        -hcmd   display commands
        -hplan  display planet numbers
        -hform  display format characters
        -hdate  display input date format
        -hexamp  display examples
        -glp  report file location of library
        */

		// Regular Tropical report
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -ut03:15 -p0123456789DAt -house-122.41942,37.77493,P -eswe -fPlZ -g, -head", $out);


		// Sidereal report
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -ut03:15 -p0123456789DAt -house-122.41942,37.77493,P -eswe -fPlZ -sid5 -g, -head", $out);

		// Ayanamsa
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -ut03:15 -p -house-122.41942,37.77493,P -eswe -fPlZ -ay5 -g, -head", $out);


        // Regular with all points (-a)
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -ut03:15 -pa -house-122.41942,37.77493,P -eswe -fPlZ -g, -head", $out);


		// With header data
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -ut03:15 -pa -house-122.41942,37.77493,P -eswe -fPlZ -g", $out);
		
		// exec( "swetest -edir{$this->sweph} -b25.2.1955 -p -h -eswe -g", $out);

		// ZP_Helper::print_to_terminal( $out );
	}
}
