<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = $this->list($request);
        return Jetstream::inertia()->render($request, 'Customer/Index', [
            'customers' => $customers
        ]);
    }

    public function list(Request $request)
    {
        $params = $request->all();

        if ($params !== []) {
            $params['q'] = $params['q'] ?? '';
            $params['sort'] = isset($params['sort']) ? explode(':', $params['sort']) : ['name','asc'];
            $response = Customer::orderBy($params['sort'][0], $params['sort']['1'])
                        ->where('name', 'like', '%' . $params['q'] . '%')
                        ->orWhere('email', 'like', '%' . $params['q'] . '%')
                        ->orWhere('federal_document', 'like', '%' . $params['q'] . '%')
                        ->orWhere('phone', 'like', '%' . $params['q'] . '%')
                        ->get();
        }else{
            $response = Customer::all();
        }
        return $response;
    }

    public function create(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Customer/Form', [
            'customer' => []
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
        $customer = Customer::where('id', $id)->get()[0];
        $this->rollbackBody($customer);
        return Jetstream::inertia()->render($request, 'Customer/Form', [
            'customer'   => $customer
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
            Customer::create($body);
        return redirect()->route('customers.index')
            ->with('message', 'Cliente criado sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('customers.create')
            ->with('message',
                \sprintf('%s|error', $message ??
                    'Ocorreu um erro ao criar o cliente. Verifique seus dados e tente novamente!'));
        }
    }


    public function update($id, Request $request)
    {
        $body = $this->parseBody($request);
        try {
            Customer::where('id', $id)->update($body);
            return redirect()->route('customers.index')
            ->with('message', 'Cliente alterado sucesso!|success');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            return redirect()->route('customers.show', $id)
            ->with('message',
                \sprintf('%s|error', $message ??
                    'Ocorreu um erro ao criar o cliente. Verifique seus dados e tente novamente!'));
        }
        return Jetstream::inertia()->render($request, 'Customer/Form');
    }


    public function parseBody(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'federal_document'  => 'required',
            'email'             => 'required|email',
            'phone'             => 'required',
            'zip_code'          => 'required',
            'state'             => 'required',
            'city'              => 'required',
            'address'           => 'required'
        ]);
        
        $body = $request->all();
        
        $body['federal_document'] = preg_replace('/\D/', '', $body['federal_document']);
        $body['phone'] = preg_replace('/\D/', '', $body['phone']);
        $body['zip_code'] = preg_replace('/\D/', '', $body['zip_code']);
        $body['is_active'] = $body['is_active'] ?? true;
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
        $body['is_active'] = isset($body['is_active']) && $body['is_active'] === 1 ? true : false;

        return $body;
    }
}