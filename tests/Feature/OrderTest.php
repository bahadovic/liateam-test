<?php

namespace Tests\Feature;

use App\Facades\JWT;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_order()
    {
        $token = $this->loginUser()['token'];

        $products = Product::factory()->count(2)->create();

        $orderData = [
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'quantity' => 1,
                ];
            })->toArray(),
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(
            uri: route('orders.store'),
            data: $orderData
        );
        $response->assertStatus(Response::HTTP_OK);

    }

    /** @test */
    public function it_can_list_orders()
    {
        $user = $this->loginUser();

        Order::factory()->count(5)->create(['user_id' => $user['id']]);

        $response = $this->getJson(
            uri: route('orders.index'),
            headers: [
                'Authorization' => sprintf('Bearer %s', $user['token'])
            ]
        );
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5);
    }

    /** @test */
    public function it_can_show_an_order()
    {
        $user = $this->loginUser();

        $order = Order::factory()->create(['user_id' => $user['id']]);

        $response = $this->getJson(
            uri: route('orders.show', ['id' => $order->id]),
            headers: [
                'Authorization' => sprintf('Bearer %s', $user['token'])
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['id' => $order->id]);
    }

    /** @test */
    public function it_can_delete_an_order()
    {
        $user = $this->loginUser();

        $order = Order::factory()->create(['user_id' => $user['id']]);

        $response = $this->deleteJson(
            uri: route('orders.destroy', ['id' => $order->id]),
            headers: [
                'Authorization' => sprintf('Bearer %s', $user['token'])
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
    }

    private function loginUser()
    {
        $user = User::factory()->create();
        $jwt = JWT::setup(user: $user);

        $user->update([
            'access_token' => $jwt['access_token'],
            'refresh_token' => $jwt['refresh_token'],
        ]);

        return  ['token' =>$jwt['access_token'], 'user_id' => $user->id];

    }
}
