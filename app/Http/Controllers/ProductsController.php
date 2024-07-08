<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Models\Products;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->list($request);
        return Jetstream::inertia()->render($request, 'Products/Index', [
            'products' => $products
        ]);
    }

    public function list(Request $request)
    {
        $params = $request->all();

            $params['q'] = $params['q'] ?? '';
            $params['sort'] = isset($params['sort']) ? explode(':', $params['sort']) : ['name','asc'];
            $response = Products::orderBy('products.'.$params['sort'][0], $params['sort'][1])
                        ->where('products.name', 'like', '%' . $params['q'] . '%')
                        ->orWhere('customers.name', 'like', '%' . $params['q'] . '%')
                        ->orWhere('products.brand', 'like', '%' . $params['q'] . '%')
                        ->orWhere('products.description', 'like', '%' . $params['q'] . '%')
                        ->join('customers','products.customer_id', '=', 'customers.id')
                        ->select('products.id', 'products.name', 'customers.name as customer_id', 'products.brand', 'products.quantity', 'products.weight', 'products.weight_unit', 'products.price', 'products.description', 'products.create', 'products.update')
                        ->get();
                        //dd($response);
        return $response;
    }

    public function create(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Products/Form', [
            'product' => []
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Inertia\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show(Request $request, $id)
    {
        $product = Products::where('products.id', $id)
                            ->join('customers','products.customer_id', '=', 'customers.id')
                            ->select('products.id', 'products.name', 'customers.id as customer_id', 'customers.name as customer_name', 'products.brand', 'products.quantity', 'products.weight', 'products.weight_unit', 'products.price', 'products.description', 'products.create', 'products.update')
                            ->get()[0];
        $this->rollbackBody($product);
        //dd($product);
        return Jetstream::inertia()->render($request, 'Products/Form', [
            'product' => $product
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request)
    {
        $body = $this->parseBody($request);

        try {
            Products::create($body);
        return redirect()->route('products.index')
            ->with('message', 'Produto criado sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('products.create')
            ->with('message',
                \sprintf('%s|error', $message ??
                    'Ocorreu um erro ao criar o Produto. Verifique seus dados e tente novamente!'));
        }
    }


    public function update($id, Request $request)
    {
        $body = $this->parseBody($request);
        try {
            Products::where('id', $id)->update($body);
            return redirect()->route('products.index')
            ->with('message', 'Produto alterado sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('products.show', $id)
            ->with('message',
                \sprintf('%s|error', $message ??
                    'Ocorreu um erro ao criar o produto. Verifique seus dados e tente novamente!'));
        }
        return Jetstream::inertia()->render($request, 'Products/Form');
    }


    public function parseBody(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name'              => 'required',
            'customer_id'       => 'required',
            'brand'             => 'required',
            'quantity'          => 'required',
            'weight'            => 'required',
            'weight_unit'       => 'required',
            'price'             => 'required',
            'description'       => 'required'
        ]);
        
        $body = $request->all();
        //dd($body);
        
        $body['create'] = isset($body['create']) ? date('Y-m-d H:i:s', strtotime('+3 hours', strtotime($body['create']))) : date('Y-m-d H:i:s');

        $body['customer_id'] = isset($body['customer_id']['id']) ? $body['customer_id']['id'] : null;

        if ($body['_method'] === 'PUT') {
            $body['update'] = date('Y-m-d H:i:s');
        }

        unset($body['_method']);

        return $body;
    }

    public function rollbackBody(&$body)
    {
        $body['create'] = date('d/m/Y H:i:s', strtotime('-3 hours', strtotime($body['create'])));
        $body['update'] = isset($body['update']) ? date('d/m/Y H:i:s', strtotime('-3 hours', strtotime($body['update']))) : null;

        $body['customer_id'] = ['id'=>$body['customer_id'],'name'=>$body['customer_name']];
        //$body['customer_id'] = $body['customer_name'];

        unset($body['customer_name']);

        //dd($body);

        return $body;
    }
}