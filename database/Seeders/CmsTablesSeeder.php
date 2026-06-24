<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CMS\Category;
use App\Models\CMS\Faq;
use App\Models\CMS\Page;
use App\Models\CMS\Post;
use App\Models\CMS\Slide;
use App\Models\CMS\Testimony;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CmsTablesSeeder
 */
class CmsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function () {
            Slide::factory(4)->create();

            $editor_role = Role::create([
                'name'         => Role::EDITOR,
                'display_name' => 'Content-Editor',
                'description'  => "Site Content Editor",
            ]);

            $admin_role = Role::findByName(Role::ADMIN);
            /**
             * @var User $user
             */
            foreach ($admin_role->users as $user) {
                $user->roles()->attach($editor_role);
            }

            $page_categories = [
                Category::C_ABOUT => [
                    'type'           => Page::morphKey(),
                    'title'          => 'Company',
                    'slug'           => Category::C_ABOUT,
                    'cardinality'    => 0,
                    'system_defined' => true,
                    'show_in_menu'   => true,
                    'show_in_footer' => true,
                    'use_index'      => false,
                ],
                'legal'           => [
                    'type'           => Page::morphKey(),
                    'title'          => 'Legal',
                    'slug'           => 'legal',
                    'cardinality'    => 1,
                    'system_defined' => true,
                    'show_in_menu'   => false,
                    'show_in_footer' => true,
                    'use_index'      => false,
                ],
            ];

            $pages = [
                Category::C_ABOUT => [
                    [
                        'slug'  => Page::SLUG_ABOUT,
                        'title' => 'About Us',
                        'system_defined' => true,
                        'show_in_menu'   => true,
                        'show_in_footer' => true,
                    ],
                    [
                        'slug'  => 'careers',
                        'title' => 'Careers',
                        'system_defined' => true,
                        'show_in_menu'   => true,
                        'show_in_footer' => true,
                    ],
                ],
                'legal'           => [
                    [
                        'slug'           => Page::SLUG_T_AND_C,
                        'title'          => 'Terms & Conditions',
                        'system_defined' => true,
                        'show_in_menu'   => true,
                        'show_in_footer' => true,
                    ],
                    [
                        'slug'           => Page::SLUG_PRIVACY,
                        'title'          => 'Privacy Policy',
                        'system_defined' => true,
                        'show_in_menu'   => true,
                        'show_in_footer' => true,
                    ],
                ],
            ];

            foreach ($page_categories as $category_slug => $category_attr) {
                $page_category = Category::factory()->create($category_attr);
                if (isset($pages[$category_slug])) {
                    foreach ($pages[$category_slug] as $page_attr) {
                        Page::factory()->create(array_merge([
                            'category_id' => $page_category->id,
                        ], $page_attr));
                    }
                }
            }

            $post_categories = [
                'tutorials'   => 'Tutorials',
                'testimonies' => 'Testimonies',
                'news'        => 'News',
            ];

            $categories = collect();
            foreach ($post_categories as $slug => $title) {
                $category = Category::factory()->create([
                    'type'  => Category::TYPE_POST,
                    'slug'  => $slug,
                    'title' => $title,
                ]);
                $categories->push($category);
            }

            ///////////////////////////
            if (app()->isLocal()) {
                $editors = $editor_role->users;

                foreach ($categories as $category) {
                    Post::factory(rand(8, 12))->create(['is_published' => true, 'category_id' => $category->id]);
                }

                Category::factory(6)->create(['type' => Faq::morphKey()])->each(function (Category $category) {
                    Faq::factory(4)->create(['category_id' => $category->id]);
                });

                Testimony::factory(20)->create();
            }
        });
    }
}
