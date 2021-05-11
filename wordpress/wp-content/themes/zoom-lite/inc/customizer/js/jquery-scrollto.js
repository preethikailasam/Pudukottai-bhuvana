/*
(The MIT License)

Copyright (c) 2010+ Benjamin Lupton b@lupton.cc

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/


/*global define:false require:false */
/***
umd: true
***/
(function () {
	// Prepare
	var $, ScrollTo
	$ = window.jQuery || require('jquery')

	// Fix scrolling animations on html/body on safari
	$.propHooks.scrollTop = $.propHooks.scrollLeft = {
		get: function (elem, prop) {
			var result = null
			if ( elem.tagName === 'HTML' || elem.tagName === 'BODY' ) {
				if ( prop === 'scrollLeft' ) {
					result = window.scrollX
				}
				else if ( prop === 'scrollTop' ) {
					result = window.scrollY
				}
			}
			if ( result == null ) {
				result = elem[prop]
			}
			return result
		}
	}
	$.Tween.propHooks.scrollTop = $.Tween.propHooks.scrollLeft = {
		get: function (tween) {
			return $.propHooks.scrollTop.get(tween.elem, tween.prop)
		},
		set: function (tween) {
			// Our safari fix
			if ( tween.elem.tagName === 'HTML' || tween.elem.tagName === 'BODY' ) {
				// Defaults
				tween.options.bodyScrollLeft = (tween.options.bodyScrollLeft || window.scrollX)
				tween.options.bodyScrollTop = (tween.options.bodyScrollTop || window.scrollY)

				// Apply
				if ( tween.prop === 'scrollLeft' ) {
					tween.options.bodyScrollLeft = Math.round(tween.now)
				}
				else if ( tween.prop === 'scrollTop' ) {
					tween.options.bodyScrollTop = Math.round(tween.now)
				}

				// Apply
				window.scrollTo(tween.options.bodyScrollLeft, tween.options.bodyScrollTop)
			}
			// jQuery's IE8 Fix
			else if ( tween.elem.nodeType && tween.elem.parentNode ) {
				tween.elem[tween.prop] = tween.now
			}
		}
	}

	// jQuery ScrollTo
	ScrollTo = {
		// Configuration
		config: {
			duration: 400,
			easing: 'swing',
			callback: null,
			durationMode: 'each',
			offsetTop: 0,
			offsetLeft: 0
		},

		// Set Configuration
		configure: function (options) {
			// Apply Options to Config
			$.extend(ScrollTo.config, options || {})

			// Chain
			return this
		},

		// Perform the Scroll Animation for the Collections
		// We use $inline here, so we can determine the actual offset start for each overflow:scroll item
		// Each collection is for each overflow:scroll item
		scroll: function (collections, config) {
			// Prepare
			var collection, $container, $target, $inline, position,
				containerScrollTop, containerScrollLeft,
				containerScrollTopEnd, containerScrollLeftEnd,
				startOffsetTop, targetOffsetTop, targetOffsetTopAdjusted,
				startOffsetLeft, targetOffsetLeft, targetOffsetLeftAdjusted,
				scrollOptions,
				callback

			// Determine the Scroll
			collection = collections.pop()
			$container = collection.$container
			$target = collection.$target

			// Prepare the Inline Element of the Container
			$inline = $('<span/>').css({
				'position': 'absolute',
				'top': '0px',
				'left': '0px'
			})
			position = $container.css('position')

			// Insert the Inline Element of the Container
			$container.css({position: 'relative'})
			$inline.appendTo($container)

			// Determine the top offset
			startOffsetTop = $inline.offset().top
			targetOffsetTop = $target.offset().top
			targetOffsetTopAdjusted = targetOffsetTop - startOffsetTop - parseInt(config.offsetTop, 10)

			// Determine the left offset
			startOffsetLeft = $inline.offset().left
			targetOffsetLeft = $target.offset().left
			targetOffsetLeftAdjusted = targetOffsetLeft - startOffsetLeft - parseInt(config.offsetLeft, 10)

			// Determine current scroll positions
			containerScrollTop = $container.prop('scrollTop')
			containerScrollLeft = $container.prop('scrollLeft')

			// Reset the Inline Element of the Container
			$inline.remove()
			$container.css({position: position})

			// Prepare the scroll options
			scrollOptions = {}

			// Prepare the callback
			callback = function () {
				// Check
				if ( collections.length === 0 ) {
					// Callback
					if ( typeof config.callback === 'function' ) {
						config.callback()
					}
				}
				else {
					// Recurse
					ScrollTo.scroll(collections, config)
				}
				// Return true
				return true
			}

			// Handle if we only want to scroll if we are outside the viewport
			if ( config.onlyIfOutside ) {
				// Determine current scroll positions
				containerScrollTopEnd = containerScrollTop + $container.height()
				containerScrollLeftEnd = containerScrollLeft + $container.width()

				// Check if we are in the range of the visible area of the container
				if ( containerScrollTop < targetOffsetTopAdjusted && targetOffsetTopAdjusted < containerScrollTopEnd ) {
					targetOffsetTopAdjusted = containerScrollTop
				}
				if ( containerScrollLeft < targetOffsetLeftAdjusted && targetOffsetLeftAdjusted < containerScrollLeftEnd ) {
					targetOffsetLeftAdjusted = containerScrollLeft
				}
			}

			// Determine the scroll options
			if ( targetOffsetTopAdjusted !== containerScrollTop ) {
				scrollOptions.scrollTop = targetOffsetTopAdjusted
			}
			if ( targetOffsetLeftAdjusted !== containerScrollLeft ) {
				scrollOptions.scrollLeft = targetOffsetLeftAdjusted
			}

			// Check to see if the scroll is necessary
			if ( $container.prop('scrollHeight') === $container.height() ) {
				delete scrollOptions.scrollTop
			}
			if ( $container.prop('scrollWidth') === $container.width() ) {
				delete scrollOptions.scrollLeft
			}

			// Perform the scroll
			if ( scrollOptions.scrollTop != null || scrollOptions.scrollLeft != null ) {
				$container.animate(scrollOptions, {
					duration: config.duration,
					easing: config.easing,
					complete: callback
				})
			}
			else {
				callback()
			}

			// Return true
			return true
		},

		// ScrollTo the Element using the Options
		fn: function (options) {
			// Prepare
			var collections, config, $container, container
			collections = []

			// Prepare
			var	$target = $(this)
			if ( $target.length === 0 ) {
				// Chain
				return this
			}

			// Handle Options
			config = $.extend({}, ScrollTo.config, options)

			// Fetch
			$container = $target.parent()
			container = $container.get(0)

			// Cycle through the containers
			while ( ($container.length === 1) && (container !== document.body) && (container !== document) ) {
				// Check Container for scroll differences
				var containerScrollTop, containerScrollLeft
				containerScrollTop = $container.css('overflow-y') !== 'visible' && container.scrollHeight !== container.clientHeight
				containerScrollLeft =  $container.css('overflow-x') !== 'visible' && container.scrollWidth !== container.clientWidth
				if ( containerScrollTop || containerScrollLeft ) {
					// Push the Collection
					collections.push({
						'$container': $container,
						'$target': $target
					})
					// Update the Target
					$target = $container
				}
				// Update the Container
				$container = $container.parent()
				container = $container.get(0)
			}

			// Add the final collection
			collections.push({
				'$container': $('html'),
				// document.body doesn't work in firefox, html works for all
				// internet explorer starts at the beggining
				'$target': $target
			})

			// Adjust the Config
			if ( config.durationMode === 'all' ) {
				config.duration /= collections.length
			}

			// Handle
			ScrollTo.scroll(collections, config)

			// Chain
			return this
		}
	}

	// Apply our extensions to jQuery
	$.ScrollTo = $.ScrollTo || ScrollTo
	$.fn.ScrollTo = $.fn.ScrollTo || ScrollTo.fn

	// Export
	return ScrollTo
}).call(this)