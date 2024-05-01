class CreateRecipe {
	constructor(products, measures) {
		this.products = products;
		this.measures = measures;
		this.body = document.getElementById("container");
		this.stepContainer = document.getElementById("step_container");
		this.imgInp = document.getElementById("img_input");
		this.imgPre = document.getElementById("img_pre");
		this.textareaCount = 0;
		this.selectCount = 0;
		this.create_recipe_btn = document.getElementById("create_recipe_btn");
		this.deletePhoto = document.getElementById("delete_photo");
		this.hasNotEmptyProducts = true;
		this.createSelect = this.createSelect.bind(this);
		this.deleteSelect = this.deleteSelect.bind(this);
		this.checkArray = this.checkArray.bind(this);
		this.createStep = this.createStep.bind(this);
		this.deleteStep = this.deleteStep.bind(this);
		this.buttonCheck = this.buttonCheck.bind(this);
		this.Filevalidation = this.Filevalidation.bind(this);
		this.form = document.getElementById("form");
	}

	init() {
		this.create_recipe_btn.disabled = true;
		this.imgInp.onchange = (evt) => {
			this.Filevalidation();
			const [file] = this.imgInp.files;
			if (file) {
				this.imgPre.src = URL.createObjectURL(file);

			}
		};
		this.deletePhoto.onclick = (evt) => {
			this.imgPre.src = "#";
			this.imgInp.value = "";
			this.deletePhoto.disabled = true;
		}
		this.create_recipe_btn.addEventListener("click", () => {
			this.disableButton();
		});
	}

	disableButton() {
		if (this.validateTime() === true
			&& this.validateProductCount() === true
			&& this.validateStepCount() === true
			&& this.validateName() === true
			&& this.validateDescription() === true) {
			this.create_recipe_btn.disabled = true;
			this.form.submit();
		}

	}

	createSelect() {
		if (this.selectCount < 15) {
			this.selectCount++;
			this.buttonCheck();
			const select = document.createElement("select");
			const measure_select = document.createElement("select");
			const input = document.createElement("input");
			const div = document.createElement("div");
			const div2 = document.createElement("div");
			const container = document.createElement("div");
			select.id = `create_product_${this.selectCount}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `create_measure_${this.selectCount}`;
			measure_select.name = `MEASURES[]`;

			input.id = `create_product_quantity_${this.selectCount}`;
			input.required = true;
			input.name = `PRODUCTS_QUANTITY[]`;
			input.type = `number`;
			input.min = 1;


			select.className = `product_select`;
			input.className = `input product_input`;
			container.className = `select_container`;
			container.id = `container_${this.selectCount}`;
			div.className = `select select_div`;
			div2.className = `select select_div`;
			div2.id = `select_div_${this.selectCount}`;

			div.appendChild(select);

			container.appendChild(div);
			this.body.appendChild(container);
			let placeholder = document.createElement("option");
			placeholder.text = "Выберите продукт";
			select.appendChild(placeholder);
			this.products.forEach(function (option) {
				var firstOption = document.createElement("option");
				firstOption.value = option.ID;
				firstOption.text = option.NAME;
				select.appendChild(firstOption);
			});
			for (let i = 1; i <= this.selectCount; i++) {

				this.buttonCheck();
				select.addEventListener("change", () => {
					var selectedValue = select.value;
					var selectedText = select.options[select.selectedIndex].text;
					measure_select.innerHTML = "";
					if (selectedText === placeholder.text) {
						div2.remove();
						input.remove();

						this.buttonCheck();
					} else {
						input.value = ``;
						container.appendChild(input);
						div2.appendChild(measure_select);
						container.appendChild(div2);

						this.buttonCheck();
					}
					this.measures[selectedValue].forEach(function (option) {
						var secondOption = document.createElement("option");
						secondOption.value = option.ID;
						secondOption.text = option.MEASURE_NAME;
						measure_select.appendChild(secondOption);
					});

					this.buttonCheck();
				});
			}
		}
	}

	deleteSelect() {
		this.buttonCheck();
		const element = document.getElementById(`container_${this.selectCount}`);
		element.remove();
		this.selectCount--;
		this.buttonCheck();
	}

	checkArray() {
		for (let i = 1; i <= this.selectCount; i++) {
			let productField = document.getElementById(`create_product_${i}`);
			let selectedText = productField.options[productField.selectedIndex].text;
			if (selectedText === "Выберите продукт") {
				alert("Есть невыбранные продукты");
				return false;
			}
		}
		return true;
	}

	createStep() {
		if (this.textareaCount < 10) {
			this.textareaCount++;
			const textarea = document.createElement("textarea");
			textarea.required = true;
			textarea.maxLength = 150;
			textarea.name = `STEPS[]`;
			textarea.id = `create_step_description_${this.textareaCount}`;
			textarea.className = `textarea`;
			this.stepContainer.appendChild(textarea);
			this.buttonCheck();
		}
	}

	deleteStep() {
		const element = document.getElementById(`create_step_description_${this.textareaCount}`);
		element.remove();
		this.textareaCount--;
		this.buttonCheck();
	}

	buttonCheck() {
		this.create_recipe_btn.disabled = !(this.textareaCount > 0 && this.selectCount > 0);
	}

	Filevalidation() {
		const fi = document.getElementById("img_input");
		// Check if any file is selected.
		if (fi.files.length > 0) {
			for (let i = 0; i <= fi.files.length - 1; i++) {
				const fsize = fi.files.item(i).size;
				const file = Math.round(fsize / 1024);
				this.deletePhoto.disabled = false;
				// The size of the file.
				if (file >= 2048) {
					alert("ФАЙЛ ДОЛЖЕН БЫТЬ МЕНЬШЕ 2 мб");
					fi.value = "";
					this.deletePhoto.disabled = true;
				}
			}
		}
	}

	validateTime() {
		let timeInput = document.getElementById("create_time_input");
		if (!(parseInt(timeInput.value) == timeInput.value)) {
			alert("НЕПРАВИЛЬНЫЙ ФОРМАТ ВРЕМЕНИ");
			this.form.preventDefault();
			return false;
		} else if (timeInput.value < 1) {
			alert("НЕПРАВИЛЬНОЕ ВРЕМЯ(Введите число больше чем 1)");
			this.form.preventDefault();
			return false;
		} else {
			return true;
		}
	}

	validateProductCount() {
		if (this.selectCount === 0) {
			alert("Нет продуктов");
			this.form.preventDefault();
			return false;
		} else if (!this.checkArray()) {
			return false;
		} else {
			for (let i = 1; i <= this.selectCount; i++) {
				const input = document.getElementById(`create_product_quantity_${i}`);
				if (input.value === '' || input.value < 1) {
					alert("Неправильно переданы продукты");
					this.form.preventDefault();
					return false;
				}
			}
			return true
		}
	}

	validateStepCount() {
		if (this.textareaCount === 0) {
			alert("Нет шагов");
			this.form.preventDefault();
			return false;
		} else {
			for (let i = 1; i <= this.textareaCount; i++) {
				const input = document.getElementById(`create_step_description_${i}`);
				if (input.value === '') {
					alert("Пустое описание шага");
					this.form.preventDefault();
					return false;
				}
			}
			return true
		}
	}

	validateName() {
		let title = document.getElementById("create_title_input");
		if (title.value.length < 1) {
			alert("Введите название");
			this.form.preventDefault();
			return false
		} else if (title.value.length > 50) {
			alert("Название должно быть меньше 50 символов");
			this.form.preventDefault();
			return false
		} else {
			return true
		}

	}

	validateDescription() {
		let description = document.getElementById("create_description_input");
		if (description.value.length < 1) {
			alert("Введите описание");
			return false
		} else if (description.value.length > 250) {
			alert("Описание должно быть меньше 250 символов");
			return false
		} else {
			return true
		}
	}
}
