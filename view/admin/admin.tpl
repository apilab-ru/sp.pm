{strip}
    <!DOCTYPE html>
    <html class="no-js">
        <head>
            <title>Управление Интсабуком</title>
            <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
            <link href="/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
            <link href="/assets/assets/styles.css?" rel="stylesheet" media="screen">
            <link href="/assets/vendors/chosen.min.css" rel="stylesheet" media="screen">
            <link href="/assets/project/admin.css?" rel="stylesheet" media="screen">
            <link href="/assets/assets/dropzone.css" rel="stylesheet">
            <link href="/assets/angular/ng-dropzone.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <a class="brand" href="#"><b>Интсабук</b></a>
                        <div class="nav-collapse collapse">
                            <ul class="nav pull-right">
                                <li class="dropdown">
                                    <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-user"></i> {$user.login} <i class="caret"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a tabindex="-1" href="/auth/out/">Выйти из системы</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="nav">
                                <li class="active">
                                    <a href="/page/main/">Главная</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row-fluid">
                    <div class="span3" id="sidebar">
                        <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
                            {foreach from=$menu key=link item=item}
                                <li class="top {if $item.check}active{/if}">
                                    <a href="{$link}"><i class="icon-chevron-right"></i> {$item.name}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>

                    <div class="span9" id="content">
                        <div class="row-fluid">
                            
                            <div class="navbar">
                                <div class="navbar-inner">
                                    <ul class="breadcrumb">
                                        <i class="icon-chevron-left hide-sidebar"><a title="Скрыть меню" rel='tooltip'>&nbsp;</a></i>
                                        <i class="icon-chevron-right show-sidebar" style="display:none;"><a title="Открыть меню" rel='tooltip'>&nbsp;</a></i>
                                        <span class="navBox">
                                            {*include file='app:page/navi'*}
                                        </span>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mainContent">
                            <div ng-app="adminContent">
                                <div ng-view></div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <footer>
                    <p>&copy; Инстабук {$smarty.now|date:"Y"}</p>
                </footer>
            </div>
            <script src="/assets/jqary/jquery-3.2.1.min.js"></script>
            <script src="/assets/jqary/jquery-ui.min.js"></script>
            <script src="/assets/assets/dropzone.js"></script>
            <script src="/assets/angular/angular.min.1.4.9.js"></script>
            <script src="/assets/angular/angular-route.min.1.2.1.js"></script>
            <script src="/assets/angular/angular-resource.min.1.2.1.js"></script>
            <script src="/assets/angular/sortable.js"></script>
            <script src="/assets/angular/ng-dropzone.min.js"></script>
            <script src="/assets/angular/angular-dnd.min.js"></script>
            <script src="/assets/project/admin.js?6"></script>
            <script async defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>
        </body>
    </html>
{/strip}