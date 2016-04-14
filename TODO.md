## v2
 
* page-breaking issues in chrome/safari
	* lots of potential solutions on StackOverflow, but none worked so far.
	* may need to make font size and avatar size smaller, but prob not if can fix chrome page-break issues
	* want `overflow:hidden` in theory, but may mess up chrome page breaking
	* try `page-break-inside: avoid;` again
	* maybe try to build minimal snippet and work up from there until find problem
	* once that's fixed
		* move the section-description back to just being a string, rather than separate file.
		* also remove browser warning.

* big spacing diff between firefox and chrome, despite normalize
	* have to fix page-break bug before this matters.

* improve the default design

* add checkbox to include twitter, option to pick arbitrary image, option for name instead of image, etc
	* can use the [gear] icon like the Menus section does
	
* move documentation from make/comm/handbook to a `?` icon like menu/widget panels have.
	* might need custom section markup for that.
