class UpdateRecipe {
	constructor(products, measures, stepsSize, productsSize) {
		this.products = products;
		this.measures = measures;
		this.body = document.getElementById("container");
		this.imgInp = document.getElementById("img_input");
		this.imgPre = document.getElementById("img_pre");
		this.deletePhoto = document.getElementById("delete_photo");
		this.photoStatus = document.getElementById("photoStatus");
		this.textareaCount = stepsSize;
		this.selectCount = productsSize;
		this.stepContainer = document.getElementById("step_container");
		this.update_recipe_btn = document.getElementById("update_recipe_btn");
		this.emptyProducts = [];
		this.hasNotEmptyProducts = true;
		this.createSelect = this.createSelect.bind(this);
		this.deleteSelect = this.deleteSelect.bind(this);
		this.createStep = this.createStep.bind(this);
		this.deleteStep = this.deleteStep.bind(this);
		this.buttonCheck = this.buttonCheck.bind(this);
		this.Filevalidation = this.Filevalidation.bind(this);
		this.form=document.getElementById("form");
	}

	init() {
		for (let i = 1; i <= this.selectCount; i++) {
			let startSelect = document.getElementById(`update_product_${i}`);
			let input = document.getElementById(`update_product_quantity_${i}`);
			let measure_select = document.getElementById(`update_measure_${i}`);
			let div2 = document.getElementById(`select_div_${i}`);
			measure_select.id = `update_measure_${i}`;
			measure_select.name = `MEASURES[]`;
			div2.className = `select select_div`;
			div2.id = `select_div_${i}`;

			startSelect.addEventListener("change", () => {
				let selectedValue = startSelect.value;
				let selectedText = startSelect.options[startSelect.selectedIndex].text;
				measure_select.innerHTML = "";
				this.buttonCheck();
				if (selectedText === "Выберите продукт") {
					document.getElementById(`update_product_quantity_${i}`).remove();
					this.emptyProducts[i] = true;
					document.getElementById(`update_measure_${i}`).remove();
					measure_select.remove();
					document.getElementById(`select_div_${i}`).remove();
					this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
					this.buttonCheck();
				} else {
					input.value = ``;
					document.getElementById(`container_${i}`).appendChild(input);
					this.emptyProducts[i] = false;
					div2.appendChild(measure_select);
					document.getElementById(`container_${i}`).appendChild(div2);
					this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
					this.buttonCheck();
				}
				this.measures[selectedValue].forEach(function (option) {
					let secondOption = document.createElement("option");
					secondOption.value = option.ID;
					secondOption.text = option.MEASURE_NAME;
					measure_select.appendChild(secondOption);
				});
				this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
				this.buttonCheck();
			});
		}

		if(this.imgPre.src[this.imgPre.src.length - 1] === "#")
		{
			this.photoStatus.value = '1'
			this.deletePhoto.disabled = true;
		}


		this.imgInp.onchange = (evt) => {
			this.Filevalidation();
			const [file] = this.imgInp.files;
			if (file) {
				this.imgPre.src = URL.createObjectURL(file);
				this.photoStatus.value = 0
			}
		};
		this.deletePhoto.onclick = (evt) => {
			this.photoStatus.value = 1
			this.imgPre.src = "#"
			this.deletePhoto.disabled = true;
		}

		this.buttonCheck();
		this.update_recipe_btn.addEventListener("click", () => {
			this.disableButton();
		});
	}
	disableButton()
	{
		if(this.validateTime()===true
			&& this.validateProductCount()===true
			&& this.validateStepCount()===true
			&& this.validateName()===true
			&& this.validateDescription()===true)
		{
			this.update_recipe_btn.disabled = true;
			alert("HERE");
			this.form.submit();
		}

	}

	checkArray(emptyProducts) {
		for (let i = 0; i < emptyProducts.length; i++) {
			if (emptyProducts[i] === true) {
				return false;
			}
		}
		return true;
	}

