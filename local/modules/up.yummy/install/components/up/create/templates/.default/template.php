<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */
$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
?>
<form action="/" method="get" class="create_btn">
	<button class="button is-success" id="comeback_btn">Назад</button>
</form>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">Добавить рецепт</p>
		<form action="/add/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" id="create_title_input" placeholder="Название рецепта" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" name="DESCRIPTION" id="create_description_input" placeholder="Описание рецепта" maxlength="250"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" id="create_time_input" min="1" pattern="[0-9]{,3}"
								   placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<?php echo bitrix_sessid_post(); ?>
                            <input type="file" name="IMAGES" id="img_input" accept="image/*">
                            <img id="img_pre" src="#" alt=""/>
						</p>
					</div>
				</div>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">

				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" id="add_product_btn" onClick="createSelect()">Добавить
						продукт
					</button>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" id="remove_product_btn" onClick="deleteSelect()">Удалить
						продукт
					</button>
				</div>
			</div>
			<div id="step_container">
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" id="add_step_btn"  onClick="createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" id="remove_step_btn" onClick="deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button type="submit" class="button is-primary" id="create_recipe_btn" disabled>
								Добавить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
	const products = JSON.parse('<?=$products;?>');
	const measures = JSON.parse('<?=$productMeasures;?>');
	const body = document.getElementById("container");
	const stepContainer = document.getElementById("step_container");
    const imgInp=document.getElementById("img_input");
    const imgPre=document.getElementById("img_pre");
	let textareaCount = 0;
	let selectCount = 0;
    let create_recipe_btn = document.getElementById("create_recipe_btn");

	let emptyProducts = [];
	let hasNotEmptyProducts = true;

	function createSelect() {
		if (selectCount < 15) {
			selectCount++;
			emptyProducts[selectCount] = true;
			hasNotEmptyProducts = checkArray(emptyProducts);
			buttonCheck()
			const select = document.createElement("select");
			const measure_select = document.createElement("select");
			const input = document.createElement("input");
			const div = document.createElement("div");
			const div2 = document.createElement("div");
			const container = document.createElement("div");
			select.id = `create_product_${selectCount}`;
			select.name = `PRODUCTS[]`;
			measure_select.id = `create_measure_${selectCount}`;
			measure_select.name = `MEASURES[]`;

			input.id = `create_product_quantity_${selectCount}`;
			input.required = true;
			input.name = `PRODUCTS_QUANTITY[]`;
			input.type = `number`;
			input.min = 1;
			select.className = `product_select`;
			input.className = `input product_input`;
			container.className = `select_container`
			container.id = `container_${selectCount}`;
			div.className = `select select_div`;
			div2.className = `select select_div`;
			div2.id = `select_div_${selectCount}`;

			div.appendChild(select);

			container.appendChild(div);
			body.appendChild(container);
			let placeholder = document.createElement("option");
			placeholder.text = "Выберите продукт";
			select.appendChild(placeholder);
			products.forEach(function (option) {
				var firstOption = document.createElement('option');
				firstOption.value = option.ID;
				firstOption.text = option.NAME;
				select.appendChild(firstOption);
			});
			for (let i = 1; i <= selectCount; i++) {
				hasNotEmptyProducts = checkArray(emptyProducts);
				buttonCheck()
				select.addEventListener('change', function () {
					var selectedValue = this.value;
					var selectedText = this.options[this.selectedIndex].text;
					measure_select.innerHTML = '';
					if (selectedText === placeholder.text) {
						emptyProducts[i] = true;
						div2.remove();
						input.remove();
						hasNotEmptyProducts = checkArray(emptyProducts);
						buttonCheck()
					} else {
						emptyProducts[i] = false;
						input.value = ``;
						container.appendChild(input);
						div2.appendChild(measure_select);
						container.appendChild(div2);
						hasNotEmptyProducts = checkArray(emptyProducts);
						buttonCheck()
					}
					measures[selectedValue].forEach(function (option) {
						var secondOption = document.createElement('option');
						secondOption.value = option.ID;
						secondOption.text = option.MEASURE_NAME;
						measure_select.appendChild(secondOption);
					});
					hasNotEmptyProducts = checkArray(emptyProducts);
					buttonCheck()
				});
			}
		}
	}

	function deleteSelect() {
		hasNotEmptyProducts = checkArray(emptyProducts);
		buttonCheck()
		const element = document.getElementById(`container_${selectCount}`);
		element.remove();
		selectCount--;
		hasNotEmptyProducts = checkArray(emptyProducts);
		buttonCheck()
	}

	function checkArray(emptyProducts) {
		for (let i = 0; i < emptyProducts.length; i++) {
			if (emptyProducts[i] === true) {
				return false;
			}
		}
		return true;
	}

	function createStep() {
		if (textareaCount < 10) {
			textareaCount++;
			const textarea = document.createElement('textarea');
			textarea.required = true;
			textarea.maxLength = 150;
			textarea.name = `STEPS[]`;
			textarea.className = `textarea`;
			textarea.id = `create_step_description_${textareaCount}`;
			stepContainer.appendChild(textarea);
            buttonCheck()
		}

	}

	function deleteStep() {
		const element = document.getElementById(`create_step_description_${textareaCount}`);
		element.remove();
		textareaCount--;
        buttonCheck()
	}
	function buttonCheck() {
		create_recipe_btn.disabled = !(textareaCount > 0 && selectCount > 0 && hasNotEmptyProducts === true);
	}


    imgInp.onchange = evt =>
    {
        Filevalidation()
        const [file] = imgInp.files
        if (file) {
            imgPre.src = URL.createObjectURL(file)
        }
    }
    Filevalidation = () =>
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
    }
</script>