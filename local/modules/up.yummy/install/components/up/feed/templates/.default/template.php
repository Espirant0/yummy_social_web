<?php global $APPLICATION;
/**
 * @var array $arParams;
 * @var array $arResult
 */

use Up\Yummy\Service\TemplateService;
$dailyRecipe = $arResult['DAILY_RECIPE'];
?>

<div class="content">
	<div class="columns">
		<div class="column is-three-fifths">
			<div class="search_line">
				<form action="/" method="get" class="main-ui-filter-compact-state">
					<?=$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
						'FILTER_ID' => $arParams['FILTER_ID'],
						'FILTER' => $arParams['FILTER'],
						'ENABLE_LIVE_SEARCH' => true,
						'ENABLE_LABEL' => true
					]);?>
				</form>
			</div>
			<?=(empty($arResult['RECIPES'])?$arResult['NOT_FOUND_MESSAGE']: '')?>
            <?php foreach($arResult['RECIPES'] as $recipe):?>
				<div class="card recipe_card">
					<a href="/detail/<?=$recipe['ID']?>/" id="recipe_card_<?=$recipe['ID']?>">
					<img
                        <?php if (isset($recipe['IMAGE'])):?>
                        src="<?=$recipe['IMAGE']?>"
                        <?php else:?>
                        src="<?=$arParams['IMAGE']?>"
                        <?php endif?>
						alt="https://bulma.io/assets/images/placeholders/1280x960.png"
						class="recipe_img"
                    />
					<div class="card-content">
						<div class="media">
							<div class="media-left">
							</div>
							<div class="media-content">
								<div class="title_line">
									<p class="title is-4 recipe_name"><?=$recipe['TITLE']?></p>
									<p class="likes"><?=$recipe['LIKES_COUNT']?> ❤</p>
								</div>
								<p class="subtitle is-6"><?=TemplateService::truncate($recipe['DESCRIPTION'], 50)?></p>
							</div>
						</div>
						<div class="content">
							<div class="columns">
								<div class="column has-text-weight-bold">Калории</div>
								<div class="column has-text-weight-bold">Белки / Жиры / Углеводы</div>
								<div class="column has-text-weight-bold">Время приготовления</div>
							</div>
							<div class="columns">
								<div class="column"><?=$recipe['CALORIES']?> </div>
								<div class="column"><?=$recipe['PROTEINS']?> / <?=$recipe['FATS']?> / <?=$recipe['CARBS']?></div>
								<div class="column"><?=$recipe['TIME']?> мин</div>
							</div>
						</div>
					</div>
					</a>
				</div>
            <?php endforeach;?>
		</div>
		<div class="column right_col is-one-quarter is-offset-1">
			<div class="daily_recipe">
				<form action="/create/" method="get" class="create_btn">
					<button class="button is-success" id="add_recipe_btn">Добавить рецепт</button>
				</form>
				<p class="title is-4 has-text-centered">Рецепт дня</p>
				<a href="/detail/<?=$dailyRecipe['ID']?>/" id="daily_recipe_card">
					<div class="card">
						<img
							<?php if (isset($dailyRecipe['IMAGE'])):?>
								src="<?=$dailyRecipe['IMAGE']?>"
							<?php else:?>
								src="<?=$arParams['IMAGE']?>"
							<?php endif?>
							alt="https://bulma.io/assets/images/placeholders/1280x960.png"
						/>
						<div class="card-content">
							<p class="title is-4"><?=$dailyRecipe['TITLE']?></p>
						</div>
					</div>
				</a>
				<div class="daily_recipe">
					<p class="title is-4 has-text-centered plan_title">Ваши рецепты на сегодня</p>
					<div class="card">
						<div class="card-content">
							<?php if (!empty($arResult['PLANNER_RECIPES'])):?>
								<?php foreach($arResult['PLANNER_RECIPES'] as $recipe):?>
									<p class="title is-4"><?= $recipe['COURSE_NAME']?>:</p> <p class="title is-6"><a
											href="/detail/<?=$recipe['RECIPE_ID']?>/"><?= $recipe['RECIPE_NAME']?></a><br></p>
								<?php endforeach;?>
							<?php else:?>
								<p class="title is-4">У вас нет рецептов на сегодня</p>
							<?php endif?>
						</div>
					</div>
				</div>
				<form action="/planner/" method="get" class="create_btn">
					<button class="button is-success" id="planner_btn">Планировщик</button>
				</form>
			</div>

		</div>
	</div>
    <?php if(!($arResult['PAGES'][0]==1 && $arResult['PAGE']<2)):?>
    <a id="prev_page_btn" href="/?page=<?=$arResult['PAGES'][0]?>">НАЗАД</a>
    <?php endif;?>
    <?php if($arResult['PAGE']<$arResult['PAGES'][1]):?>
    <a id="next_page_btn" href="/?page=<?=$arResult['PAGES'][1]?>">ВПЕРЕД</a>
    <?php endif;?>
</div>
