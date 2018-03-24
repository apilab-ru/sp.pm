{strip}
    <div class="flex-line catalog__box">
        <form class="catalog__filter">
            <div class="catalog__title"> Поиск закупок и акций </div>
            <div class="catalog__filter-box">
                <h2 class="catalog__podtitle"> Категории: </h2>
                <div class="catalog__list-filter">
                    {foreach from=$cats item=item}
                    <label>
                        <input name="cat[]" value="{$item.id}" type="radio" class="checkbox__hidden" {if $check && $item.link == $check}checked{/if}/>
                        <span class="main-info__link-item main-info__link checkbox__button">
                            {$item.name}
                        </span>
                    </label>
                    {/foreach}
                </div>
                <h2 class="catalog__podtitle"> Акции: </h2>
                <div class="catalog__list-filter">
                    {foreach from=$discount item=item}
                    <label>
                        <input name="discount[]" value="{$item.id}" type="checkbox" class="checkbox__hidden"/>
                        <span class="main-info__link-item main-info__link checkbox__button">
                            {$item.name}
                        </span>
                    </label>
                    {/foreach}
                </div>
                <h2 class="catalog__podtitle"> Статус закупки: </h2>
                <div class="catalog__list-filter">
                    <div class="flex-line">
                        <label class="catalog__filter-start">
                            <input name="status[start]" value="1" type="checkbox" class="checkbox__hidden" checked/>
                            <span class="main-info__link-item main-info__link checkbox__button">
                                Старт
                            </span>
                        </label>
                        <label class="catalog__filter-end">
                            <input name="status[stop]" value="1" type="checkbox" class="checkbox__hidden" />
                            <span class="main-info__link-item main-info__link checkbox__button">
                                Скоро стоп
                            </span>
                        </label>
                    </div>
                    <label>
                        <input name="status[reorder]" value="1" type="checkbox" class="checkbox__hidden"/>
                        <span class="main-info__link-item main-info__link checkbox__button">
                            Дозаказ
                        </span>
                    </label>
                </div>
                <h2 class="catalog__podtitle"> Теги: </h2>
                <div class="catalog__list-filter">
                    {foreach from=$tags item=item}
                    <label>
                        <input name="tag[]" value="{$item.id}" type="checkbox" class="checkbox__hidden"/>
                        <span class="main-info__link-item main-info__link checkbox__button">
                            {$item.name}
                        </span>
                    </label>
                    {/foreach}
                </div>
                <h2 class="catalog__podtitle"> Сортировка по: </h2>
                <div class="catalog__list-filter">
                    <div class="flex-line">
                        <label class="catalog__filter-start">
                            <input name="order_type" value="ASC" type="radio" class="checkbox__hidden"/>
                            <span class="main-info__link-item main-info__link checkbox__button">
                                возрастанию
                            </span>
                        </label>
                        <label class="catalog__filter-end">
                            <input name="order_type" value="DESC" type="radio" class="checkbox__hidden" checked/>
                            <span class="main-info__link-item main-info__link checkbox__button">
                                убыванию
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        <div class="catalog__content-box">
            <div class='catalog__content-border'>
                <div class="catalog__title">
                    <span>Закупки</span>
                    <div class='catalog__limiter'>
                        <span class=''> Выводить по: </span>
                        <select class='js-limit catalog__limit-select' name='limit'>
                            <option value='10' checked>10</option>
                            <option value='20'>20</option>
                            <option value='30'>30</option>
                            <option value='50'>50</option>
                        </select>
                    </div>
                </div>
                <div class="js-catalog-content catalog__content"></div>
            </div>
            <div class='js-pagination'></div>
        </div>
    </div>
    <script>
        window.initCatalog = 1;
        if( 'catalog' in window ){
            catalog.init();
        }
    </script>
{/strip}