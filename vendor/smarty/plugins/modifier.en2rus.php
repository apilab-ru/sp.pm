<?php
function smarty_modifier_en2rus($string)
{
	switch ($string) {
		case 'Monday': return 'Понедельник';
		case 'Tuesday': return 'Вторник';
		case 'Wednesday': return 'Среда';
		case 'Thursday': return 'Четверг';
		case 'Friday': return 'Пятница';
		case 'Saturday': return 'Суббота';
		case 'Sunday': return 'Воскресенье';

		case '01': return 'января';
		case '02': return 'февраля';
		case '03': return 'марта';
		case '04': return 'апреля';
		case '05': return 'мая';
		case '06': return 'июня';
		case '07': return 'июля';
		case '08': return 'августа';
		case '09': return 'сентября';
		case '10': return 'октября';
		case '11': return 'ноября';
		case '12': return 'декабря';	

		case 'January': return 'Январь';
		case 'February': return 'Февраль';
		case 'March': return 'Март';
		case 'April': return 'Апрель';
		case 'May': return 'Май';
		case 'June': return 'Июнь';
		case 'July': return 'Июль';
		case 'August': return 'Август';
		case 'September': return 'Сентябрь';
		case 'October': return 'Октябрь';
		case 'November': return 'Ноябрь';
		case 'December': return 'Декабрь';			
	}
}
?>