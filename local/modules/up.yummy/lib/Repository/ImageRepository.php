<?php
namespace Up\Yummy\Repository;
use CFile;
use Up\Yummy\Model\ImagesTable;

class ImageRepository
{
  public static function validateImage()
	{
		if (!check_bitrix_sessid())
		{
			die('session expired');
		}

		$file = $_FILES['IMAGES'];

		$maxSize = 2 * 1024 * 1024;
	  $error = CFile::CheckImageFile($file, $maxSize);

	  if ($error != '')
	  {
		  die('uploading error: ' . $error);
	  }

	  $fileId = CFile::SaveFile($file,'RecipeImages',true);

	  return $fileId;
	}
	public static function getRecipeCover($recipeID)
	{
		$image=ImagesTable::query()->setSelect(['PATH'])->setFilter(['RECIPE_ID'=>$recipeID])->fetch();
		if($image!==null)
		{
			$path = CFile::GetPath($image['PATH']);
			return $path;
		}
		return null;
	}
	public static function updateRecipeImage($recipeID):void
	{
		if($_FILES['IMAGES']['name']!=="")
		{
			$imageID=self::validateImage();
			$id=ImagesTable::query()->setSelect(['ID'])->setFilter(['IS_COVER'=>1,'RECIPE_ID'=>$recipeID])->fetch()['ID'];
			if(isset($id))
			{
				ImagesTable::update($id,['PATH'=>$imageID]);
			}
			else
			{
				ImagesTable::add(['PATH' => $imageID, 'RECIPE_ID' => $recipeID, 'IS_COVER' => 1]);
			}
		}
	}
}