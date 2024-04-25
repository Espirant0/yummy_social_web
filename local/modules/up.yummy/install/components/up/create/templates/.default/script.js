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
		this.deletePhoto=document.getElementById("delete_photo");
		this.emptyProducts = [];
		this.hasNotEmptyProducts = true;
		this.createSelect = this.createSelect.bind(this);
		this.deleteSelect = this.deleteSelect.bind(this);
		this.checkArray = this.checkArray.bind(this);
		this.createStep = this.createStep.bind(this);
		this.deleteStep = this.deleteStep.bind(this);
		this.buttonCheck = this.buttonCheck.bind(this);
		this.Filevalidation = this.Filevalidation.bind(this);
		this.form=document.getElementById("form");
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
		this.deletePhoto.onclick=(evt)=>
		{
			this.imgPre.src ="#";
			this.imgInp.value = "";
			this.deletePhoto.disabled=true;
		}
		this.create_recipe_btn.addEventListener("click", function(){
			this.create_recipe_btn.disabled = true;
			this.form.submit();
		});

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
						input.value = ``;
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

	checkArray(emptyProducts) {
		for (let i = 0; i < emptyProducts.length; i++) {
			if (emptyProducts[i] === true) {
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
		this.create_recipe_btn.disabled = !(this.textareaCount > 0 && this.selectCount > 0 && this.hasNotEmptyProducts === true);
	}

	Filevalidation() {
		const fi = document.getElementById("img_input");
		// Check if any file is selected.
		if (fi.files.length > 0) {
			for (let i = 0; i <= fi.files.length - 1; i++) {
				const fsize = fi.files.item(i).size;
				const file = Math.round(fsize / 1024);
				this.deletePhoto.disabled=false;
				// The size of the file.
				if (file >= 2048) {
					alert("ФАЙЛ ДОЛЖЕН БЫТЬ МЕНЬШЕ 2 мб");
					fi.value = "";
					this.deletePhoto.disabled=true;
				}
			}
		}
	}
}
