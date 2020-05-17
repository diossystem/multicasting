<?php

use Illuminate\Database\Seeder;
use Tests\Models\Template;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Template::class)->create([
            'code_name' => 'home',
            'title' => 'Homepage',
        ]);

        factory(Template::class)->create([
            'code_name' => 'page',
            'title' => 'Simple page',
        ]);

        factory(Template::class)->create([
            'code_name' => 'contact',
            'title' => 'Page with contacts',
        ]);

        factory(Template::class)->create([
            'code_name' => 'service',
            'title' => 'Service',
        ]);

        factory(Template::class)->create([
            'code_name' => 'services',
            'title' => 'Page with services',
        ]);

        $this->createTemplateWithChildren();

        factory(Template::class, 5)->create([
            'active' => false,
            'description' => 'inactive',
        ]);
    }

    protected function createTemplateWithChildren()
    {
        $parent = factory(Template::class)->create([
            'code_name' => 'blog',
            'title' => 'Blog. List of posts',
        ]);

        $parent->children()->createMany([
            factory(Template::class)
                ->make([
                    'code_name' => 'post',
                    'title' => 'Post',
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_search',
                    'title' => 'Search of posts',
                    'priority' => -100,
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_filter',
                    'title' => 'Filter of posts',
                    'priority' => -90,
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_tags',
                    'title' => 'Tags of posts',
                    'priority' => -80,
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_tag',
                    'title' => 'Pages by tag',
                    'priority' => -75,
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_categories',
                    'title' => 'Categories of posts',
                    'priority' => -60,
                ])
                ->toArray(),
            factory(Template::class)
                ->make([
                    'code_name' => 'blog_category',
                    'title' => 'Category of posts',
                    'priority' => -55,
                ])
                ->toArray(),
        ]);
    }
}
