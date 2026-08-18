<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Public: list all products.
     */
    public function index()
    {
        return response()->json(Product::orderBy('id')->get());
    }

    /**
     * Public: show a single product by numeric id or est_id (e.g. est-001).
     */
    public function show($identifier)
    {
        $product = Product::where('est_id', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Admin: create a new product with optional image uploads.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $this->productData($request);
        $data['est_id'] = $this->uniqueEstId($request->input('est_id'));
        $data['sku'] = $data['sku'] ?? ('EST-'.strtoupper($data['est_id']));
        $data['images'] = $this->resolveImages($request);

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    /**
     * Admin: update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $rules = $this->rules();
        $rules['est_id'] = ['nullable', 'string', 'max:50', 'unique:products,est_id,'.$product->id];
        $rules['sku'] = ['nullable', 'string', 'max:255', 'unique:products,sku,'.$product->id];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $this->productData($request);
        $data['est_id'] = $request->input('est_id') ?? $product->est_id;
        $data['sku'] = $request->input('sku') ?? $product->sku;
        $data['images'] = $this->resolveImages($request, $product);

        foreach ($data as $key => $value) {
            if ($value === null) {
                $data[$key] = $product->{$key};
            }
        }

        $product->update($data);

        return response()->json($product);
    }

    /**
     * Admin: delete a product and its stored images.
     */
    public function destroy(Product $product)
    {
        $this->deleteStoredImages($product->images);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Validation rules shared by store and update.
     */
    protected function rules(): array
    {
        return [
            'est_id' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'main_category' => 'required|string|max:255',
            'sub_category' => 'required|string|max:255',
            'fabric' => 'required|string|max:255',
            'occasion' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'review_count' => 'nullable|integer|min:0',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'is_trending' => 'boolean',
            'is_featured' => 'boolean',
            'in_stock' => 'boolean',
            'sku' => 'nullable|string|max:255',
            'description' => 'required|string',
            'care' => 'nullable|string',
            'colors' => 'required|json',
            'sizes' => 'required|json',
            'details' => 'nullable|json',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_urls' => 'nullable|json',
            'keep_images' => 'nullable|json',
        ];
    }

    /**
     * Normalise the scalar fields from the request into column names.
     */
    protected function productData(Request $request): array
    {
        return [
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'main_category' => $request->input('main_category'),
            'sub_category' => $request->input('sub_category'),
            'fabric' => $request->input('fabric'),
            'occasion' => $request->input('occasion'),
            'price' => $request->input('price'),
            'old_price' => $request->input('old_price'),
            'discount' => $request->input('discount') ?? 0,
            'rating' => $request->input('rating') ?? 0,
            'review_count' => $request->input('review_count') ?? 0,
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_trending' => $request->boolean('is_trending'),
            'is_featured' => $request->boolean('is_featured'),
            'in_stock' => $request->boolean('in_stock'),
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'care' => $request->input('care') ?? '',
            'colors' => $this->decodeJson($request->input('colors')),
            'sizes' => $this->decodeJson($request->input('sizes')),
            'details' => $this->decodeJson($request->input('details', '[]')),
        ];
    }

    /**
     * Build the final images array for create/update.
     *
     * Keeps images marked in `keep_images`, stores newly uploaded files,
     * and appends any pasted image URLs from `image_urls`.
     */
    protected function resolveImages(Request $request, ?Product $product = null): array
    {
        $existing = $product ? (array) $product->images : [];
        $keep = $this->decodeJson($request->input('keep_images', '[]'));

        $images = [];
        foreach ($existing as $image) {
            if (in_array($image, $keep, true)) {
                $images[] = $image;
            }
        }

        foreach ($request->file('images', []) as $file) {
            $path = Storage::disk('public')->putFile('products', $file);
            $images[] = '/storage/'.$path;
        }

        foreach ($this->decodeJson($request->input('image_urls', '[]')) as $url) {
            if (is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                $images[] = $url;
            }
        }

        $this->deleteStoredImages(array_diff($existing, $images));

        return $images;
    }

    /**
     * Delete previously stored product images that are no longer referenced.
     */
    protected function deleteStoredImages(array $images): void
    {
        foreach ($images as $image) {
            if (is_string($image) && str_starts_with($image, '/storage/products/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image));
            }
        }
    }

    /**
     * Generate a unique est_id when none is supplied.
     */
    protected function uniqueEstId(?string $estId): string
    {
        if ($estId) {
            return $estId;
        }

        $count = Product::max('id') + 1;

        return 'est-'.str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Decode a JSON string, returning a default on empty/invalid input.
     */
    protected function decodeJson($value, array $default = []): array
    {
        if (! is_string($value) || trim($value) === '') {
            return $default;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : $default;
    }
}
