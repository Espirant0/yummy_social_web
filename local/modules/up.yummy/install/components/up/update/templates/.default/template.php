<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
/**
 * @var $arResult
 *
 */
$recipe=$arResult['RECIPE'];
?>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">ИЗМЕНИТЬ РЕЦЕПТ</p>
		<form action="/update/<?=$recipe['ID']?>/" method="post" enctype="multipart/form-data">
			<div class="field is-horizontal ">
				<div class="field-body">
					<div class="field ">
						<p class="control">
							<input class="input" name="NAME" type="text" placeholder="Название рецепта" value="<?=$recipe['TITLE']?>" required>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control">
							<textarea class="textarea" required name= "DESCRIPTION" ><?=$recipe['DESCRIPTION']?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" value="<?=$recipe['TIME']?>" placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">

                            <?php
                            echo bitrix_sessid_post();
                            ?>
                                <input type="file" name="IMAGES">
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="button is-primary">
								Изменить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
            <button id="button">hythty</button>
		</form>
		<script>
			const div = document.getElementById('button');
			button.addEventListener('click', function() {
				dialog.show();
			});
			const dialog = new BX.UI.EntitySelector.Dialog({
				targetNode: button,
				enableSearch: true,
				context: 'MY_MODULE_CONTEXT',
				entities: [
					{
						id: 'products',
						dynamicLoad: true,
					},
				],
				width: 400,
				height: 300,
				dropdownMode: true,
				compactView: true,
				showAvatars: false,
				tabs: [
					{ id: 'products', title: 'Продукты', itemOrder: { sort: 'asc',  title: 'asc' } },
				],
				items: [
					{id: 1, entityId: 'products', tabs: 'products',},
				],

				events: {
					'Search:onItemCreateAsync': (event) => {
						return new Promise((resolve) => {
							const { searchQuery } = event.getData();
							const dialog = event.getTarget();
							/*setTimeout(() => { // эмуляция асинхронного действия
								let tab = dialog.getTab('products');
								const item = dialog.addItem({
									id: Text.getRandom(),
									entityId: 'products',
									title: searchQuery.getQuery(),
									tabs: 'products',
									// можно использовать для сортировки элементов на вкладке
									// для вкладки cities указана сортировка по этому полю.
									sort: 1
								});
								if (item)
								{
									item.select();
								}
								dialog.selectTab(tab.getId());
								resolve();
							}, 1000);*/
						});
					}
				}
			});
		</script>
	</div>

	<script>
		(function() { const tagSelector = new BX.UI.EntitySelector.TagSelector({
			textBoxAutoHide: true,
			textBoxWidth: 350,
			maxHeight: 99,
			placeholder: 'введите название элемента',
			addButtonCaption: 'Добавить элемент',
			addButtonCaptionMore: 'Добавить еще',
			showCreateButton: true,
			items: [
				{ id: 1, entityId: 'products', textColor: 'orange', title: 'Василий Иванов' },
			],
			events: {
				onBeforeTagAdd: function(event) {
					const selector = event.getTarget();
					const { tag } = event.getData();
					if (tag.getTitle() === 'xxx')
					{
						event.preventDefault();
					}
				},
				onBeforeTagRemove: function(event) {
					const selector = event.getTarget();
					const { tag } = event.getData();
					if (tag.getTitle() === 'aaa')
					{
						event.preventDefault();
					}
				},
				onEnter: (event) => {
					const selector = event.getTarget();
					const value = selector.getTextBoxValue();
					value.split(' ').forEach(function(title) {
						selector.addTag({
							id: id++,
							title: title,
							entityId: 'xxx',
						});
					});
				}
			},
		});
			tagSelector.renderTo(document.getElementById('test1'))})();
	</script>

</div>


