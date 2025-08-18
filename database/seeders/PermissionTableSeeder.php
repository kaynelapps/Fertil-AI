<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class PermissionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('permissions')->delete();
        
        DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'role',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'role-add',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'role-list',
                'guard_name' => 'web',
                'parent_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'permission',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'permission-add',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'permission-list',
                'guard_name' => 'web',
                'parent_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            6 => 
            array (
                'id' => 7,
                'name' => 'user',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'user-list',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'user-add',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'user-edit',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'user-delete',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'user-show',
                'guard_name' => 'web',
                'parent_id' => 7,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            12 => 
            array (
                'id' => 13,
                'name' => 'category',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'category-list',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'category-add',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'category-show',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'category-edit',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'category-delete',
                'guard_name' => 'web',
                'parent_id' => 13,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            18 => 
            array (
                'id' => 19,
                'name' => 'sections',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'sections-list',
                'guard_name' => 'web',
                'parent_id' => 19,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'sections-add',
                'guard_name' => 'web',
                'parent_id' => 19,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'sections-edit',
                'guard_name' => 'web',
                'parent_id' => 19,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'sections-delete',
                'guard_name' => 'web',
                'parent_id' => 19,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            23 => 
            array (
                'id' => 24,
                'name' => 'common-question',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'common-question-list',
                'guard_name' => 'web',
                'parent_id' => 24,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'common-question-add',
                'guard_name' => 'web',
                'parent_id' => 24,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'common-question-edit',
                'guard_name' => 'web',
                'parent_id' => 24,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'common-question-delete',
                'guard_name' => 'web',
                'parent_id' => 24,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            28 => 
            array (
                'id' => 29,
                'name' => 'sections-data-main',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'sections-data-main-list',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'sections-data-main-add',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'sections-data-main-edit',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'sections-data-main-show',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'sections-data-main-delete',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'sections-data-main-restore',
                'guard_name' => 'web',
                'parent_id' => 29,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            35 => 
            array (
                'id' => 36,
                'name' => 'sections-data',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'sections-data-list',
                'guard_name' => 'web',
                'parent_id' => 36,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'sections-data-add',
                'guard_name' => 'web',
                'parent_id' => 36,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'sections-data-edit',
                'guard_name' => 'web',
                'parent_id' => 36,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'sections-data-delete',
                'guard_name' => 'web',
                'parent_id' => 36,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            40 => 
            array (
                'id' => 41,
                'name' => 'article',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'article-list',
                'guard_name' => 'web',
                'parent_id' => 41,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'article-add',
                'guard_name' => 'web',
                'parent_id' => 41,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'article-edit',
                'guard_name' => 'web',
                'parent_id' => 41,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'article-delete',
                'guard_name' => 'web',
                'parent_id' => 41,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            45 => 
            array (
                'id' => 46,
                'name' => 'tags',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'tags-list',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'tags-add',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'tags-edit',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'tags-delete',
                'guard_name' => 'web',
                'parent_id' => 46,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            50 => 
            array (
                'id' => 51,
                'name' => 'insights',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'insights-list',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'insights-add',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'insights-edit',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'name' => 'insights-delete',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            55 => 
            array (
                'id' => 56,
                'name' => 'symptoms',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'name' => 'symptoms-list',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'name' => 'symptoms-add',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'name' => 'symptoms-show',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'name' => 'symptoms-edit',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'name' => 'symptoms-delete',
                'guard_name' => 'web',
                'parent_id' => 56,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            61 => 
            array (
                'id' => 62,
                'name' => 'sub-symptoms',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'name' => 'sub-symptoms-list',
                'guard_name' => 'web',
                'parent_id' => 62,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'name' => 'sub-symptoms-add',
                'guard_name' => 'web',
                'parent_id' => 62,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'name' => 'sub-symptoms-edit',
                'guard_name' => 'web',
                'parent_id' => 62,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'name' => 'sub-symptoms-delete',
                'guard_name' => 'web',
                'parent_id' => 62,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            66 => 
            array (
                'id' => 67,
                'name' => 'health-experts',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'name' => 'health-experts-list',
                'guard_name' => 'web',
                'parent_id' => 67,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'name' => 'health-experts-add',
                'guard_name' => 'web',
                'parent_id' => 67,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'name' => 'health-experts-edit',
                'guard_name' => 'web',
                'parent_id' => 67,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'name' => 'health-experts-delete',
                'guard_name' => 'web',
                'parent_id' => 67,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ), 
            71 => 
            array (
                'id' => 72,
                'name' => 'article-show',
                'guard_name' => 'web',
                'parent_id' => 41,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'name' => 'imagesection',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'name' => 'imagesection-list',
                'guard_name' => 'web',
                'parent_id' => 73,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'name' => 'default-log-category',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'name' => 'calculatortool',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'name' => 'calculatortool-list',
                'guard_name' => 'web',
                'parent_id' => 76,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'name' => 'calculatortool-edit',
                'guard_name' => 'web',
                'parent_id' => 76,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'name' => 'pregnancydate',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'name' => 'pregnancydate-list',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'name' => 'pregnancydate-add',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'name' => 'pregnancydate-edit',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'name' => 'pregnancydate-delete',
                'guard_name' => 'web',
                'parent_id' => 79,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'name' => 'cycledays',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'name' => 'cycledays-list',
                'guard_name' => 'web',
                'parent_id' => 84,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'name' => 'cycledays-add',
                'guard_name' => 'web',
                'parent_id' => 84,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'name' => 'cycledays-show',
                'guard_name' => 'web',
                'parent_id' => 84,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'name' => 'cycledays-delete',
                'guard_name' => 'web',
                'parent_id' => 84,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'name' => 'uploadvideos',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'name' => 'uploadvideos-list',
                'guard_name' => 'web',
                'parent_id' => 89,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'name' => 'uploadvideos-add',
                'guard_name' => 'web',
                'parent_id' => 89,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'name' => 'uploadvideos-edit',
                'guard_name' => 'web',
                'parent_id' => 89,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'name' => 'uploadvideos-delete',
                'guard_name' => 'web',
                'parent_id' => 89,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'name' => 'faq',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'name' => 'faq-list',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'name' => 'faq-add',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'name' => 'faq-edit',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'name' => 'faq-delete',
                'guard_name' => 'web',
                'parent_id' => 94,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'name' => 'push notification',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'name' => 'push notification-list',
                'guard_name' => 'web',
                'parent_id' => 99,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'name' => 'push notification-add',
                'guard_name' => 'web',
                'parent_id' => 99,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'name' => 'push notification-delete',
                'guard_name' => 'web',
                'parent_id' => 99,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'name' => 'pages',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'name' => 'terms-condition',
                'guard_name' => 'web',
                'parent_id' => 103,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'name' => 'privacy-policy',
                'guard_name' => 'web',
                'parent_id' => 103,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'name' => 'imagesection-add',
                'guard_name' => 'web',
                'parent_id' => 73,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'name' => 'imagesection-edit',
                'guard_name' => 'web',
                'parent_id' => 73,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'name' => 'imagesection-delete',
                'guard_name' => 'web',
                'parent_id' => 73,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'name' => 'default-log-category-list',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'name' => 'default-log-category-edit',
                'guard_name' => 'web',
                'parent_id' => 75,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            110 =>
            array(
                'id' => 111,
                'name' => 'screen',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            111 =>
            array (
                'id' => 112,
                'name' => 'screen-list',
                'guard_name' => 'web',
                'parent_id' => 111,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            112 =>
            array (
                'id' => 113,
                'name' => 'defaultkeyword',
                'guard_name' => 'web',
                'parent_id' => NULL,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            113 =>
            array (
                'id' => 114,
                'name' => 'defaultkeyword-list',
                'guard_name' => 'web',
                'parent_id' => 113,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            114 =>
            array (
                'id' => 115,
                'name' => 'defaultkeyword-add',
                'guard_name' => 'web',
                'parent_id' => 113,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            115 =>
            array (
                'id' => 116,
                'name' => 'defaultkeyword-edit',
                'guard_name' => 'web',
                'parent_id' => 113,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            116 =>
            array (
                'id' => 117,
                'name' => 'languagelist',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            117 =>
            array (
                'id' => 118,
                'name' => 'languagelist-list',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            118 =>
            array (
                'id' => 119,
                'name' => 'languagelist-add',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            119 =>
            array (
                'id' => 120,
                'name' => 'languagelist-edit',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            120 =>
            array (
                'id' => 121,
                'name' => 'languagelist-delete',
                'guard_name' => 'web',
                'parent_id' => 117,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            121 =>
            array (
                'id' => 122,
                'name' => 'languagewithkeyword',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            122 =>
            array (
                'id' => 123,
                'name' => 'languagewithkeyword-list',
                'guard_name' => 'web',
                'parent_id' => 122,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            123 =>
            array (
                'id' => 124,
                'name' => 'languagewithkeyword-edit',
                'guard_name' => 'web',
                'parent_id' => 122,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            124 =>
            array (
                'id' => 125,
                'name' => 'bulkimport',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            125 =>
            array (
                'id' => 126,
                'name' => 'bulkimport-add',
                'guard_name' => 'web',
                'parent_id' => 125,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            126 =>
            array (
                'id' => 127,
                'name' => 'healthexpertsession',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            127 =>
            array (
                'id' => 128,
                'name' => 'healthexpertsession-list',
                'guard_name' => 'web',
                'parent_id' => 127,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            128 =>
            array (
                'id' => 129,
                'name' => 'healthexpertsession-add',
                'guard_name' => 'web',
                'parent_id' => 127,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            129 =>
            array (
                'id' => 130,
                'name' => 'healthexpertsession-edit',
                'guard_name' => 'web',
                'parent_id' => 127,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            130 =>
            array (
                'id' => 131,
                'name' => 'healthexpertsession-delete',
                'guard_name' => 'web',
                'parent_id' => 127,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),

            131 =>
            array (
                'id' => 132,
                'name' => 'educationalsession',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            132 =>
            array (
                'id' => 133,
                'name' => 'educationalsession-list',
                'guard_name' => 'web',
                'parent_id' => 132,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            133 =>
            array (
                'id' => 134,
                'name' => 'educationalsession-add',
                'guard_name' => 'web',
                'parent_id' => 132,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            134 =>
            array (
                'id' => 135,
                'name' => 'educationalsession-edit',
                'guard_name' => 'web',
                'parent_id' => 132,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            135 =>
            array (
                'id' => 136,
                'name' => 'educationalsession-delete',
                'guard_name' => 'web',
                'parent_id' => 132,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            136 =>
            array (
                'id' => 137,
                'name' => 'holiday',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            137 =>
            array (
                'id' => 138,
                'name' => 'holiday-list',
                'guard_name' => 'web',
                'parent_id' => 137,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            138 =>
            array (
                'id' => 139,
                'name' => 'holiday-add',
                'guard_name' => 'web',
                'parent_id' => 137,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            139 =>
            array (
                'id' => 140,
                'name' => 'holiday-edit',
                'guard_name' => 'web',
                'parent_id' => 137,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            140 =>
            array (
                'id' => 141,
                'name' => 'holiday-delete',
                'guard_name' => 'web',
                'parent_id' => 137,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            141 =>
            array (
                'id' => 142,
                'name' => 'subadmin',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            142 =>
            array (
                'id' => 143,
                'name' => 'subadmin-add',
                'guard_name' => 'web',
                'parent_id' => 142,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            143 =>
            array (
                'id' => 144,
                'name' => 'subadmin-edit',
                'guard_name' => 'web',
                'parent_id' => 142,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            144 =>
            array (
                'id' => 145,
                'name' => 'subadmin-list',
                'guard_name' => 'web',
                'parent_id' => 142,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            145 =>
            array (
                'id' => 146,
                'name' => 'subadmin-delete',
                'guard_name' => 'web',
                'parent_id' => 142,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            146 =>
            array (
                'id' => 147,
                'name' => 'personalinsights',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            147 =>
            array (
                'id' => 148,
                'name' => 'personalinsights-list',
                'guard_name' => 'web',
                'parent_id' => 147,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            148 =>
            array (
                'id' => 149,
                'name' => 'personalinsights-add',
                'guard_name' => 'web',
                'parent_id' => 147,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            149 =>
            array (
                'id' => 150,
                'name' => 'personalinsights-edit',
                'guard_name' => 'web',
                'parent_id' => 147,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            150 =>
            array (
                'id' => 151,
                'name' => 'personalinsights-delete',
                'guard_name' => 'web',
                'parent_id' => 147,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'name' => 'insights-show',
                'guard_name' => 'web',
                'parent_id' => 51,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            152 =>
            array (
                'id' => 153,
                'name' => 'subscription',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'name' => 'subscription-list',
                'guard_name' => 'web',
                'parent_id' => 153,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'name' => 'subscription-delete',
                'guard_name' => 'web',
                'parent_id' => 153,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            155 =>
            array (
                'id' => 156,
                'name' => 'askexpert',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'name' => 'askexpert-list',
                'guard_name' => 'web',
                'parent_id' => 156,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'name' => 'askexpert-delete',
                'guard_name' => 'web',
                'parent_id' => 156,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            158 =>
            array (
                'id' => 159,
                'name' => 'pregnancyweek',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'name' => 'pregnancyweek-list',
                'guard_name' => 'web',
                'parent_id' => 159,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'name' => 'pregnancyweek-edit',
                'guard_name' => 'web',
                'parent_id' => 159,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'name' => 'pregnancyweek-delete',
                'guard_name' => 'web',
                'parent_id' => 159,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'name' => 'pregnancyweek-add',
                'guard_name' => 'web',
                'parent_id' => 159,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 164,
                'name' => 'askexpert-image',
                'guard_name' => 'web',
                'parent_id' => 156,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 165,
                'name' => 'sections-show',
                'guard_name' => 'web',
                'parent_id' => 19,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            165 =>
            array (
                'id' => 166,
                'name' => 'customtopic',
                'guard_name' => 'web',
                'parent_id' => NUll,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 167,
                'name' => 'customtopic-list',
                'guard_name' => 'web',
                'parent_id' => 166,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 168,
                'name' => 'customtopic-add',
                'guard_name' => 'web',
                'parent_id' => 166,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 169,
                'name' => 'customtopic-show',
                'guard_name' => 'web',
                'parent_id' => 166,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 170,
                'name' => 'customtopic-edit',
                'guard_name' => 'web',
                'parent_id' => 166,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 171,
                'name' => 'customtopic-delete',
                'guard_name' => 'web',
                'parent_id' => 166,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => NULL,
            ),
        ));
    }
}