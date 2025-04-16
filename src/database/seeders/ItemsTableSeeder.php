<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg');
        Storage::put('public/image/item/Clock.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => '腕時計',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image_path' => '/image/item/Clock.jpg',
            'condition' => 1,
            'price' => 15000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([1, 5]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg');
        Storage::put('public/image/item/Disk.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'HDD',
            'description' => '高速で信頼性の高いハードディスク',
            'image_path' => '/image/item/Disk.jpg',
            'condition' => 2,
            'price' => 5000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([2]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg');
        Storage::put('public/image/item/Onion.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => '玉ねぎ3束',
            'description' => '新鮮な玉ねぎ3束のセット',
            'image_path' => '/image/item/Onion.jpg',
            'condition' => 3,
            'price' => 300,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([10, 11]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg');
        Storage::put('public/image/item/Shoes.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => '革靴',
            'description' => 'クラシックなデザインの革靴',
            'image_path' => '/image/item/Shoes.jpg',
            'condition' => 4,
            'price' => 4000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([1, 5]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg');
        Storage::put('public/image/item/Laptop.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'ノートPC',
            'description' => '高性能なノートパソコン',
            'image_path' => '/image/item/Laptop.jpg',
            'condition' => 1,
            'price' => 45000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([2]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg');
        Storage::put('public/image/item/Mike.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'マイク',
            'description' => '高音質のレコーディング用マイク',
            'image_path' => '/image/item/Mike.jpg',
            'condition' => 2,
            'price' => 8000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([2]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg');
        Storage::put('public/image/item/Bag.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'description' => 'おしゃれなショルダーバッグ',
            'image_path' => '/image/item/Bag.jpg',
            'condition' => 3,
            'price' => 3500,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([1, 4]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg');
        Storage::put('public/image/item/Tumbler.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'タンブラー',
            'description' => '使いやすいタンブラー',
            'image_path' => '/image/item/Tumbler.jpg',
            'condition' => 4,
            'price' => 500,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([10]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg');
        Storage::put('public/image/Mill.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'description' => '手動のコーヒーミル',
            'image_path' => '/image/Mill.jpg',
            'condition' => 1,
            'price' => 4000,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([10]);

        $image = file_get_contents('https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg');
        Storage::put('public/image/Make.jpg', $image);
        $param = [
            'user_id' => 1,
            'name' => 'メイクセット',
            'description' => '便利なメイクアップセット',
            'image_path' => '/image/Make.jpg',
            'condition' => 2,
            'price' => 2500,
            'purchased' => 0,
        ];
        Item::create($param)->categories()->sync([4, 6]);
    }
}
