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

    public static function run($id)
    {
        $customer = Catalog::where(['name' => 'Khách hàng'])->first();
        $customer->catalogs()->create(['name' => 'Loại'])->catalogs()->createMany([
            ['name' => 'Khách hàng cá nhân', 'description' => 'Khách hàng cá nhân', 'company_id' => $id],
            ['name' => 'Tổ chức', 'description' => 'Tổ chức', 'company_id' => $id],
        ]);
        $customer->catalogs()->create(['name' => 'Nguồn'])->catalogs()->createMany([
            ['name' => 'Webform', 'description' => 'Webform', 'company_id' => $id],
            ['name' => 'Facebook', 'description' => 'Facebook', 'company_id' => $id],
            ['name' => 'Trò truyện', 'description' => 'Trò truyện', 'company_id' => $id],
            ['name' => 'Tham chiếu web', 'description' => 'Tham chiếu web', 'company_id' => $id],
            ['name' => 'Quan hệ đối ngoại', 'description' => 'Quan hệ đối ngoại', 'company_id' => $id],
            ['name' => 'Đối tác', 'description' => 'Đối tác', 'company_id' => $id],
            ['name' => 'Cửa hàng trực tuyến', 'description' => 'Cửa hàng trực tuyến', 'company_id' => $id],
            ['name' => 'Tham chiếu từ bên ngoài', 'description' => 'Tham chiếu từ bên ngoài', 'company_id' => $id],
            ['name' => 'Tham chiếu từ nhân viên', 'description' => 'Tham chiếu từ nhân viên', 'company_id' => $id],
            ['name' => 'Gọi điện thoại', 'description' => 'Gọi điện thoại', 'company_id' => $id],
            ['name' => 'Quảng cáo', 'description' => 'Quảng cáo', 'company_id' => $id],
        ]);
        $customer->catalogs()->create(['name' => 'Ngành nghề'])->catalogs()->createMany([
            ['name' => 'CNTT', 'description' => 'Công nghệ thông tin', 'company_id' => $id],
            ['name' => 'Giáo dục', 'description' => 'Giáo dục', 'company_id' => $id],
            ['name' => 'Bất động sản', 'description' => 'Bất động sản', 'company_id' => $id],
            ['name' => 'Bán lẻ', 'description' => 'Bán lẻ', 'company_id' => $id],
        ]);




        $contact = Catalog::where(['name' => 'Liên hệ'])->first();
        $contact->catalogs()->create(['name' => 'Chức vụ'])->catalogs()->createMany([
            ['name' => 'Cộng tác viên', 'description' => 'Cộng tác viên', 'company_id' => $id],
            ['name' => 'Nhân viên', 'description' => 'Nhân viên', 'company_id' => $id],
            ['name' => 'Trưởng nhóm', 'description' => 'Trưởng nhóm', 'company_id' => $id],
            ['name' => 'Trưởng phòng', 'description' => 'Trưởng phòng', 'company_id' => $id],
            ['name' => 'Giám đốc', 'description' => 'Giám đốc', 'company_id' => $id],
        ]);
        $contact->catalogs()->create(['name' => 'Phòng ban'])->catalogs()->createMany([
            ['name' => 'Kỹ thuật', 'description' => 'Kỹ thuật', 'company_id' => $id],
            ['name' => 'Kế toán', 'description' => 'Kế toán', 'company_id' => $id],
            ['name' => 'Marketing', 'description' => 'Marketing', 'company_id' => $id],
            ['name' => 'Kinh doanh', 'description' => 'Kinh doanh', 'company_id' => $id],

        ]);

        // ['name' => '', 'description' => '', 'company_id' => $id],
        $opportunity = Catalog::where(['name' => 'Cơ hội'])->first();
        $opportunity->catalogs()->create(['name' => 'Kiểu'])->catalogs()->createMany([
            ['name' => 'Kinh doanh hiện thời', 'description' => 'Kinh doanh hiện thời', 'company_id' => $id],
            ['name' => 'Kinh doanh mới', 'description' => 'Kinh doanh mới', 'company_id' => $id],

        ]);
        $opportunity->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Đã kết thúc- Mất đến hoàng tất', 'description' => 'Đã kết thúc- Mất đến hoàng tất', 'company_id' => $id],
            ['name' => 'Bỏ qua kết thức', 'description' => 'Bỏ qua kết thức', 'company_id' => $id],
            ['name' => 'Đã kết thúc thành công', 'description' => 'Đã kết thúc thành công', 'company_id' => $id],
            ['name' => 'Thương lượng/xem lại', 'description' => 'Thương lượng/xem lại', 'company_id' => $id],
            ['name' => 'Gửi dự toán/giá', 'description' => 'Gửi dự toán/giá', 'company_id' => $id],
            ['name' => 'Xác định những người quyết định', 'description' => 'Xác định những người quyết định', 'company_id' => $id],
            ['name' => 'Đề xuất giá trị', 'description' => 'Đề xuất giá trị', 'company_id' => $id],
            ['name' => 'Cần phân tích', 'description' => 'Cần phân tích', 'company_id' => $id],
            ['name' => 'Chứng thực', 'description' => 'Chứng thực', 'company_id' => $id],
        ]);
        $opportunity->catalogs()->create(['name' => 'Nguồn'])->catalogs()->createMany([
            ['name' => 'Trò truyện', 'description' => 'Trò truyện', 'company_id' => $id],
            ['name' => 'Tham chiếu web', 'description' => 'Tham chiếu web', 'company_id' => $id],
            ['name' => 'Quan hệ đối ngoại', 'description' => 'Quan hệ đối ngoại', 'company_id' => $id],
            ['name' => 'Đối tác', 'description' => 'Đối tác', 'company_id' => $id],
            ['name' => 'Cửa hàng trực tuyến', 'description' => 'Cửa hàng trực tuyến', 'company_id' => $id],
            ['name' => 'Tham chiếu từ bên ngoài', 'description' => 'Tham chiếu từ bên ngoài', 'company_id' => $id],
            ['name' => 'Tham chiếu từ nhân viên', 'description' => 'Tham chiếu từ nhân viên', 'company_id' => $id],
            ['name' => 'Gọi điện thoạ', 'description' => 'Gọi điện thoạ', 'company_id' => $id],
            ['name' => 'Quảng cáo', 'description' => 'Quảng cáo', 'company_id' => $id],
        ]);






        $bill = Catalog::where(['name' => 'Hóa đơn'])->first();
        $bill->catalogs()->create(['name' => 'Trạng thái']);


        $lead = Catalog::where(['name' => 'Tiềm năng'])->first();
        $lead->catalogs()->create(['name' => 'Nguồn'])->catalogs()->createMany([
            ['name' => 'Webform', 'description' => 'Webform', 'company_id' => $id],
            ['name' => 'Facebook', 'description' => 'Facebook', 'company_id' => $id],
            ['name' => 'Trò truyện', 'description' => 'Trò truyện', 'company_id' => $id],
            ['name' => 'Tham chiếu web', 'description' => 'Tham chiếu web', 'company_id' => $id],
            ['name' => 'Quan hệ đối ngoại', 'description' => 'Quan hệ đối ngoại', 'company_id' => $id],
            ['name' => 'Đối tác', 'description' => 'Đối tác', 'company_id' => $id],
            ['name' => 'Cửa hàng trực tuyến', 'description' => 'Cửa hàng trực tuyến', 'company_id' => $id],
            ['name' => 'Tham chiếu từ bên ngoài', 'description' => 'Tham chiếu từ bên ngoài', 'company_id' => $id],
            ['name' => 'Tham chiếu từ nhân viên', 'description' => 'Tham chiếu từ nhân viên', 'company_id' => $id],
            ['name' => 'Gọi điện thoại', 'description' => 'Gọi điện thoại', 'company_id' => $id],
            ['name' => 'Quảng cáo', 'description' => 'Quảng cáo', 'company_id' => $id],
        ]);
        $lead->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Không đủ tư cách', 'description' => 'Không đủ tư cách', 'company_id' => $id],
            ['name' => 'Chưa liên hệ', 'description' => 'Chưa liên hệ', 'company_id' => $id],
            ['name' => 'Chào hàng mất', 'description' => 'Chào hàng mất', 'company_id' => $id],
            ['name' => 'Chào hàng linh tinh', 'description' => 'Chào hàng linh tinh', 'company_id' => $id],
            ['name' => 'Đã liên hệ', 'description' => 'Đã liên hệ', 'company_id' => $id],
            ['name' => 'Liên hệ trong tương lai', 'description' => 'Liên hệ trong tương lai', 'company_id' => $id],
            ['name' => 'Đã thử liên hệ', 'description' => 'Đã thử liên hệ', 'company_id' => $id],
        ]);
        $lead->catalogs()->create(['name' => 'Ngành nghề'])->catalogs()->createMany([
            ['name' => 'CNTT', 'description' => 'Công nghệ thông tin', 'company_id' => $id],
            ['name' => 'Giáo dục', 'description' => 'Giáo dục', 'company_id' => $id],
            ['name' => 'Bất động sản', 'description' => 'Bất động sản', 'company_id' => $id],
            ['name' => 'Bán lẻ', 'description' => 'Bán lẻ', 'company_id' => $id],
        ]);



        $order = Catalog::where(['name' => 'Đơn hàng'])->first();
        $order->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Giao hàng', 'description' => 'Giao hàng', 'company_id' => $id],
            ['name' => 'Đã hủy', 'description' => 'Đã hủy', 'company_id' => $id],
            ['name' => 'Đã chấp nhận', 'description' => 'Đã chấp nhận', 'company_id' => $id],
            ['name' => 'Được tạo', 'description' => 'Được tạo', 'company_id' => $id],
        ]);

        $quote = Catalog::where(['name' => 'Báo giá'])->first();
        $quote->catalogs()->create(['name' => 'Trạng thái'])->catalogs()->createMany([
            ['name' => 'Giao hàng', 'description' => 'Giao hàng', 'company_id' => $id],
            ['name' => 'Đã hủy', 'description' => 'Đã hủy', 'company_id' => $id],
            ['name' => 'Đã chấp nhận', 'description' => 'Đã chấp nhận', 'company_id' => $id],
            ['name' => 'Được tạo', 'description' => 'Được tạo', 'company_id' => $id],
        ]);
    }
}
