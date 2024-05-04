<?php

namespace Up\Yummy\Repository;

use CFile,
	Up\Yummy\Model\ImagesTable;

class ImageRepository
{
	public static function validateImage(): false|int|string
	{
		$file = $_FILES['IMAGES'];

		$maxSize = 2 * 1024 * 1024;
		$error = CFile::CheckImageFile($file, $maxSize);

		if ($error != '')
		{
			die('Ошибка загрузки файла: ' . $error);
		}

		return CFile::SaveFile($file, 'RecipeImages', true);
	}

	public static function getRecipeCover($recipeID): ?string
	{
		$image = ImagesTable::query()->setSelect(['PATH'])->setFilter(['RECIPE_ID' => $recipeID])->fetch();
		if ($image !== null)
		{
			return CFile::GetPath($image['PATH']);
		}
		return null;
	}

	public static function updateRecipeImage($recipeId, $photoStatus): void
	{
		if ($photoStatus == 0)
		{
			if ($_FILES['IMAGES']['name'] !== "")
			{
				$imageId = self::validateImage();
				$id = ImagesTable::query()->setSelect(['ID'])->setFilter(['IS_COVER' => 1, 'RECIPE_ID' => $recipeId])->fetch()['ID'];
				if (isset($id))
				{
					ImagesTable::update($id, ['PATH' => $imageId]);
				}
				else
				{
					ImagesTable::add(['PATH' => $imageId, 'RECIPE_ID' => $recipeId, 'IS_COVER' => 1]);
				}
			}
		}
		else if ($photoStatus == 1)
		{
			ImageRepository::deleteImageRecipe($recipeId);
		}
	}

	public static function deleteImageRecipe($recipeId): void
	{
		ImagesTable::deleteByFilter(['RECIPE_ID' => $recipeId]);
	}
}