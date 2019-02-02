<?
/** 
 *  getResultRunBitrixStaticFunctionToCache - функция выполнит статичный метод класса битрикс
 *  	и закэширует результат. (название класса, метода и параметров нужно передать)
 * 	цель - не создавать много методов для получения всякого и кэширование полученного а использовать эту функцию.
 *  @param $Class - класс
 *  @param $function - метод
 *  @param $property - параметры метода
 *  @param int $cacheTime время кэширования (может быть false), 24ч. по умолчанию
 *	@return array
 */
function getResultRunBitrixStaticFunctionToCache($Class, $function, $property, $cacheTime = 86400   ){
	$result = false;
	$isFalseCacheTime = ($cacheTime === false);
	
	if (!$isFalseCacheTime){
		$cacheID = 'getResultRunBitrixStaticFunctionToCache_'.$Class.'_'.$function.'_'.serialize($property);
		$obCache = new CPHPCache();
		$cache_url = '/iblock/getResultRunBitrixStaticFunctionToCache';
	}

	if (!$isFalseCacheTime && $obCache->InitCache($cacheTime, $cacheID, $cache_url)) { 
		$result = $obCache->GetVars();
	} else{
		$res = call_user_func_array( "$Class::$function", $property );

		if ($res !== false) {
			while($arRes = $res->Fetch()){
				$result[] = $arRes;
			}
		} 

		if (!$isFalseCacheTime){
			$obCache->StartDataCache();
			$obCache->EndDataCache($result);
		}
	}
	return $result;
}

/* привер использования 
$res = getResultRunBitrixStaticFunctionToCache(
	'CIBlockElement',
	'GetList',
	array(
		array('id'=>'desc'), 
		array('IBLOCK_ID'=> 3), 
		false,
		false,
		array('NAME')
	), 
	false // без кеша // 86400 - кеш на 24ч.
);
*/

?>