export class EditForm
{
	constructor(options = {})
	{
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
	}

	initCreate() {
		this.confirmRecipeBtn.disabled = true;
		this.imgInput.onchange = (evt) => {
			this.validateFiles();
			const [file] = this.imgInput.files;
			if (file) {
				this.imgPrevImage.src = URL.createObjectURL(file);

			}
		};
		this.deletePhotoBtn.onclick = (evt) => {
			this.imgPrevImage.src = "#";
			this.imgInput.value = "";
			this.deletePhotoBtn.disabled = true;
		}
		this.confirmRecipeBtn.addEventListener("click", () => {
			this.disableButton();
		});
	}

	initUpdate() {
		for (let i = 1; i <= this.selectCount; i++) {
			let startSelect = document.getElementById(`product_${i}`);
			let input = document.getElementById(`product_quantity_${i}`);
			let measure_select = document.getElementById(`measure_${i}`);
			let div2 = document.getElementById(`select_div_${i}`);
			measure_select.id = `measure_${i}`;
			measure_select.name = `MEASURES[]`;
			div2.className = `ui-ctl ui-ctl-after-icon ui-ctl-dropdown measure_select_div`;
			div2.id = `select_div_${i}`;

			startSelect.addEventListener("change", () => {
				let selectedValue = startSelect.value;
				let selectedText = startSelect.options[startSelect.selectedIndex].text;
				measure_select.innerHTML = "";
				this.buttonCheck();
				if (selectedText === "Выберите продукт") {
					document.getElementById(`product_quantity_${i}`).remove();
					document.getElementById(`measure_${i}`).remove();
					measure_select.remove();
					document.getElementById(`select_div_${i}`).remove();
					this.buttonCheck();
				} else {
					input.value = ``;
					document.getElementById(`container_${i}`).appendChild(input);
					div2.appendChild(measure_select);
					document.getElementById(`container_${i}`).appendChild(div2);
					this.buttonCheck();
				}
				this.measures[selectedValue].forEach(function (option) {
					let secondOption = document.createElement("option");
					secondOption.value = option.ID;
					secondOption.text = option.MEASURE_NAME;
					measure_select.appendChild(secondOption);
				});
				this.buttonCheck();
			});
		}
		this.deletePhotoBtn.disabled = false;
		if (this.imgPrevImage.src[this.imgPrevImage.src.length - 1] === "#") {
			this.photoStatus.value = '1'
			this.deletePhotoBtn.disabled = true;
		}

		this.imgInput.onchange = (evt) => {
			this.validateFiles();
			const [file] = this.imgInput.files;
			if (file) {
				this.imgPrevImage.src = URL.createObjectURL(file);
				this.photoStatus.value = 0
			}
		};
		this.deletePhotoBtn.onclick = (evt) => {
			this.photoStatus.value = 1
			this.imgPrevImage.src = "#"
			this.deletePhotoBtn.disabled = true;
		}

		this.buttonCheck();
		this.confirmRecipeBtn.addEventListener("click", () => {
			this.disableButton();
		});
	}

	disableButton() {
		if (this.validateTime() === true
			&& this.validateProductCount() === true
			&& this.validateStepCount() === true
			&& this.validateName() === true
			&& this.validateDescription() === true) {
			this.confirmRecipeBtn.disabled = true;
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
			const div3 = document.createElement("div");
			const angleDiv = document.createElement("div");
			const angleDivMain = document.createElement("div");
			const container = document.createElement("div");
			angleDiv.className = `ui-ctl-after ui-ctl-icon-angle`;
			angleDivMain.className = `ui-ctl-after ui-ctl-icon-angle`;
			select.id = `product_${this.selectCount}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `measure_${this.selectCount}`;
			measure_select.name = `MEASURES[]`;
			measure_select.className = `ui-ctl-element measure_select`;

			input.id = `product_quantity_input_${this.selectCount}`;
			input.required = true;
			input.name = `PRODUCTS_QUANTITY[]`;
			input.type = `number`;
			input.min = 1;


			select.className = `ui-ctl-element`;
			input.className = `ui-ctl-element product_input`;
			container.className = `select_container`;
			container.id = `container_${this.selectCount}`;
			div.className = `ui-ctl ui-ctl-after-icon ui-ctl-dropdown select_div`;
			div2.className = `ui-ctl ui-ctl-after-icon ui-ctl-dropdown measure_select_div`;
			div3.className = `ui-ctl ui-ctl-textbox product_input`
			div3.id = `product_quantity_${this.selectCount}`;
			div2.id = `select_div_${this.selectCount}`;
			div.appendChild(angleDivMain);
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
						div3.remove();
						this.buttonCheck();
					} else {
						input.value = ``;
						div3.appendChild(input);
						container.appendChild(div3);
						div2.appendChild(angleDiv);
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
			let productField = document.getElementById(`product_${i}`);
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
			const textareaDiv = document.createElement("div");
			const stepNumber = document.createElement("p");
			stepNumber.className = `title is-5`;
			stepNumber.textContent = `Шаг ${this.textareaCount}`;
			stepNumber.id = `step_${this.textareaCount}`
			textareaDiv.className = `ui-ctl-textarea ui-ctl-no-resize step_div`
			textarea.required = true;
			textarea.placeholder = `Описание шага`;
			textarea.maxLength = 150;
			textarea.name = `STEPS[]`;
			textareaDiv.id = `step_description_${this.textareaCount}`;
			textarea.className = `ui-ctl-element`;
			textarea.id = `step_textarea_${this.textareaCount}`
			textareaDiv.appendChild(textarea);
			this.stepContainer.appendChild(stepNumber);
			this.stepContainer.appendChild(textareaDiv);
			this.buttonCheck();
		}
	}

	deleteStep() {
		const element1 = document.getElementById(`step_description_${this.textareaCount}`);
		const element2 = document.getElementById(`step_${this.textareaCount}`);
		element1.remove();
		element2.remove();
		this.textareaCount--;
		this.buttonCheck();
	}

	buttonCheck() {
		this.confirmRecipeBtn.disabled = !(this.textareaCount > 0 && this.selectCount > 0);
	}

	validateFiles() {
		const fileInput = document.getElementById("img_input");
		if (fileInput.files.length > 0) {
			for (let i = 0; i <= fileInput.files.length - 1; i++) {
				const fileSize = fileInput.files.item(i).size;
				const file = Math.round(fileSize / 1024);
				this.deletePhotoBtn.disabled = false;
				if (file >= 2048) {
					alert("ФАЙЛ ДОЛЖЕН БЫТЬ МЕНЬШЕ 2 мб");
					fileInput.value = "";
					this.deletePhotoBtn.disabled = true;
				}
			}
		}
	}

	validateTime() {
		let timeInput = document.getElementById("time_input");
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

	validateProductCount() {
		if (this.selectCount === 0) {
			alert("Нет продуктов");
			this.form.preventDefault();
			return false;
		} else if (!this.checkArray()) {
			return false;
		} else {
			for (let i = 1; i <= this.selectCount; i++) {
				const input = document.getElementById(`product_quantity_input_${i}`);
				if (input.value === '') {
					alert("Не все продукты заполнены");
					this.form.preventDefault();
					return false;
				}
				if(input.value < 0){
					alert("Количество продукта должно быть больше 0");
					this.form.preventDefault();
					return false;
				}
				if(input.value > 5000){
					alert("Слишком большое число в количестве продуктов");
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
				const input = document.getElementById(`step_textarea_${i}`);
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
		let title = document.getElementById("title_input");
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
		let description = document.getElementById("description_input");
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
