<!DOCTYPE html>
<html class="no-js">
    <head>
        <title>Управление Совместными закупками</title>
        <link href="/build/admin.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="/build/events.js"></script>
    </head>
    <body ng-app="adminContent">
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="/admin/"><b>Бутичок</b></a>
                    <div class="nav-collapse collapse" ng-controller="MainUserController">
                        <ul class="nav pull-right">
                            <li class="dropdown js-toggle-open">
                                <a class="cursor" role="button" class="dropdown-toggle" >
                                    <i class="icon-user"></i> {$user.surname} {$user.name}  <i class="caret"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a tabindex="-1" href="/auth/out/">Выйти из системы</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li {*class="active"*}>
                                <a href="/">Главная</a>
                            </li>
                            <li class="active">
                                <a href="/admin/">Админпанель</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid admin-box" >
            <div class="row-fluid admin-content">
                <div class="span2" id="sidebar">
                    <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse js-navigation">
                        {foreach from=$menu key=class item=sect}
                            <li class="top {if $sect.check}active{/if}">
                                <a {if !$sect.list}href="/admin/{if $class}{$class}{/if}"{/if}>
                                    <i class="icon-chevron-right"></i> {$sect.name}
                                </a>
                                {if $sect.list}
                                <ul class="in-nav">
                                    {foreach from=$sect.list item=item key=method}
                                        <li {if $item.check}class='active'{/if}>
                                            <a href="/admin/{$class}/{$method}"> {$item.name}</a>
                                        </li>
                                    {/foreach}
                                </ul>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>
                <div class="span10" id="content">
                    <div class="row-fluid">
                        <div class="navbar" >
                            <div class="navbar-inner">
                                <ul class="breadcrumb">
                                    <i class="icon-chevron-left hide-sidebar"><a title="Скрыть меню" rel='tooltip'>&nbsp;</a></i>
                                    <i class="icon-chevron-right show-sidebar" style="display:none;"><a title="Открыть меню" rel='tooltip'>&nbsp;</a></i>
                                    <span class="navBox">
                                        {*<span ng-repeat="item in current" class="item">
                                            {$item.name} <span ng-show="item.checked == false" class="divider">/</span>
                                        </span>*}
                                        {include file='app:admin/navi'}
                                    </span>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mainContent">
                        {$html}
                    </div>
                </div>
            </div>
            <footer>
                <hr>
                <p>&copy; Бутичок {$smarty.now|date:"Y"}</p>
            </footer>
        </div>
        {*<script src="/build/angular.js"></script>*}
        <script src='/build/jquery-ui.js'></script>
        <script src='/vendor/chosen/chosen.jquery.js'></script>
        <script src="/vendor/ckeditor/ckeditor.js"></script>
        <script src="{'/build/admin.js'|checkVersion}"></script>
        {*<script>
            admin.setStorage({$data|json_encode}); 
        </script>*}
    </body>
</html>