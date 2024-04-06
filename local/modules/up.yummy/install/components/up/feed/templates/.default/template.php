<?php
/**
 * @var array $arParams;
 * @var array $arResult
 */
?>

<div class="content">
	<div class="columns">
		<div class="column is-three-fifths">
			<div class="column search_line is-flex">
				<input class="input is-half" type="text" placeholder="Поиск рецепта" />
				<button class="button is-light ml-3">Искать</button>
			</div>
            <?php foreach($arResult['RECIPES'] as $recipe):?>
			<a href="/detail/<?=$recipe['ID']?>/">
				<div class="card column recipe_card">
					<div class="card-image column is-two-thirds is-offset-one-fifth">
						<figure class="image card_image">
							<img
								src="<?=$arParams['IMAGE']?>" width="360"/>
						</figure>
					</div>
					<div class="card-content">
						<div class="media">
							<div class="media-left">
							</div>
							<div class="media-content">
								<p class="title is-4 "><?=$recipe['TITLE']?></p>
								<p class="subtitle is-6"><?=$recipe['DESCRIPTION']?></p>
							</div>
						</div>
						<div class="content">
							<div class="columns">
								<div class="column has-text-weight-bold">Калории</div>
								<div class="column has-text-weight-bold">Белки / Жиры / Углеводы</div>
								<div class="column has-text-weight-bold">Время приготовления</div>
							</div>
							<div class="columns">
								<div class="column">5423423</div>
								<div class="column"><?=$recipe['PROTEINS']?> / <?=$recipe['FATS']?> / <?=$recipe['CARBS']?></div>
								<div class="column"><?=$recipe['TIME']?> мин</div>
							</div>
						</div>
					</div>
				</div>
			</a>
            <?php endforeach;?>
		</div>
		<div class="column">
			<p class="title has-text-centered">Рецепт дня</p>
			<a href="/detail/1/">
				<div class="card column is-half is-offset-3">
					<div class="card-image column">
						<figure class="image card_image">
							<img
								src="<?=$arParams['IMAGE']?>"/>
						</figure>
					</div>
					<div class="card-content">
						<div class="media">
							<div class="media-left">
							</div>
							<div class="media-content">
								<p class="title is-4">Рецепт</p>
							</div>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
    <a href="/?page=<?=$arResult['PAGES'][0]?>">НАЗАД</a>
    <a href="/?page=<?=$arResult['PAGES'][1]?>">ВПЕРЕД</a>
</div>