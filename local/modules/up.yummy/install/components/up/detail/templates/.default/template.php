<?php
/**
 * @var array $arResult
* @var array $arParams;
 */
$recipe=$arResult['RECIPE'];
?>
<div class="content">
	<div class="column is-flex is-half is-offset-one-quarter">
		<input class="input is-half" type="text" placeholder="Поиск рецепта" />
		<button class="button is-light ml-3">Искать</button>
	</div>
	<div class="columns">
		<div class="column recipe_image">
			<img src="<?=$arParams['IMAGE']?>"/>
		</div>
		<div class="column right_col">
			<p class="title recipe_title"><?=$recipe['TITLE']?></p>
			<div class="columns recipe_calories">
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
			<p><strong>Время приготовления: </strong><?=$recipe['TIME']?> мин</p>
			<div class="buttons recipe_buttons">
				<?php if($recipe['AUTHOR_ID']==$arResult['AUTHOR_ID']):?>
				<?php if(!$arResult['IS_PUBLIC']): ?>
				<form action="/publish/" method="post">
					<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
					<button class="button is-success">Опубликовать рецепт</button>
				</form>
				<?php else:?>
					<form action="/publish/" method="post">
						<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
						<button class="button is-danger">Убрать с публикации</button>
					</form>
					<?php endif;?>
				<button class="button is-warning">Изменить рецепт</button>
                <form action="/delete/" method="post">
                    <input type="hidden" name="deleteId" value="<?=$recipe['id']?>">
                    <button class="button is-danger" onclick="return window.confirm('Вы уверены, что хотите удалить этот рецепт?');">Удалить рецепт</button>
                </form>
				<?php endif;?>
				<?php if(!$arResult['FEATURED']): ?>
					<form action="/featured/" method="post">
						<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
						<button class="button is-success">В избранное</button>
					</form>
				<?php else:?>
				<form action="/featured/" method="post">
					<input type="hidden" name="recipeId" value="<?=$recipe['id']?>">
					<button class="button is-danger">Убрать из избранного</button>
				</form>
				<?php endif;?>
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
                <?php foreach($arResult['PRODUCTS'] as $product):?>
				<tr>
					<td><?= $product['TITLE']?></td>
					<td><?= $product['VALUE']?> <?= $product['MEASURE_NAME']?></td>
				</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>

		<div class="notification is-info">
			<p class="title">Описание</p><br>
			<?=$recipe['DESCRIPTION']?>
		</div>
		</div>
</div>
</div>