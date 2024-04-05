<?php
/**
 * @var array $arResult

 */
$recipe=$arResult['RECIPE'];
/**
 * @var array $arParams;
 */
?>
<div class="content">
	<div class="column is-flex is-half is-offset-one-quarter">
		<input class="input is-half" type="text" placeholder="Поиск рецепта" />
		<button class="button is-light ml-3">Искать</button>
	</div>
	<div class="columns">
		<div class="column">
			<img src="<?=$arParams['IMAGE']?>"/>
		</div>
		<div class="column">
			<p class="title"><?=$recipe['title']?></p>
			<div class="columns">
				<div class="column">
					<h5>Калории</h5>
					<p>432423</p>
				</div>
				<div class="column">
					<h5>Белки</h5>
					<p>143232</p>
				</div>
				<div class="column">
					<h5>Жиры</h5>
					<p>32423</p>
				</div>
				<div class="column">
					<h5>Углеводы</h5>
					<p>534534</p>
				</div>
			</div>
			<p><strong>Время приготовления:</strong><?=$recipe['time']?> мин</p>
			<div class="buttons">
				<button class="button is-success">Опубликовать рецепт</button>
				<button class="button is-warning">Изменить рецепт</button>
				<button class="button is-danger">Удалить рецепт</button>
			</div>
		</div>
	</div>
	<div class="container bottom_content">
		<p class="title">Ингредиенты</p><br>
		<div class="column is-half is-offset-one-quarter">
			<table class="table">
				<thead>
				<tr>
					<th>Продукт</th>
					<th>Количество</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>Морковь</td>
					<td>300 гр</td>
				</tr>
				</tbody>
			</table>
		</div>

		<div class="notification is-info">
			<p class="title">Описание</p><br>
			<?=$recipe['description']?>
		</div>
		</div>
	</div>
</div>