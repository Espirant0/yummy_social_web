<?php
/**
 * @var array $arParams ;
 * @var array $arResult
 */
$products = json_encode($arResult['PRODUCTS']);
$productMeasures = json_encode($arResult['PRODUCT_MEASURES']);
?>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">Добавить рецепт</p>
		<form action="/add/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" name="DESCRIPTION" placeholder="Описание рецепта"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="text" pattern="[0-9]{,3}"
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
							<input type="file" name="IMAGES" id="img_inp">
                            <img id="img_pre" src="#" alt="your image" />
						</p>
					</div>
				</div>
			</div>
			<div class="product_container">
				<div id="container" class="products_selects">

				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" onClick="createSelect()">Добавить
						продукт
					</button>
				</div>
				<div class="product_btn">
					<button class="button is-primary is-expanded" type="button" onClick="deleteSelect()">Удалить
						продукт
					</button>
				</div>
			</div>
			<div id="step_container">
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" onClick="createStep()">Добавить шаг
					</button>
				</div>
				<div class="step_btn">
					<button class="button is-primary is-expanded" type="button" onClick="deleteStep()">Удалить шаг
					</button>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button type="submit" class="button is-primary" id="submit_button" disabled>
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
    const imgInp=document.getElementById("img_inp");
    const imgPre=document.getElementById("img_pre");
	let textareaCount = 0;
	let selectCount = 0;
    let submit_button = document.getElementById("submit_button");
	function createSelect() {
		selectCount++;
		const select = document.createElement("select");
		const measure_select = document.createElement("select");
		const input = document.createElement("input");
		const div = document.createElement("div");
		const div2 = document.createElement("div");
		const container = document.createElement("div");
		select.id = `PRODUCT_${selectCount}`;
		select.name = `PRODUCTS[]`;

		measure_select.id = `MEASURE_${selectCount}`;
		measure_select.name = `MEASURES[]`;

		input.id = `PRODUCT_QUANTITY_${selectCount}`;
		input.required = true;
		input.name = `PRODUCTS_QUANTITY[]`;

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
		products.forEach(function(option) {
			var firstOption = document.createElement('option');
			firstOption.value = option.ID;
			firstOption.text = option.NAME;
			select.appendChild(firstOption);
		});

		select.addEventListener('change', function() {
			var selectedValue = this.value;
			var selectedText = this.options[this.selectedIndex].text;
			measure_select.innerHTML = '';
			if(selectedText === placeholder.text)
			{
				document.getElementById(`PRODUCT_QUANTITY_${selectCount}`).remove();
				document.getElementById(`MEASURE_${selectCount}`).remove();
				document.getElementById(`select_div_${selectCount}`).remove();
			}
			else
			{
				container.appendChild(input);
				div2.appendChild(measure_select);
				container.appendChild(div2);
			}
			measures[selectedValue].forEach(function(option) {
				var secondOption = document.createElement('option');
				secondOption.value = option.ID;
				secondOption.text = option.MEASURE_NAME;
				measure_select.appendChild(secondOption);
			});
		});
		buttonCheck();
	}

	function deleteSelect() {
		const element = document.getElementById(`container_${selectCount}`);
		element.remove();
		selectCount--;
        buttonCheck()
	}

	function createStep() {
		if (textareaCount < 10) {
			textareaCount++;
			const textarea = document.createElement('textarea');
			textarea.required = true;
			textarea.name = `STEPS[]`;
			textarea.id = `textarea-${textareaCount}`;
			stepContainer.appendChild(textarea);
            buttonCheck()
		}

	}

	function deleteStep() {
		const element = document.getElementById(`textarea-${textareaCount}`);
		element.remove();
		textareaCount--;
        buttonCheck()
	}
    function buttonCheck()
    {
        submit_button.disabled = !(textareaCount > 0 && selectCount > 0);
    }


    imgInp.onchange = evt =>
    {
        const [file] = imgInp.files
        if (file) {
            imgPre.src = URL.createObjectURL(file)
        }
    }
</script>