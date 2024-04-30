/* eslint-disable */
this.BX = this.BX || {};
(function (exports,main_core) {
	'use strict';

	var Detail = /*#__PURE__*/function () {
	  function Detail() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {
	      name: 'Detail'
	    };
	    babelHelpers.classCallCheck(this, Detail);
	    this.name = options.name;
	  }
	  babelHelpers.createClass(Detail, [{
	    key: "setName",
	    value: function setName(name) {
	      if (main_core.Type.isString(name)) {
	        this.name = name;
	      }
	    }
	  }, {
	    key: "getName",
	    value: function getName() {
	      return this.name;
	    }
	  }]);
	  return Detail;
	}();

	exports.Detail = Detail;

}((this.BX[''] = this.BX[''] || {}),BX));
