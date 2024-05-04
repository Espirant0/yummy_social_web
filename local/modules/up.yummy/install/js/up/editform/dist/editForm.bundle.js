/* eslint-disable */
this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports) {
	'use strict';

	var EditForm = /*#__PURE__*/function () {
	  function EditForm() {
	    var _this = this;
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, EditForm);
	    this.products = options.products;
	    this.measures = options.measures;
	    this.textareaCount = options.stepsSize;
	    this.selectCount = options.productsSize;
	    this.photoStatus = document.getElementById("photo_status");
	    this.body = document.getElementById("container");
	    this.stepContainer = document.getElementById("step_container");
	    this.imgInput = document.getElementById("img_input");
	    this.imgPrevImage = document.getElementById("img_prev");
	    this.confirmRecipeBtn = document.getElementById("confirm_recipe_btn");
	    this.deletePhotoBtn = document.getElementById("delete_photo");
	    this.form = document.getElementById("form");
	    this.addProductBtn = document.getElementById("add_product_btn");
	    this.removeProductBtn = document.getElementById("remove_product_btn");
	    this.addStepBtn = document.getElementById("add_step_btn");
	    this.removeStepBtn = document.getElementById("remove_step_btn");
	    this.addProductBtn.addEventListener("click", function () {
	      _this.createSelect();
	    });
	    this.removeProductBtn.addEventListener("click", function () {
	      _this.deleteSelect();
	    });
	    this.addStepBtn.addEventListener("click", function () {
	      _this.createStep();
	    });
	    this.removeStepBtn.addEventListener("click", function () {
	      _this.deleteStep();
	    });
	  }
	  babelHelpers.createClass(EditForm, [{
	    key: "initCreate",
	    value: function initCreate() {
	      var _this2 = this;
	      this.confirmRecipeBtn.disabled = true;
	      this.imgInput.onchange = function (evt) {
	        _this2.validateFiles();
	        var _this2$imgInput$files = babelHelpers.slicedToArray(_this2.imgInput.files, 1),
	          file = _this2$imgInput$files[0];
	        if (file) {
	          _this2.imgPrevImage.src = URL.createObjectURL(file);
	        }
	      };
	      this.deletePhotoBtn.onclick = function (evt) {
	        _this2.imgPrevImage.src = "#";
	        _this2.imgInput.value = "";
	        _this2.deletePhotoBtn.disabled = true;
	      };
	      this.confirmRecipeBtn.addEventListener("click", function () {
	        _this2.disableButton();
	      });
	    }
	  }, {
	    key: "initUpdate",
	    value: function initUpdate() {
	      var _this3 = this;
	      var _loop = function _loop(i) {
	        var startSelect = document.getElementById("product_".concat(i));
	        var input = document.getElementById("product_quantity_".concat(i));
	        var measure_select = document.getElementById("measure_".concat(i));
	        var div2 = document.getElementById("select_div_".concat(i));
	        measure_select.id = "measure_".concat(i);
	        measure_select.name = "MEASURES[]";
	        div2.className = "ui-ctl ui-ctl-after-icon ui-ctl-dropdown measure_select_div";
	        div2.id = "select_div_".concat(i);
	        startSelect.addEventListener("change", function () {
	          var selectedValue = startSelect.value;
	          var selectedText = startSelect.options[startSelect.selectedIndex].text;
	          measure_select.innerHTML = "";
	          _this3.buttonCheck();
	          if (selectedText === "Выберите продукт") {
	            document.getElementById("product_quantity_".concat(i)).remove();
	            document.getElementById("measure_".concat(i)).remove();
	            measure_select.remove();
	            document.getElementById("select_div_".concat(i)).remove();
	            _this3.buttonCheck();
	          } else {
	            input.value = "";
	            document.getElementById("container_".concat(i)).appendChild(input);
	            div2.appendChild(measure_select);
	            document.getElementById("container_".concat(i)).appendChild(div2);
	            _this3.buttonCheck();
	          }
	          _this3.measures[selectedValue].forEach(function (option) {
	            var secondOption = document.createElement("option");
	            secondOption.value = option.ID;
	            secondOption.text = option.MEASURE_NAME;
	            measure_select.appendChild(secondOption);
	          });
	          _this3.buttonCheck();
	        });
	      };
	      for (var i = 1; i <= this.selectCount; i++) {
	        _loop(i);
	      }
	      this.deletePhotoBtn.disabled = false;
	      if (this.imgPrevImage.src[this.imgPrevImage.src.length - 1] === "#") {
	        this.photoStatus.value = '1';
	        this.deletePhotoBtn.disabled = true;
	      }
	      this.imgInput.onchange = function (evt) {
	        _this3.validateFiles();
	        var _this3$imgInput$files = babelHelpers.slicedToArray(_this3.imgInput.files, 1),
	          file = _this3$imgInput$files[0];
	        if (file) {
	          _this3.imgPrevImage.src = URL.createObjectURL(file);
	          _this3.photoStatus.value = 0;
	        }
	      };
	      this.deletePhotoBtn.onclick = function (evt) {
	        _this3.photoStatus.value = 1;
	        _this3.imgPrevImage.src = "#";
	        _this3.deletePhotoBtn.disabled = true;
	      };
	      this.buttonCheck();
	      this.confirmRecipeBtn.addEventListener("click", function () {
	        _this3.disableButton();
	      });
	    }
	  }, {
	    key: "disableButton",
	    value: function disableButton() {
	      if (this.validateTime() === true && this.validateProductCount() === true && this.validateStepCount() === true && this.validateName() === true && this.validateDescription() === true) {
	        this.confirmRecipeBtn.disabled = true;
	        this.form.submit();
	      }
	    }
	  }, {
	    key: "createSelect",
	    value: function createSelect() {
	      var _this4 = this;
	      if (this.selectCount < 15) {
	        this.selectCount++;
	        this.buttonCheck();
	        var select = document.createElement("select");
	        var measure_select = document.createElement("select");
	        var input = document.createElement("input");
	        var div = document.createElement("div");
	        var div2 = document.createElement("div");
	        var div3 = document.createElement("div");
	        var angleDiv = document.createElement("div");
	        var angleDivMain = document.createElement("div");
	        var container = document.createElement("div");
	        angleDiv.className = "ui-ctl-after ui-ctl-icon-angle";
	        angleDivMain.className = "ui-ctl-after ui-ctl-icon-angle";
	        select.id = "product_".concat(this.selectCount);
	        select.name = "PRODUCTS[]";
	        measure_select.id = "measure_".concat(this.selectCount);
	        measure_select.name = "MEASURES[]";
	        measure_select.className = "ui-ctl-element measure_select";
	        input.id = "product_quantity_input_".concat(this.selectCount);
	        input.required = true;
	        input.name = "PRODUCTS_QUANTITY[]";
	        input.type = "number";
	        input.min = 1;
	        select.className = "ui-ctl-element";
	        input.className = "ui-ctl-element product_input";
	        container.className = "select_container";
	        container.id = "container_".concat(this.selectCount);
	        div.className = "ui-ctl ui-ctl-after-icon ui-ctl-dropdown select_div";
	        div2.className = "ui-ctl ui-ctl-after-icon ui-ctl-dropdown measure_select_div";
	        div3.className = "ui-ctl ui-ctl-textbox product_input";
	        div3.id = "product_quantity_".concat(this.selectCount);
	        div2.id = "select_div_".concat(this.selectCount);
	        div.appendChild(angleDivMain);
	        div.appendChild(select);
	        container.appendChild(div);
	        this.body.appendChild(container);
	        var placeholder = document.createElement("option");
	        placeholder.text = "Выберите продукт";
	        select.appendChild(placeholder);
	        this.products.forEach(function (option) {
	          var firstOption = document.createElement("option");
	          firstOption.value = option.ID;
	          firstOption.text = option.NAME;
	          select.appendChild(firstOption);
	        });
	        for (var i = 1; i <= this.selectCount; i++) {
	          this.buttonCheck();
	          select.addEventListener("change", function () {
	            var selectedValue = select.value;
	            var selectedText = select.options[select.selectedIndex].text;
	            measure_select.innerHTML = "";
	            if (selectedText === placeholder.text) {
	              div2.remove();
	              div3.remove();
	              _this4.buttonCheck();
	            } else {
	              input.value = "";
	              div3.appendChild(input);
	              container.appendChild(div3);
	              div2.appendChild(angleDiv);
	              div2.appendChild(measure_select);
	              container.appendChild(div2);
	              _this4.buttonCheck();
	            }
	            _this4.measures[selectedValue].forEach(function (option) {
	              var secondOption = document.createElement("option");
	              secondOption.value = option.ID;
	              secondOption.text = option.MEASURE_NAME;
	              measure_select.appendChild(secondOption);
	            });
	            _this4.buttonCheck();
	          });
	        }
	      }
	    }
	  }, {
	    key: "deleteSelect",
	    value: function deleteSelect() {
	      this.buttonCheck();
	      var element = document.getElementById("container_".concat(this.selectCount));
	      element.remove();
	      this.selectCount--;
	      this.buttonCheck();
	    }
	  }, {
	    key: "createStep",
	    value: function createStep() {
	      if (this.textareaCount < 10) {
	        this.textareaCount++;
	        var textarea = document.createElement("textarea");
	        var textareaDiv = document.createElement("div");
	        var stepNumber = document.createElement("p");
	        stepNumber.className = "title is-5";
	        stepNumber.textContent = "\u0428\u0430\u0433 ".concat(this.textareaCount);
	        stepNumber.id = "step_".concat(this.textareaCount);
	        textareaDiv.className = "ui-ctl-textarea ui-ctl-no-resize step_div";
	        textarea.required = true;
	        textarea.placeholder = "\u041E\u043F\u0438\u0441\u0430\u043D\u0438\u0435 \u0448\u0430\u0433\u0430";
	        textarea.maxLength = 150;
	        textarea.name = "STEPS[]";
	        textareaDiv.id = "step_description_".concat(this.textareaCount);
	        textarea.className = "ui-ctl-element";
	        textarea.id = "step_textarea_".concat(this.textareaCount);
	        textareaDiv.appendChild(textarea);
	        this.stepContainer.appendChild(stepNumber);
	        this.stepContainer.appendChild(textareaDiv);
	        this.buttonCheck();
	      }
	    }
	  }, {
	    key: "deleteStep",
	    value: function deleteStep() {
	      var element1 = document.getElementById("step_description_".concat(this.textareaCount));
	      var element2 = document.getElementById("step_".concat(this.textareaCount));
	      element1.remove();
	      element2.remove();
	      this.textareaCount--;
	      this.buttonCheck();
	    }
	  }, {
	    key: "buttonCheck",
	    value: function buttonCheck() {
	      this.confirmRecipeBtn.disabled = !(this.textareaCount > 0 && this.selectCount > 0);
	    }
	  }, {
	    key: "validateProductValue",
	    value: function validateProductValue() {
	      for (var i = 1; i <= this.selectCount; i++) {
	        var productField = document.getElementById("product_".concat(i));
	        var selectedText = productField.options[productField.selectedIndex].text;
	        if (selectedText === "Выберите продукт") {
	          alert("Есть невыбранные продукты");
	          return false;
	        }
	      }
	      return true;
	    }
	  }, {
	    key: "validateFiles",
	    value: function validateFiles() {
	      var fileInput = document.getElementById("img_input");
	      if (fileInput.files.length > 0) {
	        for (var i = 0; i <= fileInput.files.length - 1; i++) {
	          var fileSize = fileInput.files.item(i).size;
	          var file = Math.round(fileSize / 1024);
	          this.deletePhotoBtn.disabled = false;
	          if (file >= 2048) {
	            alert("ФАЙЛ ДОЛЖЕН БЫТЬ МЕНЬШЕ 2 мб");
	            fileInput.value = "";
	            this.deletePhotoBtn.disabled = true;
	          }
	        }
	      }
	    }
	  }, {
	    key: "validateTime",
	    value: function validateTime() {
	      var timeInput = document.getElementById("time_input");
	      if (!(parseInt(timeInput.value) == timeInput.value)) {
	        alert("Неправильный формат времени");
	        this.form.preventDefault();
	        return false;
	      }
	      if (timeInput.value < 1) {
	        alert("Неправильное время (Введите число больше чем 1)");
	        this.form.preventDefault();
	        return false;
	      }
	      if (timeInput.value > 500) {
	        alert("Неправильное время (Введите число меньше чем 500)");
	        this.form.preventDefault();
	        return false;
	      }
	      return true;
	    }
	  }, {
	    key: "validateProductCount",
	    value: function validateProductCount() {
	      if (this.selectCount === 0) {
	        alert("Нет продуктов");
	        this.form.preventDefault();
	        return false;
	      } else if (!this.validateProductValue()) {
	        return false;
	      } else {
	        for (var i = 1; i <= this.selectCount; i++) {
	          var input = document.getElementById("product_quantity_input_".concat(i));
	          if (input.value === '') {
	            alert("Не все продукты заполнены");
	            this.form.preventDefault();
	            return false;
	          }
	          if (input.value < 0) {
	            alert("Количество продукта должно быть больше 0");
	            this.form.preventDefault();
	            return false;
	          }
	          if (input.value > 5000) {
	            alert("Слишком большое число в количестве продуктов");
	            this.form.preventDefault();
	            return false;
	          }
	        }
	        return true;
	      }
	    }
	  }, {
	    key: "validateStepCount",
	    value: function validateStepCount() {
	      if (this.textareaCount === 0) {
	        alert("Нет шагов");
	        this.form.preventDefault();
	        return false;
	      } else {
	        for (var i = 1; i <= this.textareaCount; i++) {
	          var input = document.getElementById("step_textarea_".concat(i));
	          if (input.value === '') {
	            alert("Пустое описание шага");
	            this.form.preventDefault();
	            return false;
	          }
	        }
	        return true;
	      }
	    }
	  }, {
	    key: "validateName",
	    value: function validateName() {
	      var title = document.getElementById("title_input");
	      if (title.value.length < 1) {
	        alert("Введите название");
	        this.form.preventDefault();
	        return false;
	      } else if (title.value.length > 50) {
	        alert("Название должно быть меньше 50 символов");
	        this.form.preventDefault();
	        return false;
	      } else {
	        return true;
	      }
	    }
	  }, {
	    key: "validateDescription",
	    value: function validateDescription() {
	      var description = document.getElementById("description_input");
	      if (description.value.length < 1) {
	        alert("Введите описание");
	        return false;
	      } else if (description.value.length > 250) {
	        alert("Описание должно быть меньше 250 символов");
	        return false;
	      } else {
	        return true;
	      }
	    }
	  }]);
	  return EditForm;
	}();

	exports.EditForm = EditForm;

}((this.BX.Up.Yummy = this.BX.Up.Yummy || {})));
