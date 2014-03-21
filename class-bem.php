<?php
class BEM {
	public static $child_seperator = '__';
	public static $variation_seperator = '--';
	public static $stacks = array();

	public static function new_stack( $stack, $block = false ) {
		if ( is_array( $stack ) ) {
			self::$stacks[ $stack[0] ] = array();
			self::add_block( $stack[0], $stack );
		} else {
			self::$stacks[ $stack ] = array();
			if ( $block ) {
				self::add_block( $stack, $block );
			} else {
				self::add_block( $stack, $stack );
			}
		}
	}

	public static function add_block( $stack, $block ) {
		if ( empty( $block ) ) {
			return;
		}

		if ( is_array( $block ) ) {
			$blocks = $block;
			foreach ( $blocks as $block ) {
				self::$stacks[ $stack ][ $block ] = array();
			}
		} else {
			self::$stacks[ $stack ][ $block ] = array();
		}
	}

	public static function remove_block( $stack ) {
		array_pop( self::$stacks[ $stack ] );
	}

	public static function remove_blocks_to( $stack, $block ) {
		$keys = array_reverse( array_keys( self::$stacks[ $stack ] ) );
		foreach ( $keys as $key ) {
			if ( $block == $key ) {
				break;
			}

			unset( self::$stacks[ $stack ][ $key ] );
		}
	}

	public static function add_variation( $stack, $variation ) {
		$key = array_keys( self::$stacks[ $stack ] );
		$block = end( $key );
		if ( is_array( $variation ) ) {
			self::$stacks[ $stack ][ $block ] = array_merge( self::$stacks[ $stack ][ $block ], $variation );
		} else {
			self::$stacks[ $stack ][ $block ][] = $variation;
		}
	}

	public static function add_variation_to( $stack, $block, $variation ) {
		if ( is_array( $variation ) ) {
			self::$stacks[ $stack ][ $block ] = array_merge( self::$stacks[ $stack ][ $block ], $variation );
		} else {
			self::$stacks[ $stack ][ $block ][] = $variation;
		}
	}

	public static function remove_variation( $stack, $variation ) {
		$key = array_keys( self::$stacks[ $stack ] );
		$block = end( $key );
		$i = array_search( $variation, self::$stacks[ $stack ][ $block ] );
		if ( false !== $i ) {
			unset( $variation, self::$stacks[ $stack ][ $block ][ $i ] );
		}
	}

	public static function remove_variation_from( $stack, $block, $variation ) {
		$i = array_search( $variation, self::$stacks[ $stack ][ $block ] );
		if ( false !== $i ) {
			unset( $variation, self::$stacks[ $stack ][ $block ][ $i ] );
		}
	}

	protected static function append_variations( $stack, $key, $classes ) {
		$varried_classes = array();
		foreach ( $classes as $class ) {
			foreach ( self::$stacks[ $stack ][ $key ] as $variation ) {
				$varried_classes[] = $class . self::$variation_seperator . $variation;
			}
		}
		return $varried_classes;
	}

	public static function get_classes( $stack ) {
		return self::get_classes_to( $stack, '' );
	}

	public static function get_classes_to( $stack, $block ) {
		$keys = array_keys( self::$stacks[ $stack ] );
		$classes = array();
		foreach ( $keys as $key ) {
			// if it's the first itteration just add the classes
			if ( empty( $classes ) ) {
				$classes[] = $key;
			} else {
				// if it's the second add block with child seperator
				foreach ( $classes as $k => $class ) {
					$classes[ $k ] .= self::$child_seperator . $key;
				}
			}
			$classes = array_merge( $classes, self::append_variations( $stack, $key, $classes ) );

			if ( $key == $block ) {
				break;
			}
		}

		return $classes;
	}

	public static function print_classes( $stack, $extra = '' ) {
		echo 'class="' . implode( ' ', self::get_classes( $stack ) ) . ' ' . $extra . '"';
	}

	public static function print_classes_to( $stack, $block, $extra = '' ) {
		echo 'class="' . implode( ' ', self::get_classes_to( $stack, $block ) ) . ' ' . $extra . '"';
	}
}