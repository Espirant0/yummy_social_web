<?php
\Bitrix\Main\UI\Extension::load('up.yummy-selector');
\Bitrix\Main\UI\Extension::load('ui.entity-selector');
?>
<div class="content">
	<div class="column is-half is-offset-one-quarter add_form">
		<p class="title has-text-centered">Добавить рецепт</p>
		<form action="/add/" method="post">
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
							<textarea class="textarea" name= "DESCRIPTION" placeholder="Описание рецепта"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">
							<input class="input" name="TIME" type="number" placeholder="Время приготовления" required>
						</p>
					</div>
				</div>
			</div>
			<label for="IMAGES">Фото рецепта</label>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<p class="control">

							<form action="/add/" method="post" enctype="multipart/form-data">
								<input type="file" name="IMAGES">
							</form>
						</p>
					</div>
				</div>
			</div>
			<div class="field is-horizontal">
				<div class="field-body">
					<div class="field">
						<div class="control add_btn">
							<button class="button is-primary">
								Добавить рецепт
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!--<div id="test1" name="test1"></div>-->
		<div id="test2" name="test2"></div>
		<button id="button">hythty</button>
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
						id: '1', // пользователи
						dynamicLoad: true,
					},
				],

				/*targetNode: div,
				width: 400,
				height: 300,
				dropdownMode: true,
				enableSearch: true,
				compactView: true,
				showAvatars: false,
				tabs: [
					{ id: 'cities', title: 'Города', itemOrder: { sort: 'asc',  title: 'asc' } },
					{ id: 'countries', title: 'Страны', itemOrder: { 'title': 'asc' } },
				],
				items: [],
				searchOptions: {
					allowCreateItem: true,
					footerOptions: {
						label: 'Создать город:'
					}
				},
				events: {
					'Search:onItemCreateAsync': (event) => {
						return new Promise((resolve) => {
							const { searchQuery } = event.getData();
							const dialog = event.getTarget();
							setTimeout(() => { // эмуляция асинхронного действия
								let tab = dialog.getTab('cities');
								const item = dialog.addItem({
									id: Text.getRandom(),
									entityId: 'city',
									title: searchQuery.getQuery(),
									tabs: 'cities',
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
							}, 1000);
						});
					}
				}*/
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


