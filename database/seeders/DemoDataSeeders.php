<?php
namespace Database\Seeders;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Type;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Coupon;
use Spatie\Permission\Models\Permission;
use App\Enums\Permission as UserPermission;
use Illuminate\Database\Eloquent;


class DemoDataSeeders extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users_path = database_path('seeders/sql/shop/users.sql');
        $users_sql = file_get_contents($users_path);
        DB::statement($users_sql);

        $types_path = database_path('seeders/sql/shop/types.sql');
        $types_sql = file_get_contents($types_path);
        DB::statement($types_sql);

        $categories_path = database_path('seeders/sql/shop/categories.sql');
        $categories_sql = file_get_contents($categories_path);
        DB::statement($categories_sql);

        $products_path = database_path('seeders/sql/shop/products.sql');
        $products_sql = file_get_contents($products_path);
        DB::statement($products_sql);

        $coupons_path = database_path('seeders/sql/shop/coupons.sql');
        $coupons_sql = file_get_contents($coupons_path);
        DB::statement($coupons_sql);

        $orders_status_path = database_path('seeders/sql/shop/order_status.sql');
        $orders_status_sql = file_get_contents($orders_status_path);
        DB::statement($orders_status_sql);

        $category_product_path = database_path('seeders/sql/shop/category_product.sql');
        $category_product_sql = file_get_contents($category_product_path);
        DB::statement($category_product_sql);

        $orders_path = database_path('seeders/sql/shop/orders.sql');
        $orders_sql = file_get_contents($orders_path);
        DB::statement($orders_sql);

        $order_product_path = database_path('seeders/sql/shop/order_product.sql');
        $order_product_sql = file_get_contents($order_product_path);
        DB::statement($order_product_sql);

        $settings_path = database_path('seeders/sql/shop/settings.sql');
        $settings_sql = file_get_contents($settings_path);
        DB::statement($settings_sql);

        $permissions_path = database_path('seeders/sql/shop/permissions.sql');
        $permissions_sql = file_get_contents($permissions_path);
        DB::statement($permissions_sql);

        $shipping_classes_path = database_path('seeders/sql/shop/shipping_classes.sql');
        $shipping_classes_sql = file_get_contents($shipping_classes_path);
        DB::statement($shipping_classes_sql);

        $tax_classes_path = database_path('seeders/sql/shop/tax_classes.sql');
        $tax_classes_sql = file_get_contents($tax_classes_path);
        DB::statement($tax_classes_sql);

        $this->command->info('Seed completed from sql file!');
    }
}
