<?php

namespace Tests\Feature;
use App\Facades\JWT;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_product()
    {

        $token = $this->loginUser()['token'];

        $product = Product::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(
                uri: route('products.store'),
                data: $product
            );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['name' => $product->name]);
    }

    /** @test */
    public function it_can_list_products()
    {
        $token = $this->loginUser()['token'];

        Product::factory()->count(5)->create();

        $response = $this->getJson(
            uri: route('products.index'),
            headers: [
                'Authorization' => sprintf('Bearer %s', $token)
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5);
    }

    /** @test */
    public function it_can_show_a_product()
    {
        $token = $this->loginUser()['token'];

        $product = Product::factory()->create();

        $response = $this->getJson(
            uri: route('products.show', ['id' => $product->id]),
            headers: [
                'Authorization' => sprintf('Bearer %s', $token)
            ]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['id' => $product->id]);
    }

    /** @test */
    public function it_can_update_a_product()
    {
        $token = $this->loginUser()['token'];

        $product = Product::factory()->create();


        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson(
                uri: route('products.update', ['id' => $product->id]),
                data: [
                    'name' => 'Updated Product',
                    'price' => 79.99,
                ]
            );

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Product']);
    }

    /** @test */
    public function it_can_delete_a_product()
    {
        $token = $this->loginUser()['token'];

        $product = Product::factory()->create();

        $response = $this->deleteJson(
            uri: route('products.destroy', ['id' => $product->id]),
            headers: [
                'Authorization' => sprintf('Bearer %s', $token)
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