	createSelect() {
		if (this.selectCount < 15) {
			this.selectCount++;
			this.emptyProducts[this.selectCount] = true;
			this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
			this.buttonCheck();
			const select = document.createElement("select");
			const measure_select = document.createElement("select");
			const input = document.createElement("input");
			const div = document.createElement("div");
			const div2 = document.createElement("div");
			const container = document.createElement("div");
			select.id = `update_product_${this.selectCount}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `update_measure_${this.selectCount}`;
			measure_select.name = `MEASURES[]`;

			input.id = `update_product_quantity_${this.selectCount}`;
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
				this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
				this.buttonCheck();
				select.addEventListener("change", () => {
					var selectedValue = select.value;
					var selectedText = select.options[select.selectedIndex].text;
					measure_select.innerHTML = "";
					if (selectedText === placeholder.text) {
						this.emptyProducts[i] = true;
						div2.remove();
						input.remove();
						this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
						this.buttonCheck();
					} else {
						this.emptyProducts[i] = false;
						container.appendChild(input);
						div2.appendChild(measure_select);
						container.appendChild(div2);
						this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
						this.buttonCheck();
					}
					this.measures[selectedValue].forEach(function (option) {
						var secondOption = document.createElement("option");
						secondOption.value = option.ID;
						secondOption.text = option.MEASURE_NAME;
						measure_select.appendChild(secondOption);
					});
					this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
					this.buttonCheck();
				});
			}
		}
	}

	deleteSelect() {
		this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
		this.buttonCheck();
		const element = document.getElementById(`container_${this.selectCount}`);
		element.remove();
		this.selectCount--;
		this.hasNotEmptyProducts = this.checkArray(this.emptyProducts);
		this.buttonCheck();
	}

	createStep() {
		if (this.textareaCount < 10) {
			this.textareaCount++;
			const textarea = document.createElement("textarea");
			textarea.required = true;
			textarea.name = `STEPS[]`;
			textarea.className = `textarea`;
			textarea.id = `update_step_description_${this.textareaCount}`;
			textarea.maxLength = 150;
			this.stepContainer.appendChild(textarea);
			this.buttonCheck();
		}
	}

	deleteStep() {
		const element = document.getElementById(`update_step_description_${this.textareaCount}`);
		element.remove();
		this.textareaCount--;
		this.buttonCheck();
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

	buttonCheck() {
		this.update_recipe_btn.disabled = !(this.textareaCount > 0 && this.selectCount > 0 && this.hasNotEmptyProducts === true);
	}
	validateTime()
	{
		let TimeInput=document.getElementById("update_time_input");
		if(!(parseInt(TimeInput.value)==TimeInput.value)|| TimeInput.value<1)
		{
			alert("НЕПРАВИЛЬНОЕ ВРЕМЯ");
			this.form.preventDefault();
			return false;
		}
		else
		{
			return true;
		}
	}
	validateProductCount()
	{
		if(this.selectCount === 0)
		{
			alert("Нет продуктов");
			this.form.preventDefault();
			return false;
		}
		else {
			for (let i = 1; i <= this.selectCount; i++)
			{
				const input = document.getElementById(`update_product_quantity_${i}`);
				if (input.value === '' || input.value < 1) {
					alert("Неправильно переданы продукты");
					this.form.preventDefault();
					return false;
				}
			}
			return true
		}
	}
	validateStepCount()
	{
		if(this.textareaCount === 0)
		{
			alert("Нет шагов");
			this.form.preventDefault();
			return false;
		}
		else {
			for (let i = 1; i <= this.textareaCount; i++)
			{
				const input = document.getElementById(`update_step_description_${i}`);
				if(input.value === '')
				{
					alert("Пустое описание шага");
					this.form.preventDefault();
					return false;
				}
			}
			return true
		}
	}
	validateName()
	{
		let title=document.getElementById("update_title_input");
		if(title.value.length<1 || title.value.length>50)
		{
			alert("Неправильное название");
			this.form.preventDefault();

			return false
		}
		else
		{
			return true
		}

	}
	validateDescription()
	{
		let description=document.getElementById("update_description_input");
		if(description.value.length<1 || description.value.length>250)
		{
			alert("Неправильное описание");
			return false
		}
		else
		{
			return true
		}
	}
}