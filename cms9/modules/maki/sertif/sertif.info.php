<fieldset class="note"><legend>Пояснения</legend>
<ul>
	<li>Поле "Название блока" - произвольное название, например назначение инфоблока;</li>
	<li>"Метка блока" - строка вида <strong>:::iblock_sample:::</strong>, применяется для указания места в шаблоне страницы, где будет размещен инфоблок;</li>
	<li>"Шаблон блока" - если к инфоблоку должен применяться шаблон, то его можно выбрать из списка. Для этого в шаблоне должна быть метка <strong>:::iblock_text:::</strong>. Вместо этой метки в шаблон подставляется код инфоблока и только после этого происходит вставка инфоблока в код страницы;</li>
	<li>"Показывать блок" - показать инфоблок на странице. Если флажок снят, то метка блока просто удаляется из кода страницы;</li>
	<li>"Вырезать все HTML-теги" - из html-кода инфоблока будут удалены все теги, кроме указанных. Это делается для исключения некорректного форматирования кода инфоблока за счет добавления html-редактором избыточных тегов (в частности <?=htmlspecialchars("<p></p>")?> );</li>
	<li>"Текст блока" - html-редактор инфоблока</li>
</ul>
</fieldset>