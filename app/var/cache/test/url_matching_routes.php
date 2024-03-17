<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
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
                .'|/api/(?'
                    .'|expenses(?'
                        .'|/([^/]++)(?'
                            .'|(*:38)'
                        .')'
                        .'|\\-categories/([^/]++)(?'
                            .'|(*:70)'
                        .')'
                    .')'
                    .'|incomes/([^/]++)(?'
                        .'|(*:98)'
                    .')'
                    .'|trackings/([^/]++)(?'
                        .'|(*:127)'
                    .')'
                .')'
                .'|/(.+)?(*:143)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [
            [['_route' => 'api_expenses_delete', '_controller' => 'App\\Controller\\Expense\\DeleteExpenseController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_expenses_get', '_controller' => 'App\\Controller\\Expense\\GetExpenseController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_expenses_update', '_controller' => 'App\\Controller\\Expense\\UpdateExpenseController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        70 => [
            [['_route' => 'api_expenses_categories_get', '_controller' => 'App\\Controller\\ExpenseCategory\\GetExpenseCategoryController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_expenses_categories_update', '_controller' => 'App\\Controller\\ExpenseCategory\\UpdateExpenseCategoryController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        98 => [
            [['_route' => 'api_incomes_delete', '_controller' => 'App\\Controller\\Income\\DeleteIncomeController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_incomes_get', '_controller' => 'App\\Controller\\Income\\GetIncomeController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_incomes_update', '_controller' => 'App\\Controller\\Income\\UpdateIncomeController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        127 => [
            [['_route' => 'api_trackings_delete', '_controller' => 'App\\Controller\\Tracking\\DeleteTrackingController'], ['id'], ['DELETE' => 0], null, false, true, null],
            [['_route' => 'api_trackings_get', '_controller' => 'App\\Controller\\Tracking\\GetTrackingController'], ['id'], ['GET' => 0], null, false, true, null],
            [['_route' => 'api_trackings_update', '_controller' => 'App\\Controller\\Tracking\\UpdateTrackingController'], ['id'], ['PUT' => 0], null, false, true, null],
        ],
        143 => [
            [['_route' => 'app_default', 'path' => null, 'template' => 'base.html.twig', '_controller' => 'Symfony\\Bundle\\FrameworkBundle\\Controller\\TemplateController'], ['path'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
