<?php

use App\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact = Catalog::create(['name' => 'Liên hệ']);
        $contact->catalogs()->create(['name' => 'Chức vụ'])->catalogs()->createMany([
            ['name' => 'Cộng tác viên', 'description' => 'Cộng tác viên', 'company_id' => 1],
            ['name' => 'Nhân viên', 'description' => 'Nhân viên', 'company_id' => 1],
            ['name' => 'Trưởng nhóm', 'description' => 'Trưởng nhóm', 'company_id' => 1],
            ['name' => 'Trưởng phòng', 'description' => 'Trưởng phòng', 'company_id' => 1],
            ['name' => 'Giám đốc', 'description' => 'Giám đốc', 'company_id' => 1],
        ]);
        $contact->catalogs()->create(['name' => 'Phòng ban'])->catalogs()->createMany([
            ['name' => 'Kỹ thuật', 'description' => 'Kỹ thuật', 'company_id' => 1],
            ['name' => 'Kế toán', 'description' => 'Kế toán', 'company_id' => 1],
            ['name' => 'Marketing', 'description' => 'Marketing', 'company_id' => 1],
            ['name' => 'Kinh doanh', 'description' => 'Kinh doanh', 'company_id' => 1],

        ]);

        // ['name' => '', 'description' => '', 'company_id' => 1],
        $opportunity = Catalog::create(['name' => 'Cơ hội']);
        $opportunity->catalogs()->create(['name' => 'Kiểu'])->catalogs()->createMany([
            ['name' => 'Kinh doanh hiện thời', 'description' => 'Kinh doanh hiện thời', 'company_id' => 1],
            ['name' => 'Kinh doanh mới', 'description' => 'Kinh doanh mới', 'company_id' => 1],

        ]);
        $opportunity->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Đã kết thúc- Mất đến hoàng tất', 'description' => 'Đã kết thúc- Mất đến hoàng tất', 'company_id' => 1],
            ['name' => 'Bỏ qua kết thức', 'description' => 'Bỏ qua kết thức', 'company_id' => 1],
            ['name' => 'Đã kết thúc thành công', 'description' => 'Đã kết thúc thành công', 'company_id' => 1],
            ['name' => 'Thương lượng/xem lại', 'description' => 'Thương lượng/xem lại', 'company_id' => 1],
            ['name' => 'Gửi dự toán/giá', 'description' => 'Gửi dự toán/giá', 'company_id' => 1],
            ['name' => 'Xác định những người quyết định', 'description' => 'Xác định những người quyết định', 'company_id' => 1],
            ['name' => 'Đề xuất giá trị', 'description' => 'Đề xuất giá trị', 'company_id' => 1],
            ['name' => 'Cần phân tích', 'description' => 'Cần phân tích', 'company_id' => 1],
            ['name' => 'Chứng thực', 'description' => 'Chứng thực', 'company_id' => 1],
        ]);
        $opportunity->catalogs()->create(['name' => 'Nguồn'])->catalogs()->createMany([
            ['name' => 'Trò truyện', 'description' => 'Trò truyện', 'company_id' => 1],
            ['name' => 'Tham chiếu web', 'description' => 'Tham chiếu web', 'company_id' => 1],
            ['name' => 'Quan hệ đối ngoại', 'description' => 'Quan hệ đối ngoại', 'company_id' => 1],
            ['name' => 'Đối tác', 'description' => 'Đối tác', 'company_id' => 1],
            ['name' => 'Cửa hàng trực tuyến', 'description' => 'Cửa hàng trực tuyến', 'company_id' => 1],
            ['name' => 'Tham chiếu từ bên ngoài', 'description' => 'Tham chiếu từ bên ngoài', 'company_id' => 1],
            ['name' => 'Tham chiếu từ nhân viên', 'description' => 'Tham chiếu từ nhân viên', 'company_id' => 1],
            ['name' => 'Gọi điện thoạ', 'description' => 'Gọi điện thoạ', 'company_id' => 1],
            ['name' => 'Quảng cáo', 'description' => 'Quảng cáo', 'company_id' => 1],
        ]);






        $bill = Catalog::create(['name' => 'Hóa đơn']);
        $bill->catalogs()->create(['name' => 'Trạng thái']);


        $lead = Catalog::create(['name' => 'Tiềm năng']);
        $lead->catalogs()->create(['name' => 'Nguồn'])->catalogs()->createMany([
            ['name' => 'Webform', 'description' => 'Webform', 'company_id' => 1],
            ['name' => 'Facebook', 'description' => 'Facebook', 'company_id' => 1],
            ['name' => 'Trò truyện', 'description' => 'Trò truyện', 'company_id' => 1],
            ['name' => 'Tham chiếu web', 'description' => 'Tham chiếu web', 'company_id' => 1],
            ['name' => 'Quan hệ đối ngoại', 'description' => 'Quan hệ đối ngoại', 'company_id' => 1],
            ['name' => 'Đối tác', 'description' => 'Đối tác', 'company_id' => 1],
            ['name' => 'Cửa hàng trực tuyến', 'description' => 'Cửa hàng trực tuyến', 'company_id' => 1],
            ['name' => 'Tham chiếu từ bên ngoài', 'description' => 'Tham chiếu từ bên ngoài', 'company_id' => 1],
            ['name' => 'Tham chiếu từ nhân viên', 'description' => 'Tham chiếu từ nhân viên', 'company_id' => 1],
            ['name' => 'Gọi điện thoạ', 'description' => 'Gọi điện thoạ', 'company_id' => 1],
            ['name' => 'Quảng cáo', 'description' => 'Quảng cáo', 'company_id' => 1],
        ]);
        $opportunity->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Không đủ tư cách', 'description' => 'Không đủ tư cách', 'company_id' => 1],
            ['name' => 'Chưa liên hệ', 'description' => 'Chưa liên hệ', 'company_id' => 1],
            ['name' => 'Chào hàng mất', 'description' => 'Chào hàng mất', 'company_id' => 1],
            ['name' => 'Chào hàng linh tinh', 'description' => 'Chào hàng linh tinh', 'company_id' => 1],
            ['name' => 'Đã liên hệ', 'description' => 'Đã liên hệ', 'company_id' => 1],
            ['name' => 'Liên hệ trong tương lai', 'description' => 'Liên hệ trong tương lai', 'company_id' => 1],
            ['name' => 'Đã thử liên hệ', 'description' => 'Đã thử liên hệ', 'company_id' => 1],
        ]);
        $opportunity->catalogs()->create(['name' => 'Ngành nghề']);

        $order = Catalog::create(['name' => 'Đơn hàng']);
        $order->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Giao hàng', 'description' => 'Giao hàng', 'company_id' => 1],
            ['name' => 'Đã hủy', 'description' => 'Đã hủy', 'company_id' => 1],
            ['name' => 'Đã chấp nhận', 'description' => 'Đã chấp nhận', 'company_id' => 1],
            ['name' => 'Được tạo', 'description' => 'Được tạo', 'company_id' => 1],
        ]);

        $quote = Catalog::create(['name' => 'Báo giá']);
        $quote->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Giao hàng', 'description' => 'Giao hàng', 'company_id' => 1],
            ['name' => 'Đã hủy', 'description' => 'Đã hủy', 'company_id' => 1],
            ['name' => 'Đã chấp nhận', 'description' => 'Đã chấp nhận', 'company_id' => 1],
            ['name' => 'Được tạo', 'description' => 'Được tạo', 'company_id' => 1],
        ]);
    }
}
