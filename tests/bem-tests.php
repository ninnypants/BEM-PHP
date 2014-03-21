<?php
class BEM_Tests extends PHPUnit_Framework_Testcase {
	function tearDown() {
		BEM::$stacks = array();
	}

	function test_new_no_custom_name() {
		BEM::new_stack( 'root' );
		$this->assertEquals( array( 'root' => array( 'root' => array() ) ), BEM::$stacks );
	}

	function test_new_custom_name() {
		BEM::new_stack( 'custom', 'root' );
		$this->assertEquals( array( 'custom' => array( 'root' => array() ) ), BEM::$stacks );
	}

	function test_new_multiblock() {
		BEM::new_stack( array(
			'root',
			'child',
			'childs-child',
		) );
		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
			'childs-child' => array(),
		) ), BEM::$stacks );
	}

	function test_new_multiblock_custom_name() {
		BEM::new_stack( 'custom', array(
			'root',
			'child',
			'childs-child',
		) );
		$this->assertEquals( array( 'custom' => array(
			'root' => array(),
			'child' => array(),
			'childs-child' => array(),
		) ), BEM::$stacks );
	}

	function test_add_new_block() {
		BEM::new_stack( 'root' );
		BEM::add_block( 'root', 'child' );
		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
		) ), BEM::$stacks );
	}

	function test_add_new_multiblock() {
		BEM::new_stack( 'root' );
		BEM::add_block( 'root', array(
			'child',
			'childs-child',
		) );
		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
			'childs-child' => array(),
		) ), BEM::$stacks );
	}

	function test_remove_block() {
		BEM::new_stack( array(
			'root',
			'child',
			'childs-child',
		) );

		BEM::remove_block( 'root' );
		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
		) ), BEM::$stacks );
	}

	function test_remove_to() {
		BEM::new_stack( array(
			'root',
			'child',
			'childs-child',
			'childs-childs-child'
		) );

		BEM::remove_blocks_to( 'root', 'child' );

		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
		) ), BEM::$stacks );
	}

	function test_add_variation_single() {
		BEM::new_stack( 'root' );
		BEM::add_variation( 'root', 'var' );

		$this->assertEquals( array( 'root' => array(
			'root' => array( 'var' ),
		) ), BEM::$stacks );
	}

	function test_add_variation_single_child() {
		BEM::new_stack( array(
			'root',
			'child',
		) );
		BEM::add_variation( 'root', 'var' );

		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array( 'var' ),
		) ), BEM::$stacks );
	}

	function test_add_variation_multiple() {
		BEM::new_stack( 'root' );
		BEM::add_variation( 'root', array( 'var1', 'var2' ) );

		$this->assertEquals( array( 'root' => array(
			'root' => array( 'var1', 'var2' ),
		) ), BEM::$stacks );
	}

	function test_add_variation_to_child() {
		BEM::new_stack( array(
			'root',
			'child',
		) );
		BEM::add_variation_to( 'root', 'child', 'var' );

		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array( 'var' ),
		) ), BEM::$stacks );
	}

	function test_add_variation_to_child_multiple() {
		BEM::new_stack( array(
			'root',
			'child',
		) );
		BEM::add_variation_to( 'root', 'child', array( 'var1', 'var2' ) );

		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array( 'var1', 'var2' ),
		) ), BEM::$stacks );
	}

	function test_remove_variation() {
		BEM::new_stack( array(
			'root',
			'child',
		) );
		BEM::add_variation( 'root', 'var' );
		BEM::remove_variation( 'root', 'var' );

		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
		) ), BEM::$stacks );
	}

	function test_remove_variation_from() {
		BEM::new_stack( array(
			'root',
			'child',
		) );
		BEM::add_variation_to( 'root', 'root', 'var' );
		BEM::remove_variation_from( 'root', 'root', 'var' );
		$this->assertEquals( array( 'root' => array(
			'root' => array(),
			'child' => array(),
		) ), BEM::$stacks );
	}

	function test_get_classes_no_variations() {
		BEM::new_stack( array(
			'root',
			'child',
		) );

		$this->assertEquals( array( 'root__child' ), BEM::get_classes( 'root' ) );
	}

	function test_get_classes_variations() {
		BEM::new_stack( 'root' );
		BEM::add_variation( 'root', array( 'var1', 'var2' ) );
		BEM::add_block( 'root', 'child' );

		$this->assertEquals( array(
			'root__child',
			'root--var1__child',
			'root--var2__child',
		), BEM::get_classes( 'root' ) );
	}

	function test_get_classes_to() {
		BEM::new_stack( 'root' );
		BEM::add_variation( 'root', array( 'var1', 'var2' ) );
		BEM::add_block( 'root', array( 'child', 'childs-child' ) );

		$this->assertEquals( array(
			'root__child',
			'root--var1__child',
			'root--var2__child',
		), BEM::get_classes_to( 'root', 'child' ) );
	}
}