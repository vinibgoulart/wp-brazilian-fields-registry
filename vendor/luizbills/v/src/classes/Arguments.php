<?php

namespace luizbills\v;

final class Arguments {
	protected $arguments;

	public function __construct ( string $expression ) {
		$this->arguments = $this->parse_arguments( $expression );
	}

	public function get ( int $index, $default = null ) {
		if ( isset( $this->arguments[ $index ] ) ) {
			return $this->arguments[ $index ];
		}
		return $default;
	}

	public function get_all () : array {
		return $this->arguments;
	}

	protected function parse_arguments ( string $expression ) : array {
		if ( '' != $expression ) {
			if ( $expression[0] != '(' || $expression[-1] != ')' ) {
				throw new \RuntimeException( __METHOD__ . ": invalid filter arguments syntax" );
			}

			// get the value between '(' and ')' and trim
			$values = trim( \preg_replace('/(^\(|\)$)/', '', $expression ) );

			// remove breaklines
			$values = \str_replace( [ "\n", "\r" ], '', $values );

			// remove unecessary whitespaces
			$values = \preg_replace( '/(\s+,(\s+)?)/', ',', $values );

			// replace escaped quotes
			$quote_placeholder = '{' . \md5( \time() + \mt_rand() ). '}';
			$values = \preg_replace( '/\\\"/', $quote_placeholder, $values );

			// check for unclosed quotes
			\preg_match_all( '/"/', $values, $matches );
			// the quantity of quotes should be an even number
			if ( 0 != count( $matches[0] ) % 2 ) {
				throw new \RuntimeException( __METHOD__ . ": unclosed string in filter arguments" );
			}

			// parse arguments as CSV line
			$values = \str_getcsv( $values, ',', '"' );

			// restore the escaped commas
			foreach ( $values as $key => $value) {
				$values[ $key ] = \str_replace( $quote_placeholder, '"', $values[ $key ] );
			}

			return $values;
		}

		return [];
	}
}
