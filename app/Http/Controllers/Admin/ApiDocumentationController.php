<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        $apiDocs = [
            'Authentication' => [
                [
                    'name' => 'Register',
                    'method' => 'POST',
                    'endpoint' => '/api/v1/register',
                    'description' => 'Register a new user',
                    'parameters' => [
                        'name' => 'required|string',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|min:8|confirmed',
                        'password_confirmation' => 'required'
                    ],
                    'response' => [
                        'success' => true,
                        'data' => [
                            'user' => 'User object',
                            'token' => 'Bearer token'
                        ]
                    ]
                ],
                [
                    'name' => 'Login',
                    'method' => 'POST',
                    'endpoint' => '/api/v1/login',
                    'description' => 'Login user',
                    'parameters' => [
                        'email' => 'required|email',
                        'password' => 'required'
                    ],
                    'response' => [
                        'success' => true,
                        'data' => [
                            'user' => 'User object',
                            'token' => 'Bearer token'
                        ]
                    ]
                ],
            ],
            'Products' => [
                [
                    'name' => 'List Products',
                    'method' => 'GET',
                    'endpoint' => '/api/v1/products',
                    'description' => 'Get list of products with optional filters',
                    'parameters' => [
                        'category_id' => 'optional|integer',
                        'search' => 'optional|string',
                        'min_price' => 'optional|numeric',
                        'max_price' => 'optional|numeric',
                        'sort_by' => 'optional|string (created_at, price)',
                        'sort_order' => 'optional|string (asc, desc)',
                        'per_page' => 'optional|integer'
                    ],
                    'response' => [
                        'success' => true,
                        'data' => 'Paginated products'
                    ]
                ],
                [
                    'name' => 'Get Product',
                    'method' => 'GET',
                    'endpoint' => '/api/v1/products/{id}',
                    'description' => 'Get single product details',
                    'parameters' => [],
                    'response' => [
                        'success' => true,
                        'data' => 'Product object'
                    ]
                ]
            ],
            'Cart' => [
                [
                    'name' => 'View Cart',
                    'method' => 'GET',
                    'endpoint' => '/api/v1/cart',
                    'description' => 'Get cart contents',
                    'auth' => 'Required',
                    'parameters' => [],
                    'response' => [
                        'success' => true,
                        'data' => [
                            'items' => 'Array of cart items',
                            'total' => 'Cart total'
                        ]
                    ]
                ],
                [
                    'name' => 'Add to Cart',
                    'method' => 'POST',
                    'endpoint' => '/api/v1/cart/items',
                    'description' => 'Add item to cart',
                    'auth' => 'Required',
                    'parameters' => [
                        'product_id' => 'required|exists:products,id',
                        'quantity' => 'required|integer|min:1'
                    ],
                    'response' => [
                        'success' => true,
                        'message' => 'Product added to cart successfully',
                        'data' => 'Cart item object'
                    ]
                ]
            ],
            'Orders' => [
                [
                    'name' => 'Place Order',
                    'method' => 'POST',
                    'endpoint' => '/api/v1/orders',
                    'description' => 'Create new order from cart',
                    'auth' => 'Required',
                    'parameters' => [
                        'shipping_address' => 'required|array',
                        'billing_address' => 'required|array',
                        'payment_method' => 'required|string',
                        'notes' => 'nullable|string'
                    ],
                    'response' => [
                        'success' => true,
                        'message' => 'Order placed successfully',
                        'data' => 'Order object'
                    ]
                ]
            ]
        ];

        return view('admin.api-documentation.index', compact('apiDocs'));
    }
}
