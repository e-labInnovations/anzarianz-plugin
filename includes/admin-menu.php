<?php

require_once  __DIR__ . '/leave-page.php';
require_once  __DIR__ . '/food-timetable-page.php';
require_once  __DIR__ . '/food-timetable-settings-page.php';
require_once  __DIR__ . '/food-timetable-add-new-page.php';

//Add Admin Menu
add_action('admin_menu', 'anzarianz_leave_menu');
function anzarianz_leave_menu() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'anzarianz_leaves';
    $leaves_query = "SELECT * FROM $table_name WHERE status = 'added'";
    $query_results = $wpdb->get_results( $leaves_query, ARRAY_A  );
    $notification_count = count($query_results);

    add_menu_page(
        'Leaves',
        $notification_count ? sprintf( 'Leaves <span class="awaiting-mod">%d</span>', $notification_count ) : 'Leaves',
        'manage_options',
        'anzarianz_leave',
        'leavesHTML',
        'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpb25pY29uIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+CiAgICA8dGl0bGU+Q2FsZW5kYXI8L3RpdGxlPgogICAgPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTQ4MCAxMjhhNjQgNjQgMCAwMC02NC02NGgtMTZWNDguNDVjMC04LjYxLTYuNjItMTYtMTUuMjMtMTYuNDNBMTYgMTYgMCAwMDM2OCA0OHYxNkgxNDRWNDguNDVjMC04LjYxLTYuNjItMTYtMTUuMjMtMTYuNDNBMTYgMTYgMCAwMDExMiA0OHYxNkg5NmE2NCA2NCAwIDAwLTY0IDY0djEyYTQgNCAwIDAwNCA0aDQ0MGE0IDQgMCAwMDQtNHpNMzIgNDE2YTY0IDY0IDAgMDA2NCA2NGgzMjBhNjQgNjQgMCAwMDY0LTY0VjE3OWEzIDMgMCAwMC0zLTNIMzVhMyAzIDAgMDAtMyAzem0zNDQtMjA4YTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0wIDgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0tODAtODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bTAgODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bTAgODBhMjQgMjQgMCAxMS0yNCAyNCAyNCAyNCAwIDAxMjQtMjR6bS04MC04MGEyNCAyNCAwIDExLTI0IDI0IDI0IDI0IDAgMDEyNC0yNHptMCA4MGEyNCAyNCAwIDExLTI0IDI0IDI0IDI0IDAgMDEyNC0yNHptLTgwLTgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0em0wIDgwYTI0IDI0IDAgMTEtMjQgMjQgMjQgMjQgMCAwMTI0LTI0eiIvPgo8L3N2Zz4=',
        4
    );

    add_menu_page(
        'Food Timetable',
        'Food Timetable',
        'manage_options',
        'anzarianz_food_timetable',
        'food_timetable_html',
        'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpb25pY29uIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+CiAgICA8dGl0bGU+RmFzdCBGb29kPC90aXRsZT4KICAgIDxwYXRoIGZpbGw9IiNhN2FhYWQiIGQ9Ik0zNjggMTI4aC4wOU00NzkuNTUgOTZoLTkxLjA2bDguOTItMzUuNjYgMzguMzItMTMuMDVjOC4xNS0yLjc3IDEzLTExLjQzIDEwLjY1LTE5LjcxYTE2IDE2IDAgMDAtMjAuNTQtMTAuNzNsLTQ3IDE2YTE2IDE2IDAgMDAtMTAuMzYgMTEuMjdMMzU1LjUxIDk2SDIyNC40NWMtOC42MSAwLTE2IDYuNjItMTYuNDMgMTUuMjNBMTYgMTYgMCAwMDIyNCAxMjhoMi43NWwxIDguNjZBOC4zIDguMyAwIDAwMjM2IDE0NGMzOSAwIDczLjY2IDEwLjkgMTAwLjEyIDMxLjUyQTEyMS45IDEyMS45IDAgMDEzNzEgMjE4LjA3YTEyMy40IDEyMy40IDAgMDExMC4xMiAyOS41MSA3LjgzIDcuODMgMCAwMDMuMjkgNC44OCA3MiA3MiAwIDAxMjYuMzggODYuNDMgNy45MiA3LjkyIDAgMDAtLjE1IDUuNTNBOTYgOTYgMCAwMTQxNiAzNzZjMCAyMi4zNC03LjYgNDMuNjMtMjEuNCA1OS45NWE4MC4xMiA4MC4xMiAwIDAxLTI4Ljc4IDIxLjY3IDggOCAwIDAwLTQuMjEgNC4zNyAxMDguMTkgMTA4LjE5IDAgMDEtMTcuMzcgMjkuODYgMi41IDIuNSAwIDAwMS45IDQuMTFoNDkuMjFhNDguMjIgNDguMjIgMCAwMDQ3Ljg1LTQ0LjE0TDQ3Ny40IDEyOGgyLjZhMTYgMTYgMCAwMDE2LTE2Ljc3Yy0uNDItOC42MS03Ljg0LTE1LjIzLTE2LjQ1LTE1LjIzeiIvPgogICAgPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTEwOC42OSAzMjBhMjMuODcgMjMuODcgMCAwMTE3IDdsMTUuNTEgMTUuNTFhNCA0IDAgMDA1LjY2IDBMMTYyLjM0IDMyN2EyMy44NyAyMy44NyAwIDAxMTctN2gxOTYuNThhOCA4IDAgMDA4LjA4LTcuOTJWMzEyYTQwLjA3IDQwLjA3IDAgMDAtMzItMzkuMmMtLjgyLTI5LjY5LTEzLTU0LjU0LTM1LjUxLTcyQzI5NS42NyAxODQuNTYgMjY3Ljg1IDE3NiAyMzYgMTc2aC03MmMtNjguMjIgMC0xMTQuNDMgMzguNzctMTE2IDk2LjhBNDAuMDcgNDAuMDcgMCAwMDE2IDMxMmE4IDggMCAwMDggOHpNMTg1Ljk0IDM1MmE4IDggMCAwMC01LjY2IDIuMzRsLTIyLjE0IDIyLjE1YTIwIDIwIDAgMDEtMjguMjggMGwtMjIuMTQtMjIuMTVhOCA4IDAgMDAtNS42Ni0yLjM0aC02OS40YTE1LjkzIDE1LjkzIDAgMDAtMTUuNzYgMTMuMTdBNjUuMjIgNjUuMjIgMCAwMDE2IDM3NmMwIDMwLjU5IDIxLjEzIDU1LjUxIDQ3LjI2IDU2IDIuNDMgMTUuMTIgOC4zMSAyOC43OCAxNy4xNiAzOS40N0M5My41MSA0ODcuMjggMTEyLjU0IDQ5NiAxMzQgNDk2aDEzMmMyMS40NiAwIDQwLjQ5LTguNzIgNTMuNTgtMjQuNTUgOC44NS0xMC42OSAxNC43My0yNC4zNSAxNy4xNi0zOS40NyAyNi4xMy0uNDcgNDcuMjYtMjUuMzkgNDcuMjYtNTZhNjUuMjIgNjUuMjIgMCAwMC0uOS0xMC44M0ExNS45MyAxNS45MyAwIDAwMzY3LjM0IDM1MnoiLz4KPC9zdmc+',
        4
    );

    add_submenu_page(
        "anzarianz_food_timetable",
        "Add New",
        "Add New",
        'manage_options',
        "anzarianz_food_add_new",
        "anzarianz_food_add_new_html"
    );

    add_submenu_page(
        "anzarianz_food_timetable",
        "Settings",
        "Settings",
        'manage_options',
        "anzarianz_food_timetable_settings",
        "anzarianz_food_timetable_settings_html"
    );
}

