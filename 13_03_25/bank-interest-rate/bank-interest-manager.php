<?php
/**
 * Plugin Name: Bank Interest Manager
 * Description: Plugin quản lý lãi suất ngân hàng với tính năng thêm cột động.
 * Version: 1.0
 * Author: Khang
 */

global $bank_interest_db_version;
$bank_interest_db_version = '1.0';

// 1. Tạo bảng cơ sở dữ liệu khi kích hoạt plugin
function create_bank_interest_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bank_interest_rates';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        bank_name varchar(255) NOT NULL,
        one_month float DEFAULT 0,
        six_months float DEFAULT 0,
        twelve_months float DEFAULT 0,
        twenty_four_months float DEFAULT 0,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_bank_interest_table');

// 2. Hiển thị dữ liệu trên trang với shortcode
function display_bank_interest_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bank_interest_rates';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name");

    $output = '<table border="1" cellpadding="5" cellspacing="0"><tr>';
    foreach ($columns as $column) {
        $output .= '<th>' . esc_html($column->Field) . '</th>';
    }
    $output .= '</tr>';

    foreach ($results as $row) {
        $output .= '<tr>';
        foreach ($columns as $column) {
            $field_name = $column->Field;
            $output .= '<td>' . esc_html($row->$field_name) . '</td>';
        }
        $output .= '</tr>';
    }
    $output .= '</table>';

    return $output;
}

add_shortcode('bank_interest_table', 'display_bank_interest_table');

// 3. Thêm menu trong Admin
function bank_interest_admin_menu() {
    add_menu_page(
        'Quản lý Lãi suất Ngân hàng',
        'Lãi suất Ngân hàng',
        'manage_options',
        'bank-interest-manager',
        'render_bank_interest_page'
    );
}

add_action('admin_menu', 'bank_interest_admin_menu');

// 4. Trang quản lý: Thêm dữ liệu và cột mới
function render_bank_interest_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bank_interest_rates';

    // Xử lý thêm dữ liệu
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $data = [];
        foreach ($_POST as $key => $value) {
            if (!in_array($key, ['action', '_wpnonce', '_wp_http_referer'])) {
                $data[$key] = sanitize_text_field($value);
            }
        }
        $wpdb->insert($table_name, $data);
    }

    // Xử lý thêm cột mới
    if (isset($_POST['action']) && $_POST['action'] == 'add_column') {
        $new_column = sanitize_text_field($_POST['new_column']);
        $data_type = sanitize_text_field($_POST['data_type']); // Lấy kiểu dữ liệu từ form
    
        // Chuyển tên cột sang định dạng snake_case
        $new_column = strtolower(str_replace(' ', '_', $new_column));
    
        global $wpdb;
        $table_name = $wpdb->prefix . 'bank_interest_rates';
    
        // Kiểm tra xem cột đã tồn tại chưa
        $existing_columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE '$new_column'");
        if (empty($existing_columns)) {
            // Thêm cột mới với kiểu dữ liệu đã chọn
            $wpdb->query("ALTER TABLE $table_name ADD COLUMN $new_column $data_type DEFAULT NULL");
            echo '<p style="color: green;">Đã thêm cột mới thành công!</p>';
        } else {
            echo '<p style="color: red;">Cột này đã tồn tại!</p>';
        }
    }
    

    // Hiển thị form thêm dữ liệu
    echo '<h2>Thêm Lãi suất Ngân hàng</h2>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="action" value="add">';
    $columns = $wpdb->get_results("SHOW COLUMNS FROM $table_name");
    foreach ($columns as $column) {
        if ($column->Field != 'id') { // Bỏ qua cột ID
            $label = ucfirst(str_replace('_', ' ', $column->Field));
            
            // Kiểm tra kiểu dữ liệu của cột
            if (strpos($column->Type, 'varchar') !== false) {
                // Nếu là kiểu chuỗi
                echo $label . ': <input type="text" name="' . esc_attr($column->Field) . '"><br>';
            } elseif (strpos($column->Type, 'float') !== false || strpos($column->Type, 'int') !== false) {
                // Nếu là kiểu số
                echo $label . ': <input type="number" step="0.01" name="' . esc_attr($column->Field) . '"><br>';
            }
        }
    }
    echo '<input type="submit" value="Thêm">';
    echo '</form>';

    //form create new column
    echo '<h2>Thêm Cột Mới</h2>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="action" value="add_column">';
    echo 'Tên Cột Mới: <input type="text" name="new_column" required><br>';
    echo 'Kiểu Dữ Liệu: 
        <select name="data_type" required>
            <option value="float">Số thực</option>
            <option value="varchar(255)">Chuỗi</option>
        </select><br>';
    echo '<input type="submit" value="Thêm Cột">';
    echo '</form>';
    

    // Hiển thị bảng dữ liệu
    echo '<h2>Danh sách Lãi suất</h2>';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    echo '<table border="1" cellpadding="5" cellspacing="0"><tr>';
    foreach ($columns as $column) {
        echo '<th>' . esc_html($column->Field) . '</th>';
    }
    echo '</tr>';
    foreach ($results as $row) {
        echo '<tr>';
        foreach ($columns as $column) {
            $field_name = $column->Field;
            echo '<td>' . esc_html($row->$field_name) . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
}
