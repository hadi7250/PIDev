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
        '/dashboard' => [[['_route' => 'dashboard_index', '_controller' => 'App\\Controller\\DashboardController::dashboardIndex'], null, null, null, false, false, null]],
        '/dashboard/users/add' => [
            [['_route' => 'dashboard_users_add', '_controller' => 'App\\Controller\\DashboardController::usersAdd'], null, null, null, false, false, null],
            [['_route' => 'user_add', '_controller' => 'App\\Controller\\DashboardController::userAdd'], null, null, null, false, false, null],
        ],
        '/dashboard/users/create' => [[['_route' => 'dashboard_users_create', '_controller' => 'App\\Controller\\DashboardController::createUser'], null, ['POST' => 0], null, false, false, null]],
        '/dashboard/widgets/data' => [[['_route' => 'dashboard_widgets_data', '_controller' => 'App\\Controller\\DashboardController::widgetsData'], null, null, null, false, false, null]],
        '/dashboard/widgets/static' => [[['_route' => 'dashboard_widgets_static', '_controller' => 'App\\Controller\\DashboardController::widgetsStatic'], null, null, null, false, false, null]],
        '/dashboard/add/quizz' => [[['_route' => 'add_quizz', '_controller' => 'App\\Controller\\DashboardController::addQuizz'], null, null, null, false, false, null]],
        '/dashboard/add/course' => [[['_route' => 'add_course', '_controller' => 'App\\Controller\\DashboardController::addCourse'], null, null, null, false, false, null]],
        '/dashboard/add/event' => [[['_route' => 'add_event', '_controller' => 'App\\Controller\\DashboardController::addEvent'], null, null, null, false, false, null]],
        '/dashboard/add/post' => [[['_route' => 'add_post', '_controller' => 'App\\Controller\\DashboardController::addPost'], null, null, null, false, false, null]],
        '/dashboard/orders' => [[['_route' => 'ecommerce_orders', '_controller' => 'App\\Controller\\DashboardController::orders'], null, null, null, false, false, null]],
        '/dashboard/users/datatable' => [[['_route' => 'user_datatable', '_controller' => 'App\\Controller\\DashboardController::userDatatable'], null, null, null, false, false, null]],
        '/dashboard/events/datatable' => [[['_route' => 'event_datatable', '_controller' => 'App\\Controller\\DashboardController::eventDatatable'], null, null, null, false, false, null]],
        '/dashboard/feeds/datatable' => [[['_route' => 'feed_datatable', '_controller' => 'App\\Controller\\DashboardController::feedDatatable'], null, null, null, false, false, null]],
        '/dashboard/courses/datatable' => [[['_route' => 'course_datatable', '_controller' => 'App\\Controller\\DashboardController::courseDatatable'], null, null, null, false, false, null]],
        '/dashboard/quizzes/datatable' => [[['_route' => 'quizz_datatable', '_controller' => 'App\\Controller\\DashboardController::quizzDatatable'], null, null, null, false, false, null]],
        '/dashboard/reclamations/datatable' => [[['_route' => 'reclamation_datatable', '_controller' => 'App\\Controller\\DashboardController::reclamationDatatable'], null, null, null, false, false, null]],
        '/dashboard/charts/apex' => [[['_route' => 'charts_apex_chart', '_controller' => 'App\\Controller\\DashboardController::chartsApex'], null, null, null, false, false, null]],
        '/dashboard/charts/chartjs' => [[['_route' => 'charts_chartjs', '_controller' => 'App\\Controller\\DashboardController::chartsChartjs'], null, null, null, false, false, null]],
        '/dashboard/maps/google' => [[['_route' => 'map_google_maps', '_controller' => 'App\\Controller\\DashboardController::mapGoogle'], null, null, null, false, false, null]],
        '/dashboard/maps/vector' => [[['_route' => 'map_vector_maps', '_controller' => 'App\\Controller\\DashboardController::mapVector'], null, null, null, false, false, null]],
        '/admin/dashboard' => [[['_route' => 'admin_dashboard', '_controller' => 'App\\Controller\\DashboardController::adminDashboard'], null, null, null, false, false, null]],
        '/admin/users' => [[['_route' => 'admin_users', '_controller' => 'App\\Controller\\DashboardController::adminUsers'], null, null, null, false, false, null]],
        '/admin/users/add' => [[['_route' => 'admin_users_add', '_controller' => 'App\\Controller\\DashboardController::addUser'], null, null, null, false, false, null]],
        '/dashboard/users/roles' => [[['_route' => 'user_roles', '_controller' => 'App\\Controller\\DashboardController::userRoles'], null, null, null, false, false, null]],
        '/dashboard/courses/categories' => [[['_route' => 'course_categories', '_controller' => 'App\\Controller\\DashboardController::courseCategories'], null, null, null, false, false, null]],
        '/dashboard/quizzes/results' => [[['_route' => 'quizz_results', '_controller' => 'App\\Controller\\DashboardController::quizzResults'], null, null, null, false, false, null]],
        '/dashboard/support/tickets' => [[['_route' => 'support_tickets', '_controller' => 'App\\Controller\\DashboardController::supportTickets'], null, null, null, false, false, null]],
        '/dashboard/support/faq' => [[['_route' => 'faq_management', '_controller' => 'App\\Controller\\DashboardController::faqManagement'], null, null, null, false, false, null]],
        '/dashboard/reports/sales' => [[['_route' => 'reports_sales', '_controller' => 'App\\Controller\\DashboardController::reportsSales'], null, null, null, false, false, null]],
        '/dashboard/reports/users' => [[['_route' => 'reports_users', '_controller' => 'App\\Controller\\DashboardController::reportsUsers'], null, null, null, false, false, null]],
        '/dashboard/reports/courses' => [[['_route' => 'reports_courses', '_controller' => 'App\\Controller\\DashboardController::reportsCourses'], null, null, null, false, false, null]],
        '/dashboard/settings/general' => [[['_route' => 'settings_general', '_controller' => 'App\\Controller\\DashboardController::settingsGeneral'], null, null, null, false, false, null]],
        '/dashboard/settings/security' => [[['_route' => 'settings_security', '_controller' => 'App\\Controller\\DashboardController::settingsSecurity'], null, null, null, false, false, null]],
        '/dashboard/settings/notifications' => [[['_route' => 'settings_notifications', '_controller' => 'App\\Controller\\DashboardController::settingsNotifications'], null, null, null, false, false, null]],
        '/dashboard/settings/theme' => [[['_route' => 'settings_theme', '_controller' => 'App\\Controller\\DashboardController::settingsTheme'], null, null, null, false, false, null]],
        '/dashboard/products' => [[['_route' => 'ecommerce_products', '_controller' => 'App\\Controller\\DashboardController::ecommerceProducts'], null, null, null, false, false, null]],
        '/dashboard/customers' => [[['_route' => 'dashboard_customers', '_controller' => 'App\\Controller\\DashboardController::ecommerceCustomers'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'app_homepage', '_controller' => 'App\\Controller\\PageController::index'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/forgot-password' => [[['_route' => 'app_forgot_password', '_controller' => 'App\\Controller\\SecurityController::forgotPassword'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'app_register', '_controller' => 'App\\Controller\\SecurityController::register'], null, null, null, false, false, null]],
        '/app/emailbox' => [[['_route' => 'app_emailbox', '_controller' => 'App\\Controller\\SecurityController::emailbox'], null, null, null, false, false, null]],
        '/app/emailread' => [[['_route' => 'app_emailread', '_controller' => 'App\\Controller\\SecurityController::emailread'], null, null, null, false, false, null]],
        '/app/chat_box' => [[['_route' => 'app_chat_box', '_controller' => 'App\\Controller\\SecurityController::chatBox'], null, null, null, false, false, null]],
        '/test-login-page' => [[['_route' => 'test_login_page', '_controller' => 'App\\Controller\\TestController::testLogin'], null, null, null, false, false, null]],
        '/create-admin' => [[['_route' => 'app_create_admin', '_controller' => 'App\\Controller\\SecurityController::createAdmin'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/dashboard/users/(?'
                    .'|update/([^/]++)(*:237)'
                    .'|delete/([^/]++)(*:260)'
                .')'
                .'|/admin/users/edit/([^/]++)(*:295)'
                .'|/reset\\-password/([^/]++)(*:328)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        237 => [[['_route' => 'dashboard_users_update', '_controller' => 'App\\Controller\\DashboardController::updateUser'], ['id'], ['POST' => 0], null, false, true, null]],
        260 => [[['_route' => 'dashboard_users_delete', '_controller' => 'App\\Controller\\DashboardController::deleteUser'], ['id'], ['POST' => 0], null, false, true, null]],
        295 => [[['_route' => 'admin_users_edit', '_controller' => 'App\\Controller\\DashboardController::editUser'], ['id'], null, null, false, true, null]],
        328 => [
            [['_route' => 'app_reset_password', '_controller' => 'App\\Controller\\SecurityController::resetPassword'], ['token'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
