<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Creates the 5-parent catalog category tree under Root (id 1).
 *
 * Written for Bagisto's `categories` (nested set: _lft, _rgt, parent_id) and
 * `category_translations` tables. Optional columns are checked before use, so it
 * works across Bagisto 1.x and 2.x schemas.
 *
 * - Safe to re-run: a category whose slug already exists is reused, not duplicated.
 * - Legacy categories (ids 9, 10, 11, 12, 69, 70) are left untouched unless
 *   DEACTIVATE_LEGACY is set to true. Re-import products first, then disable them.
 * - down() removes only the categories this migration created.
 *
 * After running: php artisan optimize:clear
 */
return new class extends Migration
{
    private const ROOT_ID = 1;

    private const DEACTIVATE_LEGACY = false;

    private const LEGACY_IDS = [9, 10, 11, 12, 69, 70];

    private const DISPLAY_MODE = 'products_and_description';

    /**
     * Parent category => list of subcategories (Title Case, no special characters).
     */
    private const TREE = [
        'Office Supplies' => [
            'Pens Markers and Correction',
            'Paper and Notepads',
            'Notebooks and Diaries',
            'Registers and Record Books',
            'Files and Folders',
            'Envelopes',
            'Desk Tools and Office Machines',
            'Clips Pins and Rubber Bands',
            'Glue and Tapes',
            'Labels and Stickers',
            'Stamps and Stamp Pads',
            'Printer Toner and Ink',
            'Computer and Networking',
        ],
        'School and Art Supplies' => [
            'Pencils Erasers and Sharpeners',
            'Art Colours and Paints',
            'Paint Brushes and Palettes',
            'Craft Supplies',
            'Craft and Chart Paper',
            'Drawing Books and Geometry',
            'Classroom and Exam Supplies',
            'Stationery Kits',
        ],
        'Printing and Signage' => [
            'Custom Printing',
            'Lamination and ID Accessories',
            'Banners and Signboards',
            'Name Plates',
            'Notice Boards and Whiteboards',
            'Flags',
        ],
        'Cleaning and Housekeeping' => [
            'Floor and Surface Cleaners',
            'Toilet and Washroom Care',
            'Housekeeping Chemicals',
            'Air Fresheners',
            'Hand Wash and Sanitizers',
            'Dishwash and Laundry',
            'Mops and Wipers',
            'Brooms Brushes and Scrubbers',
            'Dusters and Cleaning Cloths',
            'Garbage Bags and Dustbins',
            'Pest Control',
            'Tissues and Paper Napkins',
            'Door Mats',
        ],
        'Pantry and Facility Supplies' => [
            'Food and Beverages',
            'Kitchenware and Tableware',
            'Disposable Tableware',
            'Containers and Buckets',
            'Packaging Materials',
            'Safety and PPE',
            'First Aid and Medical',
            'Personal Care and Accessories',
            'Electrical and Appliances',
            'Tools and Hardware',
            'Furniture and Fixtures',
            'Festive and Pooja Supplies',
        ],
    ];

    public function up(): void
    {
        DB::transaction(function () {
            $locales = $this->locales();
            $parentPosition = 1;

            foreach (self::TREE as $parentName => $children) {
                $parentSlug = Str::slug($parentName);
                $parentId = $this->createCategory($parentName, $parentSlug, $parentSlug, self::ROOT_ID, $parentPosition++, $locales);

                $childPosition = 1;
                foreach ($children as $childName) {
                    $childSlug = Str::slug($childName);
                    $this->createCategory($childName, $childSlug, $parentSlug.'/'.$childSlug, $parentId, $childPosition++, $locales, $parentName);
                }
            }

            if (self::DEACTIVATE_LEGACY) {
                DB::table('categories')->whereIn('id', self::LEGACY_IDS)->update(['status' => 0, 'updated_at' => now()]);
            }

            $this->rebuildNestedSet();
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            $slugs = [];
            foreach (self::TREE as $parentName => $children) {
                foreach ($children as $childName) {
                    $slugs[] = Str::slug($childName);
                }
            }
            $parentSlugs = array_map(fn ($name) => Str::slug($name), array_keys(self::TREE));

            // Children first, then parents. Category deletes cascade to translations
            // and product_categories in Bagisto; translations are removed explicitly too.
            foreach ([$slugs, $parentSlugs] as $batch) {
                $ids = DB::table('category_translations')->whereIn('slug', $batch)->pluck('category_id')->unique()->all();
                DB::table('category_translations')->whereIn('category_id', $ids)->delete();
                DB::table('categories')->whereIn('id', $ids)->delete();
            }

            if (self::DEACTIVATE_LEGACY) {
                DB::table('categories')->whereIn('id', self::LEGACY_IDS)->update(['status' => 1, 'updated_at' => now()]);
            }

            $this->rebuildNestedSet();
        });
    }

    /**
     * Inserts one category with a translation for every locale. Returns its id.
     * If the slug already exists, the existing category is moved under the given
     * parent and its id is returned.
     */
    private function createCategory(string $name, string $slug, string $urlPath, int $parentId, int $position, array $locales, ?string $parentName = null): int
    {
        $existing = DB::table('category_translations')->where('slug', $slug)->value('category_id');

        if ($existing) {
            DB::table('categories')->where('id', $existing)->update([
                'parent_id'  => $parentId,
                'position'   => $position,
                'status'     => 1,
                'updated_at' => now(),
            ]);

            return (int) $existing;
        }

        $row = [
            'position'   => $position,
            'status'     => 1,
            'parent_id'  => $parentId,
            '_lft'       => 0,
            '_rgt'       => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('categories', 'display_mode')) {
            $row['display_mode'] = self::DISPLAY_MODE;
        }

        $id = DB::table('categories')->insertGetId($row);

        $description = $parentName
            ? "Shop {$name} in our {$parentName} range."
            : "Shop our {$name} range.";

        foreach ($locales as $locale) {
            $translation = [
                'category_id'      => $id,
                'name'             => $name,
                'slug'             => $slug,
                'description'      => '<p>'.$description.'</p>',
                'meta_title'       => $name,
                'meta_description' => $description,
                'meta_keywords'    => strtolower($name),
                'locale'           => $locale['code'],
            ];

            if (Schema::hasColumn('category_translations', 'locale_id')) {
                $translation['locale_id'] = $locale['id'];
            }

            if (Schema::hasColumn('category_translations', 'url_path')) {
                $translation['url_path'] = $urlPath;
            }

            DB::table('category_translations')->insert($translation);
        }

        return $id;
    }

    /**
     * Every locale configured in the store, so the category name shows in all of them.
     */
    private function locales(): array
    {
        $rows = Schema::hasTable('locales')
            ? DB::table('locales')->select('id', 'code')->get()
            : collect();

        if ($rows->isEmpty()) {
            return [['id' => null, 'code' => config('app.locale', 'en')]];
        }

        return $rows->map(fn ($r) => ['id' => $r->id, 'code' => $r->code])->all();
    }

    /**
     * Recomputes _lft/_rgt for the whole tree from parent_id (same result as
     * kalnoy/nestedset fixTree()), ordering siblings by position, then id.
     */
    private function rebuildNestedSet(): void
    {
        $nodes = DB::table('categories')->select('id', 'parent_id', 'position')->orderBy('position')->orderBy('id')->get();

        $children = [];
        $ids = $nodes->pluck('id')->all();
        foreach ($nodes as $node) {
            $parent = ($node->parent_id && in_array($node->parent_id, $ids)) ? $node->parent_id : 0;
            $children[$parent][] = $node->id;
        }

        $counter = 0;
        $bounds = [];
        $walk = function ($parentId) use (&$walk, &$children, &$counter, &$bounds) {
            foreach ($children[$parentId] ?? [] as $id) {
                $lft = ++$counter;
                $walk($id);
                $bounds[$id] = [$lft, ++$counter];
            }
        };
        $walk(0);

        foreach ($bounds as $id => [$lft, $rgt]) {
            DB::table('categories')->where('id', $id)->update(['_lft' => $lft, '_rgt' => $rgt]);
        }
    }
};
