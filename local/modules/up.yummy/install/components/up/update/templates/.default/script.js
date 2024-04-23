BX.namespace("Yummy.UpdateRecipe");
UpdateRecipe =
{
	init: function (products, measures, stepSize, productsSize)
	{
		this.products = products;
		this.measures = measures;
		this.stepSize = stepSize ;
		this.productsSize = productsSize;

		this.productsContainer = document.getElementById("container");
		this.imgInp = document.getElementById("img_input");
		this.imgPre = document.getElementById("img_pre");
		this.stepContainer = document.getElementById("step_container")
		this.update_recipe_btn = document.getElementById("update_recipe_btn");
		this.emptyProducts = Array();
		console.log(this.products );
		console.log(this.measures );
	},

	updateSelects: function ()
	{
		for (let i = 1; i <= this.productsSize ; i++) {
			let startSelect = document.getElementById(`update_product_${i}`);
			let input = document.getElementById(`update_product_quantity_${i}`);
			let measure_select = document.getElementById(`update_measure_${i}`);
			let div2 = document.getElementById(`select_div_${i}`);
			measure_select.id = `update_measure_${i}`;
			measure_select.name = `MEASURES[]`;
			div2.className = `select select_div`;
			div2.id = `select_div_${i}`;

			startSelect.addEventListener('change', function () {
				let selectedValue = Number(startSelect.value);
				let selectedText = startSelect.options[startSelect.selectedIndex].text;
				measure_select.innerHTML = '';
				//this.buttonCheck();
				if (selectedText === 'Выберите продукт') {
					document.getElementById(`update_product_quantity_${i}`).remove();
					//this.emptyProducts[i] = true;
					document.getElementById(`update_measure_${i}`).remove();
					measure_select.remove();
					document.getElementById(`select_div_${i}`).remove();
					//this.buttonCheck();
				} else {
					input.value = ``;
					document.getElementById(`container_${i}`).appendChild(input);
					//this.emptyProducts[i] = false;
					div2.appendChild(measure_select);
					document.getElementById(`container_${i}`).appendChild(div2);
					//this.buttonCheck();
				}
				this.measures[selectedValue].forEach(function (option) {
					let secondOption = document.createElement('option');
					secondOption.value = option.ID;
					secondOption.text = option.MEASURE_NAME;
					measure_select.appendChild(secondOption);
				});
				//this.buttonCheck();
			});
		}
	},
	checkArray: function() {
		for (let i = 0; i < this.emptyProducts.length; i++) {
			if (this.emptyProducts[i] === true) {
				return false;
			}
		}
		return true;
	},

	createSelect: function() {
		if (this.productsSize < 15) {
			this.productsSize++;
			//this.emptyProducts[this.productsSize] = true;
			this.buttonCheck();
			const select = document.createElement("select");
			const measure_select = document.createElement("select");
			const input = document.createElement("input");
			const div = document.createElement("div");
			const div2 = document.createElement("div");
			const container = document.createElement("div");
			select.id = `update_product_${this.productsSize}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `update_measure_${this.productsSize}`;
			measure_select.name = `MEASURES[]`;

			input.id = `update_product_quantity_${this.productsSize}`;
			input.required = true;
			input.name = `PRODUCTS_QUANTITY[]`;

			select.className = `product_select`;
			input.className = `input product_input`;
			container.className = `select_container`
			container.id = `container_${this.productsSize}`;
			div.className = `select select_div`;
			div2.className = `select select_div`;
			div2.id = `select_div_${this.productsSize}`;

			div.appendChild(select);

			container.appendChild(div);
			this.productsContainer.appendChild(container);
			let placeholder = document.createElement("option");
			placeholder.text = "Выберите продукт";
			select.appendChild(placeholder);
			this.products.forEach(function (option) {
				let firstOption = document.createElement('option');
				firstOption.value = option.ID;
				firstOption.text = option.NAME;
				select.appendChild(firstOption);
			});
			for (let i = 1; i <= this.productsSize; i++) {
				//this.buttonCheck();
				select.addEventListener('change', function () {
					let selectedValue = select.value;
					let selectedText = select.options[select.selectedIndex].text;
					measure_select.innerHTML = '';
					if (selectedText === placeholder.text) {
						//this.emptyProducts[i] = true;
						div2.remove();
						input.remove();
						//this.buttonCheck();
					} else {
						//this.emptyProducts[i] = false;
						container.appendChild(input);
						div2.appendChild(measure_select);
						container.appendChild(div2);
						//this.buttonCheck();
					}
					this.measures[select.value].forEach(function (option) {
						let secondOption = document.createElement('option');
						secondOption.value = option.ID;
						secondOption.text = option.MEASURE_NAME;
						measure_select.appendChild(secondOption);
					});
					//this.buttonCheck();
				});
			}
		}
	},

	deleteSelect: function(){
		this.buttonCheck();
		const element = document.getElementById(`container_${this.productsSize}`);
		element.remove();
		this.productsSize--;
		this.buttonCheck();
	},

	createStep: function() {
		if (this.stepSize < 10) {
			this.stepSize++;
			const textarea = document.createElement('textarea');
			textarea.required = true;
			textarea.name = `STEPS[]`;
			textarea.className = `textarea`;
			textarea.id = `update_step_description_${this.stepSize}`
			this.stepContainer.appendChild(textarea);
			this.buttonCheck();
		}
	},

	deleteStep: function () {
		const element = document.getElementById(`update_step_description_${this.stepSize}`);
		element.remove();
		this.stepSize--;
		this.buttonCheck();
	},

	changeImage: function () {
		this.imgInp.onchange = evt => {
			this.fileValidation();
			const [file] = this.imgInp.files
			if (file) {
				this.imgPre.src = URL.createObjectURL(file)
			}
		}
	},

	buttonCheck: function () {
		this.update_recipe_btn.disabled = !(this.stepSize > 0 && this.productsSize > 0 && this.checkArray() === true);
	},
	fileValidation: function ()
	{
		const fi = document.getElementById('img_input');
		// Check if any file is selected.
		if (fi.files.length > 0) {
			for ( let i = 0; i <= fi.files.length - 1; i++) {

				const fsize = fi.files.item(i).size;
				const file = Math.round((fsize / 1024));
				// The size of the file.
				if (file >= 2048)
				{
					alert(
						"ФАЙЛ ДОЛЖЕН БЫТЬ МЕНЬШЕ 2 мб");
					fi.value='';
				}

			}
		}
	},

}