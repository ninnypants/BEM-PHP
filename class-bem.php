<?php
class BEM {
	public static $child_seperator = '__';
	public static $variation_seperator = '--';
	protected $stack = array();
	protected $block = '';

	public function __construct( $block = '' ) {
		if ( ! empty( $block ) ) {
			$this->add_block( $block );
		}
	}

	public function add_block( $block ) {
		$this->stack[$block] = array();
		$this->block = $block;
	}

	public function remove_block() {
		array_pop( $this->stack );
		$keys = array_keys( $this->stack );
		$this->block = end( $keys );
	}

	public function add_variation( $variation ) {
		$this->stack[ $this->block ][] = $variation;
	}

	public function remove_variation( $variation ) {
		$key = array_search( $variation,  $this->stack[ $this->block ] );
		unset( $this->stack[ $this->block ][ $key ] );
	}

	protected function append_variations( $classes, $key ) {
		$varried_classes = array();
		foreach ( $classes as $class ) {
			foreach ( $this->stack[ $key ] as $variation ) {
				$varried_classes[] = $class . self::$variation_seperator . $variation;
			}
		}
		return $varried_classes;
	}

	public function print_classes() {
		if ( empty( $this->stack ) ) {
			return false;
		}

		$classes = array();
		do {
			$key = key( $this->stack );
			// if it's the first itteration just add the classes
			if ( empty( $classes ) ) {
				$classes[] = $key;
			} else {
				// if it's the second add block with child seperator
				foreach ( $classes as $k => $class ) {
					$classes[ $k ] .= self::$child_seperator . $key;
				}
			}
			$classes = array_merge( $classes, $this->append_variations( $classes, $key ) );
		} while( next( $this->stack ) );
		reset( $this->stack );

		echo implode( ' ', $classes );
	}
}

$test = new BEM( 'base' );
$test->add_variation( 'var-1' );
$test->add_variation( 'var-2' );
$test->print_classes();
echo "\n\n";
$test->add_block( 'child' );
$test->add_variation( 'var-1' );
$test->add_variation( 'var-2' );
$test->print_classes();
echo "\n\n";
$test->remove_variation( 'var-2' );
$test->print_classes();
echo "\n\n";
$test->remove_block();
$test->print_classes();