<?php

use Illuminate\Database\Seeder;
use Tests\Models\Page;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Page::class)->create([
            'slug' => 'article-1',
            'link' => 'blog/article-1',
        ]);

        factory(Page::class)->create([
            'slug' => 'article-10',
            'link' => 'blog/article-10',
        ]);

        $parentPage = factory(Page::class)->create([
            'slug' => 'page-1',
            'link' => 'page-1',
            'title' => 'Parent page',
        ]);

        $this->createChildren($parentPage);
        
        // Will be published
        factory(Page::class, 5)->create([
            'published_at' => new DateTime('tomorrow'),
            'keywords_tag' => 'tomorrow',
        ]);

        factory(Page::class, 10)->create([
            'keywords_tag' => 'other',
        ]);

        $pageWithManyChildren = factory(Page::class)->create([
            'slug' => 'page-children',
            'link' => 'page-children',
            'title' => 'Parent page',
        ]);

        $this->createManyChildren($pageWithManyChildren, 2, 5);
    }

    protected function createChildren(Page $parent, int $numberOfChildren = 5)
    {
        $parent->children()->createMany(
            factory(Page::class, $numberOfChildren)
                ->make([
                    'title' => 'Child page: ' . $parent->id . now()->format('H:i:s')
                ])
                ->toArray()
        );

        return $parent->children;
    }

    protected function createManyChildren(Page $parent, int $depth, int $numberOfChildren = 5)
    {
        if ($depth >= 0) {
            $depth--;

            $children = $this->createChildren($parent, $numberOfChildren);

            foreach ($children as $child) {
                $this->createManyChildren($child, $depth, $numberOfChildren);
            }
        }
    }
}
