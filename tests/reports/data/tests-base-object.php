<?php
namespace EDD\Reports\Data;

if ( ! class_exists( '\EDD\Reports\Reports_Init' ) ) {
	require_once( EDD_PLUGIN_DIR . 'includes/reports/class-reports-init.php' );
}

/**
 * Tests for the Endpoint object.
 *
 * @group edd_reports
 * @group edd_reports_endpoints
 * @group edd_objects
 */
class Base_Object_Tests extends \EDD_UnitTestCase {

	/**
	 * Reports fixture.
	 *
	 * @var \EDD\Reports\Reports_init
	 */
	protected static $reports;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$reports = new \EDD\Reports\Reports_init();
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::get_id()
	 */
	public function test_get_id_when_created_without_an_id_should_return_null() {
		$object = $this->mock_Base_Object( array(
			'label' => 'Foo'
		) );

		$this->assertNull( $object->get_id() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::get_id()
	 * @covers \EDD\Reports\Data\Base_Object::set_id()
	 */
	public function test_get_id_when_created_with_an_id_should_return_that_id() {
		$object = $this->mock_Base_Object( array(
			'id'    => 'foo',
			'label' => 'Foo',
		) );

		$this->assertSame( 'foo', $object->get_id() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::get_label()
	 */
	public function test_get_label_when_created_without_a_label_should_return_null() {
		$object = $this->mock_Base_Object( array(
			'id' => 'foo',
		) );

		$this->assertNull( $object->get_label() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::get_label()
	 * @covers \EDD\Reports\Data\Base_Object::set_label()
	 */
	public function test_get_label_when_created_with_a_label_should_return_that_label() {
		$object = $this->mock_Base_Object( array(
			'id'    => 'foo',
			'label' => 'Foo',
		) );

		$this->assertSame( 'Foo', $object->get_label() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::set_props()
	 * @group edd_errors
	 */
	public function test_set_props_with_missing_id_should_flag_WP_Error() {
		$object = $this->mock_Base_Object( array(
			'label' => 'Foo',
			'views' => array(
				'tile' => array(
					'display_args'     => array( 'something' ),
					'display_callback' => '__return_false',
					'data_callback'    => '__return_false',
				),
			),
		) );

		$this->assertTrue( $object->has_errors() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::set_props()
	 * @group edd_errors
	 */
	public function test_set_props_with_missing_id_should_flag_WP_Error_including_code_missing_object_id() {
		$object = $this->mock_Base_Object( array(
			'label' => 'Foo',
		) );

		$this->assertContains( 'missing_object_id', $object->get_errors()->get_error_codes() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::set_props()
	 * @group edd_errors
	 */
	public function test_set_props_with_missing_object_label_should_flag_WP_Error() {
		$object = $this->mock_Base_Object( array(
			'id'    => 'foo',
		) );

		$this->assertTrue( $object->has_errors() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::set_props()
	 * @group edd_errors
	 */
	public function test_set_props_with_missing_object_label_should_flag_WP_Error_including_code_missing_object_label() {
		$object = $this->mock_Base_Object( array(
			'id'    => 'foo',
		) );

		$this->assertContains( 'missing_object_label', $object->get_errors()->get_error_codes() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::has_errors()
	 */
	public function test_has_errors_if_no_errors_should_return_false() {
		// Add a completely valid endpoint.
		$object = $this->mock_Base_Object( array(
			'id'    => 'foo',
			'label' => 'Foo',
		) );

		$this->assertFalse( $object->has_errors() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::has_errors()
	 */
	public function test_has_errors_if_errors_should_return_true() {
		$object = $this->mock_Base_Object( array( 'bar' ) );

		$this->assertTrue( $object->has_errors() );
	}

	/**
	 * @covers \EDD\Reports\Data\Base_Object::get_errors()
	 */
	public function test_get_errors_should_return_WP_Error_object() {
		$object = $this->mock_Base_Object( array( 'bar' ) );

		$this->assertWPError( $object->get_errors() );
	}

	/**
	 * Mocks a copy of the Base_Object abstract class.
	 *
	 * @param array $args
	 * @return \EDD\Reports\Data\Base_Object Mocked Base_Object instance.
	 */
	protected function mock_Base_Object( $args ) {
		return $this->getMockForAbstractClass( '\EDD\Reports\Data\Base_Object', array( $args ) );
	}

}
