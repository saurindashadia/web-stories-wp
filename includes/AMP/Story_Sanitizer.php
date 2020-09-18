<?php
/**
 * Class Story_Sanitizer.
 *
 * @package   Google\Web_Stories
 * @copyright 2020 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/google/web-stories-wp
 */

/**
 * Copyright 2020 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Web_Stories\AMP;

use AMP_Base_Sanitizer;
use DOMElement;
use Google\Web_Stories\Traits\Publisher;

/**
 * Story sanitizer.
 *
 * Sanitizer for Web Stories related features.
 *
 * @see AMP_Story_Sanitizer
 *
 * @since 1.0.0
 */
class Story_Sanitizer extends AMP_Base_Sanitizer {
	use Publisher;

	/**
	 * Sanitize the HTML contained in the DOMDocument received by the constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sanitize() {
		// Adds a publisher logo if missing or just a placeholder.

		$story_elements = $this->dom->body->getElementsByTagName( 'amp-story' );

		if ( $story_elements ) {
			/**
			 * The <amp-story> element.
			 *
			 * @var DOMElement $story_element
			 */
			$story_element = $story_elements->item( 0 );

			if ( $story_element ) {
				$publisher_logo = $story_element->getAttribute( 'publisher-logo-src' );

				if ( empty( $publisher_logo ) || $publisher_logo === $this->get_publisher_logo_placeholder() ) {
					$story_element->setAttribute( 'publisher-logo-src', $this->get_publisher_logo() );
				}
			}
		}
	}
}
