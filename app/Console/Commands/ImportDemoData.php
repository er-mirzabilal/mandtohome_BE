<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;


class ImportDemoData extends Command
{
    protected $signature = 'shop:seed';

    protected $description = 'Import Demo Data';

    public function handle()
    {

//        $this->info('Copying necessary files for seeding....');
//
//        (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/sql/' . config('shop.dummy_data_path'), public_path('sql'));
//
//        $this->info('File copying successful');

        $this->info('Seeding....');

        $this->seedDemoData();
    }
    public function seedDemoData()
    {
//        $media_path = database_path('seeders/sql/shop/media.sql');
//        $media_sql = file_get_contents($media_path);
//        DB::statement($media_sql);

        $attachments_path = database_path('seeders/sql/shop/attachments.sql');
        $attachments_sql = file_get_contents($attachments_path);
        DB::statement($attachments_sql);

        $permissions_path = database_path('seeders/sql/shop/permissions.sql');
        $permissions_sql = file_get_contents($permissions_path);
        DB::statement($permissions_sql);

        $types_path = database_path('seeders/sql/shop/types.sql');
        $types_sql = file_get_contents($types_path);
        DB::statement($types_sql);

        $banners_path = database_path('seeders/sql/shop/banners.sql');
        $banners_sql = file_get_contents($banners_path);
        DB::statement($banners_sql);

        $users_path = database_path('seeders/sql/shop/users.sql');
        $users_sql = file_get_contents($users_path);
        DB::statement($users_sql);

        $user_profiles_path = database_path('seeders/sql/shop/user_profiles.sql');
        $user_profiles_sql = file_get_contents($user_profiles_path);
        DB::statement($user_profiles_sql);

        $address_path = database_path('seeders/sql/shop/address.sql');
        $address_sql = file_get_contents($address_path);
        DB::statement($address_sql);

        $model_has_permissions_path = database_path('seeders/sql/shop/model_has_permissions.sql');
        $model_has_permissions_sql = file_get_contents($model_has_permissions_path);
        DB::statement($model_has_permissions_sql);

        $shops_path = database_path('seeders/sql/shop/shops.sql');
        $shops_sql = file_get_contents($shops_path);
        DB::statement($shops_sql);

        $balances_path = database_path('seeders/sql/shop/balances.sql');
        $balances_sql = file_get_contents($balances_path);
        DB::statement($balances_sql);

        $attributes_path = database_path('seeders/sql/shop/attributes.sql');
        $attributes_sql = file_get_contents($attributes_path);
        DB::statement($attributes_sql);

        $attribute_values_path = database_path('seeders/sql/shop/attribute_values.sql');
        $attribute_values_sql = file_get_contents($attribute_values_path);
        DB::statement($attribute_values_sql);

        $categories_path = database_path('seeders/sql/shop/categories.sql');
        $categories_sql = file_get_contents($categories_path);
        DB::statement($categories_sql);

        $tags_path = database_path('seeders/sql/shop/tags.sql');
        $tags_sql = file_get_contents($tags_path);
        DB::statement($tags_sql);

        $authors_path = database_path('seeders/sql/shop/authors.sql');
        $authors_sql = file_get_contents($authors_path);
        DB::statement($authors_sql);

        $manufacturers_path = database_path('seeders/sql/shop/manufacturers.sql');
        $manufacturers_sql = file_get_contents($manufacturers_path);
        DB::statement($manufacturers_sql);

        $products_path = database_path('seeders/sql/shop/products.sql');
        $products_sql = file_get_contents($products_path);
        DB::statement($products_sql);

        $attribute_product_path = database_path('seeders/sql/shop/attribute_product.sql');
        $attribute_product_sql = file_get_contents($attribute_product_path);
        DB::statement($attribute_product_sql);

        $variation_options_path = database_path('seeders/sql/shop/variation_options.sql');
        $variation_options_sql = file_get_contents($variation_options_path);
        DB::statement($variation_options_sql);

        $coupons_path = database_path('seeders/sql/shop/coupons.sql');
        $coupons_sql = file_get_contents($coupons_path);
        DB::statement($coupons_sql);

        $digital_files_path = database_path('seeders/sql/shop/digital_files.sql');
        $digital_files_sql = file_get_contents($digital_files_path);
        DB::statement($digital_files_sql);

        $orders_status_path = database_path('seeders/sql/shop/order_status.sql');
        $orders_status_sql = file_get_contents($orders_status_path);
        DB::statement($orders_status_sql);

        $category_product_path = database_path('seeders/sql/shop/category_product.sql');
        $category_product_sql = file_get_contents($category_product_path);
        DB::statement($category_product_sql);

        $product_tag_path = database_path('seeders/sql/shop/product_tag.sql');
        $product_tag_sql = file_get_contents($product_tag_path);
        DB::statement($product_tag_sql);

        $settings_path = database_path('seeders/sql/shop/settings.sql');
        $settings_sql = file_get_contents($settings_path);
        DB::statement($settings_sql);

        $shipping_classes_path = database_path('seeders/sql/shop/shipping_classes.sql');
        $shipping_classes_sql = file_get_contents($shipping_classes_path);
        DB::statement($shipping_classes_sql);

        $tax_classes_path = database_path('seeders/sql/shop/tax_classes.sql');
        $tax_classes_sql = file_get_contents($tax_classes_path);
        DB::statement($tax_classes_sql);

        $reviews_path = database_path('seeders/sql/shop/reviews.sql');
        $reviews_sql = file_get_contents($reviews_path);
        DB::statement($reviews_sql);

        $questions_path = database_path('seeders/sql/shop/questions.sql');
        $questions_sql = file_get_contents($questions_path);
        DB::statement($questions_sql);

        $this->info('Seed completed successfully!');
    }
}
