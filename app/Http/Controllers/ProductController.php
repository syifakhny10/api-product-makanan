<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;




    /**
 * @OA\Info(
 *     title="API Dokumentasi Produk",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk pengelolaan produk.",
 *     @OA\Contact(
 *         email="nofafirdaus@example.com"
 *     )
 * )
 */
class ProductController extends Controller
{


          /**
  * @OA\SecurityScheme(
  *     securityScheme="bearerAuth",
  *     type="http",
  *     scheme="bearer",
  *     bearerFormat="JWT", // Optional, specify if using JWT
  *     description="Masukkan token Bearer Anda di sini"
  * )
  */

     /**

      * Menampilkan semua produk.
      *
      * @OA\Get(
      *     path="/product",
      *     summary="Daftar produk",
      *     description="Mengambil semua data produk.",
      *     security={{"bearerAuth": {}}},
      *     @OA\Response(
      *         response=200,
      *         description="Daftar produk berhasil diambil"
      *     )
      * )
      */

    public function index()
    {
        return response()->json(Product::all());
    }
     /**
      * Menyimpan produk baru.
      *
      * @OA\Post(
      *     path="/product",
      *     summary="Tambah produk",
      *     description="Menyimpan produk baru ke database.",
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(
      *             @OA\Property(property="name", type="string"),
      *             @OA\Property(property="description", type="string"),
      *             @OA\Property(property="price", type="number", format="float")
      *         )
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Produk berhasil disimpan"
      *     )
      * )
      */
    public function store(Request $request)
    {

        $validate = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($validate);
        return response()->json($product, 201);
    }
       /**

   * Menampilkan semua produk.
   *
   * @OA\Get(
   *     path="/product/{id}",
   *     summary="Daftar produk",
   *     description="Mengambil semua data produk.",
   *  @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer"),
   *         description="ID produk yang ingin ditampilkan"
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Daftar produk berhasil diambil"
   *     )
   * )
   */
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

         /**
 * Mengupdate produk baru.
 *
 * @OA\Put(
 *     path="/products/{id}",
 *     summary="Tambah produk",
 *     description="Menyimpan produk baru ke database.",
 *      *  @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID produk yang ingin ditampilkan"
 *     ),

 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="price", type="number", format="float")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Produk berhasil disimpan"
 *     )
 * )
 */

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }
         /**
 * Mengupdate produk baru.
 *
 * @OA\Delete(
 *     path="/products/{id}",
 *     summary="Menghapus produk",
 *     description="Menghapus produk baru ke database.",
 *      *  @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID produk yang ingin ditampilkan"
 *     ),

 *     @OA\Response(
 *         response=200,
 *         description="Produk berhasil disimpan"
 *     )
 * )
 */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'Product deleted']);
    }

}




















