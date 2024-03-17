<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\Authentication\\RegisterController'], null, ['POST' => 0], null, false, false, null]],
        '/api/expenses' => [
            [['_route' => 'api_expenses_create', '_controller' => 'App\\Controller\\Expense\\CreateExpenseController'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'api_expenses_list', '_controller' => 'App\\Controller\\Expense\\ListExpenseController'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/expenses-categories' => [[['_route' => 'api_expenses_categories_list', '_controller' => 'App\\Controller\\ExpenseCategory\\ListExpenseCategoryController'], null, ['GET' => 0], null, false, false, null]],
        '/api/incomes' => [
            [['_route' => 'api_incomes_create', '_controller' => 'App\\Controller\\Income\\CreateIncomeController'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'api_incomes_list', '_controller' => 'App\\Controller\\Income\\ListIncomeController'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/trackings' => [
            [['_route' => 'api_trackings_create', '_controller' => 'App\\Controller\\Tracking\\CreateTrackingController'], null, ['POST' => 0], null, false, false, null],
            [['_route' => 'api_trackings_list', '_controller' => 'App\\Controller\\Tracking\\ListTrackingController'], null, ['GET' => 0], null, false, false, null],
        ],
        '/api/users/me' => [[['_route' => 'api_users_get', '_controller' => 'App\\Controller\\User\\GetUserController'], null, ['GET' => 0], null, false, false, null]],
        '/api/doc' => [[['_route' => 'api.swagger_ui', '_controller' => 'nelmio_api_doc.controller.swagger_ui'], null, ['GET' => 0], null, false, false, null]],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/build/(.+)(*:18)'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:56)'
                    .'|wdt/([^/]++)(*:75)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:116)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:153)'
                                .'|router(*:167)'
                                .'|exception(?'
                                    .'|(*:187)'
                                    .'|\\.css(*:200)'
                                .')'
                            .')'
                            .'|(*:210)'
                        .')'
                    .')'
                .')'
                .'|/api/(?'
                    .'|expenses(?'
                        .'|/([^/]++)(?'
                            .'|(*:252)'
                        .')'
                        .'|\\-categories/([^/]++)(?'
                            .'|(*:285)'
                        .')'
                    .')'
                    .'|incomes/([^/]++)(?'
                        .'|(*:314)'
                    .')'
                    .'|trackings/([^/]++)(?'
                        .'|(*:344)'
                    .')'
                .')'
                .'|/(.+)?(*:360)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        18 => [[['_route' => 'pentatrion_vite_build_proxy', '_controller' => 'Pentatrion\\ViteBundle\\Controller\\ViteController::proxyBuild'], ['path'], null, null, false, true, null]],
        56 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        75 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        116 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        153 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        167 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        187 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        200 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        210 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        252 => [
            [['_route' => 'api_expenses_delete', '_controller' => 'App\\Controller\\Expense\\DeleteExpenseController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_expenses_get', '_controller' => 'App\\Controller\\Expense\\GetExpenseController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_expenses_update', '_controller' => 'App\\Controller\\Expense\\UpdateExpenseController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        285 => [
            [['_route' => 'api_expenses_categories_get', '_controller' => 'App\\Controller\\ExpenseCategory\\GetExpenseCategoryController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_expenses_categories_update', '_controller' => 'App\\Controller\\ExpenseCategory\\UpdateExpenseCategoryController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        314 => [
            [['_route' => 'api_incomes_delete', '_controller' => 'App\\Controller\\Income\\DeleteIncomeController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_incomes_get', '_controller' => 'App\\Controller\\Income\\GetIncomeController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_incomes_update', '_controller' => 'App\\Controller\\Income\\UpdateIncomeController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        344 => [
            [['_route' => 'api_trackings_delete', '_controller' => 'App\\Controller\\Tracking\\DeleteTrackingController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_trackings_get', '_controller' => 'App\\Controller\\Tracking\\GetTrackingController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_trackings_update', '_controller' => 'App\\Controller\\Tracking\\UpdateTrackingController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        360 => [
            [['_route' => 'app_default', 'path' => null, 'template' => 'base.html.twig', '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController'], ['path'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
