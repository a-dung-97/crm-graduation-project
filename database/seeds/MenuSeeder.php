<?php

use App\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Menu::create(['name' => 'Công ty']);
        $company->children()->create(['name' => 'Thông tin công ty']);
        $company->children()->create(['name' => 'Phòng ban']);
        $company->children()->create(['name' => 'Chức vụ']);
        $company->children()->create(['name' => 'Người dùng']);
        $company->children()->create(['name' => 'Nhóm người dùng']);
        $company->children()->create(['name' => 'Quyền hạn']);

        $goods = Menu::create(['name' => 'Hàng hóa']);
        $goods->children()->create(['name' => 'Kho hàng']);
        $goods->children()->create(['name' => 'Sản phẩm']);
        $goods->children()->create(['name' => 'Thêm mới sản phẩm']);
        $goods->children()->create(['name' => 'Chỉnh sửa sản phẩm']);
        $goods->children()->create(['name' => 'Chi tiết sản phẩm']);
        $goods->children()->create(['name' => 'Phiếu nhập']);
        $goods->children()->create(['name' => 'Thêm mới phiếu nhập']);
        $goods->children()->create(['name' => 'Chỉnh sửa phiếu nhập']);
        $goods->children()->create(['name' => 'Chi tiết phiếu nhập']);
        $goods->children()->create(['name' => 'Phiếu xuất']);
        $goods->children()->create(['name' => 'Thêm mới phiếu xuất']);
        $goods->children()->create(['name' => 'Chỉnh sửa phiếu xuất']);
        $goods->children()->create(['name' => 'Chi tiết phiếu xuất']);
        $goods->children()->create(['name' => 'Hàng tồn kho']);

        $customer = Menu::create(['name' => 'Quản lý khách hàng']);

        $customer->children()->create(['name' => 'Tiềm năng']);
        $customer->children()->create(['name' => 'Thêm mới tiềm năng']);
        $customer->children()->create(['name' => 'Chỉnh sửa tiềm năng']);
        $customer->children()->create(['name' => 'Chi tiết tiềm năng']);
        $customer->children()->create(['name' => 'Chuyển đổi tiềm năng']);

        $customer->children()->create(['name' => 'Khách hàng']);
        $customer->children()->create(['name' => 'Thêm mới khách hàng']);
        $customer->children()->create(['name' => 'Chỉnh sửa khách hàng']);
        $customer->children()->create(['name' => 'Chi tiết khách hàng']);

        $customer->children()->create(['name' => 'Liên hệ']);
        $customer->children()->create(['name' => 'Thêm mới liên hệ']);
        $customer->children()->create(['name' => 'Chỉnh sửa liên hệ']);
        $customer->children()->create(['name' => 'Chi tiết liên hệ']);

        $business = Menu::create(['name' => 'Kinh doanh']);

        $business->children()->create(['name' => 'Công việc']);
        $business->children()->create(['name' => 'Thêm mới công việc']);
        $business->children()->create(['name' => 'Chỉnh sửa công việc']);
        $business->children()->create(['name' => 'Chi tiết công việc']);
        $business->children()->create(['name' => 'Chi tiết cuộc gọi']);
        $business->children()->create(['name' => 'Chi tiết cuộc hẹn']);

        $business->children()->create(['name' => 'Cơ hội']);
        $business->children()->create(['name' => 'Thêm mới cơ hội']);
        $business->children()->create(['name' => 'Chỉnh sửa cơ hội']);
        $business->children()->create(['name' => 'Chi tiết cơ hội']);

        $business->children()->create(['name' => 'Báo giá']);
        $business->children()->create(['name' => 'Thêm mới báo giá']);
        $business->children()->create(['name' => 'Chỉnh sửa báo giá']);
        $business->children()->create(['name' => 'Chi tiết báo giá']);

        $business->children()->create(['name' => 'Đơn hàng']);
        $business->children()->create(['name' => 'Thêm mới đơn hàng']);
        $business->children()->create(['name' => 'Chỉnh sửa đơn hàng']);
        $business->children()->create(['name' => 'Chi tiết đơn hàng']);

        $business->children()->create(['name' => 'Ghi chú']);

        $accounting = Menu::create(['name' => 'Kế toán']);

        $accounting->children()->create(['name' => 'Sổ quỹ']);

        $accounting->children()->create(['name' => 'Hóa đơn']);
        $accounting->children()->create(['name' => 'Thêm mới hóa đơn']);
        $accounting->children()->create(['name' => 'Chỉnh sửa hóa đơn']);
        $accounting->children()->create(['name' => 'Chi tiết hóa đơn']);


        $marketing = Menu::create(['name' => 'Marketing']);





        $marketing->children()->create(['name' => 'Danh sách email']);

        $marketing->children()->create(['name' => 'Mẫu Email']);
        $marketing->children()->create(['name' => 'Thêm mới mẫu email']);
        $marketing->children()->create(['name' => 'Chỉnh sửa mẫu email']);

        $marketing->children()->create(['name' => 'Chiến dịch email']);
        $marketing->children()->create(['name' => 'Chi tiết chiến dịch email']);

        $marketing->children()->create(['name' => 'Webform']);
        $marketing->children()->create(['name' => 'Thêm mới webform']);
        $marketing->children()->create(['name' => 'Chỉnh sửa webform']);
        $marketing->children()->create(['name' => 'Chi tiết webform']);

        $setting = Menu::create(['name' => 'Cài đặt']);

        $setting->children()->create(['name' => 'Danh mục']);
        $setting->children()->create(['name' => 'Quy tắc tính điểm']);

        $report = Menu::create(['name' => 'Báo cáo']);
        $report->children()->create(['name' => 'Báo cáo động']);
        $report->children()->create(['name' => 'Chi tiết báo cáo']);
        $report->children()->create(['name' => 'Báo cáo công nợ']);

        $report = Menu::create(['name' => 'Automation']);
        $report->children()->create(['name' => 'Email tự động']);
        $report->children()->create(['name' => 'Chi tiết email tự động']);
    }
}
