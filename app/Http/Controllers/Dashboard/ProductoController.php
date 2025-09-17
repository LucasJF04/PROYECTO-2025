<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ProductoController extends Controller
{
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) abort(400, 'El parámetro debe estar entre 1 y 100.');

        return view('productos.index', [
            'productos' => Producto::with(['categoria','proveedor'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function create()
    {
        return view('productos.create', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::all(),
        ]);
    }

    public function store(Request $request)
    {
        $codigo = IdGenerator::generate([
            'table' => 'productos',
            'field' => 'codigo_producto',
            'length' => 6,
            'prefix' => 'PC'
        ]);

        $validatedData = $request->validate([
            'imagen_producto' => 'image|file|max:1024',
            'nombre_producto' => 'required|string',
            'categoria_id' => 'required|integer',
            'proveedor_id' => 'required|integer',
            'almacen_producto' => 'string|nullable',
            'tienda_producto' => 'string|nullable',
            'fecha_compra' => 'date_format:Y-m-d|nullable',
            'fecha_expiracion' => 'date_format:Y-m-d|nullable',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
        ]);

        $validatedData['codigo_producto'] = $codigo;

        if ($file = $request->file('imagen_producto')) {
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/products/', $fileName);
            $validatedData['imagen_producto'] = $fileName;
        }

        Producto::create($validatedData);

        return Redirect::route('productos.index')->with('success','¡Producto creado con éxito!');
    }

    public function show(Producto $producto)
    {
        $generator = new BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($producto->codigo_producto, $generator::TYPE_CODE_128);

        return view('productos.show', [
            'producto' => $producto,
            'barcode' => $barcode,
        ]);
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', [
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::all(),
            'producto' => $producto
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $validatedData = $request->validate([
            'imagen_producto' => 'image|file|max:1024',
            'nombre_producto' => 'required|string',
            'categoria_id' => 'required|integer',
            'proveedor_id' => 'required|integer',
            'almacen_producto' => 'string|nullable',
            'tienda_producto' => 'string|nullable',
            'fecha_compra' => 'date_format:Y-m-d|nullable',
            'fecha_expiracion' => 'date_format:Y-m-d|nullable',
            'precio_compra' => 'required|numeric',
            'precio_venta' => 'required|numeric',
        ]);

        if ($file = $request->file('imagen_producto')) {
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            if($producto->imagen_producto) Storage::delete('public/products/' . $producto->imagen_producto);
            $file->storeAs('public/products/', $fileName);
            $validatedData['imagen_producto'] = $fileName;
        }

        $producto->update($validatedData);

        return Redirect::route('productos.index')->with('success','¡Producto actualizado!');
    }

    public function destroy(Producto $producto)
    {
        if($producto->imagen_producto) Storage::delete('public/products/' . $producto->imagen_producto);
        $producto->delete();
        return Redirect::route('productos.index')->with('success','¡Producto eliminado!');
    }

    public function importView() { return view('productos.import'); }

    public function importStore(Request $request)
    {
        $request->validate(['upload_file'=>'required|file|mimes:xls,xlsx']);
        $the_file = $request->file('upload_file');

        try {
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $row_limit = $sheet->getHighestDataRow();

            $data = [];
            for($row=2; $row<=$row_limit; $row++){
                $data[] = [
                    'nombre_producto' => $sheet->getCell('A'.$row)->getValue(),
                    'categoria_id' => $sheet->getCell('B'.$row)->getValue(),
                    'proveedor_id' => $sheet->getCell('C'.$row)->getValue(),
                    'codigo_producto' => $sheet->getCell('D'.$row)->getValue(),
                    'almacen_producto' => $sheet->getCell('E'.$row)->getValue(),
                    'imagen_producto' => $sheet->getCell('F'.$row)->getValue(),
                    'tienda_producto' => $sheet->getCell('G'.$row)->getValue(),
                    'fecha_compra' => $sheet->getCell('H'.$row)->getValue(),
                    'fecha_expiracion' => $sheet->getCell('I'.$row)->getValue(),
                    'precio_compra' => $sheet->getCell('J'.$row)->getValue(),
                    'precio_venta' => $sheet->getCell('K'.$row)->getValue(),
                ];
            }

            Producto::insert($data);

        } catch(Exception $e) {
            return Redirect::route('productos.index')->with('error','Hubo un problema al importar los datos.');
        }

        return Redirect::route('productos.index')->with('success','¡Datos importados con éxito!');
    }

    public function exportData()
    {
        $productos = Producto::all()->sortByDesc('id');

        $product_array[] = [
            'Nombre producto','Categoría','Proveedor','Código','Almacén','Imagen','Tienda',
            'Fecha compra','Fecha expiración','Precio compra','Precio venta'
        ];

        foreach($productos as $producto){
            $product_array[] = [
                $producto->nombre_producto,
                $producto->categoria_id,
                $producto->proveedor_id,
                $producto->codigo_producto,
                $producto->almacen_producto,
                $producto->imagen_producto,
                $producto->tienda_producto,
                $producto->fecha_compra,
                $producto->fecha_expiracion,
                $producto->precio_compra,
                $producto->precio_venta,
            ];
        }

        $this->exportExcel($product_array);
    }

    public function exportExcel($productos)
    {
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($productos);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Productos_Exportados.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) { return; }
    }
}
