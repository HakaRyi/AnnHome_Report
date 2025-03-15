jQuery(document).ready(function($) {
    $('th').on('contextmenu', function(e) {
        e.preventDefault(); // Ngăn menu chuột phải mặc định

        const columnName = $(this).text(); // Lấy tên cột hiện tại
        const position = $(this).index(); // Lấy vị trí của cột

        // Hiển thị prompt để nhập tên cột mới
        const newColumnName = prompt("Nhập tên cột mới:");
        if (newColumnName) {
            // Hiển thị danh sách kiểu dữ liệu để người dùng chọn
            const dataType = prompt("Chọn kiểu dữ liệu cho cột mới: \n1: Số thực (FLOAT)\n2: Chuỗi (VARCHAR)");
            let selectedType = '';

            if (dataType === "1") {
                selectedType = 'float';
            } else if (dataType === "2") {
                selectedType = 'varchar(255)';
            } else {
                alert("Kiểu dữ liệu không hợp lệ. Vui lòng thử lại!");
                return; // Dừng nếu kiểu dữ liệu không hợp lệ
            }

            // Gửi dữ liệu về server qua AJAX
            $.post(ajaxurl, {
                action: 'add_column',
                column_name: newColumnName,
                column_position: position,
                data_type: selectedType
            }, function(response) {
                alert(response.message);
                location.reload(); // Tải lại trang để hiển thị cột mới
            });
        }
    });
});
