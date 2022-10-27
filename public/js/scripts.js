/**
 *
 * Scripts.js
 *
 * Initialization of the page scripts.
 *
 *
 */

class Scripts {
  constructor() {
    this._initCommon();
    this._initPages();
  }

  // Common plugins and overrides initialization
  _initCommon() {
    // common.js initialization
    if (typeof Common !== 'undefined') {
      let common = new Common();
    }
  }

  // Page scripts initialization
  _initPages() {
    // horizontal.js initialization
    if (typeof HorizontalPage !== 'undefined') {
      const horizontalPage = new HorizontalPage();
    }

    // vertical.js initialization
    if (typeof VerticalPage !== 'undefined') {
      const verticalPage = new VerticalPage();
    }
  }
}
