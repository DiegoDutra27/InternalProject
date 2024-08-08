<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Models\Movements;
use App\Models\Products;


class MovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = $this->list($request);
        return Jetstream::inertia()->render($request, 'Movements/Index', [
            'movements' => $movements
        ]);
    }

    public function list(Request $request)
    {
        $params = $request->all();

        $params['q'] = $params['q'] ?? '';
        $params['size'] = $params['size'] ?? 20;
        $params['sort'] = isset($params['sort']) ? explode(':', $params['sort']) : ['create', 'desc'];
        $response = Movements::orderBy($params['sort'][0], $params['sort'][1])
            ->where('movements.quantity', 'like', '%' . $params['q'] . '%')
            ->orWhere('movement', 'like', '%' . $params['q'] . '%')
            ->orWhere('customers.name', 'like', '%' . $params['q'] . '%')
            ->orWhere('products.name', 'like', '%' . $params['q'] . '%')
            ->join('products', 'movements.product_id', '=', 'products.id')
            ->join('customers', 'products.customer_id', '=', 'customers.id')
            ->select('movements.id', 'movements.quantity', 'movements.movement', 'movements.create', 'movements.update', 'products.name as product_name', 'customers.name as customer_name')
            ->paginate($params['size'])->withQueryString();
        return $response;
    }

    public function create(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Movements/Form', [
            'movement' => []
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
        $movement = Movements::where('movements.id', $id)
            ->join('products', 'movements.product_id', '=', 'products.id')
            ->join('customers', 'products.customer_id', '=', 'customers.id')
            ->select('movements.id', 'movements.product_id', 'movements.quantity', 'movements.movement', 'movements.create', 'movements.update', 'products.name as product_name', 'customers.name as customer_name', 'customers.federal_document as customer_federal_document')
            ->get()[0];

        $this->rollbackBody($movement);
        return Jetstream::inertia()->render($request, 'Movements/Form', [
            'movement'   => $movement
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
        $bodyProduct = $this->parseBodyProduct($body, null);

        if ($bodyProduct['quantity'] < 0) {
            return redirect()->route('movements.create')
                ->with(
                    'message',
                    \sprintf('%s|error', $message ??
                        'Os produtos não podem ser menor que 0')
                );
        }

        try {
            Products::where('id', $bodyProduct['id'])->update($bodyProduct);
            Movements::create($body);
            return redirect()->route('movements.index')
                ->with('message', 'Movimento criado sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('movements.create')
                ->with(
                    'message',
                    \sprintf('%s|error', $message ??
                        'Ocorreu um erro ao criar o movimento. Verifique seus dados e tente novamente!')
                );
        }
    }


    public function update($id, Request $request)
    {
        $body = $this->parseBody($request);
        $bodyProduct = $this->parseBodyProduct($body, $id, true);

        try {
            Products::where('id', $bodyProduct['id'])->update($bodyProduct);
            Movements::where('id', $id)->update($body);
            return redirect()->route('movements.index')
                ->with('message', 'Movimentação alterada com sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('movements.show', $id)
                ->with(
                    'message',
                    \sprintf('%s|error', $message ??
                        'Ocorreu um erro ao criar a movimentação. Verifique seus dados e tente novamente!')
                );
        }
        return Jetstream::inertia()->render($request, 'Movements/Form');
    }


    public function parseBody(Request $request)
    {
        $request->validate([
            'product_id'        => 'required',
            'quantity'          => 'required',
            'movement'          => 'required'
        ]);

        $body = $request->all();

        $body['product_id'] = isset($body['product_id']['id']) ? $body['product_id']['id'] : null;
        $body['movement'] = $body['movement']['id'] ?? 'entry';
        $body['create'] = isset($body['create']) ? date('Y-m-d H:i:s', strtotime('+3 hours', strtotime($body['create']))) : date('Y-m-d H:i:s');

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
        $body['movement'] = isset($body['movement']) && $body['movement'] === 'entry' ? ['id' => 'entry', 'name' => 'Entrada'] : ['id' => 'output', 'name' => 'Saída'];
        $body['product_id'] = ['id' => $body['product_id'], 'name' => $body['product_name'], 'customer_name' => $body['customer_name'], 'customer_federal_document' => $body['customer_federal_document']];

        unset($body['product_name'], $body['customer_name'], $body['customer_federal_document']);
        return $body;
    }

    public function parseBodyProduct($body, $id, $isUpdate = false)
    {
        $product = Products::where('id', $body['product_id'])->get()[0];
        if ($isUpdate) {
            $oldMovement = Movements::where('id', $id)->get()[0];
            if ($oldMovement['movement'] === 'output') {
                $product['quantity'] += $oldMovement['quantity'];
            } else {
                $product['quantity'] -= $oldMovement['quantity'];
            }
        }

        if ($body['movement'] === 'output') {
            $product['quantity'] -= $body['quantity'];
        } else {
            $product['quantity'] += $body['quantity'];
        }

        $product = [
            "id" => $product['id'],
            "customer_id" => $product['customer_id'],
            "name" => $product['name'],
            "brand" => $product['brand'],
            "quantity" => $product['quantity'],
            "weight" => $product['weight'],
            "weight_unit" => $product['weight_unit'],
            "price" => $product['price'],
            "description" => $product['description'],
            "create" => $product['create'],
            "update" => $product['update']
        ];

        return $product;
    }
}
