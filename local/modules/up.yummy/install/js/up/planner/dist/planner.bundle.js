/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var _templateObject, _templateObject2, _templateObject3, _templateObject4;
	var Planner = /*#__PURE__*/function () {
	  function Planner() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, Planner);
	    if (main_core.Type.isStringFilled(options.rootNodeId)) {
	      this.rootNodeId = options.rootNodeId;
	    } else {
	      throw new Error('Planner: options.rootNodeId required');
	    }
	    this.rootNode = document.getElementById(this.rootNodeId);
	    if (!this.rootNode) {
	      throw new Error("Planner: element with id \"".concat(this.rootNodeId, "\" not found"));
	    }
	    this.currentDay = options.currentDay;
	    this.user = options.userId;
	    this.plannerList = [];
	    this.courseList = [];
	    this.reload();
	  }
	  babelHelpers.createClass(Planner, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadList().then(function (plannerList) {
	        _this.plannerList = plannerList;
	        _this.render();
	      });
	      this.loadCourses().then(function (courseList) {
	        _this.courseList = courseList;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getList', {
	          data: {
	            apiKey: 'very_secret_key'
	          }
	        }).then(function (response) {
	          resolve(response.data);
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      });
	    }
	  }, {
	    key: "loadCourses",
	    value: function loadCourses() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getCourses', {
	          data: {
	            apiKey: 'very_secret_key'
	          }
	        }).then(function (response) {
	          resolve(response.data);
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      });
	    }
	  }, {
	    key: "render",
	    value: function render() {
	      var _this2 = this;
	      this.rootNode.innerHTML = '';
	      var daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
	      var currentDate = this.currentDay;
	      var currentDayOfWeek = currentDate.getDay();
	      var firstDayOfWeek = new Date(currentDate);
	      firstDayOfWeek.setDate(firstDayOfWeek.getDate() - currentDayOfWeek + 1);
	      var dates = Array.from({
	        length: 7
	      }, function (_, index) {
	        var date = new Date(firstDayOfWeek);
	        date.setDate(date.getDate() + index);
	        var dayOfWeek = daysOfWeek[index];
	        return {
	          date: date.toISOString().split('T')[0],
	          dayOfWeek: dayOfWeek
	        };
	      });
	      this.courseList.forEach(function (courseData) {
	        var recipes = dates.map(function (dateData, index) {
	          var matchingRecipes = _this2.plannerList.filter(function (plannerData) {
	            return plannerData.date_of_plan === dateData.date + 'T03:00:00+03:00' && plannerData.course_name === courseData.TITLE && plannerData.owner_id === _this2.user;
	          });
	          return matchingRecipes.map(function (recipe) {
	            return recipe.recipe_name;
	          }).join(', ');
	        });
	        var planRow = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n     <tr>\n        <th class=\"is-info\">", "</th>\n        ", "\n     </tr>\n    "])), courseData.TITLE, recipes.map(function (recipe, index) {
	          var date = dates[index].date;
	          var params = "course=".concat(encodeURIComponent(courseData.TITLE), "&date=").concat(encodeURIComponent(date));
	          return main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n            <td>\n             <a href=\"?", "#win1\">", "</a>\n            </td>\n         "])), params, recipe);
	        }));
	        _this2.rootNode.appendChild(planRow);
	      });
	      var headerRow = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n    <tr>\n     <th></th>\n     ", "\n    </tr>\n"])), dates.map(function (dateData) {
	        return main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n        <th class=\"is-info\">", " (", ")</th>\n     "])), dateData.date, dateData.dayOfWeek);
	      }));
	      this.rootNode.insertBefore(headerRow, this.rootNode.firstChild);
	    }
	  }]);
	  return Planner;
	}();

	exports.Planner = Planner;

}((this.BX.Up.Yummy = this.BX.Up.Yummy || {}),BX));
