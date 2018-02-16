
/* формат вывода числа */
function number_format(_number, _cfg){
  function obj_merge(obj_first, obj_second){
    var obj_return = {};
    for (key in obj_first){
      if (typeof obj_second[key] !== 'undefined') obj_return[key] = obj_second[key];
      else obj_return[key] = obj_first[key];
      }
    return obj_return;
  }
  function thousands_sep(_num, _sep){
    if (_num.length <= 3) return _num;
    var _count = _num.length;
    var _num_parser = '';
    var _count_digits = 0;
    for (var _p = (_count - 1); _p >= 0; _p--){
      var _num_digit = _num.substr(_p, 1);
      if (_count_digits % 3 == 0 && _count_digits != 0 && !isNaN(parseFloat(_num_digit))) _num_parser = _sep + _num_parser;
      _num_parser = _num_digit + _num_parser;
      _count_digits++;
      }
    return _num_parser;
  }
  if (typeof _number !== 'number'){
    _number = parseFloat(_number);
    if (isNaN(_number)) return false;
  }
  var _cfg_default = {before: '', after: '', decimals: 2, dec_point: '.', thousands_sep: ','};
  if (_cfg && typeof _cfg === 'object'){
    _cfg = obj_merge(_cfg_default, _cfg);
  }
  else _cfg = _cfg_default;
  _number = _number.toFixed(_cfg.decimals);
  if(_number.indexOf('.') != -1){
    var _number_arr = _number.split('.');
    var _number = thousands_sep(_number_arr[0], _cfg.thousands_sep) + _cfg.dec_point + _number_arr[1];
  }
  else var _number = thousands_sep(_number, _cfg.thousands_sep);
  return _cfg.before + _number + _cfg.after;
}

/************************/
/* ресайз главного меню */
/************************/
function resizeMainMenu()
{
	var ulMM = $('ul.menuMain');
	var ulMMLi = $('ul.menuMain li')
	var ulMMBoxW = ulMM.width();
	var a = 0;
	$(ulMMLi).each(function()
	{
		a += $(this).width();		
	})
	var freeSpace = (ulMMBoxW - a);
	var mr = freeSpace /  ($('ul.menuMain li').length);
	$('ul.menuMain li').not('.lastLi, .prelastLi').css(
	{
		'margin-right' : Math.ceil(mr) + 1
	})	
}
/*************************/
/* /ресайз главного меню */
/*************************/

/***********************************/
/* получить значение свойства в px */
/***********************************/
function getRealSize(value)
{
	if(value != undefined)
		value = value.replace("px", "") * 1;
	return value;
}
/************************************/
/* /получить значение свойства в px */
/************************************/


/*****************************************/
/* функции, выполняемые при ресайзе окна */
/*****************************************/
function onResize()
{
	resizeMainMenu()
}
/******************************************/
/* /функции, выполняемые при ресайзе окна */
/******************************************/


$(document).ready(function()
{
	// ресайз при изменении размера окна
	onResize();
	
	var resizeTimer = null;
	$(window).bind('resize', function() 
	{
		if (resizeTimer) clearTimeout(resizeTimer);
		resizeTimer = setTimeout(onResize, 100);
	});
	/* /ресайз страницы */	
})