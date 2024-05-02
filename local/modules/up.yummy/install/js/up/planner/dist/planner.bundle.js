/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core,main_popup) {
	'use strict';

	var _templateObject, _templateObject2, _templateObject3, _templateObject4, _templateObject5, _templateObject6, _templateObject7, _templateObject8;
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
	    this.title = options.title;
	    this.currentDate = options.currentDate;
	    this.start = options.start;
	    this.user = options.userId;
	    this.plannerList = [];
	    this.productList = [];
	    this.courseList = [];
	    this.recipeList = [];
	    this.selectedRecipe = [];
	    this.reload();
	  }
	  babelHelpers.createClass(Planner, [{
	    key: "reload",
	    value: function reload() {
	      var _this = this;
	      this.loadList(this.start).then(function (plannerList) {
	        _this.plannerList = plannerList;
	        _this.render();
	      });
	      this.loadCourses().then(function (courseList) {
	        _this.courseList = courseList;
	        _this.render();
	      });
	      this.loadProductList(this.start).then(function (productList) {
	        _this.productList = productList;
	        _this.render();
	      });
	      this.loadRecipeList().then(function (recipeList) {
	        _this.recipeList = recipeList;
	        _this.render();
	      });
	    }
	  }, {
	    key: "loadList",
	    value: function loadList(start) {
	      var _this2 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getList', {
	          data: {
	            start: start,
	            user: _this2.user
	          }
	        }).then(function (response) {
	          resolve(response.data);
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      });
	    }
	  }, {
	    key: "loadProductList",
	    value: function loadProductList(start) {
	      var _this3 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getProducts', {
	          data: {
	            start: start,
	            user: _this3.user
	          }
	        }).then(function (response) {
	          resolve(response.data);
	        })["catch"](function (error) {
	          console.error(error);
	        });
	      });
	    }
	  }, {
	    key: "loadDailyProductList",
	    value: function loadDailyProductList(date) {
	      var _this4 = this;
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getDailyProducts', {
	          data: {
	            date: date,
	            user: _this4.user
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
	    key: "loadRecipeList",
	    value: function loadRecipeList() {
	      return new Promise(function (resolve, reject) {
	        BX.ajax.runAction('up:yummy.planner.getRecipeList', {
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
	      var _this5 = this;
	      this.rootNode.innerHTML = '';
	      var daysOfWeek = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
	      var currentDate = this.currentDate;
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
	          var matchingRecipes = _this5.plannerList.filter(function (plannerData) {
	            return plannerData.DATE_OF_PLAN === dateData.date + 'T03:00:00+03:00' && plannerData.COURSE_NAME === courseData.TITLE && plannerData.OWNER_ID === _this5.user;
	          });
	          return matchingRecipes.map(function (recipe) {
	            return recipe.RECIPE_NAME;
	          }).join(', ');
	        });
	        var planRow = main_core.Tag.render(_templateObject || (_templateObject = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<tr>\n\t\t\t\t<th class=\"is-info\">", "</th>\n\t\t\t\t", "\n\t\t\t</tr>\n\t\t\t"])), courseData.TITLE, recipes.map(function (recipe, index) {
	          var cellContent = '+';
	          if (recipe !== '') {
	            cellContent = recipe;
	          }
	          var date = dates[index].date;
	          return main_core.Tag.render(_templateObject2 || (_templateObject2 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t\t<td class=\"table_cell\" data-date=\"", "\" data-target=\"modal\" data-course-name=\"", "\" data-course-id=\"", "\">\n\t\t\t\t\t\t", "\n\t\t\t\t\t\t</td>\n\t\t\t\t\t"])), date, courseData.TITLE, courseData.ID, cellContent);
	        }));
	        _this5.rootNode.appendChild(planRow);
	      });
	      document.getElementById("product_table").innerHTML = "";
	      var productHeader = main_core.Tag.render(_templateObject3 || (_templateObject3 = babelHelpers.taggedTemplateLiteral(["\n\t\t<tr>\n\t\t\t<th>\u041F\u0440\u043E\u0434\u0443\u043A\u0442</th>\n\t\t\t<th>\u041A\u043E\u043B\u0438\u0447\u0435\u0441\u0442\u0432\u043E</th>\n\t\t\t\n\t\t</tr>"])));
	      document.getElementById("product_table").appendChild(productHeader);
	      for (var key in this.productList) {
	        var productRow = main_core.Tag.render(_templateObject4 || (_templateObject4 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t <tr>\n\t\t\t\t<td>", "</td>\n\t\t\t\t<td>", " ", "</td>\n\t\t\t</tr>\n\t\t\t"])), this.productList[key][3], this.productList[key][1], this.productList[key][2]);
	        document.getElementById("product_table").appendChild(productRow);
	      }
	      var headerRow = main_core.Tag.render(_templateObject5 || (_templateObject5 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t<tr>\n\t\t\t\t<th class=\"is-info\"></th>\n\t\t\t\t", "\n\t\t\t</tr>\n\t\t"])), dates.map(function (dateData) {
	        return main_core.Tag.render(_templateObject6 || (_templateObject6 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t\t\t<th class=\"is-info table_cell\" data-date=\"", "\">", " <br>", "</th>\n\t\t\t\t"])), dateData.date, _this5.formatDate(dateData.date), dateData.dayOfWeek);
	      }));
	      this.rootNode.insertBefore(headerRow, this.rootNode.firstChild);
	      var cells = document.querySelectorAll('td[data-date][data-course-id]');
	      cells.forEach(function (cell) {
	        cell.addEventListener('click', function (event) {
	          _this5.openEditForm(event, _this5.recipeList, _this5.selectedRecipe);
	        });
	      });
	      var headerDates = document.querySelectorAll('th[data-date]');
	      headerDates.forEach(function (date) {
	        _this5.loadDailyProductList(date.getAttribute('data-date')).then(function (productList) {
	          date.addEventListener('click', function (event) {
	            _this5.openProductsView(event, productList);
	          });
	        });
	      });
	    }
	  }, {
	    key: "formatDate",
	    value: function formatDate(dateString) {
	      var date = new Date(dateString);
	      var day = date.getDate();
	      var month = date.getMonth() + 1;
	      var year = date.getFullYear();

	      // Добавляем ведущий ноль, если день или месяц состоят из одной цифры
	      if (day < 10) {
	        day = "0" + day;
	      }
	      if (month < 10) {
	        month = "0" + month;
	      }
	      return day + "." + month + "." + year;
	    }
	  }, {
	    key: "openProductsView",
	    value: function openProductsView(event, products) {
	      event.preventDefault();
	      var target = event.target;
	      var table = document.getElementById("daily_product_table");
	      var productHeader = main_core.Tag.render(_templateObject7 || (_templateObject7 = babelHelpers.taggedTemplateLiteral(["\n\t\t<tr class=\"popup_table\">\n\t\t\t<th>\u041F\u0440\u043E\u0434\u0443\u043A\u0442</th>\n\t\t\t<th>\u041A\u043E\u043B\u0438\u0447\u0435\u0441\u0442\u0432\u043E</th>\n\t\t</tr>"])));
	      table.appendChild(productHeader);
	      for (var key in products) {
	        var productRow = main_core.Tag.render(_templateObject8 || (_templateObject8 = babelHelpers.taggedTemplateLiteral(["\n\t\t\t <tr class=\"popup_table\">\n\t\t\t\t<td>", "</td>\n\t\t\t\t<td>", " ", "<</td>\n\t\t\t</tr>\n\t\t\t"])), products[key][3], products[key][1], products[key][2]);
	        table.appendChild(productRow);
	      }
	      var popup = new BX.PopupWindow("products", target, {
	        autoHide: true,
	        lightShadow: true,
	        closeIcon: true,
	        closeByEsc: true,
	        offsetLeft: "auto",
	        offsetTop: "auto",
	        overlay: {
	          backgroundColor: 'black',
	          opacity: '80'
	        },
	        events: {
	          onPopupClose: function onPopupClose() {
	            table.innerHTML = "";
	          }
	        }
	      });
	      popup.setContent(BX('daily_product_table'));
	      popup.show();
	    }
	  }, {
	    key: "openEditForm",
	    value: function openEditForm(event, recipes) {
	      var _this6 = this;
	      event.preventDefault();
	      var target = event.target;
	      var date = target.getAttribute('data-date');
	      var courseName = target.getAttribute('data-course-name');
	      var courseId = target.getAttribute('data-course-id');
	      var currentTime = new Date(date);
	      var popupForm = document.createElement("form");
	      popupForm.method = "post";
	      var dateInput = document.createElement("input");
	      dateInput.className = "input";
	      dateInput.name = "date";
	      dateInput.type = "text";
	      dateInput.id = "edit_date";
	      dateInput.className = "ui-ctl-element";
	      dateInput.value = this.formatDate(currentTime.toISOString().split("T")[0]);
	      dateInput.readOnly = true;
	      var course = document.createElement("div");
	      course.id = "edit_course";
	      course.className = "notification course";
	      course.textContent = courseName;
	      var recipeDivSelect = document.createElement("div");
	      recipeDivSelect.className = "ui-ctl ui-ctl-after-icon ui-ctl-dropdown";
	      var iconDiv = document.createElement("div");
	      iconDiv.className = "ui-ctl-after ui-ctl-icon-angle";
	      recipeDivSelect.appendChild(iconDiv);
	      var recipeSelect = document.createElement("select");
	      recipeSelect.id = "edit_recipe";
	      recipeSelect.className = "ui-ctl-element";
	      recipeDivSelect.appendChild(recipeSelect);
	      var buttonsDiv = document.createElement("div");
	      buttonsDiv.className = "popup_buttons";
	      var editButton = document.createElement("button");
	      editButton.className = "ui-btn ui-btn-success ui-btn-icon-edit";
	      editButton.type = "button";
	      editButton.id = "edit_btn";
	      editButton.textContent = "Применить";
	      var deleteButton = document.createElement("button");
	      deleteButton.className = "ui-btn ui-btn-danger ui-btn-icon-remove";
	      deleteButton.type = "button";
	      deleteButton.id = "delete_btn";
	      deleteButton.textContent = "Удалить";
	      buttonsDiv.appendChild(editButton);
	      buttonsDiv.appendChild(deleteButton);
	      recipes.forEach(function (recipesData) {
	        var option = document.createElement("option");
	        option.value = recipesData.ID;
	        option.textContent = recipesData.TITLE;
	        recipeSelect.appendChild(option);
	      });
	      popupForm.appendChild(dateInput);
	      popupForm.appendChild(course);
	      popupForm.appendChild(recipeDivSelect);
	      popupForm.appendChild(buttonsDiv);
	      var modal = document.getElementById('modal');
	      var div = document.createElement('modal_content');
	      div.appendChild(popupForm);
	      modal.appendChild(div);
	      var popup = new BX.PopupWindow("add_recipe", target, {
	        autoHide: true,
	        lightShadow: true,
	        closeIcon: true,
	        closeByEsc: true,
	        offsetLeft: "auto",
	        offsetTop: "auto",
	        overlay: {
	          backgroundColor: 'black',
	          opacity: '80'
	        },
	        events: {
	          onPopupClose: function onPopupClose() {
	            div.remove();
	          }
	        }
	      });
	      popup.setContent(BX('modal'));
	      popup.show();
	      editButton.addEventListener('click', function () {
	        BX.ajax.runAction('up:yummy.planner.editPlan', {
	          data: {
	            date: date,
	            course: courseId,
	            recipe: recipeSelect.value,
	            user: _this6.user
	          }
	        }).then(function (response) {
	          console.log("success");
	          _this6.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	        popup.close();
	        _this6.reload();
	      });
	      deleteButton.addEventListener('click', function () {
	        BX.ajax.runAction('up:yummy.planner.deletePlan', {
	          data: {
	            date: date,
	            course: courseId,
	            user: _this6.user
	          }
	        }).then(function (response) {
	          console.log("success");
	          _this6.reload();
	        })["catch"](function (error) {
	          console.error(error);
	        });
	        popup.close();
	        _this6.reload();
	      });
	    }
	  }]);
	  return Planner;
	}();

	exports.Planner = Planner;

}((this.BX.Up.Yummy = this.BX.Up.Yummy || {}),BX,BX.Main));
